<?php
if(isset($_POST["btningresar"]))
{
    // $dbhost = "localhost";
    // $dbuser = "root";
    // $dbpass = "1234";
    // $dbname = "sistema_academico2";

    $conn = mysqli_connect("localhost","root","1234","sistema_academico2");
    if(!$conn)
    {
        die("No hay conxion: ".mysqli_connect_error());
    }
    
    $nombre = $_POST["usuario"];
    $pass = $_POST["pass"];

    $query = mysqli_query($conn,"SELECT * FROM Administrador WHERE adm_usuari = '$nombre' AND adm_contraseña = '$pass'");
	$nr = mysqli_num_rows($query);

    
	if($nr==1)
	{
		echo "<script> alert('Bienvenido $nombre'); window.location='tables.html' </script>";

	}else
	{
		echo "<script> alert('Usuario no Existente'); window.location='index.html' </script>";
	}

}


?>
