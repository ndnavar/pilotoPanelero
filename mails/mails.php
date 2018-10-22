<?php
error_reporting(-1);
ini_set('display_errors', 'On');
set_time_limit(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'mailer/src/Exception.php';
require 'mailer/src/PHPMailer.php';
require 'mailer/src/SMTP.php';




$fila = 2;
if (($gestor = fopen("adicionales_31Jul_3.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $numero = count($datos);
        $fila++;
	send($datos[0],$datos[1],$datos[3]);
	
    }
    fclose($gestor);
}


function send($mail,$name,$file){
	echo $mail." ".$name." ".$file."\n";	
		$message = "Apreciado(a) ".$name.":<br />\n<br />
		
Reciba un cordial saludo de parte del comité organizador. Le damos la bienvenida al Congreso Internacional Industria y Organizaciones CIIO 2018 - Universidad Nacional de Colombia. <br />\n<br />\n

A continuación usted encontrará el código QR correspondiente a su inscripción, el cual deberá ser presentado de manera impresa o bien en su dispositivo móvil el primer día del evento, para la formalización de su registro. <br />\n<br />\n

<img width='430px' src='cid:qr'><br>

Le recordamos que puede consultar la programación del evento en la página web <a href='http://www.seprologistica.unal.edu.co/CIIO2018/info.html#programa'> http://www.seprologistica.unal.edu.co/CIIO2018</a>. <br><br>Le esperamos!

<br><br><br><img width='430px' src='cid:firma'>";
		

$email = new PHPMailer();

$email->IsSMTP();                                      // Set mailer to use SMTP
$email->Host = 'smtp.gmail.com';                 // Specify main and backup server
$email->Port = 25;                                    // Set the SMTP port
$email->SMTPAuth = true;                               // Enable SMTP authentication
$email->Username = 'ciio_fibog@unal.edu.co';                // SMTP username
$email->Password = 'Seprociio2018';                  // SMTP password
//$email->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
//$email->SMTPDebug = 2;

$email->CharSet = 'UTF-8';
$email->isHTML(true);
$email->From      = 'ciio_fibog@unal.edu.co';
$email->FromName  = 'CIIO 2018';
$email->Subject   = 'Mensaje de bienvenida al CIIO 2018';
$email->Body      = $message;
$email->AddAddress($mail,$name);
$email->AddEmbeddedImage('firma.png', 'firma');
$email->AddEmbeddedImage('QR/'.$file.'.png', 'qr');
if(!$email->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $email->ErrorInfo;
   exit;
}
echo $mail;
}        		

?>
