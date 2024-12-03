<?php
include 'conexion.php';

session_start();
if ($_SESSION['rol'] !== 'admin') {
    echo "<p>No tienes permiso para acceder a esta página.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new Conecta();
    $cnn = $conn->conectarDB();

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $descripcion = $_POST['descripcion'];

    // Crear carpeta para el vendedor
    $carpetaVendedor = "vendedores/" . strtolower(str_replace(' ', '_', $nombre));
    if (!is_dir($carpetaVendedor)) {
        mkdir($carpetaVendedor, 0755, true);
    }

    // Procesar imagen
    $imagen = $_FILES['imagen_perfil']['name'];
    $imagenRuta = $carpetaVendedor . "/" . basename($imagen);
    move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $imagenRuta);

    // Guardar datos en la base de datos
    $stmt = $cnn->prepare("INSERT INTO vendedores (nombre, email, telefono, descripcion, imagen_perfil) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $email, $telefono, $descripcion, $imagenRuta);
    $stmt->execute();
    $vendedorId = $stmt->insert_id;

    // Crear enlace dinámico a vendedor.php
    $pagina = "vendedor.php?id=" . $vendedorId;

    // Actualizar la URL de la página en la base de datos
    $stmt = $cnn->prepare("UPDATE vendedores SET pagina = ? WHERE id = ?");
    $stmt->bind_param("si", $pagina, $vendedorId);
    $stmt->execute();

    $stmt->close();
    $conn->cerrar();

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Vendedor</title>
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
    <h2>Registrar Vendedor</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea><br>
        <label for="imagen_perfil">Imagen de Perfil:</label>
        <input type="file" id="imagen_perfil" name="imagen_perfil" accept="image/*" required><br>
        <button type="submit">Registrar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>