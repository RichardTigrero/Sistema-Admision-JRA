<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unidad_educativa";
$port = 3306;
//$password = "root2021";
//$dbname = "unidad_educativa";
//$port = 3308;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}else{
  // echo "conexion existosa.....!!!!";
}


?>