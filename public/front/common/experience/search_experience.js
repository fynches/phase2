function initialize() {
   var latlng = new google.maps.LatLng(28.5355161,77.39102649999995);
    var map = new google.maps.Map(document.getElementById('map'), {
      center: latlng,
      zoom: 13
    });
    var marker = new google.maps.Marker({
      map: map,
      position: latlng,
      draggable: true,
      anchorPoint: new google.maps.Point(0, -29)
   });
    var input = document.getElementById('searchInput');
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    var geocoder = new google.maps.Geocoder();
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();   
    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }
  
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
       
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);          
    	console.log('search result ',place);
    	// var city = place.address_components[0].long_name;
    	// var country = place.address_components[5].long_name;
    	// var country_short_code = place.address_components[5].short_name;
    	// var current_location= city+','+country_short_code;
    	
    	//console.log('current location ',city+','+country_short_code);
        bindDataToForm(place.formatted_address,place.geometry.location.lat(),place.geometry.location.lng(),place.formatted_address);
        infowindow.setContent(place.formatted_address);
        infowindow.open(map, marker);
       
    });
    // this function will work on marker move event into map 
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {        
              bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
          }
        }
        });
    });
}
function bindDataToForm(address,lat,lng,current_location){
   document.getElementById('location').value = current_location;
   //document.getElementById('lat').value = lat;
   //document.getElementById('lng').value = lng;
}
google.maps.event.addDomListener(window, 'load', initialize);



$(document).on('click', '.add-perfect-exp', function(e) {
	var yelp_id= $(this).attr('data-id');
	$('.yelp_id').val(yelp_id);
});

$(document).on('click', '.search_result', function(e) {
	var search_exp = $('.search_experience_name').val();
	var location = $('.current_location_name').val();
	//alert(search_exp);
	if(search_exp!="")
	{
		var redirect_page= window.base_url+'/find-perfect-experience/'+search_exp+'/'+location+'/term';
		window.location.href= redirect_page;	
	}
	
});

$(document).on('click', '.add_new_experience', function(e) {
	
	var event_id= $('input[name=event_id]:checked').val();
	var event_name = $.trim($('input[name=event_id]:checked').parent().text());
	var yelp_id = $('.yelp_id').val();
	var total_exp_added= $(".total_exprience_count").html();
	
	if(event_id =="0")
	{
		var redirect_page= window.base_url+'/event';
		window.location.href= redirect_page;
	}else{
		//alert(total_exp_added);
		if(total_exp_added=="0")
		{
			alert('select any of one experience.');
			return false;
		}else{
			$('.my_event_id').val(event_id);
			 if (confirm('Are you sure you want to add this experience in '+event_name+'?')) {
				if(yelp_id!="")
				{
					var redirect_page= window.base_url+'/add-find-perfect-exp/'+event_id+'';
					window.location.href= redirect_page;
				}
		    }	
		}
		
	}
	
});


/* Paginations for recommnded  */
$(document).on('click', '#pane-B .pagination li 1a', function(e) {
	//alert('adsfads');
	// return false;
	event.preventDefault();
	var myurl = $(this).attr('href');	
	//var favourite_activity = $("#favourite_activity").val();
	var other_tags = $("#search_for_an_experience").val();
	var events_id = $("#events_id").val() || 0;
	$('body').loading('start');
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: "get",
		data: { events_id:events_id, other_tags: other_tags, token:$('meta[name="csrf-token"]').attr('content')},
		url: myurl,
		success: function (res) {
			//alert(res);
			if(res)
			{
				$('#search_yelp_experiences .showpaging').html(res.pagination);
				$('#search_yelp_experiences #show_pagination').removeClass('hide');
				$('#search_yelp_experiences .recomanded_experiences').html(res.html);
			}
			$('body').loading('stop');
		},
		error: function (xhr, status, errorThrown) {
			$('body').loading('stop');
		}
	});
	
	
});


$(document).on('click', '#search_yelp_experiences .pagination li a', function(e) {
	 //alert('dddd');
	// return false;
	event.preventDefault();
	var myurl = $(this).attr('href');
	//alert(myurl);
	var page=$(this).attr('href').split('page=')[1];
	var search_terms= $('.search_experience_name').val();
	var location= $('.current_location_name').val();
	var other_tags = $("#search_for_an_experience").val();
	var event_id = $("#events_id").val() || 0;
	$('body').loading('start');
	$.ajax({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: "get",
		//data: { event_id:events_id, other_tags: other_tags, token:$('meta[name="csrf-token"]').attr('content')},
		data: {search_terms: search_terms,location:location,event_id:event_id,token:$('meta[name="csrf-token"]').attr('content')},
		url: myurl,
		success: function (res) {
			//alert(res);
			if(res)
			{
				$('#search_yelp_experiences .showpaging').html(res.pagination);
				$('#search_yelp_experiences #show_pagination').removeClass('hide');
				$('#search_yelp_experiences .recomanded_experiences').html(res.html);
			}
			$('body').loading('stop');
		},
		error: function (xhr, status, errorThrown) {
			$('body').loading('stop');
		}
	});
	
	
});
