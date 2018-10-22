<?php
$id=$_GET["id"];
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Registro de Producción</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="css/style.css">
  <script src="js/modernizr-1.js"></script>

  <script src="js/main.js"></script>
        
    </head>
    <body>
  <div id="container">
    <header>

    </header>
    <div id="main" role="main">

      <form id="formulario" class="login-form clearfix" method="post" action="validar_usuario.php">
	<fieldset>
          <label for="username">ID <span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
            <input name="username" id="username" required=""  type="text"  <?php if ($id!='')echo 'value=\''.$id.'\' readonly';?> >
          <label for="password">Password <span class="ico"><img src="images/pass.png" alt="Password Icon" border="0" ></span></label>
            <input name="password" id="password" required="" autofocus="" type="password"  <?php //if ($id!='')echo 'value=\'2CIIO\'';?>>
	  <p class="form-response error"></p>
	</fieldset>
        <fieldset>
	  <button id="login-btn" type="submit">&gt;&gt;&nbsp;&nbsp;&nbsp;</button>
        </fieldset>
          <?php if ($id!="")echo '<span class="password"><a href="reset.php?id='.$id.'">Resetear Password</a></span>'; ?>
      </form>	
			
    </div>
    
       <footer>

    </footer>

  </div>
  
    <script src="js/jquery.js"></script>
  <script>window.jQuery || document.write("<script src='js/libs/jquery-1.5.1.min.js'>\x3C/script>")</script>

  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
  <!-- end scripts-->

  <!--[if lt IE 7 ]>
    <script src="js/libs/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
  <![endif]-->
  
       	<script type="text/javascript" src="js/vendor/jquery-1.10.1.min.js"></script>
        <script type="text/javaxcript" src="js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>
        <script type="text/javascript" src="/js/TweenMax.min.js"></script>
        <script type="text/javascript" src="js/parsley.js"></script>
		
		
        <script src="js/main.js"></script>
    </body>
    

    
</html>
