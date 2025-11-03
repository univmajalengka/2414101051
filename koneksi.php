<?php

$db_host = 'localhost';
$db_user = 'tugaspabw_2414101051'; 
$db_pass = 'irfa2414101051';     
$db_name = 'tugaspabw_2414101051';


$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);


if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>