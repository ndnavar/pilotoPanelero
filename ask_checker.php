<?php require('common.php');
//get current counter
$data['current'] = (int)$db->check_changes();
//set initial value of update to false
//$data['update'] = true;
//check if it's ajax call with POST containing current (for user) counter;
//and check if that counter is diffrent from the one in database
/*if(isset($_POST) && !empty($_POST['counter']) && (int)$_POST['counter']!=$data['current']){
	//the counters are diffrent so get new message list
	$data['news'] .= $db->get_ask();
	$data['update'] = true;
}*/
	$data['news'] .= $db->get_ask();
	$data['update'] = true;
        
//just echo as JSON
echo json_encode($data);
/* End of file checker.php */