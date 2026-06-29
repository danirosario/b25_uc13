<?php 
$server = "localhost"; 
$user = "root"; 
$pass = ""; 
$db = "cliente"; 

$conn = mysqli_connect($server, $user, $pass, $db); 

if (!$conn) { 
    die("Connection Error: " . mysqli_connect_error()); 
} 

// echo "Connection successfully established."; 
