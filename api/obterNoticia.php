<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../includes/json.php';
include '../includes/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    sendResponse(false,'ID de noticia non válido');
    exit;
}

$noticiaId = (int)$_GET['id'];

try {
    $stmt = $conexion->prepare("SELECT n.id,n.titulo,n.contenido,n.fecha,n.categoria,i.ruta
                                FROM noticias n
                                inner join img i on n.imagen_id = i.id
                                WHERE n.id = ? AND n.categoria = 'publica'
                            ");
    $stmt->execute([$noticiaId]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($noticia) {
        sendResponse(true,'ok',$noticia);
    } else {
        sendResponse(false,'Noticia non atopada');
    }
} catch (PDOException $e) {
    sendResponse(false,'Erro na base de datos: '.$e->getMessage());
}

