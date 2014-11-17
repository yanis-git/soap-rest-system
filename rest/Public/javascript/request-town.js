var RestInitInterface = function(rest_domain,townid) {

	var uri = '/places?townid='+townid;
	if(townid == ""){
		uri = '/places';
	}
	$.get(rest_domain+uri, function(data) {
    	var places = data.getElementsByTagName("integer");
		for(key in places){
    		if(typeof places[key] === "object"){
    			var latitude = $(places[key].getElementsByTagName("latitude")[0]).text();
    			var longitude = $(places[key].getElementsByTagName("longitude")[0]).text();
    			var address = $(places[key].getElementsByTagName("address")[0]).text();
    			var id = $(places[key].getElementsByTagName("id")[0]).text();
    			var cityname = $(places[key].getElementsByTagName("cityname")[0]).text();
    			var name = $(places[key].getElementsByTagName("name")[0]).text();
    			var idtown = $(places[key].getElementsByTagName("town_id")[0]).text();


    			$('#map_canvas').gmap("addMarker",{ 'placeid':id, 'cityname':cityname, 'address':address, 'position': new google.maps.LatLng(latitude,longitude) })
    							.click(function() {
    								var contenu = this.address+", "+this.cityname+"<br> <a href='place.php?id="+this.placeid+"'> Voir la fiche de cette place.</a>";
									$('#map_canvas').gmap('openInfoWindow', { content : contenu }, this);
				});



    			var tableLineTemplate = " \
    				<tr> \
    					<td>"+name+"</td> \
    					<td>"+address+"</td> \
    					<td><a href='list.php?id="+idtown+"'>"+cityname+"</a></td> \
    					<td><a href='place.php?id="+id+"&townid="+idtown+"'> Voir </a></td> \
    				</tr> \
    			";

    			$("#table-content-wrapper").append(tableLineTemplate);
    		}
    	}
	});
};