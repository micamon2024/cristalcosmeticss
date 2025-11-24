<?php
// Incluir la conexión a la base de datos para mostrar algunos productos
include 'config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Obtener algunos productos para mostrar en el inicio
    $query = "SELECT * FROM productos ORDER BY fecha_creacion DESC LIMIT 3";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $productos_recientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $productos_recientes = array();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Emprendimiento - Inicio</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }
        
        .categorias {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin: 40px 0;
        }
        
        .categoria-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .categoria-card:hover {
            transform: translateY(-5px);
        }
        
        .categoria-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        
        .categoria-card p {
            color: #666;
            line-height: 1.6;
        }
        
        .productos-recientes {
            margin: 50px 0;
        }
        
        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .producto-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .producto-card:hover {
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .producto-categoria {
            background: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .producto-precio {
            font-size: 1.3em;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }
        
        .btn-large {
            padding: 15px 30px;
            font-size: 1.1em;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 50px 0;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 10px;
        }
        
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 30px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <img src="img/logo.jpeg" alt="Logo de Mi Emprendimiento" class="logo">
        <nav class="nav-menu">
            <a href="index.php">Inicio</a>
            <a href="crud.php">Gestionar Productos</a>
            <a href="#categorias">Categorías</a>
            <a href="#contacto">Contacto</a>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Bienvenido a Mi Emprendimiento</h1>
            <p>Descubre nuestra exclusiva colección de accesorios, productos de cuidado facial y perfumes</p>
            <a href="crud.php" class="btn btn-primary btn-large">Gestionar Productos</a>
        </div>
    </section>

    <div class="container">
        <!-- Categorías -->
        <section id="categorias">
            <h2 class="section-title">Nuestras Categorías</h2>
            <div class="categorias">
                <div class="categoria-card">
                    <img src="img/cremas.jpeg" alt="Accesorios" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 15px;">
                    <h3>Accesorios</h3>
                    <p>Descubre nuestra exclusiva colección de joyería y accesorios elegantes para cada ocasión.</p>
                </div>
                
                <div class="categoria-card">
                    <img src="img/serum.jpeg" alt="Cuidado Facial" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 15px;">
                    <h3>Cuidado Facial</h3>
                    <p>Productos premium para el cuidado de tu piel. Calidad y resultados garantizados.</p>
                </div>
                
                <div class="categoria-card">
                    <img src="img/perfumes.jpeg" alt="Perfumes" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; margin-bottom: 15px;">
                    <h3>Perfumes</h3>
                    <p>Fragancias únicas que reflejan tu personalidad. Duraderas y de alta calidad.</p>
                </div>
            </div>
        </section>

        <!-- Productos Recientes -->
        <?php if(count($productos_recientes) > 0): ?>
        <section class="productos-recientes">
            <h2 class="section-title">Productos Recientes</h2>
            <div class="productos-grid">
                <?php foreach($productos_recientes as $producto): ?>
                <div class="producto-card">
                    <span class="producto-categoria"><?php echo ucfirst(str_replace('_', ' ', $producto['categoria'])); ?></span>
                    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                    <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <div class="producto-precio">$<?php echo number_format($producto['precio'], 2); ?></div>
                    <div>Stock: <?php echo $producto['stock']; ?> unidades</div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Estadísticas -->
        <section class="stats">
            <div class="stat-card">
                <div class="stat-number">+500</div>
                <div>Clientes Satisfechos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">+100</div>
                <div>Productos Únicos</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">3</div>
                <div>Años de Experiencia</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98%</div>
                <div>Clientes Contentos</div>
            </div>
        </section>

        <!-- Llamada a la acción -->
        <section style="text-align: center; margin: 50px 0;">
            <h2>¿Listo para gestionar tu inventario?</h2>
            <p>Accede al panel de administración para agregar, editar o eliminar productos.</p>
            <a href="crud.php" class="btn btn-success btn-large">Ir al CRUD de Productos</a>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer" id="contacto">
        <div class="container">
            <h3>Mi Emprendimiento</h3>
            <p>Especialistas en accesorios, cuidado facial y perfumes de calidad</p>
            <p>Email:cristalcosmeticss.com | Tel: +1 234 567 890</p>
            <p>&copy; 2024 Mi Emprendimiento. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>