<?php require('common.php');
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
}else{
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php";
			</SCRIPT>';
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Registro</title>

    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/main.css">
    <link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
    
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>

	<script src="js/jquery.js"></script>
	<script>
		/* AJAX request to checker */
		function check(){
			$.ajax({
				type: 'POST',
				url: 'checker.php',
				dataType: 'json',
				data: {
					counter:$('#message-list').data('counter'),
					id_curso:$('#message-list').data('id_curso')
				}
			}).done(function( response ) {
				/* update counter */
				$('#message-list').data('counter',response.current);
				/* check if with response we got a new update */
				if(response.update==true){
					$('#message-list').html(response.news);
					$('#contador').html(response.count);
				}
			});
		}
		//Every 20 sec check if there is new update
		setInterval(check,1500);
	</script>
</head>
<body style="background: #1B1B1B">
	<button onclick="location.replace(document.referrer)">Atras</button>
	<?php /* Our message container. data-counter should contain initial value of couner from database */ ?>
	 <div id="contador"><?php echo $db->get_count($id_curso); ?></div>
	<div id="message-list" data-id_curso="<?php echo $id_curso;?> data-counter="<?php echo (int)$db->check_changes();?>">
		<?php echo $db->get_news($id_curso);?>
	</div>
</body>
</html>

