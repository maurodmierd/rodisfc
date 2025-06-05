<?php
// Cando se envia o formulario, procesase a informacion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST['usuario_id']);
    $texto = htmlspecialchars($_POST['nota']);

    $entrada = [
        'usuario' => $nombre,
        'texto' => $email,
    ];

    $archivo = '../datos/notas.json';

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
    header('Location: ../procesos/gestionarUsuarios.php');
    exit();
}
?>