<?php

include('phpqrcode/qrlib.php');
    
    // outputs image directly into browser, as PNG stream
    
$fila = 0;
if (($gestor = fopen("adicionales_31Jul_3.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $numero = count($datos);
        $fila++;
	QRcode::png($datos[3]."?c=".substr(md5($datos[3]),1,8),"QR/".$datos[3].".png",QR_ECLEVEL_H,15);	
    }
    fclose($gestor);
}
?>
