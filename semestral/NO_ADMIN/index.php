<?php
require_once 'conexion.php';

$conn = new Conecta();
$cnn = $conn->conectarDB();

$query = "SELECT * FROM vendedores";
$result = $cnn->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Vendedores</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
    <h1>Vendedores Registrados</h1>
<nav>
    <ul>
        <li><a href="index.php">Página Principal</a></li>
        <li><a href="registro.php">Registro de Vendedores</a></li>
        <li><a href="eliminar.php">Eliminar Vendedores</a></li>
        <li><a href="modificar.php">Modificar Vendedores</a></li>
    </ul>
</nav>


    <div class="grid-container">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="grid-item">
            <img src="<?= htmlspecialchars($row['imagen_perfil']) ?>" alt="Perfil de <?= htmlspecialchars($row['nombre']) ?>" class="profile-img">
            <h3><?= htmlspecialchars($row['nombre']) ?></h3>
            <p>Email: <?= htmlspecialchars($row['email']) ?></p>
            <p>Teléfono: <?= htmlspecialchars($row['telefono']) ?></p>
            <a href=<?= htmlspecialchars($row['pagina']) ?>>"Ver Página" </a>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php $conn->cerrar(); ?>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    .grid-item {
        border: 1px solid #ddd;
        padding: 15px;
        text-align: center;
        background-color: #fff;
        border-radius: 10px;
    }
    .profile-img {
        max-width: 100%;
        height: auto;
        border-radius: 50%;
    }
</style>

