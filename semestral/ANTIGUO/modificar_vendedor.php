<?php
require_once 'conexion.php';

$conn = new Conecta();
$cnn = $conn->conectarDB();

$vendedor = null;

// Obtener datos del vendedor por ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $cnn->prepare("SELECT * FROM vendedores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $vendedor = $result->fetch_assoc();
    $stmt->close();
}

// Actualizar vendedor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $descripcion = $_POST['descripcion'];

    // Actualizar en la base de datos
    $stmt = $cnn->prepare("UPDATE vendedores SET nombre = ?, email = ?, telefono = ?, descripcion = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $email, $telefono, $descripcion, $id);
    $stmt->execute();
    $stmt->close();

    echo "Vendedor actualizado exitosamente.";
    header("Location: modificar.php");
}

$conn->cerrar();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Vendedor</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="registro.php">Registro de Vendedores</a></li>
        </ul>
    </nav>
    <h1>Modificar Vendedor</h1>

    <?php if ($vendedor): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $vendedor['id']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($vendedor['nombre']); ?>" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendedor['email']); ?>" required><br>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($vendedor['telefono']); ?>" required><br>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($vendedor['descripcion']); ?></textarea><br>
            <button type="submit">Actualizar</button>
        </form>
    <?php else: ?>
        <p>No se encontró el vendedor.</p>
    <?php endif; ?>
</body>
</html>
