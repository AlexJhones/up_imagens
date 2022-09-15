<?php

$host = "localhost";
$user = "root";
$pass = "";
$bd = "upload";

$mysqli = new mysqli($host, $user, $pass, $bd);

/* Check Connection */
if($mysqli->connect_errno){
    echo "Falha na conexão com o bando de dados." . $mysqli->connect_error;
    exit();
}
?>