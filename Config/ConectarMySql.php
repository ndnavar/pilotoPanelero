<?php
    function ConexionMySQL()
    {
	//$conn = mysql_connect('localhost',"id1722893_root",'DB_CII_2017')or die ('Ha fallado la conexión: '.mysql_error());
	$conn = mysql_connect('localhost',"panelero",'P4n3l4')or die ('Ha fallado la conexión: '.mysql_error());
	mysql_select_db('seminario2018')or die ('Error al seleccionar la Base de Datos: '.mysql_error());
	//mysql_select_db('test')or die ('Error al seleccionar la Base de Datos: '.mysql_error());
            if($conn)
            {
                return $conn;
            }
            else
            {
              echo "Fallo la Conexion desde Funcion";
              return false;
            }
    }
?>
