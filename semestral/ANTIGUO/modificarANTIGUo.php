<?php
require_once 'conexion.php';

$conn = new Conecta();
$cnn = $conn->conectarDB();

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
    <title>Modificar Vendedores</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/nav.css">
</head>
<body>
<h1>Modificar Vendedores</h1>

    <nav>
        <ul>
            <li><a href="index.php">Página Principal</a></li>
            <li><a href="registro.php">Registro de Vendedores</a></li>
            <li><a href="eliminar.php">Eliminar Vendedores</a></li>
            <li><a href="modificar.php">Modificar Vendedores</a></li>
        </ul>
    </nav>

    
    <ul>
        <?php foreach ($vendedores as $vendedor): ?>
            <li>
                <?php echo htmlspecialchars($vendedor['nombre']); ?>
                <form method="GET" action="modificar_vendedor.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $vendedor['id']; ?>">
                    <button type="submit">Modificar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
