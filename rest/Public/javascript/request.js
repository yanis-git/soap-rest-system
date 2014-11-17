var RestInitInterface = function(rest_domain) {

	var reference = ["longitude","latitude", "continent","country","city","name","address","comment"];
	var formIsOk = function (){
		var verif = true;
		for(key in reference){
			var selector = ".form-control[name="+reference[key]+"]";
			if($(selector).val() == ""){
				verif = false;
			}
		}
		return verif;
	};


	$.get(rest_domain+'/places', function(data) {
    	var places = data.getElementsByTagName("integer");
		for(key in places){
    		if(typeof places[key] === "object"){
    			var latitude = $(places[key].getElementsByTagName("latitude")[0]).text();
    			var longitude = $(places[key].getElementsByTagName("longitude")[0]).text();
    			var address = $(places[key].getElementsByTagName("address")[0]).text();
    			var id = $(places[key].getElementsByTagName("id")[0]).text();
    			var cityname = $(places[key].getElementsByTagName("cityname")[0]).text();
    			var townid = $(places[key].getElementsByTagName("town_id")[0]).text();

    			$('#map_canvas').gmap("addMarker",{ 'townid':townid,'placeid':id, 'cityname':cityname, 'address':address, 'position': new google.maps.LatLng(latitude,longitude) })
    							.click(function() {
    								var contenu = this.address+", "+this.cityname+"<br> <a href='place.php?id="+this.placeid+"&townid="+this.townid+"'> Voir la fiche de cette place.</a>";
									$('#map_canvas').gmap('openInfoWindow', { content : contenu }, this);
				});
    		}
			// $('#map_canvas').gmap('set', 'MarkerClusterer', new MarkerClusterer(map, $(this).gmap('get', 'markers')));
	
    	}
	});


	$.get(rest_domain+'/continents', function(data) {
    	var continents = data.getElementsByTagName("integer");
		$(".select-continents.chosen-select").append("<option value=''></option>");

    	for(key in continents){
    		if(typeof continents[key] === "object"){
    			var name = $(continents[key].getElementsByTagName("name")[0]).text();
    			var code = $(continents[key].getElementsByTagName("code")[0]).text();
    			$(".select-continents.chosen-select").append("<option value='"+code+"'>"+name+"</option>");
    		}
    		
    	}
    	$(".select-continents-wrapper").show();
   		$(".select-continents.chosen-select").chosen({width:$("input[name=name]").width()+"px;"});
   		if(formIsOk())
   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
   		else
   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
    });

    $(".select-continents.chosen-select").change(function(event) {
    	var continent_code = $(this).val();

		$.get(rest_domain+'/countries?continent_code='+continent_code, function(data) {

	    	var countries = data.getElementsByTagName("integer");

	    	$(".select-countries.chosen-select").append("<option value=''></option>");

	    	for(key in countries){
	    		if(typeof countries[key] === "object"){
	    			var name = $(countries[key].getElementsByTagName("name")[0]).text();
	    			var code = $(countries[key].getElementsByTagName("code")[0]).text();
	    			$(".select-countries.chosen-select").append("<option value='"+code+"'>"+name+"</option>");
	    		}
	    		
	    	}
	    	$(".select-countries-wrapper").show();
			$(".select-continents-wrapper").removeClass('has-warning').removeClass('has-error').addClass('has-success');
			$(".select-continents-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-remove').addClass('glyphicon-ok');

	   		$(".select-countries.chosen-select").chosen({width:$("input[name=name]").width()+"px;"});
	  		if(formIsOk())
	   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
	   		else
	   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
	   		$(".select-countries.chosen-select").trigger("chosen:updated");
 	    });

    });

    $(".select-countries.chosen-select").change(function(){
		if($(this).val() == ""){
			$(".autocomplete-town-wrapper").hide();
		}
		else{
			$(".select-countries-wrapper").removeClass('has-warning').removeClass('has-error').addClass('has-success');
			$(".select-countries-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-remove').addClass('glyphicon-ok');
			$(".autocomplete-town-wrapper").show();
	  		if(formIsOk())
	   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
	   		else
	   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
	 	}
    });

    $("textarea[name=comment]").change(function(event) {
    	if($(this).val() == ""){
			$(".comment-form-wrapper").removeClass('has-warning').removeClass('has-success').addClass('has-error');
			$(".comment-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-ok').addClass('glyphicon-remove');
    	}
    	else{
    		$(".comment-form-wrapper").removeClass('has-warning').removeClass('has-error').addClass('has-success');
			$(".comment-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-remove').addClass('glyphicon-ok');
	  		if(formIsOk())
	   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
	   		else
	   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
	 	}
    });


    $("input[name=name]").change(function(event) {
    	if($(this).val() == ""){
			$(".name-form-wrapper").removeClass('has-warning').removeClass('has-success').addClass('has-error');
			$(".name-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-ok').addClass('glyphicon-remove');
    	}
    	else{
    		$(".name-form-wrapper").removeClass('has-warning').removeClass('has-error').addClass('has-success');
			$(".name-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-remove').addClass('glyphicon-ok');
	  		if(formIsOk())
	   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
	   		else
	   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
	 	}
    });


// 	    geocoder = new google.maps.Geocoder();
// geocoder.geocode( { 'address': "03 rue george sand, la queue en brie, france"}, function(results, status) {console.log(results);});    

	$("#input-adress").focusout(function(event) {
		if($(this).val() == ""){
			$(".address-form-wrapper").addClass('has-warning');
			$(".address-form-wrapper").children('span.glyphicon').addClass('glyphicon-warning-sign');
			return false;
		}
		geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': $(this).val()+", "+$('#town').val()}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				console.log(results[0].geometry.location.toString());

				$('#map_canvas').gmap("addMarker",{'position': results[0].geometry.location, 'bounds': true});
				$('#map_canvas').gmap({'center': results[0].geometry.location });
				
				$(".form-control[name=latitude]").val(results[0].geometry.location.lat());
				$(".form-control[name=longitude]").val(results[0].geometry.location.lng());

				//$('#map_canvas').gmap('addMarker', { 'position': new google.maps.LatLng(results[0].geometry.location.latitude, results[0].geometry.location.longitude), 'bounds': true });
				$(".address-form-wrapper").removeClass('has-warning').removeClass('has-error').addClass('has-success');
				$(".address-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-remove').addClass('glyphicon-ok');
			}
			else{
				$(".address-form-wrapper").removeClass('has-warning').removeClass('has-success').addClass('has-error');
				$(".address-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-ok').addClass('glyphicon-remove');
		  		if(formIsOk())
		   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
		   		else
		   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
			}
		});
	});


     $('#town').autocomplete({    
		source: function (request, response) {
         	$.ajax({
	             url: rest_domain+'/city',
	             data: { q: request.term, country: function() {return $(".select-countries.chosen-select").val()}},
	             dataType: "xml",
	             error: function () {
	                 response([]);
	             },
	             success: function( data ) {
				    var continents = data.getElementsByTagName("integer");
				    var towns = [];
					for(key in continents){
						if(typeof continents[key] === "object"){
							var name = $(continents[key].getElementsByTagName("name")[0]).text();
							var id = $(continents[key].getElementsByTagName("id")[0]).text();
							var latitude = $(continents[key].getElementsByTagName("latitude")[0]).text();
							var longitude = $(continents[key].getElementsByTagName("longitude")[0]).text();
							towns.push({label:name,name:name,id:id,longitude:longitude,latitude:latitude});
						}
					}
					response(towns);
				}
        	 })
     	},
     	focus: function( event, ui ) {
	        $( "#town" ).val( ui.item.name );
	        return false;
	    },
	    select: function( event, ui ) {
	    console.log(ui.item);
        $( "#town" ).val( ui.item.name );
        $( "#townid" ).val( ui.item.id );

        // var yourStartLatLng = new google.maps.LatLng(ui.item.latitude, ui.item.longitude); //currentSelectedTown
        // $('#map_canvas').gmap('addMarker', { 'position': new google.maps.LatLng(ui.item.latitude, ui.item.longitude), 'bounds': true });
        $('#map_canvas').gmap('get','map').setOptions({'center':new google.maps.LatLng(ui.item.latitude, ui.item.longitude),"zoom":14});
        $(".spot-form-wrapper").show("slow");
        $(".town-form-wrapper").removeClass('has-warning').removeClass('has-error').addClass('has-success');
		$(".town-form-feedback").removeClass('glyphicon-warning-sign').removeClass('glyphicon-remove').addClass('glyphicon-ok');
  		if(formIsOk())
   			$("#submit-form-btn").removeClass('disabled').addClass('btn-success');
   		else
   			$("#submit-form-btn").addClass('disabled').removeClass('btn-success');
 
       return false;
      }
     });

	$("#submit-form-btn").click(function(){
		if(!formIsOk())
			return false;

		var postValues = {
			"name" : $(".form-control[name=name]").val(),
			"description" : $(".form-control[name=comment]").val(),
			"town_id" : $(".form-control[name=townid]").val(),
			"longitude" : $(".form-control[name=longitude]").val(),
			"latitude" : $(".form-control[name=latitude]").val(),
			"address" : $(".form-control[name=address]").val()
		};

		$.ajax({
		  type: "POST",
		  url: rest_domain+'/place',
		  data: postValues,
		  success: function(data){console.log(data);},
		  dataType: "xml"
		});
		return false;
	});
	$("#add-place-form").submit(function(){return false;});

};