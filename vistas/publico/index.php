<?php
session_start();
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Paraíso - Bienvenido</title>
    <link rel="stylesheet" href="style.css">
    <script type="importmap">
        {
            "imports": {
                "canvas-confetti": "https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.module.mjs"
            }
        }
    </script>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../../recursos/imagenes/logo.png" alt="Logo Hotel Paraíso" id="logo">
            <h1>Hotel Paraíso</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#hero">Inicio</a></li>
                <li><a href="#services">Servicios</a></li>
                <li><a href="#contact">Contacto</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <?php if ($_SESSION['rol'] === 'usuario'): ?>
                        <li><a href="../../vistas/usuario/index.php">Mi Panel</a></li>
                    <?php elseif ($_SESSION['rol'] === 'recepcionista'): ?>
                        <li><a href="../../vistas/recepcionista/index.php">Mi Panel</a></li>
                    <?php elseif ($_SESSION['rol'] === 'administrador'): ?>
                        <li><a href="../../vistas/administrador/index.php">Mi Panel</a></li>
                    <?php elseif ($_SESSION['rol'] === 'mucama'): ?>
                        <li><a href="../../vistas/mucama/index.php">Mi Panel</a></li>
                    <?php endif; ?>
                    <li><a href="../auth/logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a class="button-outline" href="../auth/login.php">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section id="hero" class="hero-section" style="background-image: url('../../recursos/imagenes/hotel_banner.png');">
            <div class="hero-content">
                <h2>Bienvenido al Hotel Paraíso</h2>
                <p>Su estancia de ensueño comienza aquí. Disfrute de nuestras lujosas instalaciones y servicio de primera clase.</p>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <?php if ($_SESSION['rol'] === 'usuario'): ?>
                        <a href="../../vistas/usuario/index.php">
                            <button id="reservar">Reservar Ahora</button>
                        </a>
                    <?php elseif ($_SESSION['rol'] === 'recepcionista'): ?>
                        <a href="../../vistas/recepcionista/index.php">
                            <button id="reservar">Ir al Panel de Recepcionista</button>
                        </a>
                    <?php elseif ($_SESSION['rol'] === 'administrador'): ?>
                        <a href="../../vistas/administrador/index.php">
                            <button id="reservar">Ir al Panel de Administrador</button>
                        </a>
                    <?php elseif ($_SESSION['rol'] === 'mucama'): ?>
                        <a href="../../vistas/mucama/index.php">
                            <button id="reservar">Ir al Panel de Mucama</button>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="../auth/login.php">
                        <button id="reservar">Iniciar Sesión</button>
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <section id="featured-rooms" class="content-section">
            <h2>Habitaciones Destacadas</h2>
            <div class="featured-grid">
                <div class="featured-item">
                    <img src="../../recursos/imagenes/room1.jpg" alt="Habitación Estándar">
                    <h3>Habitación Estándar</h3>
                    <p>$100/noche - Capacidad: 2</p>
                </div>
                <div class="featured-item">
                    <img src="../../recursos/imagenes/room2.jpg" alt="Suite">
                    <h3>Suite</h3>
                    <p>$200/noche - Capacidad: 4</p>
                </div>
            </div>
        </section>

        <section id="services" class="content-section">
            <h2>Nuestros Servicios</h2>
            <div class="services-grid">
                <div class="service-item">
                    <img src="../../recursos/imagenes/icon_room.png" alt="Habitaciones Lujosas">
                    <h3>Habitaciones Lujosas</h3>
                    <p>Confort y elegancia en cada detalle.</p>
                </div>
                <div class="service-item">
                    <img src="../../recursos/imagenes/icon_restaurant.png" alt="Restaurante Gourmet">
                    <h3>Restaurante Gourmet</h3>
                    <p>Sabores exquisitos para paladares exigentes.</p>
                </div>
                <div class="service-item">
                    <img src="../../recursos/imagenes/icon_spa.png" alt="Spa y Bienestar">
                    <h3>Spa y Bienestar</h3>
                    <p>Relájese y renuévese en nuestro spa.</p>
                </div>
            </div>
        </section>

        <section id="gallery" class="content-section">
            <h2>Galería</h2>
            <div class="gallery-grid">
                <div class="gallery-item"><img src="../../recursos/imagenes/gallery1.jpg" alt="Galeria 1"></div>
                <div class="gallery-item"><img src="../../recursos/imagenes/gallery2.jpg" alt="Galeria 2"></div>
                <div class="gallery-item"><img src="../../recursos/imagenes/gallery3.jpg" alt="Galeria 3"></div>
            </div>
        </section>

        <section id="contact" class="content-section">
            <h2>Contacto</h2>
            <p>Estamos ubicados en Calle Ficticia 123, Ciudad Paraíso.</p>
            <p>Teléfono: +123 456 7890</p>
            <p>Email: info@hotelparaiso.com</p>
        </section>
    </main>

    <footer>
        <p>© 2025 Hotel Paraíso. Todos los derechos reservados.</p>
    </footer>

    <script src="script.js" type="module"></script>
</body>
</html>