<?php
require('common.php');
session_start();


if (!isset($_SESSION['k_id']) || $_SESSION['k_id']!=1) {
	//echo $_SESSION['k_id'];
	echo '<SCRIPT LANGUAGE="javascript">
			location.href = "login.php";
			</SCRIPT>';
}

if ($_POST['curso']!="" && $_POST['nombre']!="" && $_POST['inicio_1']!="" && $_POST['fin_1']!="") {
  if ($_POST['curso']==0){
    $query="INSERT into cursos VALUES (NULL,'".$_POST['nombre']."')";
    //echo $query;
    $db->db->query($query);
    $id = $db->db->insert_id;
    for ($i=1;$i<(count($_POST)-1)/2;$i++){
      $query="INSERT into horarios_curso VALUES (NULL,'".$id."','".$_POST['inicio_'.$i.'']."','".$_POST['fin_'.$i.'']."')";
	//echo $query;
      $db->db->query($query);
    }
  }elseif ($_POST['del']=="False"){

    $query="UPDATE cursos SET Nombre='".$_POST['nombre']."' WHERE Id='".$_POST['curso']."'";
    //echo $query;
    $db->db->query($query);
    $query="DELETE FROM horarios_curso WHERE id_curso='".$_POST['curso']."'";
    $db->db->query($query);//echo $query;
    for ($i=1;$i<(count($_POST)-1)/2;$i++){
      if ($_POST['inicio_'.$i.'']!="" && $_POST['fin_'.$i.'']!=""){
      $query="INSERT into horarios_curso VALUES (NULL,'".$_POST['curso']."','".$_POST['inicio_'.$i.'']."','".$_POST['fin_'.$i.'']."')";
      $db->db->query($query);//echo $query;
      }}}
    elseif ($_POST['del']=="True"){
    $query="DELETE FROM horarios_curso WHERE id_curso='".$_POST['curso']."'";
    $db->db->query($query);
    $query="DELETE FROM cursos WHERE Id='".$_POST['curso']."'";
    $db->db->query($query);
  }
}else{
  echo "<h2 style='color:red'>Por favor aseguresé que todos los campos estén llenos</h2>";
}


?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" charset='utf-8'/>
    <link rel="stylesheet" href="css/main.css">
    <link type="text/css" rel="stylesheet" media="all" href="css/chat.css" />
    
    <link rel="stylesheet" type="text/css" href="css/default.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/modernizr.custom.js"></script>
    
    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
    </style>
    

  <script type="text/javascript" src="js/jquery.min.js"></script>

  <script type="text/javascript" src="js/jquery.timepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css" />

  <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
  <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.css" />
    
  </head>
  <body>
    <h2>Creación o actualización de un lote</h2>
    <form action=cursos.php method="post">
    Lote: <Select name="curso" id="curso">
	<option value="0" SELECTED>Crear un lote</option>
    <?php
	$cursos= $db->db->query("SELECT * FROM cursos WHERE 1");
	while($row=$cursos->fetch_array())
		echo "<OPTION VALUE='".$row[0]."'>".$row[0]." - ".$row[1]."</OPTION>";

    ?>
    </Select>
    
    <br><br><div id="nombre">
    No. de lote: <input id="nombre_curso" name="nombre" type="text" />
    </div>
    
    <br>Bloques de trabajo:      <a href="javascript:nuevo()">+ nuevo</a>
    <div id="bloques">
    <p id="datepair_1">
    De <input name="inicio_1" id="inicio" type="text" class="time start"/> a
    <input name="fin_1" id="fin" type="text" class="time end" />
    </p>
    </div>
    <input name="del" id="del" type="hidden" value="False" />
    <button>Guardar</button>
    <button onclick="$('#del').val('True');" style="display: none" id="del_btn">Eliminar</button>
    </form>
<br>
<button onclick="location.replace('..')">Atras</button>
  
<script src="js/datepair.js"></script>
<script src="js/jquery.datepair.js"></script>
<script>
    var count=1;
    // initialize input widgets first
    $('#datepair_1 .time').timepicker({
        'showDuration': true,
        'timeFormat': 'H:i:s'
    });

    // initialize datepair
    $('#datepair_1').datepair();
    $('#curso').change(function(){
            if ($(this).val()=="0") {
                $('#bloques').html('<p id="datepair_1">De <input name="inicio_1" id="inicio_1" type="text" class="time start"/> a<input name="fin_1" id="fin_1" type="text" class="time end" /></p>')
                    $('#datepair_1 .time').timepicker({
                        'showDuration': true,
                        'timeFormat': 'H:i:s'
                    });
                
                    // initialize datepair
                    $('#datepair_1').datepair();
		    $('#del_btn').hide();
		    $('#nombre_curso').val("");
            }
            else{
		    $('#del_btn').show();
                    $.ajax({
				type: 'POST',
				url: '../Controller/bloques.php',
				dataType: 'json',
				data: {
					id_curso:$(this).val()
				}
			}).done(function( response ) {
				/* check if with response we got a new update */
					if (response[1]!=""){
					$('#bloques').html(response[1]);
                                        $('#nombre_curso').val(response[2]);
                                        for (a=1;a<response[0];a++){
                                            var nombre="datepair_"+a
                                                $('#'+nombre+' .time').timepicker({
                                                    'showDuration': true,
                                                    'timeFormat': 'H:i:s'
                                                });
                                                $('#'+nombre).datepair();
						count++;
                                        }count--;}
					else{
					$('#bloques').html('<p id="datepair_1">De <input name="inicio_1" id="inicio" type="text" class="time start"/> a <input name="fin_1" id="fin" type="text" class="time end" /></p>');
					    $('#datepair_1 .time').timepicker({
						  'showDuration': true,
						  'timeFormat': 'H:i:s'
					      });
					  
					      // initialize datepair
					      $('#datepair_1').datepair();
					}
                                        
				
			});
            }
    })

    function nuevo() {
        count++;
        var nombre="datepair_"+count
    $('#bloques').append('<p id="'+nombre+'">De <input name="inicio_'+count+'" id="inicio_'+count+'" type="text" class="time start"/> a <input name="fin_'+count+'" id="fin_'+count+'" type="text" class="time end" /></p>');
    $('#'+nombre+' .time').timepicker({
        'showDuration': true,
        'timeFormat': 'H:i:s'
    });
    $('#'+nombre).datepair();
    }

</script>
  </body>
</html>
