<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $mensaje = htmlspecialchars($_POST['mensaje']);
    $fecha = date('Y-m-d H:i:s');

    $entrada = [
        'nombre' => $nombre,
        'email' => $email,
        'mensaje' => $mensaje,
        'fecha' => $fecha
    ];

    $archivo = '../datos/contacto.json';

    $datos = [];

    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        $datos = json_decode($contenido, true);
    }

    $datos[] = $entrada;
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT));
    header('Location: ../views/contacto.php?enviado=1');
    exit();
}
?>
