
<?php
//llama la función de inicio de sesion que requiere los datos de los usuarios
session_start();
//Este programa es el encargado de hacer la validación de usuarios en en las bases de datos,esto tomando los datos ingresados y comparandolos con los existentes
//datos para establecer la conexion con la base de mysql.
require('../View/common.php');

function quitar($mensaje)
{
//Caraxteres que no deben existir en los usuarios
	$nopermitidos = array("'",'\\','<','>',"\"");
	$mensaje = str_replace($nopermitidos, "", $mensaje);
	return $mensaje;
}
$username = $_POST["username"];

if($_POST["username"] != "" && $_POST["password"] != "")
{
	
	$username = $_POST["username"];
	$password = md5($_POST['password']);
	$query='SELECT id,U_password, CC FROM Usuarios WHERE id=\''.$username.'\'';
	
	$result = $db->db->query($query);
	
	//comparacion con la base de datos
	if($row = $result->fetch_array() ){
		//Validacion correcta
		if($row["U_password"] == $password){
			setcookie('k_username', $username , time()+ (10 * 365 * 24 * 60 * 60));
			$_SESSION["k_id"] = $row['id'];
			$_SESSION["k_username"] = $row['CC'];
			$_SESSION["chatuser_name"] = $row['nick'];
			$_SESSION['chatuser'] =  $row['id'];
			
			echo 'ingreso exitoso';
		}else{
			// ingeso no valido
			die("Password incorrecto".$password);
		}
	//}else{
	//	die("Usuario no registrado");
	//	}
	}else{
		//si no encuentra
		die("Username no existente en la base de datos");
	}
	$result->free_result();
}else{
	die("Debe especificar un username y password");
}
$db->db->close();
?>
