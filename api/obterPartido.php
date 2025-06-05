<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../includes/json.php';
include '../includes/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    sendResponse(false, 'ID de partido non válido');
    exit;
}

try {
    $stmt = $conexion->prepare("SELECT * FROM partido WHERE id = ?");
    $stmt->bindValue(1, $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $partido = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($partido) {
        sendResponse(true, 'Partido obtido correctamente', $partido);
    } else {
        sendResponse(false, 'Partido non atopado');
    }
} catch (PDOException $e) {
    sendResponse(false, 'Erro na base de datos: ' . $e->getMessage());
}
?>
