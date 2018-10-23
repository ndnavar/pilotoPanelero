<?php
require('../View/common.php');
if($_POST && !empty($_POST['fname'])){	
	$result = $db->track_qr(explode("?", $_POST['codigo'],2)[0]);
	echo json_encode($result);
}

?>
