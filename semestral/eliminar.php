<?php
require_once 'conexion.php';

session_start();
if ($_SESSION['rol'] !== 'admin') {
    echo "<p>No tienes permiso para acceder a esta página.</p>";
    exit;
}

$conn = new Conecta();
$cnn = $conn->conectarDB();

// Manejar eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Obtener datos del vendedor para eliminar carpeta
    $stmt = $cnn->prepare("SELECT pagina FROM vendedores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($pagina);
    $stmt->fetch();
    $stmt->close();

    // Eliminar carpeta del vendedor
    $carpetaVendedor = "vendedores/" . pathinfo($pagina, PATHINFO_FILENAME);
    if (is_dir($carpetaVendedor)) {
        array_map('unlink', glob("$carpetaVendedor/*.*")); // Eliminar archivos dentro de la carpeta
        rmdir($carpetaVendedor); // Eliminar carpeta
    }

    // Eliminar registro de la base de datos
    $stmt = $cnn->prepare("DELETE FROM vendedores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "Vendedor eliminado exitosamente.";
}

// Obtener lista de vendedores
$result = $cnn->query("SELECT id, nombre FROM vendedores");
$vendedores = $result->fetch_all(MYSQLI_ASSOC);

$conn->cerrar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Vendedores</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- <link rel="stylesheet" href="css/nav.css"> -->
    
</head>
<body>
        <h1 class="titulo">BILUBILU STORE</h1>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="registro.php">Registro de Vendedores</a></li>
            <li><a href="eliminar.php">Eliminar Vendedores</a></li>
            <li><a href="modificar.php">Modificar Vendedores</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <h2>Eliminar Vendedores</h2>
    
    <ul>
        <?php foreach ($vendedores as $vendedor): ?>
            <li>
                <?php echo htmlspecialchars($vendedor['nombre']); ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $vendedor['id']; ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
