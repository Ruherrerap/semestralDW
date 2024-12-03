<!-- CODIGO UTILIZADO PARA AGREGAR USUARIOS A LA BASE DE DATO -->
<?php
require_once 'conexion.php';

$conn = new Conecta();
$cnn = $conn->conectarDB();

$username = 'admin';
$password = password_hash('admin1', PASSWORD_DEFAULT);
$rol = 'admin';

$stmt = $cnn->prepare("INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $rol);
$stmt->execute();
echo "Usuario admin creado correctamente.";
$stmt->close();
$conn->cerrar();
?>
