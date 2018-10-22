<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


session_start();
//datos para establecer la conexion con la base de mysql.

if (!isset($_SESSION['k_id']) || $_SESSION['k_id']!=1) {
	//echo $_SESSION['k_id'];
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php?id='.$id.'";
			</SCRIPT>';
}


function formRegistro(){
  
  require('common.php');
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/main.css">
    <link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
    <meta charset="utf-8">
    
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
    
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
    </style>
    
  </head>
  <body>

<form action="registrar.php" method="post" id="form">
	
Tipo:
<Select onchange="show_formfields();" style="width: 150px" id="tipo" name="tipo">
<option value="P" SELECTED>Participante</option>
<option value="C" >Conferencista</option>
<option value="C" >Coordinador</option>
<option value="M" >Monitor</option>
<option value="O" >Organizador</option>
</Select>
<br>

C.C.: 
<input type="text" id="cc" name="cc" size="20" maxlength="100" /><br>
<!--Usar la C.C. como usuario? <input onchange="ccasuser(this)" type="checkbox" name="iserisid" />
<br />
Consecutivo (max 100): 
  <input type="text" id="id" name="id" size="20" maxlength="100" /><br />-->
  
Nombres (max 100): 
  <input type="text" name="username" size="20" maxlength="100" /><br />
<div id="pass" style="display: none">
Password: 
<input value="CII2018" type="password" name="password" size="10" maxlength="10" />
Confirma: <input value="CII2018" type="password" name="password2" size="10" maxlength="10" /><br />
</div>

<Select name="curso" style="display:none">
	<option value="0" SELECTED>--</option>
<?php
	$cursos= $db->db->query("SELECT * FROM cursos WHERE 1");
	while($row=$cursos->fetch_array())
		echo "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".utf8_encode($row[1])."</OPTION>";

?>
</Select>
<br />
<input type="submit" value="Registrar" />
<button onclick="reimprimir()"> Re-Imprimir </button>
<input hidden name="reimp" id="reprint" value="False" />
</form>
<button onclick="location.replace('..')">Atras</button>

<script>
function reimprimir(){
document.getElementById("reprint").value="True";
document.getElementById("form").submit();
}
</script>

<?php
}
// verificamos si se han enviado ya las variables necesarias.
require('common.php');
if (isset($_POST["cc"])) {

	//$id = $_POST["id"];
	$username = $_POST["username"];
	$cc = $_POST["cc"];
	$password = $_POST["password"];
	$password2 = $_POST["password2"];
	$tipo=$_POST["tipo"];
	$curso=$_POST["curso"];
	// Hay campos en blanco
	if ($_POST["reimp"]=="True"){
		$checkuser = $db->db->query("SELECT Id FROM Usuarios WHERE Id='$cc'");
			$username_exist = mysqli_num_rows($checkuser);
			if ($username_exist>0) {
				$query = "UPDATE Usuarios SET valor=1 WHERE Id='$cc'";
				$db->db->query($query);
			}else{
				echo "Usuario no existe";
			}
		formRegistro();
		exit();
	}

	if($username==NULL|$password==NULL|$password2==NULL|$cc==NULL) {
		echo utf8_encode("Uno o más campos están vacios, verifique por favor.");
		formRegistro();
	}else{
		// Coinciden las contraseÃ±as?
		if($password!=$password2 & $tipo!="E") {
			echo "Las contraseñas no coinciden";
			formRegistro();
		}else{
			// Comprobamos si el nombre de usuario o la cuenta de correo ya existían
			$checkuser = $db->db->query("SELECT Id FROM Usuarios WHERE Id='$cc'");
			$username_exist = mysqli_num_rows($checkuser);
			if ($username_exist>0) {
				echo "El nombre de usuario ya existe";
				formRegistro();
			}else{
				$query='SELECT MAX(CC) as CC FROM Usuarios WHERE Tipo="P"';
				$consecutivo=$db->db->query($query)->fetch_object()->CC;
				//echo $query;
				$consecutivo=$consecutivo+1;				
				$password=md5($password);
				$query = 'INSERT INTO Usuarios_temp (Id, Username, CC, U_password, valor, Tipo)
				VALUES (\''.$cc.'\',\''.utf8_decode($username).'\','.$consecutivo.',\''.$password.'\',0,\''.$tipo.'\')';
				//echo $query;
				if (!$db->db->query($query))
          die(mysqli_error($db->db));
				//if ($tipo=="E" or $tipo=="M"){
				//$query = 'INSERT INTO usuarios_curso VALUES (\''.$id.'\',\''.$curso.'\')';
				//$db->db->query($query) or die(mysql_error());
				//}
				echo utf8_encode('El usuario '.$username.' ha sido registrado de manera satisfactoria con número de consecutivo #'.$consecutivo.'<br />');
				//echo 'Ahora puede entrar ingresando su usuario y su password <br />';
				formRegistro();
			}
		}
	}
}else{
	formRegistro();
}
?>
<script>
	function ccasuser(checkb) {
	f=document.getElementById("id");
	g=document.getElementById("cc");
		if (checkb.checked) {
			f.value=g.value;
			f.readOnly = true;
		
		}else{
			f.value="";
			f.readOnly = false;
		}

		
	}
	function show_formfields(){
	f=document.getElementById("pass");
	if (!esparticipante()) {
		f.style.display="inline";
	}
	else {
		f.style.display="none";
		}
	}
	
	function esparticipante(){
	e=document.getElementById("tipo");
	val=e.options[e.selectedIndex].value;
	if (val=="A"){
		return true;
	}
	else {
		return false;
	}
	}
</script>
</body>
</html>
