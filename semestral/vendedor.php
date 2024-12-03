<?php
session_start();
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
    // Agregar producto
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar_producto'])) {
        $nombreProducto = $_POST['nombre_producto'];
        $descripcionProducto = $_POST['descripcion_producto'];
        $precio = $_POST['precio'];
        $imagenProducto = $_FILES['imagen_producto']['name'];

        // Guardar imagen del producto
        $rutaCarpeta = "vendedores/" . strtolower(str_replace(' ', '_', $vendedor['nombre']));
        $rutaImagen = $rutaCarpeta . "/" . basename($imagenProducto);
        move_uploaded_file($_FILES['imagen_producto']['tmp_name'], $rutaImagen);

        // Insertar producto en la base de datos
        $stmt = $cnn->prepare("INSERT INTO productos (vendedor_id, nombre_producto, descripcion_producto, precio, imagen_producto) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $id, $nombreProducto, $descripcionProducto, $precio, $rutaImagen);
        $stmt->execute();
        $stmt->close();

        header("Location: vendedor.php?id=" . $id);
        exit;
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
        exit;
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
    <!-- <link rel="stylesheet" href="css/nav.css"> -->
</head>
<body>
<h1 class="titulo">BILUBILU STORE</h1>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <?php if ($vendedor): ?>
        <h2><?php echo htmlspecialchars($vendedor['nombre']); ?></h2>
        <div class="vendedor-info">
            <img src="<?php echo htmlspecialchars($vendedor['imagen_perfil']); ?>" alt="Perfil de <?php echo htmlspecialchars($vendedor['nombre']); ?>" class="profile-img">
        <div class="info">
            <p>Email: <?php echo htmlspecialchars($vendedor['email']); ?></p>
            <p>Teléfono: <?php echo htmlspecialchars($vendedor['telefono']); ?></p>
            <p>Descripción: <?php echo htmlspecialchars($vendedor['descripcion']); ?></p>
        </div>
        </div>
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
                    <?php if ($_SESSION['rol'] === 'admin'): // Mostrar estas opciones solo si es admin ?>
                    <button type="submit" name="eliminar_producto">Eliminar</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endwhile; ?>

        <?php if ($_SESSION['rol'] === 'admin'): // Mostrar estas opciones solo si es admin ?>
            <h2>Agregar Producto</h2>
            <form method="POST" enctype="multipart/form-data">
                <label for="nombre_producto">Nombre del Producto:</label>
                <input type="text" id="nombre_producto" name="nombre_producto" required><br>
                <label for="descripcion_producto">Descripción:</label>
                <textarea id="descripcion_producto" name="descripcion_producto" required></textarea><br>
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01" required><br>
                <label for="imagen_producto">Imagen del Producto:</label>
                <input type="file" id="imagen_producto" name="imagen_producto" accept="image/*" required><br>
                <button type="submit" name="agregar_producto">Agregar Producto</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p>Vendedor no encontrado.</p>
    <?php endif; ?>
</body>
</html>

