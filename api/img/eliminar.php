<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../../includes/json.php';
include '../../includes/conexion.php';

session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../views/areaPrivada.php');
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['id']) || !is_numeric($input['id'])) {
        sendResponse(false, 'ID de imaxe non válido');
    }
    
    // Query para seleccionar a imaxe a eliminar
    $stmt = $conexion->prepare("SELECT ruta FROM img WHERE id = :id AND activo = 1");
    $stmt->bindValue(':id',$input['id'], PDO::PARAM_INT);
    $stmt->execute();
    $ruta_archivo =$stmt->fetch(PDO::FETCH_ASSOC)['ruta'];

    if (!$ruta_archivo) {
        sendResponse(false, 'Imaxe non atopada');
    }
    
    // Eliminar arquivo
    $archivo_eliminado = true;
    $ruta_archivo = __DIR__."../../$ruta_archivo";
    if (file_exists($ruta_archivo)) {
        $archivo_eliminado = unlink($ruta_archivo);
    }
    $deleteStmt = $conexion->prepare("UPDATE img SET activo = 0 WHERE id = :id");
    $deletestmt->bindValue(':id',$input['id'], PDO::PARAM_INT);
    if ($deleteStmt->execute()) {
        if (!$archivo_eliminado) {
            sendResponse(true, 'Imaxe eliminada da base de datos, arquivo físico non encontrado');
        } else {
            sendResponse(true, 'Imaxe eliminada correctamente');
        }
    } else {
        sendResponse(false, 'Error al eliminar la imagen');
    }
    
} catch (PDOException $e) {
    sendResponse(false, 'Erro da base de datos');
} catch (Exception $e) {
    sendResponse(false, 'Erro interno do servidor');
}