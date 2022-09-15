<?php

$host = "localhost";
$user = "root";
$pass = "";
$bd = "arquivos";

$mysqli = new mysqli($host, $user, $pass, $arquivos);

/* Check Connection */
if($mysqli->connect_errno){
    echo "Falha na conexão com o bando de dados." . $mysqli->connect_error;
    exit();
}
?>