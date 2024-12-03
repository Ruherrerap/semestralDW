<?php
require_once 'conexion.php';

$conn = new Conecta();
$cnn = $conn->conectarDB();

$vendedor = null;

// Obtener datos del vendedor por ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $cnn->prepare("SELECT nombre, email, telefono, descripcion, imagen_perfil FROM vendedores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendedor = $result->fetch_assoc();
    $stmt->close();
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
    <?php else: ?>
        <p>Vendedor no encontrado.</p>
    <?php endif; ?>
</body>
</html>
