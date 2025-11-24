<?php
include 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$mensaje = "";
$producto_editar = null;

// CREATE - Insertar producto
if (isset($_POST['agregar_producto'])) {
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    try {
        $query = "INSERT INTO productos (nombre, categoria, descripcion, precio, stock) VALUES (:nombre, :categoria, :descripcion, :precio, :stock)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);
        
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Producto agregado exitosamente!</div>";
        }
    } catch(PDOException $e) {
        $mensaje = "<div class='alert alert-danger'>Error al agregar: " . $e->getMessage() . "</div>";
    }
}

// UPDATE - Editar producto
if (isset($_POST['editar_producto'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    
    try {
        $query = "UPDATE productos SET nombre=:nombre, categoria=:categoria, descripcion=:descripcion, precio=:precio, stock=:stock WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":categoria", $categoria);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":precio", $precio);
        $stmt->bindParam(":stock", $stock);
        
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Producto actualizado exitosamente!</div>";
        }
    } catch(PDOException $e) {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}

// DELETE - Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    try {
        $query = "DELETE FROM productos WHERE id=:id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>Producto eliminado exitosamente!</div>";
        }
    } catch(PDOException $e) {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar: " . $e->getMessage() . "</div>";
    }
}

// Obtener producto para editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $query = "SELECT * FROM productos WHERE id=:id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $producto_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// SELECT - Obtener todos los productos
$query = "SELECT * FROM productos ORDER BY id DESC";
$stmt = $db->prepare($query);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Productos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <img src="img/logo.jpeg" alt="Logo" class="logo">
        <nav class="nav-menu">
            <a href="index.php">Inicio</a>
            <a href="crud.php">CRUD Productos</a>
        </nav>
    </header>

    <div class="container">
        <!-- Mensajes -->
        <?php if($mensaje): ?>
            <?php echo $mensaje; ?>
        <?php endif; ?>

        <!-- Formulario para Agregar/Editar -->
        <div class="card">
            <h2><?php echo $producto_editar ? 'Editar Producto' : 'Agregar Nuevo Producto'; ?></h2>
            
            <form action="crud.php" method="post">
                <?php if($producto_editar): ?>
                    <input type="hidden" name="id" value="<?php echo $producto_editar['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" 
                           value="<?php echo $producto_editar ? $producto_editar['nombre'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria" class="form-control" required>
                        <option value="">Seleccionar categoría</option>
                        <option value="accesorios" <?php echo ($producto_editar && $producto_editar['categoria'] == 'accesorios') ? 'selected' : ''; ?>>Accesorios</option>
                        <option value="cuidado_facial" <?php echo ($producto_editar && $producto_editar['categoria'] == 'cuidado_facial') ? 'selected' : ''; ?>>Cuidado Facial</option>
                        <option value="perfumes" <?php echo ($producto_editar && $producto_editar['categoria'] == 'perfumes') ? 'selected' : ''; ?>>Perfumes</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control"><?php echo $producto_editar ? $producto_editar['descripcion'] : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" step="0.01" name="precio" class="form-control" 
                           value="<?php echo $producto_editar ? $producto_editar['precio'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" class="form-control" 
                           value="<?php echo $producto_editar ? $producto_editar['stock'] : ''; ?>" required>
                </div>
                
                <?php if($producto_editar): ?>
                    <button type="submit" name="editar_producto" class="btn btn-primary">Actualizar Producto</button>
                    <a href="crud.php" class="btn btn-warning">Cancelar</a>
                <?php else: ?>
                    <button type="submit" name="agregar_producto" class="btn btn-success">Agregar Producto</button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Lista de Productos -->
        <div class="card">
            <h2>Lista de Productos</h2>
            
            <?php if(count($productos) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $producto['categoria'])); ?></td>
                                <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td>
                                    <a href="crud.php?editar=<?php echo $producto['id']; ?>" class="btn btn-warning">Editar</a>
                                    <a href="crud.php?eliminar=<?php echo $producto['id']; ?>" class="btn btn-danger" 
                                       onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay productos registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>