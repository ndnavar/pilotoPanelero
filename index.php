<?php
require('common.php');
?>

<?php
session_start();
//datos para establecer la conexion con la base de mysql.


$id=$_GET["id"];
$del = $_GET['del'];

if (isset($_SESSION['k_id'])) {
	//echo $_SESSION['k_id'];
	$r=$db->es_registrador($_SESSION['k_id']);
	if ($_SESSION['k_id']==1){
	}
	else if ($r){
		echo '<SCRIPT LANGUAGE="javascript">
			location.href = "registro.php";
			</SCRIPT>';	
	}
	
}else{
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php?id='.$id.'";
			</SCRIPT>';
}

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

    <script type="text/javascript">
	        
      function initialize() {
	chatWith(1,'CIIO');
      }
    </script>
    
    	<script>
		/* AJAX request to checker */
		function check(){
			$.ajax({
				type: 'POST',
				url: 'ask_checker.php',
				dataType: 'json',
				data: {
					counter:$('#message-list').data('counter')
				}
			}).done(function( response ) {
				/* update counter */
				$('#message-list').data('counter',response.current);
				/* check if with response we got a new update */
				if(response.update==true){
					$('#message-list').html(response.news);
				}
			});
		}
		//Every 20 sec check if there is new update
		setInterval(check,1500);
		
		function del(msg){
			document.getElementById("del").value = msg;
			document.forms["delForm"].submit();
		}
	</script>
    
  </head>
  <body <?php if ($_SESSION['k_id']!="1"){echo 'onload="initialize()"';}?>>
      
      <form id="delForm">
	<input type='hidden' name='del' id="del">
      </form>
      
	<center style="height: 100%">
		
	<?php
	
	if ($_SESSION['k_id']!="1"){
		echo '<a href="#"><img src="images/chat.png" onclick="chatWith(1,\'CIIO\');toggleChatBoxGrowth(\'1\')" style="height: 100px;position: absolute;top: 0"></a>';
		echo '<a href="changepass.php"><img src="images/pw.png" onclick="" style="height: 100px;position: absolute;top: 0;margin-left:150px"></a>';
		}
		
	if ($_SESSION['k_id']=="1"){
		
		echo '<a href="registrar.php"><h2>Creación de Productores</h2></a>';
		echo '<a href="cursos.php"><h2>Creación de Lotes</h2></a>';
		echo '<a href="reportes.php"><h2>Reportes</h2></a>';
		echo '<a href="registro.php"><h2>Registro de producción</h2></a>';
		echo '<a href="control.php"><h2>Punto de Control</h2></a>';
		if ($del){
		$db->del_msg($del);	
		}
		
		echo '<div id="message-list" data-counter="'.(int)$db->check_changes().'">';
		echo $db->get_ask();
		echo "</div>";
	}
	else {
	echo "<img src='images/1.png' align='center'>";
	}
	?>
	
	
      </center>
       	<script type="text/javascript" src="js/vendor/jquery-1.10.1.min.js"></script>
        <script type="text/javaxcript" src="js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>
        <script type="text/javascript" src="/js/TweenMax.min.js"></script>
        <script type="text/javascript" src="js/parsley.js"></script>
		
		
        <script src="js/main.js"></script>
        <script type="text/javascript" src="js/jquery.min.js"></script>
	<?php if ($_SESSION['k_id']=="1"){echo '<script type="text/javascript" src="js/chat1.js"></script>';}
	else{ echo '<script type="text/javascript" src="js/chat.js"></script>';}
	?>
    
  </body>
</html>
