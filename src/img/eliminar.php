<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../../includes/json.php';
include '../../includes/conexion.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!isset($input['id']) || !is_numeric($input['id'])) {
        sendResponse(false, 'ID de imaxe non válido');
    }
    
    // Query para seleccionar a imaxe a eliminar
    $stmt = $pdo->prepare("SELECT nombre, categoria FROM img WHERE id = ? AND activo = 1");
    $stmt->execute([(int)$input['id']]);
    $imagen = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$imagen) {
        sendResponse(false, 'Imaxe non atopada');
    }
    
    $ruta_archivo = "../../img/" . $imagen['categoria'] . "/" . $imagen['nombre'];
    
    // Eliminar arquivo
    $archivo_eliminado = true;
    if (file_exists($ruta_archivo)) {
        $archivo_eliminado = unlink($ruta_archivo);
    }
    $deleteStmt = $pdo->prepare("UPDATE img SET activo = 0 WHERE id = ?");
    if ($deleteStmt->execute([$imagen_id])) {
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