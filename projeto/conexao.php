<?php 
$server = "localhost"; 
$user = "root"; 
$pass = ""; 
$db = "db_clientes"; 

$conn = mysqli_connect($server, $user, $pass, $db); 

if (!$conn) { 
    die("Connection Error: " . mysqli_connect_error()); 
} 

echo "Connection successfully established."; 
