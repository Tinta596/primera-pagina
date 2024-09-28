
const usuarios = [];

// Ruta para registrar un usuario
app.post('/register', (req, res) => {
    const { email, password } = req.body;
    // Aquí podrías agregar validaciones adicionales y guardar en la base de datos
    usuarios.push({ email, password });
    res.status(201).json({ message: 'Usuario registrado exitosamente' });
});

// Ruta para crear cuenta de administrador
app.post('/create-admin', authenticate, (req, res) => {
    const { email, password } = req.body;
    // Aquí también puedes agregar validaciones y guardar en la base de datos
    usuarios.push({ email, password });
    res.status(201).json({ message: 'Cuenta de administrador creada exitosamente' });
});
