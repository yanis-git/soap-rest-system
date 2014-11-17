var RestInitInterface = function(rest_domain,placeid,townid) {
	/**** SECTION GET current Place information ****/
	$.get(rest_domain+'/place/id/'+placeid, function(data) {
    	var place = data.getElementsByTagName("result");
    	place = place[0];
		var name = $(place.getElementsByTagName("name")[0]).text();
		var address = $(place.getElementsByTagName("address")[0]).text();
		var cityname = $(place.getElementsByTagName("cityname")[0]).text();
		var description = $(place.getElementsByTagName("description")[0]).text();

		var longitude = $(place.getElementsByTagName("longitude")[0]).text();
		var latitude = $(place.getElementsByTagName("latitude")[0]).text();

		$("#name").text(name);
		$("#address").text(address);
		$("#ville").text(cityname);
		$("#description").text(description);

        $('#map_canvas').gmap({'center': new google.maps.LatLng(latitude,longitude)});
        $(".detailBox").show("slow");
	});

	/**** SECTION GET Places for marker ****/

	$.get(rest_domain+'/places?townid='+townid, function(data) {
    	var places = data.getElementsByTagName("integer");
		for(key in places){
    		if(typeof places[key] === "object"){
    			var latitude = $(places[key].getElementsByTagName("latitude")[0]).text();
    			var longitude = $(places[key].getElementsByTagName("longitude")[0]).text();
    			var address = $(places[key].getElementsByTagName("address")[0]).text();
    			var id = $(places[key].getElementsByTagName("id")[0]).text();
    			var cityname = $(places[key].getElementsByTagName("cityname")[0]).text();

    			$('#map_canvas').gmap("addMarker",{ 'placeid':id, 'cityname':cityname, 'address':address, 'position': new google.maps.LatLng(latitude,longitude) })
    							.click(function() {
    								var contenu = this.address+", "+this.cityname+"<br> <a href='place.php?id="+this.placeid+"'> Voir la fiche de cette place.</a>";
									$('#map_canvas').gmap('openInfoWindow', { content : contenu }, this);
				});
    		}
    	}
	});

/**** SECTION GET Commentaires ****/
	var getValues = {
		"placeid" : placeid,
		"action" : "getComments"
	};

	$.ajax({
	  type: "GET",
	  url: 'ajax.php',
	  data: getValues,
	  success: function(data){
	  	console.log(data);
	  	var comments = data.getElementsByTagName("entry");
//	  	console.log(comments);
	  	if(comments.length == 0){
	  		$(".commentList").append('<li><div id="nocomment-form" style="" class="alert alert-info" role="alert">Aucun commentaire trouvé.</div></li>');
	  	}
		
		for(key in comments){
    		if(typeof comments[key] === "object"){
	    			var author = $(comments[key].getElementsByTagName("author")[0]).text();
	    			var comment = $(comments[key].getElementsByTagName("content")[0]).text();
	    			var rate = $(comments[key].getElementsByTagName("rate")[0]).text();
		  	
				  	var template = '<li> \
					            <div class="commenterImage"> \
					                  <img src="http://lorempixel.com/50/50/people/" /> \
					            </div> \
					            <div class="commentText"> \
    	                          <div class="pseudoBox">'+author+'</div> \
					               <p>'+comment+' </p> <span class="date sub-text"> Rate : '+rate+' / 10</span> \
					            </div> \
					       </li>';

	       $(".commentList").append(template);
	   	}
	   }
	 },
	  dataType: "xml"
	});


	$("form.form-inline").submit(function(){return false});

	$("#submit-form").click(function(){
		var placeid = $("input[name=placeid]").val();
		var comment = $("input[name=comment]").val();
		var rate = $("select[name=rate]").val();
		var author = $("input[name=author]").val();

		if(townid == "" || comment == "" || rate == "" || author == ""){
			$("#feedback-form").show("slow");
			return false;
		}
		else{
			$("#feedback-form").hide('slow');
		}

		var postValues = {
			"placeid" : placeid,
			"comment" : comment,
			"rate" : rate,
			"author" : author,
			"action" : "addComment"
		};

		$.ajax({
		  type: "POST",
		  url: 'ajax.php',
		  data: postValues,
		  success: function(data){
		  	console.log(data);
		  	//TODO : add template of comment
		  	
		  	var template = '<li> \
			            <div class="commenterImage"> \
			                  <img src="http://lorempixel.com/50/50/people/" /> \
			            </div> \
			            <div class="commentText"> \
			            	<div class="pseudoBox">'+author+'</div> \
			               <p>'+comment+' </p> <span class="date sub-text"> Rate : '+rate+' / 10</span> \
			            </div> \
			       </li>';

	       $(".commentList").append(template);
	       $("#nocomment-form").hide();
	       $("input, select").val("");
		 },
		  dataType: "xml"
		});
		return false;
	});
};