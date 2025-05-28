<?php
//Destruimos a sesión e rediriximos ao inicio
session_start();
session_destroy();
header("Location: ../index.php");
exit();
