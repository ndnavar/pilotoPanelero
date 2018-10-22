<?php
require('common.php');
session_start();

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
    
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
    
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
    </style>

  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="js/jquery-1.12.4.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#desde" ).datepicker();
    $( "#desde" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
    $( "#hasta" ).datepicker();
    $( "#hasta" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
  } );
  </script>
  </head>
  <body>

<form action="reportes.php" method="post">


Curso: <Select name="curso">
	<option value="0" SELECTED>--</option>
<?php
	$cursos= $db->db->query("SELECT * FROM cursos WHERE 1");
	while($row=$cursos->fetch_array())
		echo "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".$row[1]."</OPTION>";

?>
</Select>
<br />
Desde:<input type="text" id="desde" name="desde">
Hasta:<input type="text" id="hasta" name="hasta">
Discriminar por sesión?<input type="checkbox" id="sesion" name="sesion">
<br />
<input type="submit" value="Generar Reporte" />
</form>
<button onclick="location.replace('..')">Atras</button>
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
	if (val=="E"){
		return true;
	}
	else {
		return false;
	}
	}
</script>
</body>
</html>
<?php
}
// verificamos si se han enviado ya las variables necesarias.
if (isset($_POST["curso"])) {

	$desde=$_POST["desde"];
  $hasta=$_POST["hasta"];
	$curso=$_POST["curso"];
	$sesion=$_POST["sesion"];
        // Hay campos en blanco
	if($curso==NULL|$desde==NULL|$hasta==NULL) {
		echo "Uno o más campos están vacios, verifique por favor.";
		formRegistro();
	}else{
    
    $query="SELECT Nombre from cursos WHERE Id=$curso";
    $nombre_curso = $db->db->query($query);
    $nombre_curso = $nombre_curso->fetch_array();
    
    $arr = explode(' ',trim($nombre_curso["Nombre"])); 
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$desde.'_'.$hasta.'_'.$arr[0].$arr[1].'.csv');
    $output = fopen('php://output', 'w');
    
        echo $nombre_curso["Nombre"]."\n";
            
            $query= "SELECT DISTINCT add_date FROM Registrados where id_curso='$curso' and add_date >= '$desde' and add_date <= '$hasta'";
            $fechas = $db->db->query($query);
            $fechas_array[0]="Documento";
            $fechas_array[1]="Nombre";
		$query= "SELECT hora_inicio, hora_fin FROM horarios_curso where id_curso='$curso'";
            $horarios = $db->db->query($query);
			$num=mysqli_num_rows($horarios);
			$sesiones_array[0]="";$sesiones_array[1]="";
			
			while( $horario = $horarios->fetch_assoc()){
				$sesion_array[] = [$horario["hora_inicio"],$horario["hora_fin"]];
			}
			
            while( $fecha = $fechas->fetch_assoc()){
				if ($sesion){
				for ($i=0;$i<$num;$i++){
                $fechas_array[] = $fecha["add_date"];
				
				$sesiones_array[] = $sesion_array[$i][0]." - ".$sesion_array[$i][1];
				}}
					else{
							$fechas_array[] = $fecha["add_date"];
					}
            }
            fputcsv($output, $fechas_array);
			fputcsv($output, $sesiones_array);
            $query="SELECT Id,username from Usuarios where Tipo='P'";
            $estudiantes =$db->db->query($query);
            while ($estudiante = $estudiantes->fetch_array()){
                
                $est_id=$estudiante["Id"];
                $query = "SELECT Registrados.add_date,Registrados.add_hour FROM `Usuarios`,Registrados where Usuarios.Id=Registrados.Id and Registrados.id_curso='$curso' and Registrados.add_date >= '$desde' and Registrados.add_date <= '$hasta' and Usuarios.Id='$est_id'";
                $asisxestdiante =$db->db->query($query);
                $dias=array();
				for ($i=0;$i<count($fechas_array);$i++){
					$dias[$i]="";
				}
                $dias[0]=$estudiante["Id"];
                $dias[1]=$estudiante["username"];
                while ($asisdia = $asisxestdiante->fetch_assoc()){
                    if ($sesion){
					//echo "array_search(".$asisdia["add_date"].",".$fechas_array;
					$columna_fecha = array_search($asisdia["add_date"], $fechas_array);
					//echo $columna_fecha;
					for ($i=0;$i<$num;$i++){
					if ($asisdia["add_hour"]>$sesion_array[$i][0] && $asisdia["add_hour"]<$sesion_array[$i][1]){
                    //echo 'dias['.($columna_fecha+$i).']="X"';
					$dias[$columna_fecha+$i]="X";
					}} }
					else {
						$dias[array_search($asisdia["add_date"], $fechas_array)]="X";
					}
                }
				fputcsv($output, $dias); 
	}}
}else{
	formRegistro();
}
?>
