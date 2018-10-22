<?php
require('common.php');
session_start();
//datos para establecer la conexion con la base de mysql.
include("bd.php");
$global_dbh = ConexionMySQL();

if (!isset($_SESSION['k_id']) || $_SESSION['k_id']!=1) {
	//echo $_SESSION['k_id'];
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php?id='.$id.'";
			</SCRIPT>';
}


function formRegistro($msg ="Utilice esta funcion con sumo cuidado" ){
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/main.css">
    <link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
    
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
    
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
    </style>
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  </head>
  <body>
<?php echo $msg; ?>
<form action="sql.php" method="post">
<br />
Consulta SQL:<textarea name="query" rows="20" cols="80"></textarea>
<br />
<input type="submit" value="Realizar consulta" />
</form>
<button onclick="location.replace('..')">Atras</button>
</body>
</html>
<?php
}
// verificamos si se han enviado ya las variables necesarias.
if (isset($_POST["query"])) {
    $query=$_POST["query"];
    if (mysql_query($query)){
formRegistro("Consulta exitosa");
	}
else{
formRegistro("Error");
	}
}else{
	formRegistro();
}
?>