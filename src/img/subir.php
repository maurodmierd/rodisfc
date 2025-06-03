<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');
include '../../includes/json.php';
include '../../includes/conexion.php';

// configuracions para a subida de imaxes
$max_file_size = 5 * 1024 * 1024; // 5MB
$extFotos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$categorias_validas = ['logos', 'jugadoresSenior', 'jugadoresVeteranos', 'equipo', 'noticias', 'otros'];


// validacion do tipo de archivo con pathinfo
function validarFoto($filename) {
    global $extFotos;
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $extFotos);
}
// funcion pra xerar nome único
function generarNome($original_name) {
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $name = pathinfo($original_name, PATHINFO_FILENAME);
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
    $name = substr($name, 0, 50);
    return $name . '_' . time() . '_' . uniqid() . '.' . $extension;
}


try {
    // Verificar arquivo subido
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(false, 'Non se seleccionou ningún arquivo ou houbo algún erro');
    }
    
    // Obter datos do form
    $nombre_original = trim($_POST['nombre'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    if (empty($nombre_original)) {
        sendResponse(false, 'O nome é obligatorio');
    }
    if (empty($categoria) || !in_array($categoria, $categorias_validas)) {
        sendResponse(false, 'A categoria non é válida');
    }
    
    $archivo = $_FILES['imagen'];
    
    // Validacions
    if (!validarFoto($archivo['name'])) {
        sendResponse(false, 'Tipo de arquivo non permitido.  (JPG, JPEG, PNG, GIF, WEBP)');
    }
    if ($archivo['size'] > $max_file_size) {
        sendResponse(false, 'Archivo demasiado grande. Máximo 5MB');
    }
    
    $nombre_archivo = generarNome($archivo['name']);
    $ruta_destino = "../../img/$directorio_categoria/$nombre_archivo";
    if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        sendResponse(false, 'Error ao gardar o archivo');
    }
    
    //Query para bbdd
    $stmt = $pdo->prepare("
        INSERT INTO img (nombre, categoria, descripcion, ruta, fecha_subida, activo) 
        VALUES (?, ?, ?, ?, NOW(), 1)
    ");
    
    $ruta_relativa = "img/$categoria/$nombre_archivo";
    
    if ($stmt->execute([$nombre_archivo, $categoria, $descripcion, $ruta_relativa])) {
        $imagen_id = $pdo->lastInsertId();
        sendResponse(true, 'Imaxe subida correctamente', [
            'id' => $imagen_id,
            'nombre' => $nombre_archivo,
            'categoria' => $categoria,
            'descripcion' => $descripcion,
            'url' => $ruta_relativa
        ]);
    }
} catch (PDOException $e) {
    // elimina o archivo se hai algun erro
    if (isset($ruta_destino) && file_exists($ruta_destino)) {
        unlink($ruta_destino);
    }
    sendResponse(false, 'Erro da base de datos');
} catch (Exception $e) {
    if (isset($ruta_destino) && file_exists($ruta_destino)) {
        unlink($ruta_destino);
    }
    sendResponse(false, 'Erro interno do servidor');
}