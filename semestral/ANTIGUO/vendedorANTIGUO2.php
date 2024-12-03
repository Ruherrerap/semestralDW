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
    $stmt = $cnn->prepare("SELECT id, nombre_producto, descripcion_producto, precio FROM productos WHERE vendedor_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $productos = $stmt->get_result();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreProducto = $_POST['nombre_producto'];
    $descripcionProducto = $_POST['descripcion_producto'];
    $precio = $_POST['precio'];

    $stmt = $cnn->prepare("INSERT INTO productos (vendedor_id, nombre_producto, descripcion_producto, precio) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $id, $nombreProducto, $descripcionProducto, $precio);
    $stmt->execute();
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

        <h2>Agregar Producto</h2>
        <form method="POST">
            <label for="nombre_producto">Nombre del Producto:</label>
            <input type="text" id="nombre_producto" name="nombre_producto" required><br>
            <label for="descripcion_producto">Descripción:</label>
            <textarea id="descripcion_producto" name="descripcion_producto" required></textarea><br>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required><br>
            <button type="submit">Agregar Producto</button>
        </form>

        <h2>Productos</h2>
        <?php while ($producto = $productos->fetch_assoc()): ?>
            <div>
                <h3><?php echo htmlspecialchars($producto['nombre_producto']); ?></h3>
                <p><?php echo htmlspecialchars($producto['descripcion_producto']); ?></p>
                <p>Precio: $<?php echo htmlspecialchars($producto['precio']); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Vendedor no encontrado.</p>
    <?php endif; ?>
</body>
</html>