<?php
//Funcion para procesar JSON
function sendResponse($success, $message = '', $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'imagenes' => $data
    ]);
    exit;
}