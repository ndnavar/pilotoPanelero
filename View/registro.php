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
    <script type="text/javascript" src="js/instascan.min.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
     
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
	<meta charset="UTF-8">
	<title></title>
<script>
	function getLocationa() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        lat.value = "Geolocation is not";
								lon.value="supported by this browser.";
    }
}
function showPosition(position) {
    lat.value = position.coords.latitude;
				lat.readonly = true; 
				lon.value= position.coords.longitude;
				lon.readonly = true; 
}
    $(document).ready(function() {
					
var lat = document.getElementById("lat");
var lon = document.getElementById("lon");

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        lat.value = "Geolocation is not";
								lon.value="supported by this browser.";
    }
}
function showPosition(position) {
    lat.value = position.coords.latitude;
				lat.readonly = true; 
				lon.value= position.coords.longitude;
				lon.readonly = true; 
}
				getLocation();	
					
    // Variable to hold request
    var request;
    
    // Bind to the submit event of our form
    $("#reg_form").submit(function(event){
        // Prevent default posting of form - put here to work in case of errors
        event.preventDefault();
    
        // Abort any pending request
        if (request) {
            request.abort();
        }
        // setup some local variables
        var $form = $(this);
    
        // Let's select and cache all the fields
        var $inputs = $form.find("input, select, button, textarea");
    
        // Serialize the data in the form
        var serializedData = $form.serialize();
    
        // Let's disable the inputs for the duration of the Ajax request.
        // Note: we disable elements AFTER the form data has been serialized.
        // Disabled form elements will not be serialized.
        $inputs.prop("disabled", true);

        // Fire off the request to /form.php
        request = $.ajax({
            url: "../Controller/reg.php",
            type: "post",
            data: serializedData
        });
    
        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
	    response=JSON.parse(response);
            var sound;
			if (response[0]){
	    	sound = document.getElementById("ok");
          	sound.play();
            $("#response").html("<p>"+response[1]+"<p>");
            $('#reg_form')[0].reset();
            }
            else{
            $("#response").html("<p>"+response[1]+"<p>");
			sound = document.getElementById("fail");
          	sound.play();}
        });
    
        // Callback handler that will be called on failure
        request.fail(function (jqXHR, textStatus, errorThrown){
            // Log the error to the console
            alert("Ha ocurrido un error en el envío. Intente nuevamente");
        });
    
        // Callback handler that will be called regardless
        // if the request failed or succeeded
        request.always(function () {
            // Reenable the inputs
            
            $inputs.prop("disabled", false);
            
        });
        $inputs.prop("disabled", false);
    });
    
    });
</script>
</head>
<body>

		<h1 style="text-align: center;"><?php echo $nombre_curso->Nombre;?></h1>
		<?php if ($hora[0]) { ?>
		<h2 style="text-align: center;">Bloque: <?php echo $hora[1]; ?> a <?php echo $hora[2]; ?></h2>
		<?php
		} else { ?><h2 style="text-align: center;">--Fuera de la hora de registro--</h2> <?php }
		?>
		<br>
		<form method="post" action="#" id="reg_form" style="width: 300px;margin: auto;text-align: center;">
			
			Trapiche:
			
    <?php
	$cursos= $db->db->query("SELECT * FROM Usuarios WHERE Id='".$_SESSION['k_id']."'");
	while($row=$cursos->fetch_array())
		echo "<b>".$row[1]."</b>";

    ?>

			
			<hr><H2>Producción</H2>
			<div class="ss-item-required">
			Caña: <input required type="number" name="prod_ca" style="width: 59px"> Kg <br>
			Panela: <input required type="number" name="prod_pa" style="width: 59px"> Kg <br><br>
			</div>
			<hr><H2>Identificación</H2>
			<video id="preview" style="width: 70%;" onclick="scan()"></video><br>
			C.C.: <input required onkeyup="parse_id()" type="text" name="codigo" id="codigo"/>
			<br><br>
			<input type="hidden" value="<?php echo $fname ?>" name="fname">
			<input type="hidden" value="<?php echo $id_curso ?>" name="curso">
			<input type="hidden" value="<?php echo $_SESSION['k_id'] ?>" name="registrador">
			<div class="ss-item-required">
			Lat: <input type="text" value="" id="lat" name="lat">
			Long: <input type="text" value="" id="lon" name="lon">
			</div>
			<input type="button" onclick="getLocationa();" value="Traer coordenadas"><br><br>
			
			<input style="margin-left: 40%" type="submit" value="Registrar" />
			
			<div id="response"></div>	
			<audio id="ok" src="sounds/ok.wav" autostart="false" ></audio>	
			<audio id="fail" src="sounds/fail.wav" autostart="false" ></audio>	
</form>
	
		

<hr>Lote: <Select style="width: 150px" onchange="toggle_disp()" ID="Nombre" NAME="Nombre"><option value="0" SELECTED>--</option>
			<?php
				$cursos=$db->db->query("SELECT * FROM cursos WHERE 1");
				while($row=$cursos->fetch_array())
					echo "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".$row[1]."</OPTION>";
			?></Select>	
<br>
<button onclick="chage_id();" style="display: none" id="chang">Cambiar Lote</button>
<br>
<button onclick="display();" style="display: none" id="disp">Ver Registros</button>
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
		toggle_disp();
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
<script type="text/javascript">
	function formcheck() {
  var fields = $(".ss-item-required")
        .find("select, textarea, input").serializeArray();
  var a = true;
  $.each(fields, function(i, field) {
    if (!field.value)
      a = false;
   });

  return a;
}
	
	
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview'),mirror: false,refractoryPeriod: 2500});
      scanner.addListener('scan', function (content) {
        $("#codigo").val(content);
								if (formcheck())
								$("#reg_form").submit();
								else
								alert ("Asegurese de llenar todos los campos");
      });
	
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
	var msg="";	
	for (var i = 0; i < cameras.length; i++) {
        msg = msg+"["+i+"]- Camara #"+i+"\n";
    	}
	  var camNum =  cameras.length-1;

while ( isNaN(camNum) || camNum == null || camNum < 0 || camNum > cameras.length ) {
    camNum = prompt("Seleccionó una opción invalida.\nSeleccione la camara a usar:\n"+msg, "0");
} 
          scanner.start(cameras[camNum]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
function scan(){
let result = scanner.scan();
if (result){
 $("#codigo").val(result.content);
}
else{
 $("#codigo").val("nada");
}
}
   </script>	
</body>
</html>

