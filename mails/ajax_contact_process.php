<?php
error_reporting(-1);
ini_set('display_errors', 'On');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';

$fila = 2;
if (($gestor = fopen("test.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $numero = count($datos);
        $fila++;
	echo $datos[1]."\n".send($datos[0],$datos[1],$datos[2])."\n<br>";
	
    }
    fclose($gestor);
}


function send($name,$mail,$file){
		
	$pos = strpos($file,"M");
	if ($pos === false) {
		$message = "Apreciado ".$name."<br />\n<br />\n
		
Reciba un cordial saludo de parte del comité organizador.<br />\n<br />\n

Le damos la bienvenida al Congreso Internacional Industria y Organizaciones CIIO 2018 - Universidad Nacional de Colombia, en el archivo adjunto encontrará la carta oficial de invitación al Congreso para los fines pertinentes.<br />\n<br />\n

Le recordamos que para que su trabajo sea incluido en el Programa del Congreso, al menos uno de los autores debe registrarse antes del 19 de julio de 2018. Recuerde también que un autor puede presentar máximo dos trabajos en el Congreso y puede ser coautor de otros trabajos.
<br><br><br><img width='430px' src='cid:firma'>";
		
		}
	else {
		$message = "Apreciada ".$name."<br />\n<br />\n
		
Reciba un cordial saludo de parte del comité organizador.<br />\n<br />\n

Le damos la bienvenida al Congreso Internacional Industria y Organizaciones CIIO 2018 - Universidad Nacional de Colombia, en el archivo adjunto encontrará la carta oficial de invitación al Congreso para los fines pertinentes.<br />\n<br />\n

Le recordamos que para que su trabajo sea incluido en el Programa del Congreso, al menos uno de los autores debe registrarse antes del 19 de julio de 2018. Recuerde también que un autor puede presentar máximo dos trabajos en el Congreso y puede ser coautor de otros trabajos.
<br><br><br><img  width='430px' src='cid:firma'>";
		
	}
	echo $message."<br />\n";echo $name . "<br />\n";echo $mail . "<br />\n";echo $file . ".pdf<br />\n";

$email = new PHPMailer();
$email->CharSet = 'UTF-8';
$email->isHTML(true);
$email->AddBCC('jflatorreo@unal.edu.co');
$email->From      = 'ciio_fibog@unal.edu.co';
$email->FromName  = 'CIIO 2018';
$email->Subject   = 'Remisión carta de invitación CIIO 2018';
$email->Body      = $message;
$email->AddAddress($mail,$name);
$email->AddEmbeddedImage('firma.png', 'firma');
$email->AddAttachment( "PDF/".$file.".pdf" );

return $email->Send();	

}        		

?>