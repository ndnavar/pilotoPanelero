<?php
require('common.php');
session_start();

if (isset($_SESSION['k_username'])) {
	$nombre=$db->nombre($_SESSION['k_id']);
	
}else{
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php?id='.$id.'";
			</SCRIPT>';
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
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<h2>Buenos días <?php echo $nombre; ?>,</h2>
	<p>Selecciona un Lote de producción:</p>
	<form method="get" action="registro.php">
		<Select name="id"><option value="0" SELECTED>--</option>
	<?php
	$cursos=$db->db->query("SELECT * FROM cursos WHERE 1");
	while($row=$cursos->fetch_array())
		echo "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".utf8_encode($row[1])."</OPTION>"; ?>
	</Select><br><input type="submit" value="Continuar" /></form>
</body>
</html>