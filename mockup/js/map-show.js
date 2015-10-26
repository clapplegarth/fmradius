function initMap()
{
  /*if (typeof latlng == 'undefined')
  {
    var latlng = {lat: 38.9560, lng: -76.0932};
    var callsign = 'WCEI-FM';
  }*/

  //latlng.lat = latlng.lat.toFixed(4);
  //latlng.lng = latlng.lng.toFixed(4);

  console.log(latlng);

  var map = new google.maps.Map(document.getElementById('map'), {
    center: latlng,
    zoom: 9
  });

  var marker = new google.maps.Marker({
    map: map,
    // Define the place with a location, and a query string.
    place: {
      location: latlng,
      query: callsign

    },
    // Attributions help users find your site again.
    attribution: {
      source: 'fmradius',
      webUrl: 'http://fmradi.us/'
    }
  });

  var poly = new google.maps.Polygon({
    map: map,
    // Define the place with a location, and a query string.
    paths: polyjson,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35
  });


}
