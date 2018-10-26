
<?php
//Esta sección esta desarrollada para hacer la creación de un nuevo punto de control.
require('../View/common.php');
if($_POST){
	// Se accede a la base de datos y se agrega el codigo del producto y la ubicación en la que esta 
	$result = $db->add_control_point($_POST['codigo'],$_POST['lat'],$_POST['lon'],$_POST['registrador']);
	
	echo json_encode($result);
}

?>
