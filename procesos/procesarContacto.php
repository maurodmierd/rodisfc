<?php
// Cando se envia o formulario, procesase a informacion
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

    // Comproba se o JSON existe, 
    if (file_exists($archivo)) {
        // Se existe, recolle o contido e gardao coma un string
        $contenido = file_get_contents($archivo);
        // Decodifica o string do JSON
        $datos = json_decode($contenido, true);
    }

    $datos[] = $entrada;
    // Codifica os datos de novo a formato JSON e gárdao no arquivo
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT));
    header('Location: ../views/contacto.php');
    exit();
}
?>