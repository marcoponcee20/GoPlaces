<?php
$host = '127.0.0.1';
$dbName = 'goplaces';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}

if(isset($_POST['registrarse'])) {
    $nombre = $_POST['name'];
    $contrasena = $_POST['password'];

    $sql = "SELECT * FROM iniciosesion WHERE nombre = :nombre";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo 'El usuario ya existe';
    } else {
        $contrasenaHash = hash('SHA256', $contrasena); // Encripta la contraseña utilizando SHA256

        try {
            $sql = "INSERT INTO iniciosesion (nombre, contrasena) VALUES (:nombre, :contrasena)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':contrasena', $contrasenaHash);
            $stmt->execute();
            echo 'Registro exitoso';

            header('Location: principal.html');
            exit;
        } catch(PDOException $e) {
            echo 'Error al registrar: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro-GoPlaces</title>
    <link rel="stylesheet" type="text/css" href="style-registro.css">
    <link rel="shortcut icon" href="images/favicon.png">
</head>

<body>
    <div class="container">
        <form action="registro.php" name="" method="POST">
            <h2>Registro</h2>
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Iniciar Sesión" name="iniciarsesion" class="btn-login">
                <input type="submit" value="Registrarse" name="registrarse" class="btn-register">
            </div>
        </form>
    </div>
</body>

</html>
