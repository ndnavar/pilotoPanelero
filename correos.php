<?php

require_once('PDF/tcpdf.php');

class MYPDF extends TCPDF {

    var $radicado;

//Page header  

    public function Header() {

//set margins
        $this->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
//if page is 2/4/6... don't print anything            
        $image_file = "imgages/logo.png";
//$image_file = "http://localhost/app/webroot/img/Header.png";
        $this->Image($image_file, 25, 0, 160, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
// Set font
        $this->SetFont('helvetica', 'B', 20);
// Title
        if ($this->page >= 2) {
            $this->SetFont('helvetica', 'B', 9);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, '', 0, 1, 'L', 0, '', 0);
            $this->Cell(0, 0, 'Continuación de la respuesta al radicado ' . $this->radicado, 0, 1, 'C', 0, '', 0);
        }
// $this->Cell(0, 15, "  ", 0, false, 'C', 0, '', 0, false, 'M', 'M');
        return;
//} else {
//    $this->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
//}        
    }

    public function Footer() {
// Position at 15 mm from bottom
        $this->SetY(-15);
// Set font
        $this->SetFont('helvetica', 'I', 8);
// Page number
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

class RespuestasController extends AppController {

//public $uses = array('RespuestaEditable');
    public function beforeFilter() {
        if ($this->params['action'] == 'cron') {
//Metodos de uso general, no estan controlados por el acl
            $this->Auth->allow('cron');
        } else {
            parent::beforeFilter();
        }
    }

    function listar() {
        $this->set("respuestas", $respuestas = $this->Respuesta->find('all'));
        if (count($respuestas) == 0) {
            $this->redirect('crear');
        }
    }

    public function cambiopatron() {
        $this->set("respuestas", $respuestas = $this->Respuesta->find('all'));
        if ($this->request->is(array('post', 'put'))) {
            if (isset($this->request->data['cancelar'])) {
// abort if cancel button was pressed 
                $this->Session->setFlash(__("Los cambios no fueron aplicados <b></b>", true));
                $this->redirect(Router::url(array('controller' => 'Users', 'action' => 'listarusuariomisional')));
            } else {
                $this->loadModel('User');
                $datasource = $this->User->getDataSource();
                try {
                    $datasource->begin();
                    if (isset($_POST['data']['cambiopatron']['Aprobadas']))
                        $Aprobadas = $this->data['cambiopatron']['Aprobadas'];
                    else
                        $Aprobadas = "NULL";
                    if (isset($_POST['data']['cambiopatron']['SinAprobar']))
                        $SinAprobar = $this->data['cambiopatron']['SinAprobar'];
                    else
                        $SinAprobar = "NULL";

                    $PatronActual = $this->data['cambiopatron']['PatronActual'];
                    $PatronNuevo = $this->data['cambiopatron']['PatronNuevo'];
                    $Observaciones = $this->data['cambiopatron']['Observaciones'];

                    $iduser = $this->Auth->user('id');
                    $Uid = $this->User->query("select id from usuariomisional where Id_Users=$iduser;");
                    $Uid = $Uid[0]['usuariomisional']['id'];

                    $query = "CALL SP_CambioMasivodePatron($PatronActual,$PatronNuevo,'$Aprobadas','$SinAprobar',$Uid,'$Observaciones',@Total);";
                    $query = $this->User->query($query);

                    if (mysql_errno())
                        throw new ErrorException();

                    $pardev = $this->User->query("select @Total");
                    $datasource->commit();
                    $this->Session->setFlash("Se modificaron " . $pardev[0][0]['@Total'] . " reclamaciones <b>");
//return $this->redirect('listarusuariomisional');
                } catch (Exception $e) {
                    $datasource->rollback();
                    $this->Session->setFlash(('No se pudo modificar </b>. ' . $e));
                }
            }
        }
    }

    function estadisticas() {
        $this->autoRender = false;
        $id = $_POST['id'];
        $this->loadModel('User');
        $this->User->query("CALL SP_EstadisticasReclamacionesPorPatron($id,@SinAprobar,@Aprobadas,@Enviadas,@Total)");
        $pardev = $this->User->query("select @SinAprobar,@Aprobadas,@Enviadas,@Total");
        $result = array("sinAprobar" => $pardev[0][0]['@SinAprobar'], "Aprobadas" => $pardev[0][0]['@Aprobadas'], "Enviadas" => $pardev[0][0]['@Enviadas'], "Total" => $pardev[0][0]['@Total']);
        echo json_encode($result);
    }

    public function crear() {
        $codigo = $this->Respuesta->query("select max(PR_CodPatronRpta) as maximo from patronrpta;");
        $this->set('codigo', intval($codigo[0][0]['maximo']) + 1);
        $this->set("numeros", $numeros = $this->Respuesta->find('all'));
        if ($this->request->is(array('post', 'put'))) {
            if (isset($this->request->data['cancelar'])) {
                $this->Session->setFlash(__("La respuesta tipo no fue creada.", true));
                $this->redirect(array('action' => 'listar'));
            } else {
                if ($this->data['Respuesta']['PR_TituloRpta'] == "") {
// Si Javascript se deshabilita, no permite que se guarde una respuesta sin título
                    return $this->redirect(array('action' => 'crear'));
                }
                if ($this->data['Respuesta']['PR_CodPatronRpta'] == "") {
// Si Javascript se deshabilita, no permite que se guarde una respuesta sin número asignado
                    return $this->redirect(array('action' => 'crear'));
                }
                if ($this->data['Respuesta']['PR_TextoRpta'] == "") {
// Si Javascript se deshabilita, no permite que se guarde una respuesta sin text
                    return $this->redirect(array('action' => 'crear'));
                }
                try {
                    $this->Respuesta->save($this->data);
                    $this->Session->setFlash("Se guard&oacute; respuesta tipo <b>" . $this->data['Respuesta']['PR_CodPatronRpta'] . "</b>: <b>" . $this->data['Respuesta']['PR_TituloRpta'] . "</b>");
                    return $this->redirect('listar');
                } catch (Exception $e) {
                    $error = "Integrity constraint violation";
                    $key_BD1 = "CA1_PatronRpta";
                    $key_BD2 = "CA2_PatronRpta";
                    $resultado1 = strpos($e, $error);
                    $resultado2 = strpos($e, $key_BD1);
                    $resultado3 = strpos($e, $key_BD2);
                    $razon = "";
                    if ($resultado1 !== FALSE) {
                        if ($resultado2 !== FALSE) {
                            $razon .= "Existe otra respuesta tipo con el mismo t&iacute;tulo: <b>" . $this->data['Respuesta']['PR_TituloRpta'] . ".</b>";
                        }
                        if ($resultado3 !== FALSE) {
                            $razon .= "Existe otra respuesta tipo con el mismo n&uacute;mero: <b>" . $this->data['Respuesta']['PR_CodPatronRpta'] . ".</b>";
                        }
                    } else {
                        $razon = "";
                    }
                    $this->Session->setFlash("No se pudo crear la respuesta tipo. " . $razon);
                    $this->redirect(array('action' => 'listar'));
                }
            }
        } else {
            
        }
    }

    function editarplantilla($id) {
        if (!$id) {
            throw new NotFoundException('No existe la respuesta tipo número ' . $id . '.');
        } else {
            $post = $this->Respuesta->findById($id);
            if (!$post) {
                throw new NotFoundException('No existe la respuesta tipo número ' . $id . '.');
            } else {
                $this->set('Respuesta', $post);
                if ($this->request->is(array('post', 'put'))) {
                    if (isset($this->request->data['cancelar'])) {
// abort if cancel button was pressed 
                        $this->Session->setFlash(__("Los cambios no fueron aplicados a la respuesta tipo n&uacute;mero <b>" . $this->data['Respuesta']['PR_CodPatronRpta'] . "</b>", true));
                        $this->redirect(array('action' => 'listar'));
                    } else {
                        try {
                            $this->Respuesta->save($this->data);
                            $this->Session->setFlash("Se modific&oacute; la respuesta tipo n&uacute;mero <b>" . $this->data['Respuesta']['PR_CodPatronRpta'] . "</b>: <b>" . $this->data['Respuesta']['PR_TituloRpta'] . "</b>");
                            return $this->redirect('listar');
                        } catch (Exception $e) {
                            $error = "Integrity constraint violation";
                            $key_BD = "CA1_PatronRpta";
                            $resultado1 = strpos($e, $error);
                            $resultado2 = strpos($e, $key_BD);
                            if (($resultado1 !== FALSE) && ($resultado1 !== FALSE)) {
                                $razon = "Existe otra respusta tipo con el mismo t&iacute;tulo: <b>" . $this->data['Respuesta']['PR_TituloRpta'] . "</b>";
                            } else {
                                $razon = "";
                            }
                            $this->Session->setFlash("No se pudo modificar la respuesta tipo. " . $razon);
                            $this->redirect(array('action' => 'listar'));
                        }
                    }
                } else {
                    
                }
            }
        }
    }

    function cron() {
        $this->layout = false;
        unset($enviar);
        unset($reclamo);
        unset($reclamante);
        unset($mails);
        unset($respuesta);
        $enviar = $this->Respuesta->query("select * from enviarrespuestas where enviar=1 and  enviado=2 order by enviado desc limit 0,1");
        $reclamo = $this->Respuesta->query("select * from reclamos where id = " . $enviar[0]['enviarrespuestas']['reclamo'] . " and estado='Aprobada'");
        if (count($reclamo) > 0) {
            $reclamante = $this->Respuesta->query("select * from reclamadores inner join users on reclamadores.documento=users.username where users.id='" . $reclamo[0]['reclamos']['usuario'] . "'");
            $mails = $this->Respuesta->query("select * from mails where users='" . $reclamo[0]['reclamos']['usuario'] . "'");
            $respuesta = $this->Respuesta->query("select * from reclamos_responder where reclamo = " . $enviar[0]['enviarrespuestas']['reclamo']);
            $this->set('reclamos', $enviar[0]['enviarrespuestas']['reclamo']);

//incluimos la clase phpmailer.php
//require_once no es una función por lo tanto no debe llevar paréntesis
            require_once 'class.phpmailer.php';

//Instanciamos un objeto de la clase phpmailer
            $mail = new phpmailer();

//Indicamos a la clase phpmailer donde se encuentra la clase smtp
            $mail->PluginDir = "";

//Indicamos que vamos a conectar por smtp
            $mail->Mailer = "smtp";

//Nuestro servidor smtp. Como ves usamos cifrado ssl
            $mail->Host = "smtp.mandrillapp.com";

//Puerto de gmail 465
            $mail->Port = "587";

//Le indicamos que el servidor smtp requiere autenticación
            $mail->SMTPAuth = true;

//Le decimos cual es nuestro nombre de usuario y password
            $mail->Username = "guillermoc@gmlsoftware.com";
            $mail->Password = "oIEKOn2otOA4WmO6yInLiw";

//Indicamos cual es nuestra dirección de correo y el nombre que
//queremos que vea el usuario que lee nuestro correo
            $mail->From = "no-responder@reclamaciones.co";
            $mail->FromName = utf8_decode("Reclamaciones evaluación de competencia");

//El valor por defecto de Timeout es 10, le voy a dar un poco mas
            $mail->Timeout = 30;

//Indicamos cual es la dirección de destino del correo.
            $mail->AddAddress($reclamante[0]['reclamadores']['email']);
//$mail->AddAddress("luferquisa@gmail.com");
            echo "fijo " . $reclamante[0]['reclamadores']['email'] . "<br>";
            if (count($mails) > 0) {
                foreach ($mails as $m) {
                    if (strlen($m['mails']['nuevomail']) > 0) {
                        $mail->AddAddress($m['mails']['nuevomail']);

                        echo "nuevo " . $m['mails']['nuevomail'] . "<br>";
                    }
                }
            }

//Asignamos asunto
            $mail->Subject = utf8_decode("Respuesta a su reclamación resultado Evaluación competencias MEN 2012 ");
            if ($pos = strpos($respuesta[0]['reclamos_responder']['patron'], "27") === false) {//Se reemplaza == por ===
//Cuerpo del mensaje. Puede contener html
                $mail->Body = "<p>" . utf8_decode('Respetada-o docente:') . "</p>  <p>" . utf8_decode('La Universidad Nacional de Colombia se permite informarle que puede consultar la respuesta a su reclamación ingresando a la siguiente dirección:') . " http://www.reclamaciones.co'.</p><p>" . utf8_decode("Puede ingresar utilizando su cedula, el NIP y realizando la operación que el aplicativo le solicita para evitar spam.") . "</p><p>" . utf8_decode("Si olvidó sus datos de inscripción, vaya al sitio web del ") . "<a href='http://www.mineducacion.gov.co/proyectos/1737/propertyvalue-48604.html'>" . utf8_decode("proceso que tiene el Ministerio de Educación") . "</a>" . utf8_decode(", haga click en 'CONSULTE AQUÍ SUS RESULTADOS' y siga las instrucciones que allí encuentra.") . "</p><p>" . utf8_decode(" Atentamente,") . "<br><br>" . utf8_decode("NUBIA SÁNCHEZ MARTÍNEZ") . "<br>" . utf8_decode("Directora Convenio interadministrativo 546 de 2012") . "<br>" . utf8_decode("Universidad Nacional de Colombia") . ".</p>";
                echo "<p>" . utf8_decode('Respetada-o docente:') . "</p>  <p>" . utf8_decode('La Universidad Nacional de Colombia se permite informarle que puede consultar la respuesta a su reclamación ingresando a la siguiente dirección:') . " http://www.reclamaciones.co.</p><p>" . utf8_decode("Puede ingresar utilizando su cedula, el NIP y realizando la operación que el aplicativo le solicita para evitar spam.") . "</p><p>" . utf8_decode("Si olvidó sus datos de inscripción, vaya al sitio web del ") . "<a href='http://www.mineducacion.gov.co/proyectos/1737/propertyvalue-48604.html'>" . utf8_decode("proceso que tiene el Ministerio de Educación") . "</a>" . utf8_decode(", haga click en 'CONSULTE AQUÍ SUS RESULTADOS' y siga las instrucciones que allí encuentra.") . "</p><p>" . utf8_decode("Un cordial saludo.") . "</p>";

//Si no admite html
                $mail->AltBody = utf8_decode("Respetada-o docente:\n\nLa Universidad Nacional de Colombia se permite informarle que puede consultar la respuesta a su reclamación ingresando a la siguiente dirección: www.reclamaciones.co. \n\nPuede ingresar utilizando su cedula, el NIP y realizando la operación que el aplicativo le solicita para evitar spam.\nSi olvidó sus datos de inscripción, vaya al sitio web del proceso que tiene el Ministerio de Educación (http://www.mineducacion.gov.co/proyectos/1737/propertyvalue-48604.htm), haga click en CONSULTE AQUÍ SUS RESULTADOS y siga las instrucciones que allí encuentra.\n\n\nAtentamente\nNUBIA SÁNCHEZ MARTÍNEZ\nDirectora Convenio interadministrativo 546 de 2012\nUniversidad Nacional de Colombia.");
            } else {
//Cuerpo del mensaje. Puede contener html
                $mail->Body = "<p>" . utf8_decode("Respetada-o docente:") . "</p>  <p>" . utf8_decode("La Universidad Nacional de Colombia se permite informarle que puede consultar la respuesta a su reclamación ingresando a la siguiente dirección:") . "href='http://www.reclamaciones.co'.</p><p> " . utf8_decode("Sus datos para el ingreso son:") . "</p><p>" . utf8_decode("Usuario: " . $reclamante[0]['reclamadores']['documento']) . "<p>" . utf8_decode("NIP: " . $reclamante[0]['reclamadores']['nip']) . "</p><p>" . utf8_decode("Para poder ingresar, realice la operación que le solicita el aplicativo y haga click en INGRESAR.") . "</p><p>" . utf8_decode("Si olvidó sus datos de inscripción, vaya al sitio web del ") . " <a href='http://www.mineducacion.gov.co/proyectos/1737/propertyvalue-48604.html'>" . utf8_decode("proceso que tiene el Ministerio de Educación") . "</a>" . utf8_decode(", haga click en CONSULTE AQUÍ SUS RESULTADOS y siga las instrucciones que allí encuentra.") . "</p><p>" . utf8_decode(" Atentamente,") . "<br><br>" . utf8_decode("NUBIA SÁNCHEZ MARTÍNEZ") . "<br>" . utf8_decode("Directora Convenio interadministrativo 546 de 2012") . "<br>" . utf8_decode("Universidad Nacional de Colombia") . ".</p>";
                echo "<p>" . utf8_decode("Respetada-o docente:") . "</p>  <p>" . utf8_decode("La Universidad Nacional de Colombia se permite informarle que puede consultar la respuesta a su reclamación ingresando a la siguiente dirección:") . "http://www.reclamaciones.co.</p><p> " . utf8_decode("Sus datos para el ingreso son:") . "</p><p>" . utf8_decode("Usuario: " . $reclamante[0]['reclamadores']['documento']) . "<p>" . utf8_decode("NIP: " . $reclamante[0]['reclamadores']['nip']) . "</p><p>" . utf8_decode("Para poder ingresar, realice la operación que le solicita el aplicativo y haga click en INGRESAR.") . "</p><p>" . utf8_decode("Si olvidó sus datos de inscripción, vaya al sitio web del ") . " <a href='http://www.mineducacion.gov.co/proyectos/1737/propertyvalue-48604.html'>" . utf8_decode("proceso que tiene el Ministerio de Educación") . "</a>" . utf8_decode(", haga click en CONSULTE AQUÍ SUS RESULTADOS y siga las instrucciones que allí encuentra.") . "</p><p>" . utf8_decode(" Atentamente") . "<br>" . utf8_decode("NUBIA SÁNCHEZ MARTÍNEZ") . "<br>" . utf8_decode("Directora Convenio interadministrativo 546 de 2012") . "<br>" . utf8_decode("Universidad Nacional de Colombia") . ".</p>";

//Si no admite html
                $mail->Body = utf8_decode("Respetada-o docente:\n\nLa Universidad Nacional de Colombia se permite informarle que puede consultar la respuesta a su reclamación ingresando a la siguiente dirección: http://www.reclamaciones.co. \n\nSus datos para el ingreso son: Usuario: " . $reclamante[0]['reclamadores']['documento'] . " NIP: " . $reclamante[0]['reclamadores']['nip'] . ". \n\nPara poder ingresar, realice la operación que le solicita el aplicativo y haga click en INGRESAR.\nSi olvidó sus datos de inscripción, vaya al sitio web del proceso que tiene el Ministerio de Educación (http://www.mineducacion.gov.co/proyectos/1737/propertyvalue-48604.html), haga click en CONSULTE AQUÍ SUS RESULTADOS y siga las instrucciones que allí encuentra.\n\nAtentamente, \n\nNUBIA SÁNCHEZ MARTÍNEZ\nDirectora Convenio interadministrativo 546 de 2012\nUniversidad Nacional de Colombia.");
            }
// $mail->AddAttachment($adjunto); // attachment
//Envia en email
//$mail->SMTPDebug = 2;
//$mail->SMTPDebug = 2;
            if (!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
                $this->Respuesta->query("update enviarrespuestas set enviado=4 where reclamo = '" . $enviar[0]['enviarrespuestas']['reclamo'] . "' ");
                return false;
            } else {
                echo "Enviado";
                $this->Respuesta->query("update enviarrespuestas set enviado=1 where reclamo = '" . $enviar[0]['enviarrespuestas']['reclamo'] . "' ");
                return true;
            }
        } else {
            $this->Respuesta->query("update enviarrespuestas set enviado=2 where reclamo = '" . $enviar[0]['enviarrespuestas']['reclamo'] . "' ");
        }
    }

    function ver($id) {
        $this->set("reclamo", $this->Reclamo->findById($id));
    }

    function enviar($pagina=1) {
        if ($this->request->is("post")) {
            $this->Respuesta->query("insert into enviarrespuestas (select null, id,1,0 from reclamos )");
        }
        /*
          $p = $this->Session->read('pagina');
          if($p!= 1 && !is_null($p)){
          $pagina=$p;
          }
          $pagina--;
          $rango=100*$pagina;
          $this->set("enviar",$this->Respuesta->query("SELECT * FROM reclamos INNER JOIN reclamos_responder ON reclamos.id = reclamos_responder.reclamo WHERE estado =  'Aprobada' LIMIT $rango , 100"));
          $this->set("cuantas",$this->Respuesta->query("select count(*) from reclamos where estado = 'Aprobada' "));
          $this->set('pagina',++$pagina);
          if($this->request->is("post")){
          pr($this->data);
          $i=0;
          while(isset($this->data['Respuesta']['responder'.$i])){
          if($this->data['Respuesta']['responder'.$i]!=0){
          echo "envio";
          $voyaenviar=$this->Respuesta->query("(select * from users  as u inner join reclamadores as r on u.username=r.documento where u.id in (select usuario from reclamos where id =".$this->data['Respuesta']['responder'.$i]."))");
          pr($voyaenviar);
          if($this->enviarcorreo($voyaenviar[0]['r']['email'],$voyaenviar[0]['r']['nombre'],$voyaenviar[0]['r']['apellido'])){
          $this->Respuesta->query("update reclamos set estado='Enviada' where id = ".$this->data['Respuesta']['responder'.$i]);
          $this->Respuesta->query("insert into logs values(null, 'Enviar correo respuesta','Envio correo de respuesta para respuesta ".$this->data['Respuesta']['responder'.$i]."','".$this->Auth->user('id')."','".date("Y-m-d h:m:s")."')");
          }
          }
          $i++;
          }
          $this->Session->setFlash("Correo(s) enviados");
          return $this->redirect('enviar');
          } */
    }

    function verrespuesta($reclamo) {
        return $this->Respuesta->query("select * from reclamos_responder where reclamo =" . $reclamo);
    }

    public function enviarcorreo($correo, $nombre, $apellido) {
//incluimos la clase phpmailer.php
//require_once no es una función por lo tanto no debe llevar paréntesis
        require_once 'class.phpmailer.php';

//Instanciamos un objeto de la clase phpmailer
        $mail = new phpmailer();

//Indicamos a la clase phpmailer donde se encuentra la clase smtp
        $mail->PluginDir = "";

//Indicamos que vamos a conectar por smtp
        $mail->Mailer = "smtp";

//Nuestro servidor smtp. Como ves usamos cifrado ssl
        $mail->Host = "smtp.mandrillapp.com";

//Puerto de gmail 465
        $mail->Port = "587";

//Le indicamos que el servidor smtp requiere autenticación
        $mail->SMTPAuth = true;

//Le decimos cual es nuestro nombre de usuario y password
        $mail->Username = "luferquisa@gmail.com";
        $mail->Password = "uN4_1rmhpCVI2onmxDGkjg";

//Indicamos cual es nuestra dirección de correo y el nombre que
//queremos que vea el usuario que lee nuestro correo
        $mail->From = "no-responder@reclamaciones.co";
        $mail->FromName = utf8_decode("Reclamaciones evaluación de competencia");

//El valor por defecto de Timeout es 10, le voy a dar un poco mas
        $mail->Timeout = 30;

//Indicamos cual es la dirección de destino del correo.
        $mail->AddAddress($correo);

//Asignamos asunto
        $mail->Subject = "Información Importatnte CIIO";

//Cuerpo del mensaje. Puede contener html
        $mail->Body = $nombre + " " + $apellido + "\n" + "Hola";

//Si no admite html
        $mail->AltBody = "Cuerpo de mensaje solo texto";
// $mail->AddAttachment($adjunto); // attachment
//Envia en email
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            echo "Enviado";
            return true;
        }
    }

    function enviartodos() {
        $respuestas = $this->Respuesta->query("select * from reclamos where estado = 'Aprobada'");
        foreach ($respuestas as $r) {
            echo "envio";
            $voyaenviar = $this->Respuesta->query("(select * from users  as u inner join reclamadores as r on u.username=r.documento where u.id in (select usuario from reclamos where id =" . $r['reclamos']['id'] . "))");
            pr($voyaenviar);
            if ($this->enviarcorreo($voyaenviar[0]['r']['email'], $voyaenviar[0]['r']['nombre'], $voyaenviar[0]['r']['apellido'])) {
                $this->Respuesta->query("update reclamos set estado='Enviada' where id = " . $r['reclamos']['id']);
                $this->Respuesta->query("insert into logs values(null, 'Enviar correo respuesta','Envio correo de respuesta para respuesta " . $r['reclamos']['id'] . "','" . $this->Auth->user('id') . "','" . date("Y-m-d h:m:s") . "')");
            }
        }
        $this->Session->setFlash("Correo(s) enviados");
        return $this->redirect('enviar');
    }

    function editar() {
        $patrones = explode(' ', $this->data['Respuesta']['patron']);

        $patron = $this->data['Respuesta']['patron'];
        $error = 0;
        for ($i = 1; $i <= count($patrones); $i++) {

            if (substr_count($patron, $patrones[$i]) > 1) {
                $error = 1;
                break;
            }
        }

        if ($error == 1) {
            $this->Session->setFlash("No se pudo guardar. El patrón se repite");
            $this->Respuesta->query("insert into logs values(null,'Patron repetido', 'Se intenta guardar una respuesta con patron repetido','" . $this->Auth->user('id') . "','" . $this->data['Respuesta']['id'] . "','" . date("Y-m-d h:m") . "')");
            return $this->redirect('/reclamos/vergestor/' . $this->data['Respuesta']['reclamo']);
        } else {
            $reclamo = $this->Respuesta->query("select * from reclamos_responder where reclamo = " . $this->data['Respuesta']['reclamo']);
            $respuesta = $this->data['Respuesta']['res'];
            if (count($reclamo) > 0) {
                $this->Respuesta->query("update reclamos_responder set repuesta='" . $respuesta . "', patron = CONCAT(' " . $this->data['Respuesta']['patron'] . " ',' " . $reclamo[0]['reclamos_responder']['patron'] . " ','')  where reclamo = " . $this->data['Respuesta']['reclamo']);
            } else {
                $this->Respuesta->query("insert into reclamos_responder values(null, '" . $this->data['Respuesta']['reclamo'] . "','" . $respuesta . "','" . $this->data['Respuesta']['patron'] . "')");
            }
            $this->Respuesta->query("insert into logs values(null, 'Editar asignada','Editar repuesta asignada ','" . $this->Auth->user('id') . "','" . $this->data['Respuesta']['reclamo'] . "','" . date("Y-m-d h:i:s") . "')");
            $this->Session->setFlash("Su respuesta ha sido guardada temporalmente, regrese para generar la respuesta proyectada");
            return $this->redirect('/reclamos/vergestor/' . $this->data['Respuesta']['reclamo']);
//pr($this->data);
        }
    }

    function devolveraprobada() {
        if ($this->request->is('post')) {
            $this->Respuesta->query("update reclamos set estado='Devuelta' where id ='" . $this->data['Respuesta']['id'] . "'");
            $this->Respuesta->query("insert into devueltas values(null,'" . $this->data['Respuesta']['id'] . "','" . $this->data['Respuesta']['motivo'] . "' )");
            $this->Respuesta->query("insert into logs values(null, 'devuelta desde aprobada','Se regresa desde aprobada ','" . $this->Auth->user('id') . "','" . $this->data['Respuesta']['id'] . "','" . date('Y-m-d H:i:s') . "')");
            $this->Session->setFlash("La respuesta ha sido devuelta");
            return $this->redirect("/reclamos/listarcoordinador");
        }
    }

    function envioaterceros($patron=12) {
        $this->set('patron', $patron);
        $this->set('reclamos', $this->Respuesta->query("select * from reclamos_responder as rr inner join reclamos as r on rr.reclamo=r.id inner join users as u on r.usuario=u.id inner join reclamadores as rec on rec.documento=u.username where patron like '%$patron%'"));
    }

    function enviarMasivo() {
 
        $reclamo = $this->Respuesta->query("SELECT * 
            FROM  Usuarios r 
            WHERE 1");
        if (count($reclamo) > 0) {
            foreach ($reclamo as $rec) {
                $respuesta = $this->Respuesta->query("CALL SP_UltimaRptaTerminada ('" . $rec['r']['R_Radicado'] . "');");
                //  echo json_encode($respuesta);
                $rest = substr($rec['p']['P_prue_codigo'], 0, 1);
                if ($rest == "2" or $rest == "3") {
                    $nota1 = array("peso" => "40%", "nombre" => "Disciplinar", "puntaje" => $rec['p']['P_C1']);
                    $nota2 = array("peso" => "30%", "nombre" => "Pedagógica", "puntaje" => $rec['p']['P_C2']);
                    $nota3 = array("peso" => "30%", "nombre" => "Comportamental", "puntaje" => $rec['p']['P_C3']);
                    $notatotal = $rec['p']['P_nota_total'];
                } else {
                    $nota1 = array("peso" => "30%", "nombre" => "Disciplinar", "puntaje" => $rec['p']['P_C1']);
                    $nota2 = array("peso" => "40%", "nombre" => "Pedagógica", "puntaje" => $rec['p']['P_C2']);
                    $nota3 = array("peso" => "30%", "nombre" => "Comportamental", "puntaje" => $rec['p']['P_C3']);
                    $notatotal = $rec['p']['P_nota_total'];
                }


//PDF 
                $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                $pdf->radicado = $rec['r']['R_Radicado'];
// set document information
                $pdf->SetCreator('II CIIO');
                $pdf->SetAuthor('II CIIO');
                $pdf->SetTitle('');
                $pdf->SetSubject('Información Importante II CIIO');
                $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
                $pdf->SetHeaderData();
// set header and footer fonts
                $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
                $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
                $pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetHeaderMargin(5);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
                $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language dependent data:
                $l = Array();
//            $l['a_meta_charset'] = 'UTF-8';
//            $l['a_meta_dir'] = 'rtl';
//            $l['a_meta_language'] = 'es';
//            $l['w_page'] = 'page';
//set some language-dependent strings
                $pdf->setLanguageArray($l);

// ---------------------------------------------------------
// add a page
                $pdf->AddPage();

// set font
                $pdf->SetFont('helvetica', 'B', 20);

                $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado");
                $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

                $hoy = $dias[date('w')] . " " . date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');

                $html = '<p>Bogotá, ' . $hoy . '</p>

                <p>Docente
                <br><b>' . utf8_encode($rec['p']['P_nombre']) . ' ' . utf8_encode($rec['p']['P_apellido']) . '</b>
                <br>CC: ' . $rec['p']['P_documento'] . '
                <br>Prueba: ' . (trim($pruebas[$rec['p']['P_prue_codigo']])) . ', ' . $rec['p']['P_prue_codigo'] . '
                <br>Radicado: ' . utf8_encode($rec['r']['R_Radicado']) . '
                <br>Fecha y hora de radicación: ' . utf8_encode($rec['r']['R_FechaHoraRegistro']) . '</p>';

                $html.='<span style="text-align:justify;"><p>Respetado(a) docente:</p><p>El proceso de evaluación de competencias está regulado por el Decreto No. 1278 de 2002 (Estatuto de Profesionalización Docente), los Decretos No. 2715 de 2009 y 240 de 2012, la Resolución No. 17227 de 2012 (modificada por las Resoluciones No. 10825 de 2013, 1184 y 1708 de 2014, expedidas por el Ministerio de Educación Nacional), el Contrato Interadministrativo 198 de 2012 y el Convenio Interadministrativo No. 841 de 2013, suscrito entre la Universidad Nacional de Colombia, el Ministerio de Educación Nacional y el Instituto Colombiano para la Evaluación de la Educación – ICFES, siendo la Universidad Nacional de Colombia responsable de la construcción y calificación de las pruebas, así como de la gestión de las reclamaciones.
</p><p>En atención a la reclamación de la referencia,  interpuesta contra la publicación de los resultados de la evaluación de competencias 2013, la Universidad Nacional de Colombia,  se permite informar lo siguiente:</p></span><br>';

                foreach ($respuesta as $value) {
                    if ($value["patronrpta"]["PR_CategoriaPatron"] == "F") {
                        $html.='<span style="text-align:justify;"><p><b>' .
                                $value["patronrpta"]["PR_TituloRpta"] . '. </b> ' .
                                $value["patronrpta"]["PR_TextoRpta"] . '</p></span><br>';

                        if ($value["patronrpta"]["PR_RelPuntaje"] == "TD") {

                            $html.= '<table border="1" style="width:80%;">';
                            $html.= '<tr><td colspan="3">TABLA DETALLE RESULTADOS</td></tr>';
                            $html.= '<tr><td>Competencia</td><td>Puntaje</td><td>Peso</td></tr>';
                            $html.= '<tr><td>' . $nota1['nombre'] . '</td><td>' . $nota1['puntaje'] . '</td><td>' . $nota1['peso'] . '</td></tr>';
                            $html.= '<tr><td>' . $nota2['nombre'] . '</td><td>' . $nota2['puntaje'] . '</td><td>' . $nota2['peso'] . '</td></tr>';
                            $html.= '<tr><td>' . $nota3['nombre'] . '</td><td>' . $nota3['puntaje'] . '</td><td>' . $nota3['peso'] . '</td></tr>';
                            $html.= '<tr><td>PUNTAJE OBTENIDO</td><td colspan="2">' . $notatotal . '</td></tr>';
                            $html.= '</table>';
                            $html.='<span style="text-align:justify; font-size: 26px"><p>* El <b>Puntaje obtenido</b> es el resultado de hacer las operaciones matemáticas utilizando todas las cifras decimales empleadas por el computador. En la tabla se resentan los resultados con el formato que ordena la norma aplicable (Decreto 2715 de 2009, artículo 13), el cual incluye una parte entera y dos decimales.</p></span><br>';
                        } else if ($value["patronrpta"]["PR_RelPuntaje"] == "PT") {
                            $html.= '<table border="1">';
                            $html.= '<tr><td>PUNTAJE OBTENIDO</td><td colspan="2">' . $notatotal . '</td></tr>';
                            $html.= '</table>';
                        }
                    } else {
                        $html.='<span style="text-align:justify;"><p>' .
                                $value["rptareclamopatronedit"]["RRPE_TextoLibre"] . '</p></span><br>';

                        if ($value["patronrpta"]["PR_RelPuntaje"] == "TD") {

                            $html.= '<table border="1" style="width:80%;">';
                            $html.= '<tr><td colspan="3">TABLA DETALLE RESULTADOS</td></tr>';
                            $html.= '<tr><td>Competencia</td><td>Puntaje</td><td>Peso</td></tr>';
                            $html.= '<tr><td>' . $nota1['nombre'] . '</td><td>' . $nota1['puntaje'] . '</td><td>' . $nota1['peso'] . '</td></tr>';
                            $html.= '<tr><td>' . $nota2['nombre'] . '</td><td>' . $nota2['puntaje'] . '</td><td>' . $nota2['peso'] . '</td></tr>';
                            $html.= '<tr><td>' . $nota3['nombre'] . '</td><td>' . $nota3['puntaje'] . '</td><td>' . $nota3['peso'] . '</td></tr>';
                            $html.= '<tr><td>PUNTAJE OBTENIDO</td><td colspan="2">' . $notatotal . '</td></tr>';
                            $html.= '</table>';
                            $html.='<span style="text-align:justify; font-size: 26px"><p>* El <b>Puntaje obtenido</b> es el resultado de hacer las operaciones matemáticas utilizando todas las cifras decimales empleadas por el computador. En la tabla se resentan los resultados con el formato que ordena la norma aplicable (Decreto 2715 de 2009, artículo 13), el cual incluye una parte entera y dos decimales.</p></span><br>';
                        } else if ($value["patronrpta"]["PR_RelPuntaje"] == "PT") {
                            $html.= '<table border="1">';
                            $html.= '<tr><td>PUNTAJE OBTENIDO</td><td colspan="2">' . $notatotal . '</td></tr>';
                            $html.= '</table>';
                        }
                    }
                }


                $pdf->SetFont('helvetica', '', 10);

                $html.= '<div><br><br><br><br><p>Cordialmente,</p>
                <p><b>NUBIA ROCÍO SÁNCHEZ MARTÍNEZ</b>
                <br>Directora Proyecto
                <br>Centro de Investigaciones para el Desarrollo
                <br>Facultad de Ciencias Económicas
                <br>Universidad Nacional de Colombia</p></div>';


                $pdf->writeHTML($html, true, 0, true, true);
//$html = substr($html,0, -300) . "<br pagebreak='true' />". substr($html, -300);
                $pdf->endPage();
                $pdf->Ln();

// set UTF-8 Unicode font
                $pdf->SetFont('dejavusans', '', 10);
// reset pointer to the last page
                $pdf->lastPage();
// ---------------------------------------------------------

                $filename = "correosQR/" . $rec['r']['R_Radicado'] . ".pdf";
                $fileatt = $pdf->Output($filename, 'F');


                $gestor = $this->Respuesta->query("select * from usuariomisional where Id_Users=" . $this->Auth->user('id'));
                $idusuariomisional = $gestor[0]['usuariomisional']['id'];

                $reclamador = $this->Respuesta->query("select * from mailreclamante where Id_Reclamante = " . $rec['r']['Id_Reclamante']);
                $temporalval = false;
                

                for ($i = 0; $i < count($reclador); $i++) {
		    $fechahora=date("Y-m-d H:i:s");
                    $idmailrecl = $reclamador[$i]['mailreclamante']['id'];
                    $mailrecl = $reclamador[$i]['mailreclamante']['MR_mail'];                   
                    if ($this->enviarmasss($filename, $mailrecl)) {
                        $this->Respuesta->query("insert into enviorespuesta 
                              values(NULL,$idmailrecl," . $rec['r']['id'] . ",'$fechahora','no-responder@reclamaciones.co',$idusuariomisional);");
                        $temporalval = true;
                    }
                }
                if ($temporalval) {
                    $this->procesoreclamoenviadas($rec['r']['id'], $respuesta, basename($filename));
                }
            }
        }
    }

    private function enviarmasss($adjunto, $mails) {
		
		set_time_limit(0);
		 ini_set('memory_limit',-1);

        $this->autoRender = false;
//incluimos la clase phpmailer.php
//require_once no es una función por lo tanto no debe llevar paréntesis
        require_once 'class.phpmailer.php';
        // $reclamador = $this->Respuesta->query("select * from mailreclamante where Id_Reclamante = " . $idReclamante);
//Instanciamos un objeto de la clase phpmailer

        $mail = new phpmailer();

//Indicamos a la clase phpmailer donde se encuentra la clase smtp
        $mail->PluginDir = "";

//Indicamos que vamos a conectar por smtp
        $mail->Mailer = "smtp";

//Nuestro servidor smtp. Como ves usamos cifrado ssl
        $mail->Host = "smtp.mandrillapp.com";

//Puerto de gmail 465
        $mail->Port = "587";

//Le indicamos que el servidor smtp requiere autenticación

        $mail->SMTPAuth = true;

        $mail->CharSet = 'UTF-8';

//Le decimos cual es nuestro nombre de usuario y password
// $mail->Username = "luisa@desafiosaceptados.com";
//$mail->Password = "kibyrana2012ud";
//Le decimos cual es nuestro nombre de usuario y password
        $mail->Username = "latorrejulian@hotmail.com";
//$mail->Password = "uN4_1rmhpCVI2onmxDGkjg";
        $mail->Password = "r1p72uMx5EZ4M7La-F8fNQ";

//Indicamos cual es nuestra dirección de correo y el nombre que
//queremos que vea el usuario que lee nuestro correo
        $mail->From = "no-responder@reclamaciones.co";
        $mail->FromName = "Reclamaciones evaluación de competencia";

//El valor por defecto de Timeout es 10, le voy a dar un poco mas
        $mail->Timeout = 20;

//Indicamos cual es la dirección de destino del correo.
//        debug($reclamador[0]['mailreclamante']['MR_mail']);
//$mails = "";        
        $mail->AddAddress($mails);
        $mail->AddAttachment(WWW_ROOT . $adjunto);
//        $mails = implode(';',$reclamador[0]['mailreclamante']['MR_mail']);
//Asignamos asunto
        $mail->Subject = "Respuesta de reclamación";

//Cuerpo del mensaje. Puede contener html
        $mail->Body = "Adjunto encontrará la respuesta a su reclamación contra los resultados de la evaluación de competencias 2013. La respuesta también se prodrá consultar ingresando con su documento de identidad y NIP en <a href='www.reclamaciones.co'>www.reclamaciones.co</a> ";
//utf8_decode("Su reclamación ha sido recibida, para más detalles vea el adjunto de este correo");

        $mail->IsHTML(true);
//Si no admite html
        $mail->AltBody = "Cuerpo de mensaje solo texto";
//     if (filesize($adjunto) > 80000) {
//          $mail->AddAttachment($adjunto); // attachment
//     }
//Envia en email
        $status = $mail->Send();
        if (!$status) {
            return false;
        } else {
            return true;
        }
    }

    private function procesoreclamoenviadas($idreclamo, $respuesta, $adjunto) {
//$datasource = $this->User->getDataSource();
//$this->autoRender = false;                      
        $comentarios = "'NO HAY OBSERVACION'";
        $proceso = "EV";
        $fechahora = date("Y-m-d H:i:s");
        $gestor = $this->Respuesta->query("select * from usuariomisional where Id_Users=" . $this->Auth->user('id'));
        $reclamoestado = $this->Respuesta->query("select id from estadoreclamo where ER_CodEstadoRecl='$proceso'");
        $idusuariomisional = $gestor[0]['usuariomisional']['id'];
        $idestadoreclamo = $reclamoestado[0]['estadoreclamo']['id'];

//$datasource->begin();
        $this->Respuesta->query("insert into procesoreclamo values(NULL,$idusuariomisional,$idreclamo,'$fechahora',$comentarios,$idestadoreclamo);");
        
        foreach ($respuesta as $value) {
            $idpatron = $value['patronrptaprocrecl']['Id_PatronRpta'];
            $posicionpatron = $value['patronrptaprocrecl']['PRPR_OrdenPatron'];
            $this->Respuesta->query("insert into patronrptaprocrecl values(NULL,(SELECT MAX(id) as id FROM procesoreclamo),$idpatron,$posicionpatron,0);");
            if ($value['patronrpta']['PR_CategoriaPatron'] == "E") {
                $testolibre = $value['rptareclamopatronedit']['RRPE_TextoLibre'];
                $this->Respuesta->query("insert into rptareclamopatronedit values(NULL,(SELECT MAX(id) as id FROM patronrptaprocrecl),'$testolibre');");
            }
        }

        $this->Respuesta->query("update reclamos SET Id_EstadoReclamo='$idestadoreclamo' WHERE id=$idreclamo;");
        $this->Respuesta->query("insert into rptaarmadareclamo values(NULL,$idreclamo,(SELECT MAX(id) as id FROM procesoreclamo),'$adjunto')");
    }

}

?>
