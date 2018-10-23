<?php require('../View/common.php');
session_start();
//datos para establecer la conexion con la base de mysql.
include("../Model/bd.php");
$global_dbh = ConexionMySQL();
if (isset($_SESSION['k_username'])) {

	
}else{
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php?id='.$id.'";
			</SCRIPT>';
}

if($_POST && !empty($_POST['npass'])){
	
	if ($_POST['npass']!=$_POST['npass2']){
	echo "Las contraseñas no coinciden, intente de nuevo";}else
	$result = $db->cambiarpass($_SESSION['k_username'],md5($_POST['npass']));
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0" />
    <link rel="stylesheet" href="css/main.css">
     
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
		<form method="post" action="#">
                    <table>
			<tr><td>Contraseña nueva:</td><td><input type="password" name="npass" /></td></tr>
                        <tr><td>Confirmar:</td><td><input type="password" name="npass2" /></td></tr>
                        </table>
			<input type="submit" value="Cambiar Contraseña" />
                        <input type="button" class="btn" onclick="location.href='index.php';" value="Volver" />
		</form>
	
		<?php if(isset($result)){
		echo $result;
                }?>

	
</body>
</html>

