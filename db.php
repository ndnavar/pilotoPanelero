<?php
/**
 * Class db for Ajax Auto Refresh - Volume II - demo
 *
 * @author Eliza Witkowska <kokers@codebusters.pl>
 * @link http://blog.codebusters.pl/en/entry/ajax-auto-refresh-volume-ii
 */
date_default_timezone_set('America/Bogota');

class db{

	/**
	 * db
	 *
	 * @var $	public $db;
	 */
	public $db;


	/**
	 * __construct
	 *
	 * @return void
	 */
	function __construct(){
		//$this->db_connect('localhost','id1722893_root','DB_CII_2017','id1722893_asistencia');
		$this->db_connect('localhost','panelero','P4n3l4','CIIO2018');
	}


	/**
	 * db_connect
	 *
	 * Connect with database
	 *
	 * @param mixed $host
	 * @param mixed $user
	 * @param mixed $pass
	 * @param mixed $database
	 * @return void
	 */
	function db_connect($host,$user,$pass,$database){
		$this->db = new mysqli($host, $user, $pass, $database);

		if($this->db->connect_errno > 0){
			die('Unable to connect to database [' . $this->db->connect_error . ']');
		}
	}


	/**
	 * check_changes
	 *
	 * Get counter value from database
	 *
	 * @return void
	 */
	function check_changes(){
		$result = $this->db->query('SELECT counting FROM news WHERE id=1');
		if($result = $result->fetch_object()){
			return $result->counting;
		}
		return 0;
	}


	/**
	 * register_changes
	 *
	 * Increase value of counter in database. Should be called everytime when
	 * something change (add,edit or delete)
	 *
	 * @return void
	 */
	function register_changes(){
		$this->db->query('UPDATE news SET counting = counting + 1 WHERE id=1');
	}


	/**
	 * get_news
	 *
	 * Get list of news
	 *
	 * @return void
	 */
	function get_news($id_curso){
		$validar=$this->validar_hora($id_curso);
		if ($validar[0]){			
				$hora_inicio=$validar[1];
				$hora_fin=$validar[2];
			if($result = $this->db->query('SELECT * FROM Registrados, Usuarios WHERE Registrados.id=Usuarios.id AND Usuarios.id!=1 AND Registrados.id_curso="'.$id_curso.'" AND add_hour > "'.$hora_inicio.'" and add_hour < "'.$hora_fin.'"ORDER BY add_date DESC, add_hour DESC')){
				$return = '';
				while($r = $result->fetch_object()){
					/*if ($r->Tipo=="E"){
						$return .= '<p style="color: white; text-align: center;font-size: 1.5em;">Id: '.$r->id.' | <b>'.utf8_encode ($r->username).' </b><b style="font-size: 2em;color:red"><br> #'.utf8_encode ($r->CC).'</b></p>';
						$return .= '<hr/>';
					}else if ($r->Tipo=="E"){
						$return .= '<p style="color: white; text-align: center;font-size: 1.5em;">Id: '.$r->id.' | <b>'.utf8_encode ($r->username).' </b><b style="font-size: 2em;color:red"><br> # E - '.utf8_encode ($r->CC).'</b></p>';
						$return .= '<hr/>';
					}else if ($r->Tipo=="C"){
						$return .= '<p style="color: white; text-align: center;font-size: 1.5em;">Id: '.$r->id.' | <b>'.utf8_encode ($r->username).' </b><b style="font-size: 2em;color:black"><br> # E - '.utf8_encode ($r->CC).'</b></p>';
						$return .= '<hr/>';
					}
					else{*/
						$return .= '<p style="color: #D3DE23; text-align: center;font-size: 3.5em;">Id: '.$r->CC.' | <b>'.utf8_encode ($r->username).' </b><b style="font-size: 0.7em;color:#D3DE23"><br>'.utf8_encode ($r->add_date).'</b><b style="font-size: 0.7em;color:#D3DE23"><br>'.utf8_encode ($r->add_hour).'</b></p>'; 
						$return .= '<hr/>';	
					//}
				}
				return $return;
			}
		}return 0;
	}
	function get_count($id_curso){
		$validar=$this->validar_hora($id_curso);

		if ($validar[0]){

			$hora_inicio=$validar[1];

			$hora_fin=$validar[2];
$query='SELECT count(Registrados.id) as a FROM Registrados, Usuarios WHERE Registrados.id=Usuarios.id AND Usuarios.id!=1 AND Registrados.id_curso="'.$id_curso.'" AND add_hour > "'.$hora_inicio.'"AND add_hour < "'.$hora_fin.'"';

		if ($result=$this->db->query($query)){
$r=$result->fetch_object();
return '<p align="right" style="font-size: 1.5em;color:#D3DE23">Total registrados: '.$r->a.'</p>';

}	
}}
	function get_cursos(){
	$return="";
	$cursos=$this->db->query("SELECT * FROM cursos WHERE 1");
	while($row=$cursos->fetch_array())
		$return .= "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".utf8_encode($row[1])."</OPTION>";
	return $return;
	}
	function get_bloques($id_curso){
	$return="";
	$query="SELECT hora_inicio,hora_fin,Nombre FROM horarios_curso,cursos WHERE cursos.id=horarios_curso.id_curso and id_curso='$id_curso'";
	$cursos = $this->db->query($query);
	$count=1;
	
	while($row = $cursos->fetch_object()){
		$Nombre_curso=$row->Nombre;
		$nombre="datepair_".$count;
		$return .= '<p id="'.$nombre.'">De <input value="'.$row->hora_inicio.'" name="inicio_'.$count.'" id="inicio_'.$count.'" type="text" class="time start"/> a <input value="'.$row->hora_fin.'" name="fin_'.$count.'" id="fin_'.$count.'" type="text" class="time end" /></p>';
		$count++;
	}
	return [$count,$return,$Nombre_curso];
	}
	function get_ask(){
		$query="SELECT * FROM chat, Usuarios WHERE chat.to='1' AND chat.from=Usuarios.Id ORDER BY chat.id DESC LIMIT 50";
		$result = $this->db->query($query);
			$return = '';
			while($r = $result->fetch_object()){
				
				$return .= '<p><a href="javascript:chatWith('.$r->from.',\''.$r->username.'\');"> '.$r->username.'</a> : '.$r->message.' ';
				$return .= '<a href="javascript:del('.$r->id.');">x</a></p>';
				$return .= '<hr/>';
			}
			return $return;
		}//<a href="javascript:chatWith('+id+',\''+usr+'\');">
	
	
	
	function del_msg($msg){
		$this->db->query('DELETE FROM chat WHERE id='.$msg);
		
	}

	/**
	 * add_news
	 *
	 * Add new message
	 *
	 * @param mixed $title
	 * @return void
	 */
	function add_news($title){
		$title = $this->db->real_escape_string($title);
		if($this->db->query('INSERT into news (title) VALUES ("'.$title.'")')){
			$this->register_changes();
			return TRUE;
		}
		return FALSE;
	}
	function track_qr($qr_info){
		$existe=$this->db->query('SELECT * FROM Registrados where Packet_id="'.$qr_info.'"');
		if ($existe->num_rows==0){
			return [FALSE,"No se ha encontrado el paquete"];
		}
		
		$registro=$existe->fetch_object();
		$productor=$this->db->query('SELECT username FROM Usuarios where Id="'.$registro->id.'"')->fetch_object()->username;
		
		$msg="Lote: ".$registro->id_curso."<br>Fecha: ".$registro->add_date." ".$registro->add_hour."<br>Productor: ".utf8_encode($productor)."<br><br>Últimas localizaciones:";
		
		$ubicaciones=$this->db->query('SELECT * FROM Ubicacion where Packet_id="'.$qr_info.'"');
		$i=1;
		while($r = $ubicaciones->fetch_object()){
		$msg .= "<br>".$i." - ".$r->nombre_lugar.": ".$r->Lat." ".$r->Lon;
		$i++;
		}
		
		
		return [TRUE,$msg];
	}
	function registrar($doc,$id_curso,$prod_ca,$prod_pa,$id_registrador,$lat,$lon){
		$title = $this->db->real_escape_string($doc);
		$existe=$this->db->query('SELECT id FROM Usuarios where id="'.$doc.'"');
		
		if ($existe->num_rows==0){
			return [FALSE,"El Usuario no existe"];
		}
		
		/*$cursos=$this->db->query('SELECT id_curso FROM usuarios_curso where id_usuarios="'.$doc.'"');
		$Array = array();
		while ($record = $cursos->fetch_array()) $Array[] = $record['id_curso'];
		if (!in_array($id_curso, $Array)){
			return [FALSE,"El Usuario no esta registado en el curso ".$id_curso];
		}*/
		$validar=$this->validar_hora($id_curso);
		if ($validar[0]){			
			$hora_inicio=$validar[1];
			$hora_fin=$validar[2];
			
			$existe=$this->db->query('SELECT id FROM Registrados where add_hour > "'.$hora_inicio.'" and add_hour < "'.$hora_fin.'" AND id_curso = "'.$id_curso.'" AND add_date = "'.date("Y-m-d").'" AND id = "'.$doc.'"');
			if ($existe->num_rows!=0){
				$result=$this->db->query('SELECT CC,username,Tipo from Usuarios where id="'.$doc.'"');
				$r = $result->fetch_object();
				return [FALSE,"Usuario ya registrado en la sesión actual <br>".utf8_encode($r->username).' # '.$r->CC.' <b style="color:red"> - '.$r->Tipo.'</b>'];
			}

	#		if ($id_curso==5 ){
	#		$Tipo=$this->db->query('SELECT Tipo FROM Usuarios where Id="'.$doc.'"');
	#		$r = $Tipo->fetch_object();
	#		if ($r->Tipo!="M"){
	#		return [FALSE,"El Usuario no esta autorizado para recibir almuerzo ".$r->Tipo];
	#		}
	#		if ($this->db->query('INSERT into Registrados VALUES ("'.$doc.'","'.date("Ymd").'","'.date("H:i:s").'","'.$id_curso.'","'.$id_registrador.'")')){
	#			$this->register_changes();
	#			$result=$this->db->query('SELECT CC,username,Tipo from Usuarios where id="'.$doc.'"');
	#			$r = $result->fetch_object();
	#		return [TRUE,"Registro exitoso para almuerzo "];}}
			$fecha=date("Ymd");
			$hora=date("H:i:s");
			if ($this->db->query('INSERT into Registrados VALUES ("'.md5($id_curso.$doc.$fecha.$hora).'","'.$doc.'","'.$fecha.'","'.$hora.'","'.$id_curso.'",'.$prod_ca.",".$prod_pa.',"'.$id_registrador.'")')){
				//Call Whasapp sevice with md5($id_curso.$doc.$fecha.$hora) and
				
				#$movil=$this->db->query('SELECT movil from Usuarios where id="'.$doc.'"')->fetch_object();
				#$this->db->query('INSERT into Registrados_temp VALUES ("'.md5($id_curso.$doc.$fecha.$hora).'","'.$doc.'","'.$fecha.'","'.$hora.'","'.$id_curso.'",'.$prod_ca.",".$prod_pa.',"'.$id_registrador.'")');
				
				$this->db->query('INSERT into Registrados_temp VALUES ("'.md5($id_curso.$doc.$fecha.$hora).'","'.$doc.'","'.$fecha.'","'.$hora.'","'.$id_curso.'","'.$id_registrador.'")');
				$this->db->query("INSERT into Ubicacion VALUES ('".md5($id_curso.$doc.$fecha.$hora)."',".$lat.",".$lon.",'Trapiche #XX')");
				$this->register_changes();
				$result=$this->db->query('SELECT CC,username,Tipo from Usuarios where id="'.$doc.'"');
				
				$r = $result->fetch_object();
				#return [TRUE,"INSERT into Ubicacion VALUES ('".md5($id_curso.$doc.$fecha.$hora)."',".$lat.",".$lon.",'Trapiche #XX'"];
				return [TRUE,utf8_encode($r->username).' # '.$r->CC.' <b style="color:red"> - '.$movil.'</b>'];
				
			}else {return [FALSE, 'INSERT into Registrados VALUES ("'.md5($id_curso.$doc.$fecha.$hora).'","'.$doc.'","'.$fecha.'","'.$hora.'","'.$id_curso.'",'.$prod_ca.",".$prod_pa.',"'.$id_registrador.'")'];}
		}
		else {
			return [FALSE, "Fuera de la hora de registro para el curso ".$id_curso];
		}
		
	}
	
	function validar_hora ($id_curso){
		$horas=$this->db->query("SELECT * FROM horarios_curso WHERE id_curso='".$id_curso."'");
		while ($r = $horas->fetch_object()){
			$hora_inicio = $r->hora_inicio;
			$hora_fin = $r->hora_fin;
			if (date("H:i:s") > $hora_inicio && date("H:i:s")<$hora_fin){
				
				return [TRUE,$hora_inicio,$hora_fin];
			}
		}
		return [FALSE];
	}

	function participantes(){
		$query="SELECT * FROM Usuarios WHERE CAST( CC AS UNSIGNED ) <25 AND Tipo = 'E'";
		$result = $this->db->query($query);

			return $result;
		}//<a href="javascript:chatWith('+id+',\''+usr+'\');">
		
		function manual($id){
		$query="SELECT * FROM Usuarios WHERE Id ='".$id."'";
		$result = $this->db->query($query);

			return $result;
		}//<a href="javascript:chatWith('+id+',\''+usr+'\');">
		
		function cambiarpass($user,$pass){
		$query="UPDATE Usuarios SET U_password = '".$pass."' WHERE CC='".$user."'";
		if($this->db->query($query)){
			return "Contraseña cambiada con éxito";
		}

			return "Error cambiando la contraseña";
		}
	function es_registrador($user){
		$query="SELECT Tipo FROM Usuarios WHERE Id='".$user."'";
		$result = $this->db->query($query);
		$r = $result->fetch_object();
		if($r->Tipo!="E" ){
			return TRUE;
		}

			return FALSE;
		}
	function nombre($user){
		$query="SELECT Username FROM Usuarios WHERE Id='".$user."'";
		$result = $this->db->query($query);
		$r = $result->fetch_object();
		return $r->Username;
		}
	
}
/* End of file db.php */
