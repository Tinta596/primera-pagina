const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const mysql = require('mysql2');

const app = express();
const port = 3000;

app.use(bodyParser.json());
app.use(cors());

// Configuración de conexión a MySQL
const db = mysql.createConnection({
    host: 'localhost',
    user: 'tu_usuario', // Reemplaza con tu usuario
    password: 'tu_contraseña', 
    database: 'ventas_online' 
});

// Conectar a la base de datos
db.connect((err) => {
    if (err) {
        console.error('Error de conexión a la base de datos:', err);
        return;
    }
    console.log('Conectado a la base de datos MySQL');
});

// Credenciales del administrador
const adminCredentials = { email: 'admin@ejemplo.com', password: 'admin123' };

// Middleware para verificar autenticación
function authenticate(req, res, next) {
    const { email, password } = req.body;
    if (email === adminCredentials.email && password === adminCredentials.password) {
        next();
    } else {
        res.status(401).json({ message: 'Credenciales inválidas' });
    }
}

// Ruta para iniciar sesión
app.post('/login', (req, res) => {
    const { email, password } = req.body;
    if (email === adminCredentials.email && password === adminCredentials.password) {
        res.json({ message: 'Inicio de sesión exitoso' });
    } else {
        res.status(401).json({ message: 'Credenciales incorrectas' });
    }
});

// Ruta para agregar productos
app.post('/productos', authenticate, (req, res) => {
    const { name, price, image } = req.body;
    const query = 'INSERT INTO productos (name, price, image) VALUES (?, ?, ?)';
    db.query(query, [name, price, image], (err, results) => {
        if (err) {
            return res.status(500).json({ message: 'Error al agregar producto', error: err });
        }
        res.status(201).json({ message: 'Producto agregado', productId: results.insertId });
    });
});

// Ruta para obtener productos
app.get('/productos', (req, res) => {
    db.query('SELECT * FROM productos', (err, results) => {
        if (err) {
            return res.status(500).json({ message: 'Error al obtener productos', error: err });
        }
        res.json(results);
    });
});

app.listen(port, () => {
    console.log(`Servidor escuchando en http://localhost:${port}`);
});

