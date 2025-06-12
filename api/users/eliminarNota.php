<?php
header('Content-Type: application/json');
include '../../includes/json.php';

session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    sendResponse(false, 'Acceso non autorizado');
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['usuario_id'])) {
    sendResponse(false, 'Falta o ID do usuario.');
    exit;
}

$usuario_id = $input['usuario_id'];
$archivo = '../../datos/notas.json';
$datos = [];

if (file_exists($archivo)) {
    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido, true);
} else {
    sendResponse(false, 'O arquivo de notas non existe.');
    exit;
}

$nota_encontrada = false;
$datos_actualizados = [];

foreach ($datos as $nota) {
    if (isset($nota['usuario']) && $nota['usuario'] == $usuario_id) {
        $nota_encontrada = true;
        // No añadir esta nota al nuevo array para eliminarla
    } else {
        $datos_actualizados[] = $nota;
    }
}

if ($nota_encontrada) {
    if (file_put_contents($archivo, json_encode($datos_actualizados, JSON_PRETTY_PRINT))) {
        sendResponse(true, 'Nota eliminada correctamente.');
    } else {
        sendResponse(false, 'Erro ao gardar o arquivo de notas.');
    }
} else {
    sendResponse(false, 'Non se atopou a nota para este usuario.');
}
