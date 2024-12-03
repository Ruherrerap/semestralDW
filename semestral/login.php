<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $conn = new Conecta();
    $cnn = $conn->conectarDB();

    $stmt = $cnn->prepare("SELECT id, password, rol FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashedPassword, $rol);

    if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['rol'] = $rol;

        header('Location: index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
    $conn->cerrar();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <form method="POST">
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>

