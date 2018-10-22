<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>SIG Arauca</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
	  
    <link rel="stylesheet" href="js/jqwidgets/styles/jqx.base.css" type="text/css" />
    <script type="text/javascript" src="js/scripts/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="js/scripts/demos.js"></script>
    <script type="text/javascript" src="js/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="js/jqwidgets/jqxslider.js"></script>
    <script type="text/javascript" src="js/jqwidgets/jqxbuttons.js"></script>
    <script type="text/javascript" src="js/jqwidgets/jqxscrollbar.js"></script>
    <script type="text/javascript" src="js/jqwidgets/jqxpanel.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            function displayEvent(event) {
                var eventData;
                eventData = event.args.value+" KG";
                $('#events').jqxPanel('clearContent');
                $('#events').jqxPanel('prepend', '<div class="item" style="margin-top: 5px;color:white;">' + eventData + '</div>');
		document.getElementById("capacidad").value=event.args.value;
	    }
            $('#events').jqxPanel({  height: '25px', width: '80px' });
	    $('#events').jqxPanel('prepend', '<div class="item" style="margin-top: 5px;color:white;">500 KG</div>');
            document.getElementById("capacidad").value=500;
	    $('#jqxSlider div').css('margin', '5px');
	    $('#jqxSlider').jqxSlider({ min: 500, max: 100000, values: [500, 100000], step: 100, ticksFrequency: 1000,  mode: 'fixed'});
            //change event
            $('#jqxSlider').jqxSlider({  mode: 'fixed' });
            $('#jqxSlider').on('change', function (event) {
                displayEvent(event);
            });
        });
    </script>
	  

<link rel="stylesheet" href="css/style.css">
  <script src="js/modernizr-1.js"></script>

  <script src="js/main.js"></script>
      
      <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQZ2CrJzwrZ8ZWwrPbQV2LoyoQOBNrQyQ&sensor=true&language=es&region=CO">
    </script>
      
     <script type="text/javascript">
        var geocoder;
        var map;
        var marker;
        var infowindows=[];
        
        var infowindow = new google.maps.InfoWindow();
        
      function initialize() {
          document.getElementById("registroform").reset();
        tipo();
      
	
        var mapOptions = {
          center: new google.maps.LatLng(6.73, -71.3),
          zoom: 6,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
	
	  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
  });

	
      }
        
      </script>
      
          <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { width: 200px }
    </style>
          
          
    </head>
    <body onload="initialize()">
  
      
  <div id="container">
    <header>


    </header>
    <div id="main" role="main">


    <form id="registroform" class="login-form clearfix" method="post" action="registrar2.php">

    <fieldset style="width: 350px; border-style: ridge;border: medium dashed #A03838; border-radius: 10px 10px 10px 10px; margin-left: -25px; padding-left: 20px;">
      <legend style="font-size: 1.8em;color:white;">Soy</legend>
      <input type="radio" name="tipou" value="productor" onchange="tipo();" checked="checked"><a style="font-size: 1.8em;">Productor</a>
      <input type="radio" name="tipou" value="transportador" onchange="tipo();"><a style="font-size: 1.8em;">Trasportador</a>
      <input type="radio" name="tipou" value="consumidor" onchange="tipo();"><a style="font-size: 1.8em;">Consumidor</a>
    
    </fieldset>
    
                    <fieldset>

                        <label for="nombres">Nombres <span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<input name="nombres" id="nombres" required="" autofocus="" type="text">
                                            
                        <label for="apellidos">Apellidos <span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<input name="apellidos" id="apellidos" required="" type="text">

                        <label for="documento">Documento de Identidad <span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<input name="documento" id="documento" required=""  type="text">
			
			<label for="correo">Correo electrónico <span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<input name="correo" id="correo" required=""  type="text">
	    <fieldset id="productor">
			  
                        <label for="lugar">Nombre de mi finca <span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<input name="lugar" id="lugar"  type="text">
			
			<label for="extension">Extensión<span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<input name="extension" id="extension" required=""  type="text">

                        <label for="cacaotero">Produzco Cacáo </label>
		               		<input name="cacaotero" id="cacaotero" type="checkbox"><br>

                         <label for="platanero">Produzco Platano </label>
		               		<input name="platanero" id="platanero"  type="checkbox"><br>                                       

                        <label for="username">Produzco Carnicos </label>
                                        <input name="carnicos" id="carnicos"  type="checkbox"> <br>
				  
	    </fieldset>
			
	    <fieldset id="transportador" style="display: none">       		
                        <label for="tvehiculo">Tipo de Vehiculo<span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		               		<select name="tvehiculo" id="tvehiculo">
					    <option value="volvo">Volvo</option>
					    <option value="saab">Saab</option>
					    <option value="mercedes">Mercedes</option>
					    <option value="audi">Audi</option>
					</select> <br>
			
			<label for="capvehi">Capacidad del Vehiculo<span class="ico"><img src="images/user.png" alt="Username Icon" border="0"></span></label>
		        <div class="options-value"><div style="float: left" id="events"></div></div>
			<div id='jqxSlider'></div><input name="capacidad" id="capacidad" type="hidden">            
	   </fieldset>
	    
	  <fieldset id="consumidor" style="display: none">       		
          
	   </fieldset>

                       <label for="password">Password <span class="ico"><img src="images/pass.png" alt="Password Icon" border="0"></span></label>
	        	            <input name="password" id="password" required="" type="password">
			<label for="password2">Confirmar password: <span class="ico"><img src="images/pass.png" alt="Password Icon" border="0"></span></label>
	        	            <input name="password2" id="password2" required="" type="password">
                        
                        <label>Mi Ubicación</label>
                        <label for="lat">Latitud <span class="ico"><img src="images/pass.png" alt="Password Icon" border="0"></span></label>
	        	            <input name="lat" id="lat" required="" type="text">
                        
                        <label for="long">Longitud <span class="ico"><img src="images/pass.png" alt="Password Icon" border="0"></span></label>
	        	            <input onchange=setPosition(lat.value,this.value); name="long" id="long" required="" type="text">
                        
                        <button type="button" class="coord" onclick="getLocation()" style="width: 160px; height: 40px; text-align: center; margin-top: -30px; margin-right: -190px;">Traer mis coordenadas</button>                
                        
			<p id="nogeo"></p>
			
			<p class="form-response error"></p>

            		</fieldset>


                            
                    <fieldset>
                    	

                    	<button id="registro-btn" type="submit">&gt;&gt;&nbsp;&nbsp;&nbsp;</button>

                    </fieldset>

<div id="rigth">
                               
</div>
           		</form>	
			
    </div>
                             <div id="map_canvas" style="height: 700px; width: 400px; left: 50%; margin-left: -200px;">sadddd</div>  
       <footer>

    </footer>

  </div>
  
   
  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
  <!-- end scripts-->

  <!--[if lt IE 7 ]>
    <script src="js/libs/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix("img, .png_bg"); // Fix any <img> or .png_bg bg-images. Also, please read goo.gl/mZiyb </script>
  <![endif]-->
  
        <script type="text/javaxcript" src="js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>
        <script type="text/javascript" src="/js/TweenMax.min.js"></script>
        <script type="text/javascript" src="js/parsley.js"></script>
		
		
        <script src="js/main.js"></script>
        
        

<script>
var x = document.getElementById("nogeo");
var lon = document.getElementById("long");
var lat = document.getElementById("lat");
function a(){
    alert("sa");
}

function tipo(){


  var t = document.getElementsByName("tipou");
  var c;
  var productor = document.getElementById("productor");
  var transportador = document.getElementById("transportador");
  var consumidor = document.getElementById("consumidor");
  
    for(var i=0; i < t.length; i++){
       if(t[i].checked) {
          c = i; }
    }

  switch (c) {
    case 0:
      //alert("productor");
      productor.style.display="block";
      transportador.style.display="none";
      consumidor.style.display="none";
	    document.getElementById("extension").required=true;
      break;
    case 1: //Transportador
      productor.style.display="none";
      transportador.style.display="block";
      consumidor.style.display="none";
            document.getElementById("extension").required=false;
	    document.getElementById("extension").value=0;
      break;
     case 2://Consumidor
      productor.style.display="none";
      transportador.style.display="none";
      consumidor.style.display="block";
            document.getElementById("extension").required=false;
	    document.getElementById("extension").value=0;
      break;
  }

}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    lon.value=position.coords.longitude;
    lat.value=position.coords.latitude;
    x.innerHTML="Ubicación determinada";
    setPosition(position.coords.latitude, position.coords.longitude);

}

function setPosition(lat,lon) {
    var ubc=new google.maps.LatLng(lat, lon);
    map.setCenter(ubc);
    map.setZoom(12);
    placeMarker(ubc);

}

function populateInputs(pos) {
    lat.value=pos.lat();
    lon.value=pos.lng();
}

function placeMarker(location) {
  if ( marker ) {
    marker.setPosition(location);
  } else {
    marker = new google.maps.Marker({
      position: location,
      map: map,
      draggable: true
    });
        google.maps.event.addListener(marker, "drag", function (mEvent) {
        populateInputs(mEvent.latLng);
    });
  }
  populateInputs(location);
}
</script>
        

    

    
    </body>
    

    
</html>
