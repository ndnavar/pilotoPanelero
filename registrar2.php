<?php
include("bd.php");
$global_dbh = ConexionMySQL();




function crear_correo($euser){
$cpuser = 'transhum'; // cPanel username
$cppass = 'Z.yC8ZQV*AH:'; // cPanel password
$cpdomain = 'transformacionhumana.com'; // cPanel domain or IP
$cpskin = 'x3'; 
$epass = '123'; // email password
$edomain = 'transformacionhumana.com'; // email domain (usually same as cPanel domain above)
$equota = 20; // amount of space in megabytes
$msg = '';
   if (!empty($euser))
while(true) {


  // Create email account
  $f = fopen ("http://$cpuser:$cppass@$cpdomain:2082/frontend/$cpskin/mail/doaddpop.html?email=$euser&domain=$edomain&password=$epass&quota=$equota", "r");
  if (!$f) {
    $msg = 'Cannot create email account. Possible reasons: "fopen" function allowed on your server, PHP is running in SAFE mode';
    break;
  }

  $msg = "<h2>Email account {$euser}@{$edomain} created.</h2>";
	echo "username-registered";
  // Check result
  while (!feof ($f)) {
    $line = fgets ($f, 1024);
    if (ereg ("already exists", $line, $out)) {
      $msg = "<h2>Email account {$euser}@{$edomain} already exists.</h2>";
      break;
    }
  }
  @fclose($f);

  break;

}
}

// verificamos si se han enviado ya las variables necesarias.

if (isset($_POST["documento"])) {
	$tipou=$_POST["tipou"];
	$documento = $_POST["documento"];
	$password = $_POST["password"];
	$password2 = $_POST["password2"];
	$nombre=$_POST["nombres"];
	$apellido=$_POST["apellidos"];
	$extension=$_POST["extension"];
	$tvehiculo = $_POST["tvehiculo"];
	$capacidad = $_POST["capacidad"];
	$latitud=$_POST["lat"];
	$longitud=$_POST["long"];
	$correo=$_POST["correo"];
	if (isset($_POST["cacaotero"])){$cacaotero=1;}else$cacaotero=2;
	if (isset($_POST["platanero"])){$platanero=1;}else$platanero=2;
	if (isset($_POST["carnicos"])){$carnicos=1;}else$carnicos=2;
	$lugar=$_POST["lugar"];

	// Hay campos en blanco
	if($documento==NULL|$password==NULL|$password2==NULL|$correo==NULL) {
		echo "empy-field";
	}else{
		// ¿Coinciden las contraseñas?
		if($password!=$password2) {
			echo "not-same-password";
		}else{
			// Comprobamos si el nombre de username o la cuenta de correo ya existían
			$checkuser = mysql_query("SELECT U_productor FROM Usuarios WHERE U_productor='$documento'");
			$username_exist = mysql_num_rows($checkuser);
			if ($username_exist>0) {
				echo "user-or-mail-used";
			}else{
				$password=md5($password);
				$query = 'INSERT INTO Usuarios (username, U_productor,U_password)
				VALUES (\''.$nombre.'\',\''.$documento.'\',\''.$password.'\')';
				mysql_query($query) or die(mysql_error());
						//crear_correo($username);
				if ($tipou=="productor"){
				$query = 'INSERT INTO Productores (P_nombres, P_apellidos,P_documento,P_telefono,P_correo,P_Platanero,P_Cacaotero,P_Carnicos)
				VALUES (\''.$nombre.'\',\''.$apellido.'\',\''.$documento.'\',\''.$telefono.'\',\''.$correo.'\',\''.$platanero.'\',\''.$cacaotero.'\',\''.$carnicos.'\')';
				mysql_query($query) or die(mysql_error());
				}
				if ($tipou=="transportador"){
				$lugar=$nombre." ".$apellido;
				$query = 'INSERT INTO Transportadores (T_nombres, T_apellidos,T_documento,T_telefono,T_correo,T_tvehiculo,T_capacidad)
				VALUES (\''.$nombre.'\',\''.$apellido.'\',\''.$documento.'\',\''.$telefono.'\',\''.$correo.'\',\''.$tvehiculo.'\','.$capacidad.')';
				mysql_query($query) or die(mysql_error());
				}
				if ($tipou=="consumidor"){
				$lugar=$nombre." ".$apellido;
				$query = 'INSERT INTO Consumidores (C_nombres, C_apellidos,C_documento,C_telefono,C_correo)
				VALUES (\''.$nombre.'\',\''.$apellido.'\',\''.$documento.'\',\''.$telefono.'\',\''.$correo.'\')';
				mysql_query($query) or die(mysql_error());
				}

$query = 'INSERT INTO Ubicacion (Ub_documento, Ub_nombre,Ub_latitud,Ub_longitud,Ub_extension)
VALUES (\''.$documento.'\',\''.$lugar.'\','.$latitud.','.$longitud.','.$extension.')';

				mysql_query($query) or die(mysql_error());
				
echo "registro exitoso";
			}
		}
	}
}else{

}
?>
