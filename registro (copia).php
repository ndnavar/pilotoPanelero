<?php
require('common.php');
session_start();


if (isset($_SESSION['k_username'])) {
	$r=$db->es_registrador($_SESSION['k_id']);
	if (!$r){
		echo '<SCRIPT LANGUAGE="javascript">
			location.href = "index.php";
			</SCRIPT>';	
	}
}else{
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php?id='.$id.'";
			</SCRIPT>';
}

if($_GET && !empty($_GET["id"])){
	$id_curso=$_GET["id"];
	$nombre_curso = $db->db->query('SELECT Nombre FROM cursos WHERE id="'.$id_curso.'" ')->fetch_object();
	$hora=$db->validar_hora($id_curso);
}else{
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "sel_registro.php";
			</SCRIPT>';
}

if($_POST && !empty($_POST['fname'])){
	
	//$id = substr($_POST['title'],strrpos($_POST['title'], "id=")+3);
	$arr = explode("?", $_POST[$_POST['fname']], 2);
	$id = $arr[0];
	$result = $db->registrar(strip_tags($id),$id_curso,$_SESSION['k_id']);
}
$fname=md5(time());

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
		<h1 style="text-align: center;"><?php echo $nombre_curso->Nombre;?></h1>
		<?php if ($hora[0]) { ?>
		<h2 style="text-align: center;">Bloque: <?php echo $hora[1]; ?> a <?php echo $hora[2]; ?></h2>
		<?php
		} else { ?><h2 style="text-align: center;">--Fuera de la hora de registro--</h2> <?php }
		?>
		<br>
		<form method="post" action="#" id="reg_form">
			QR: <input onkeyup="parse_id()" type="text" name="<?php echo $fname; ?>" id="codigo"/>
			<br><br>
			<input style="margin-left: 40%" type="submit" value="Registrar" />
			<br>
			<input type="hidden" value="<?php echo $fname ?>" name="fname">
			
		</form>
	
		<?php if(isset($result)){
		if($result[0]!=FALSE){
			echo '<p>Registro Exitoso</p>'.$result[1];
		}else{
			echo '<p>'.$result[1].'</p>';
		}
	}?>

<hr>Día: <Select style="width: 150px" onchange="toggle_disp()" ID="Nombre" NAME="Nombre"><option value="0" SELECTED>--</option>
			<?php
				$cursos=$db->db->query("SELECT * FROM cursos WHERE 1");
				while($row=$cursos->fetch_array())
					echo "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".$row[1]."</OPTION>";
			?></Select>	
<br>
<button onclick="chage_id();" style="display: none" id="chang">Cambiar Día</button>
<br>
<button onclick="display();" style="display: none" id="disp">Ver Display</button>
<br>
<button onclick="location.replace('..')">Atras</button>
<script>
function display(){
	e=document.getElementById("Nombre");
	val=e.options[e.selectedIndex].value;
	if (val>0) {
		window.location.href='display.php?id='+val;
	}
}
function chage_id(){
	e=document.getElementById("Nombre");
	val=e.options[e.selectedIndex].value;
	if (val>0) {
		window.location.href='registro.php?id='+val;
	}
}
function parse_id(){
	e=document.getElementById("codigo");
	val=e.value;
	pos=val.search("\\?id\=");
	if (pos>=0) {
		f=document.getElementById("Nombre");
		f.selectedIndex = val.substring(pos+4);
		toggle_disp()
	}
}
function toggle_disp() {
	e=document.getElementById("Nombre");
	val=e.options[e.selectedIndex].value;
	f=document.getElementById("disp");
	g=document.getElementById("chang");
	if (val>0){
		f.style.display="inline";
		g.style.display="inline";
	}
	else {
		f.style.display="none";
		g.style.display="none";
	}
}
</script>	
</body>
</html>

