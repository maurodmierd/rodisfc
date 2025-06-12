<?php
// Permite solicitudes desde cualquier origen y define el tipo de contenido.
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include '../includes/conexion.php';
include '../includes/json.php'; // Incluimos la función sendResponse

// Verificamos que se haya proporcionado un DNI
if (!isset($_GET['dni']) || empty($_GET['dni'])) {
    sendResponse(false, 'Non se proporcionou un DNI de xogador.');
    exit;
}

$dni = $_GET['dni'];

try {
    // Preparamos la consulta para obtener los datos del jugador y la ruta de su imagen
    $stmt = $conexion->prepare("
        SELECT j.*, i.ruta as foto_ruta 
        FROM jugadores j
        LEFT JOIN img i ON j.foto_id = i.id
        WHERE j.dni = ?
    ");
    $stmt->execute([$dni]);
    $jugador = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($jugador) {
        // Si se encuentra el jugador, se envían los datos.
        sendResponse(true, 'Datos do xogador obtidos correctamente.', $jugador);
    } else {
        // Si no, se envía un mensaje de error.
        sendResponse(false, 'Non se atopou ningún xogador con ese DNI.');
    }
} catch (PDOException $e) {
    // En caso de error en la base de datos, se envía un mensaje genérico.
    // Es buena práctica registrar el error real en un log del servidor.
    error_log("API Error (obterXogador.php): " . $e->getMessage());
    sendResponse(false, 'Ocorreu un erro na base de datos.');
}
?>