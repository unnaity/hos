function gettwopoints(pickup_from,pickup_to,drop_from,drop_to){  
alert(pickup_from);
  var x=0;
    var map;
    var prepath;
    var path = null;
    var current_lat = pickup_from;
    var current_lng = pickup_to;
function initialize() {
  var myLatLng = new google.maps.LatLng(pickup_from, pickup_to);
  var mapOptions = {
    zoom: 3,
    center: myLatLng,
    mapTypeId: google.maps.MapTypeId.TERRAIN
  };

  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);


google.maps.event.addListener(map, "click", function (e) {  
    prepath = path;
    if(prepath){
        prepath.setMap(null);
    }   
    current_lat = e.latLng.lat();
    current_lng = e.latLng.lng();

    var flightPlanCoordinates = [               
            new google.maps.LatLng(pickup_from, pickup_to),              
            new google.maps.LatLng(drop_from, drop_to)               
    ];
    var polyline;   
    polyline = new google.maps.Polyline({
        path: flightPlanCoordinates,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 2
    }); 
    //new polyline
    polyline.setMap(map);   
    // assign to global var path
    path = polyline;
    });
google.maps.event.addDomListener(window, 'load', initialize);
}


}




