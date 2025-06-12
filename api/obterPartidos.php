<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
include '../includes/conexion.php';
include '../includes/json.php';

if (!isset($_GET['fecha']) || !isset($_GET['equipo'])) {
    sendResponse(false, 'Parámetros fecha e equipo son requeridos.');
    exit;
}

$mes = date('n', strtotime($partido['fecha']));
$ano = date('Y', strtotime($partido['fecha']));
$equipo = $_GET['equipo'];

if ($ano === false || $mes === false || $mes < 1 || $mes > 12) {
    sendResponse(false, 'Parámetros de data non válidos.');
    exit;
}

try {
    $stmt = $conexion->prepare("
        SELECT * FROM partido 
        WHERE YEAR(fecha) = :ano AND MONTH(fecha) = :mes AND equipo= :equipo
        ORDER BY fecha ASC, hora ASC
    ");
    $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
    $stmt->bindParam(':mes', $mes, PDO::PARAM_INT);
    $stmt->bindParam(':equipo', $equipo, PDO::PARAM_STR);
    $stmt->execute();
    
    $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(true, 'Partidos obtidos correctamente.', $partidos);

} catch (PDOException $e) {
    error_log("API Error (obterPartidos.php): " . $e->getMessage());
    sendResponse(false, 'Erro na base de datos ao obter os partidos.');
}
?>