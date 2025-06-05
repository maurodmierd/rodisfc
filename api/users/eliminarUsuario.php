<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../../includes/json.php';
include '../../includes/conexion.php';

if (!isset($_GET['id'])) {
    sendResponse(false, 'ID de noticia non válido');
    exit;
}

try {
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ? AND rol != 'admin'");
    $stmt->execute([$_GET['id']]);
    if ($stmt->rowCount() > 0) {
        sendResponse(true, 'Usuario eliminado correctamente.');
    } else {
        sendResponse(false, 'Non se pode eliminar o usuario. Asegúrate de que o ID é correcto e que non é un administrador.');
    }
} catch (PDOException $e) {
    sendResponse(false, 'Erro da base de datos: ' . $e->getMessage());
}
