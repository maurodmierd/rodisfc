<?php
session_start();

if (isset($_SESSION['usuario']) && $_SESSION['rol'] == 'admin'){
    if(exec("python python/exportarUsuarios.py")!=false){
        header('Content-Disposition: attachment; filename="usuarios.xlsx"');
        readfile('../python/usuarios.xlsx');
        exit();
    }
}else{
    header('Location: ../views/areaPrivada.php');
    exit();
}