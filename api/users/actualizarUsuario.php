<?php
include '../includes/conexion.php';
// Cando se envia o formulario, procesase a informacion
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = htmlspecialchars($_POST['id']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellidos = htmlspecialchars($_POST['apellidos']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $rol = htmlspecialchars($_POST['rol']);

    foreach ([$id, $nombre, $apellidos, $rol] as $campo) {
        if (empty($campo)) {
            exit("Campos obrigatorios non poden estar baleiros.");
        }
    }

    try{
        $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, email = ?, telefono = ?, rol = ? WHERE id = ?");
        $stmt->execute([$nombre, $apellidos, $email, $telefono, $rol, $id]);

        if ($stmt->rowCount() > 0) {
            exit ("Usuario actualizado correctamente.");
        } else {
            exit ("Non se realizaron cambios ou o usuario non existe.");
        }
    }catch (PDOException $e) {
        exit("Erro ao conectar coa base de datos: " . $e->getMessage());
    }

}
?>