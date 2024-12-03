<?php
require_once 'conexion.php';

$conn = new Conecta();
$cnn = $conn->conectarDB();

$vendedor = null;
$productos = [];

// Obtener datos del vendedor por ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $cnn->prepare("SELECT nombre, email, telefono, descripcion, imagen_perfil FROM vendedores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendedor = $result->fetch_assoc();
    $stmt->close();

    // Obtener productos del vendedor
    $stmt = $cnn->prepare("SELECT id, nombre_producto, descripcion_producto, precio, imagen_producto FROM productos WHERE vendedor_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $productos = $stmt->get_result();
}

// Eliminar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_producto'])) {
    $productoId = $_POST['producto_id'];

    // Obtener la ruta de la imagen del producto
    $stmt = $cnn->prepare("SELECT imagen_producto FROM productos WHERE id = ?");
    $stmt->bind_param("i", $productoId);
    $stmt->execute();
    $stmt->bind_result($imagenProducto);
    $stmt->fetch();
    $stmt->close();

    // Eliminar la imagen del producto si existe
    if ($imagenProducto && file_exists($imagenProducto)) {
        unlink($imagenProducto);
    }

    // Eliminar el producto de la base de datos
    $stmt = $cnn->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->bind_param("i", $productoId);
    $stmt->execute();
    $stmt->close();

    header("Location: vendedor.php?id=" . $id);
}

$conn->cerrar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendedor</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="registro.php">Registro de Vendedores</a></li>
        </ul>
    </nav>
    <?php if ($vendedor): ?>
        <h1>Vendedor: <?php echo htmlspecialchars($vendedor['nombre']); ?></h1>
        <img src="<?php echo htmlspecialchars($vendedor['imagen_perfil']); ?>" alt="Perfil de <?php echo htmlspecialchars($vendedor['nombre']); ?>" class="profile-img">
        <p>Email: <?php echo htmlspecialchars($vendedor['email']); ?></p>
        <p>Teléfono: <?php echo htmlspecialchars($vendedor['telefono']); ?></p>
        <p>Descripción: <?php echo htmlspecialchars($vendedor['descripcion']); ?></p>

        
        <h2>Productos</h2>
        <?php while ($producto = $productos->fetch_assoc()): ?>
            <div>
                <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                <img src="<?php echo htmlspecialchars($producto['imagen_producto']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" style="max-width: 200px;">
                <p><?php echo htmlspecialchars($producto['descripcion_producto']); ?></p>
                <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
                <!-- Formulario para eliminar producto -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <button type="submit" name="eliminar_producto">Eliminar</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Vendedor no encontrado.</p>
    <?php endif; ?>
</body>
</html>
