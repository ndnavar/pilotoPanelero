<?php
session_start();
include("../View/common.php");
#$global_dbh = ConexionMySQL();
#$result = mysql_query('UPDATE Usuarios SET `U_Online`=0 WHERE U_Productor=\''.$_SESSION["k_username"].'\'');
// Borramos toda la sesion
setcookie('k_username', $usuario , time()-3600);
session_destroy();

echo '<script>
    window.location="..";
</script>';
?>
