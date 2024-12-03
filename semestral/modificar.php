<?php
include 'conexion.php';

session_start();
if ($_SESSION['rol'] !== 'admin') {
    echo "<p>No tienes permiso para acceder a esta página.</p>";
    exit;
}


$conn = new Conecta();
$cnn = $conn->conectarDB();

// Obtener todos los vendedores para mostrarlos
$vendedores = $cnn->query("SELECT id, nombre FROM vendedores");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $descripcion = $_POST['descripcion'];
    $imagenRuta = null;

    // Procesar nueva imagen si se sube
    if (!empty($_FILES['imagen_perfil']['name'])) {
        // Obtener la ruta actual de la imagen
        $stmt = $cnn->prepare("SELECT imagen_perfil FROM vendedores WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($imagenActual);
        $stmt->fetch();
        $stmt->close();

        // Crear la carpeta del vendedor si no existe
        $carpetaVendedor = "vendedores/" . strtolower(str_replace(' ', '_', $nombre));
        if (!is_dir($carpetaVendedor)) {
            mkdir($carpetaVendedor, 0755, true);
        }

        // Subir la nueva imagen
        $imagenRuta = $carpetaVendedor . "/" . basename($_FILES['imagen_perfil']['name']);
        move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $imagenRuta);

        // Eliminar la imagen anterior si existía
        if ($imagenActual && file_exists($imagenActual)) {
            unlink($imagenActual);
        }
    }

    // Actualizar los datos en la base de datos
    $stmt = $cnn->prepare("UPDATE vendedores SET nombre = ?, email = ?, telefono = ?, descripcion = ?, imagen_perfil = COALESCE(?, imagen_perfil) WHERE id = ?");
    $stmt->bind_param("sssssi", $nombre, $email, $telefono, $descripcion, $imagenRuta, $id);
    $stmt->execute();
    $stmt->close();

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

    <h2>Modificar Vendedor</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="id">Seleccionar Vendedor:</label>
        <select id="id" name="id" required>
            <?php while ($row = $vendedores->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['nombre']); ?></option>
            <?php endwhile; ?>
        </select><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br>
        <label for="imagen_perfil">Imagen de Perfil (opcional):</label>
        <input type="file" id="imagen_perfil" name="imagen_perfil" accept="image/*"><br>
        <button type="submit">Modificar</button>
    </form>
</body>
</html>

