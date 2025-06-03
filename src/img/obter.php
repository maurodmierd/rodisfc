<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../../includes/json.php';
include '../../includes/conexion.php';

try {
    // query para obter imaxes
    $stmt = $pdo->prepare("
        SELECT id, nombre, categoria, descripcion, fecha_subida 
        FROM img 
        WHERE activo = 1 
        ORDER BY fecha_subida DESC
    ");
    $stmt->execute();
    
    $imaxes = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $ruta_archivo = "../../img/" . $row['categoria'] . "/" . $row['nombre'];
        if (file_exists($ruta_archivo)) {
            $imaxes[] = [
                'id' => (int)$row['id'],
                'nombre' => $row['nombre'],
                'categoria' => $row['categoria'],
                'descripcion' => $row['descripcion'] ?? '',
                'fecha_subida' => $row['fecha_subida'],
                'url' => "img/" . $row['categoria'] . "/" . $row['nombre']
            ];
        } else {
            $updateStmt = $pdo->prepare("UPDATE img SET activo = 0 WHERE id = ?");
            $updateStmt->execute([$row['id']]);
        }
    }
    sendResponse(true, 'Imaxes obtidas correctamente', $imagenes);
    
} catch (PDOException $e) {
    sendResponse(false, 'Erro da conexión á base de datos');
} catch (Exception $e) {
    sendResponse(false, 'Erro internoo');
}