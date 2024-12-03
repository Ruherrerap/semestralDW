<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new Conecta();
    $cnn = $conn->conectarDB();

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $descripcion = $_POST['descripcion'];
    $pagina = strtolower( "vendedores/" . str_replace(' ', '_', $nombre) . "/" . str_replace(' ', '_', $nombre)) . ".html";

    // Crear carpeta para el vendedor
    $carpetaVendedor = "vendedores/" . strtolower(str_replace(' ', '_', $nombre));
    if (!is_dir($carpetaVendedor)) {
        mkdir($carpetaVendedor, 0755, true);
    }

    // Procesar imagen
    $imagen = $_FILES['imagen_perfil']['name'];
    $imagenRuta = $carpetaVendedor . "/" . basename($imagen);
    $imagenNombre = basename($imagen);
    move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $imagenRuta);

    $stmt = $cnn->prepare("INSERT INTO vendedores (nombre, email, telefono, descripcion, pagina, imagen_perfil) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $email, $telefono, $descripcion, $pagina, $imagenRuta);
    $stmt->execute();

    // Crear archivo HTML del vendedor
    $htmlContent = "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Vendedor: $nombre</title>
    <link rel='stylesheet' href='../../css/styles.css'>
    <link rel='stylesheet' href='../../css/nav.css'>
</head>
<body>
    <h1>Vendedor: $nombre</h1>
    <nav>
    <ul>
        <li><a href='../../index.php'>Página Principal</a></li>
    </ul>
    </nav>
    
    <img src='$imagenNombre' alt='Perfil de $nombre' class='profile-img'>
    <p>Email: $email</p>
    <p>Teléfono: $telefono</p>
    <p>Descripción: $descripcion</p>
    <a href='../../index.php'>Volver</a> 
</body>
</html>";

    // Guardar página HTML del vendedor en su carpeta
    file_put_contents($pagina, $htmlContent);

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
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <h1>Registrar Vendedor</h1>
    <nav>
    <ul>
        <li><a href="index.php">Página Principal</a></li>
        <li><a href="registro.php">Registro de Vendedores</a></li>
        <li><a href="eliminar.php">Eliminar Vendedores</a></li>
        <li><a href="modificar.php">Modificar Vendedores</a></li>
    </ul>
    </nav>
    
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
