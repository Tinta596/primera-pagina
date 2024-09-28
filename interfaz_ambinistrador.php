<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#configuracion">Configuración de Productos</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal de Iniciar Sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Iniciar Sesión Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="admin-email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="admin-email" placeholder="admin@ejemplo.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin-password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="admin-password" placeholder="Contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Configuración de Productos -->
    <section id="configuracion" class="container mt-5">
        <h2 class="text-center">Configuración de Productos</h2>
        <form id="productForm">
            <div class="mb-3">
                <label for="productName" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="productName" required>
            </div>
            <div class="mb-3">
                <label for="productPrice" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="productPrice" required>
            </div>
            <div class="mb-3">
                <label for="productImage" class="form-label">Imagen (URL)</label>
                <input type="text" class="form-control" id="productImage" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Producto</button>
        </form>
        <h3 class="mt-4">Productos Actuales</h3>
        <div id="productList" class="list-group">
            <!-- Productos serán listados aquí -->
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('admin-email').value;
            const password = document.getElementById('admin-password').value;

            fetch('server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'login',
                    email: email,
                    password: password
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Inicio de sesión exitoso') {
                    alert(data.message);
                    document.querySelector('.btn-close').click(); // Cerrar el modal de login
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        document.getElementById('productForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const name = document.getElementById('productName').value;
            const price = parseFloat(document.getElementById('productPrice').value).toFixed(2);
            const image = document.getElementById('productImage').value;

            fetch('server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'add_product',
                    name: name,
                    price: price,
                    image: image
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Producto agregado') {
                    alert(data.message);
                    listarProductos();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        function listarProductos() {
            fetch('server.php?action=get_products')
            .then(response => response.json())
            .then(data => {
                const productList = document.getElementById('productList');
                productList.innerHTML = '';
                data.forEach(product => {
                    const listItem = document.createElement('div');
                    listItem.className = 'list-group-item';
                    listItem.innerHTML = `
                        <h5>${product.name}</h5>
                        <p>Precio: $${product.price}</p>
                        <img src="${product.image}" alt="${product.name}" class="img-thumbnail" style="max-width: 100px;">
                    `;
                    productList.appendChild(listItem);
                });
            })
            .catch(error => console.error('Error:', error));
        }

        document.addEventListener('DOMContentLoaded', listarProductos);
    </script>
</body>
</html>
