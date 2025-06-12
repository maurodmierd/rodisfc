<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../../views/areaPrivada.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = htmlspecialchars($_POST['usuario_id']);
    $texto_nota = htmlspecialchars($_POST['nota']);

    $archivo = '../../datos/notas.json';
    $datos = [];

    if (file_exists($archivo)) {
        $contenido = file_get_contents($archivo);
        $datos = json_decode($contenido, true);
        if (!is_array($datos)) {
            $datos = [];
        }
    }

    $nota_existente = false;
    foreach ($datos as $key => $nota) {
        if (isset($nota['usuario']) && $nota['usuario'] == $usuario_id) {
            $datos[$key]['texto'] = $texto_nota;
            $nota_existente = true;
            break;
        }
    }

    if (!$nota_existente) {
        $nueva_entrada = [
            'usuario' => $usuario_id,
            'texto' => $texto_nota,
        ];
        $datos[] = $nueva_entrada;
    }

    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT));
    
    header('Location: ../../procesos/gestionarUsuarios.php');
    exit();
}
?>