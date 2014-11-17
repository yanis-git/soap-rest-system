<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ESGI XML PROJECT</title>

    <!-- Bootstrap core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/navbar/navbar.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/chosen.css">
  <link rel="stylesheet" href="assets/css/app.css">
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="http://getbootstrap.com/assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">ESGI XML PROJECT</a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="list.php">Liste</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Explore les spots de skate</h1>
        <p>Ici vous allez pouvoir découvrir tous les spots du monde, en recherchant dans notre base de donnée regroupant des spotes du monde entier ! </p>
      </div>

	 <div class="row">
	 	<div class="col-md-6">
		 	<div id="map_canvas"></div>
	 	</div>
	 	<div class="col-md-6">
	 		<h1>Positionner une Place</h1>
      <hr>
			<form id="add-place-form" class="form-horizontal" role="form">

			  <div class="select-continents-wrapper has-warning has-feedback form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">Continent</label>
			    <div class="col-sm-10">
              <select name="continent" data-placeholder="choisir un continent..." class="select-continents chosen-select">
                  
              </select>
              <span class="select-continents-feedback glyphicon glyphicon-warning-sign form-control-feedback"></span>
			    </div>
			  </div>

        <div class="select-countries-wrapper has-warning has-feedback form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Pays</label>
          <div class="col-sm-10">
              <select name="country" data-placeholder="choisir un pays..." class="select-countries chosen-select">
                  
              </select>
               <span class="select-countries-feedback glyphicon glyphicon-warning-sign form-control-feedback"></span>
          </div>
        </div>

			  <div class="town-form-wrapper autocomplete-town-wrapper has-warning has-feedback form-group">
			    <label for="town" class="col-sm-2 control-label">Ville</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control typeahead" id="town" placeholder="Ville">
            <input type="hidden" class="form-control" name="townid" id="townid" value="">
             <span class="town-form-feedback glyphicon glyphicon-warning-sign form-control-feedback"></span>
			    </div>
			  </div>

        <hr>
        <div class="spot-form-wrapper">
          <div class="name-form-wrapper form-group has-warning has-feedback">
              <label for="town" class="col-sm-2 control-label">Nom</label>
              <div class="col-sm-offset-2 col-sm-10">
                <input type="text" class="form-control" name="name" value="" placeholder="le nom de votre choix">
                 <span class="name-form-feedback glyphicon glyphicon-warning-sign form-control-feedback"></span>
              </div>
            </div>

          <div class="address-form-wrapper form-group has-warning has-feedback">
              <label for="town" class="col-sm-2 control-label">Adresse</label>
              <div class="col-sm-offset-2 col-sm-10">
                <input type="text" class="form-control" id="input-adress" name="address" value="" placeholder="l'adresse de votre choix">
                 <span class="address-form-feedback glyphicon glyphicon-warning-sign form-control-feedback"></span>
                 <input type="hidden" class="form-control" name="longitude" value="">
                 <input type="hidden" class="form-control" name="latitude" value="">
              </div>
            </div>

            <div class="comment-form-wrapper form-group has-warning has-feedback">
              <label for="town" class="col-sm-2 control-label">Commentaire</label>
              <div class="col-sm-offset-2 col-sm-10">
                  <textarea class="form-control" name="comment"></textarea>
                   <span class="comment-form-feedback glyphicon glyphicon-warning-sign form-control-feedback"></span>
              </div>
            </div>

    			  <div class="form-group">
    			    <div class="col-sm-offset-2 col-sm-10">
    			      <button type="submit" id="submit-form-btn" class="disabled btn btn-default">Spot it !</button>
    			    </div>
    			  </div>
          </div>
			</form>
	 	</div>
	 </div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="http://getbootstrap.com/dist/js/bootstrap.min.js"></script>
	  <script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
    <script src="assets/js/map/jquery.ui.map.full.min.js"></script>
	  <script src="assets/js/chosen.jquery.min.js"></script>
    <script src="http://rest.esgi-xml.local/javascript/request.js"></script>
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function() {
                // Section gmap
                var yourStartLatLng = new google.maps.LatLng(48.84979, 2.56266); //Noisy le grand

                $("#map_canvas").width($("#map_canvas").parent("div").width());
                $("#map_canvas").height($("#map_canvas").parent("div").width());
                $('#map_canvas').gmap({'center': yourStartLatLng});
                RestInitInterface("<?php echo "http://rest.esgi-xml.local"; ?>");
        });
	</script>
  </body>
</html>
