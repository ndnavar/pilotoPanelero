<?php require('common.php');

if(isset($_POST) && !empty($_POST['id_curso']) ){
	$data = $db->get_bloques($_POST['id_curso']);
}
// JSON
echo json_encode($data);
