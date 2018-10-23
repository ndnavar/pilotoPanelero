<?php
require('../View/common.php');
if($_POST && !empty($_POST['fname'])){	
	$result = $db->registrar(explode("?", $_POST['codigo'],2)[0],$_POST['curso'],$_POST['prod_ca'],$_POST['prod_pa'],$_POST['registrador'],$_POST['lat'],$_POST['lon']);
	echo json_encode($result);
}

?>
