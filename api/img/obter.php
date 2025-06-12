<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../../includes/json.php';
include '../../includes/conexion.php';

try {
    // query para obter imaxes
    $stmt = $conexion->prepare("
        SELECT id, nombre, categoria, descripcion, fecha, ruta
        FROM img 
        WHERE activo = 1 
        ORDER BY fecha DESC
    ");
    $stmt->execute();
    $imaxes = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (file_exists("../../".$row['ruta'])) {
            $imaxes[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'categoria' => $row['categoria'],
                'descripcion' => $row['descripcion'],
                'fecha' => $row['fecha'],
                'ruta' => $row['ruta']
            ];
            
        } else {
            $updateStmt = $conexion->prepare("UPDATE img SET activo = 0 WHERE id = ?");
            $updateStmt->execute([$row['id']]);
            sendResponse(false, 'Erro na ruta da imaxe '.$row['nombre']);
        }
    }
    sendResponse(true, 'Imaxes obtidas correctamente', $imaxes);
    
} catch (PDOException $e) {
    sendResponse(false, 'Erro da conexión á base de datos: '.$e->getMessage());
} catch (Exception $e) {
    sendResponse(false, 'Erro internoo');
}