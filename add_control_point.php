<?php
require('common.php');
if($_POST){	
	$result = $db->add_control_point($_POST['codigo'],$_POST['lat'],$_POST['lon'],$_POST['registrador']);
	
	echo json_encode($result);
}

?>
