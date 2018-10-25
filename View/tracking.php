<?php
require('common.php');
session_start();

//Ya iniciado el progrma se debe iniciar sesión, de esta parte se hace el llamadao de usuario
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
}l

//Si no se encuentra registrado manda la opcion de registrarse

if($_POST && !empty($_POST['fname'])){
	
	//$id = substr($_POST['title'],strrpos($_POST['title'], "id=")+3);
	$arr = explode("?", $_POST[$_POST['fname']], 2);
	$id = $arr[0];
	$result = $db->registrar(strip_tags($id),$id_curso,$_SESSION['k_id']);
}
$fname=md5(time());
//En esta aprte esta todo lo que es de estilo de texto, todo lo realacionado con el diseño. 
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
//En esta sección se analiza todo l	
    $(document).ready(function() {
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
            url: "../Controller/track.php",
            type: "post",
            data: serializedData
        });
    
        // Callback handler that will be called on success
     request.done(function (response, textStatus, jqXHR){
	    response=JSON.parse(response);
					var sound;
            if (response[0]){
	    	     sound = document.getElementById("ok");
          	//sound.play();
            $("#response").html("<p>"+response[1]+"<p>");
            $('#reg_form')[0].reset();
            }
            else{
            $("#response").html("<p>"+response[1]+"<p>");
											sound = document.getElementById("fail");
          	//sound.play();
												}
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
				
								<?php if($_GET){
	echo '
	$("#codigo").val("'.$_GET["id"].'");
	$("#reg_form").submit();
';
	
}
?>
    
    });
</script>
</head>
<body>

		<h1 style="text-align: center;">Seguimiento a paquetes</h1>
		
		
		<br>
		<form method="post" action="#" id="reg_form" style="width: 300px;margin: auto;text-align: center;">
											<?php if(!isset($_GET["id"]))
												echo '<video id="preview" style="width: 60%; height=100px" onclick="scan()"></video><br>'
												
												?>
			QR: <input required onkeyup="parse_id()" type="text" name="codigo" id="codigo"/>
			<br><br>
			<?php if(isset($_GET["id"]))
												echo '<input style="margin-left: 40%" type="submit" onclick="window.location=\'tracking.php\'" value="Leer código QR" />'									
			?>
			<input style="margin-left: 40%" type="submit" value="Buscar" />
			<br>
			<input type="hidden" value="<?php echo $fname ?>" name="fname">
			<input type="hidden" value="<?php echo $id_curso ?>" name="curso">
			<input type="hidden" value="<?php echo $_SESSION['k_id'] ?>" name="registrador">
			<div id="response"></div>	
			<audio id="ok" src="sounds/ok.wav" autostart="false" ></audio>	
			<audio id="fail" src="sounds/fail.wav" autostart="false" ></audio>	
</form>
	
	
<button onclick="window.history.go(-1); return false;">Atras</button>

<script>


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
<script type="text/javascript">
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview'),mirror: false,refractoryPeriod: 2500});
      scanner.addListener('scan', function (content) {
        $("#codigo").val(content);
	$( "#reg_form" ).submit();
      });
	
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
	var msg="";	
	for (var i = 0; i < cameras.length; i++) {
        msg = msg+"["+i+"]- Camara #"+i+"\n";
    	}
	  var camNum =  cameras.length-1;
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

