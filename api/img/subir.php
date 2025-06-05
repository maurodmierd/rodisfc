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

// funcion pra xerar nome único
function generarNome($original_name) {
    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
    $name = pathinfo($original_name, PATHINFO_FILENAME);
    $name = substr($name, 0, 200);
    return array(
        'name'=>$name . '_' . uniqid(),
        'ext'=>$extension
    );
}

$nombre_archivo = generarNome($_FILES['imagen']['name'])['name'];
$ext_archivo = generarNome($_FILES['imagen']['name'])['ext'];

try {
    // Verificar arquivo subido
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        sendResponse(false, 'Non se seleccionou ningún arquivo ou houbo algún erro');
    }
    
    // Obter datos do form
    $nombre_original = trim($_POST['nombre']);
    $categoria = trim($_POST['categoria']);
    $descripcion = trim($_POST['descripcion']);
    if (empty($nombre_original)) {
        sendResponse(false, 'O nome é obligatorio');
    }
    if (empty($categoria) || !in_array($categoria, $categorias_validas)) {
        sendResponse(false, 'A categoria non é válida');
    }
    
    $archivo = $_FILES['imagen'];
    
    // Validacions
    if (!in_array($ext_archivo,$extFotos)) {
        sendResponse(false, "[$ext_archivo] Tipo de arquivo non permitido.  (JPG, JPEG, PNG, GIF, WEBP)");
    }
    if ($archivo['size'] > $max_file_size) {
        sendResponse(false, 'Archivo demasiado grande. Máximo 5MB');
    }
    
    
    $ruta_destino = __DIR__ . "../../../img/$categoria/$nombre_archivo.$ext_archivo";
    if (!move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
        sendResponse(false, 'Error ao gardar o archivo');
    }
    
    //Query para bbdd
    $stmt = $conexion->prepare("
        INSERT INTO img (nombre, categoria, descripcion, ruta, fecha) 
        VALUES (?, ?, ?, ?, NOW())
    ");
    
    $ruta_relativa = "img/$categoria/$nombre_archivo.$ext_archivo";
    
    if ($stmt->execute([$nombre_original, $categoria, $descripcion, $ruta_relativa])) {
        $imagen_id = $conexion->lastInsertId();
        sendResponse(true, 'Imaxe subida correctamente', [
            'id' => $imagen_id,
            'nombre' => $nombre_original,
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
    sendResponse(false, 'Erro da base de datos'.$e->getMessage());
} catch (Exception $e) {
    if (isset($ruta_destino) && file_exists($ruta_destino)) {
        unlink($ruta_destino);
    }
    sendResponse(false, 'Erro interno do servidor');
}