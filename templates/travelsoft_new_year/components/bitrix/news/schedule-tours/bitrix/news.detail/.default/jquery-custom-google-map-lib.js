/**
 * Small custom google maps library functions
 * use JQuery, google maps
 * @author dimabresky
 */

(function ($, window, gm) {
    
    'use strict'
    
    if (typeof window.GoogleMapFunctionsContainer === 'undefined' && typeof gm !== 'undefined') {
    
        window.GoogleMapFunctionsContainer = {

            // markers options
            _markersOptions: [],
            
            // need reinitialize markers
            reinitMarkers: false,
            
            // initialized markers
            _markers: [],
            
            // google map object
            _map: null
            
        };

        // init google map
        window.GoogleMapFunctionsContainer.createGoogleMap = function  (idSelector, options) {
             window.GoogleMapFunctionsContainer._map = new gm.Map(document.getElementById(idSelector), options);
             return window.GoogleMapFunctionsContainer;
        }
        
        // create markers options before to add them on map
        // markersInfo must be array with elements like {lat: , lng: , title: , infoWindow: } (infoWindow - html content)
        window.GoogleMapFunctionsContainer.createMarkersOptions = function ( markersInfo ) {
            
            window.GoogleMapFunctionsContainer._markersOptions = [];
            
            var k;
            for (k in markersInfo) {
                window.GoogleMapFunctionsContainer._markersOptions.push({
                    position: window.GoogleMapFunctionsContainer.LatLng(markersInfo[k].lat, markersInfo[k].lng),
                    map: window.GoogleMapFunctionsContainer._map,
                    title: markersInfo[k].title,
                    infoWindow: markersInfo[k].infoWindow,
					icon: "/local/templates/travelsoft/images/icon-maker.png"
                });
            }
			
            return window.GoogleMapFunctionsContainer;
            
        }
        
        // add single marker on map
        window.GoogleMapFunctionsContainer._addMarker = function (marker) {
            var infowindow = new gm.InfoWindow({
                        content: marker.infoWindow
                      });
               
                
            var Marker = new gm.Marker(marker);
            //marker.setMap(window.GoogleMapFunctionsContainer._map);

            window.GoogleMapFunctionsContainer._markers.push(Marker);

            Marker.addListener('click', function() {
                infowindow.open(window.GoogleMapFunctionsContainer._map, Marker);
              });
        }
        
        // add markers on map
        window.GoogleMapFunctionsContainer.drawMarkers = function () {
            
            if (window.GoogleMapFunctionsContainer._map == null || window.GoogleMapFunctionsContainer._markersOptions.length <= 0) {
                return;
            }

            var k, m_o = window.GoogleMapFunctionsContainer._markersOptions, infowindow, marker, bounds = new gm.LatLngBounds();
            
            for (k in m_o) {
                bounds.extend(m_o[k].position);
                window.GoogleMapFunctionsContainer._addMarker(m_o[k]);
            }
            
            window.GoogleMapFunctionsContainer._map.setCenter(bounds.getCenter(), window.GoogleMapFunctionsContainer._map.fitBounds(bounds));
           return window.GoogleMapFunctionsContainer;
        }

        // remove markers from map
        window.GoogleMapFunctionsContainer.deleteMarkers = function () {
            var k;
            for (k in window.GoogleMapFunctionsContainer._markers) {
                window.GoogleMapFunctionsContainer._markers[k].setMap(null);
            }
            window.GoogleMapFunctionsContainer._markers = [];
            
            return window.GoogleMapFunctionsContainer;
        }
        
        // latlng google object creater
        window.GoogleMapFunctionsContainer.LatLng = function (lat, lng) {
           return new gm.LatLng(lat, lng);
        }
        
        // draw route
        // markersInfo must be array with elements like {lat: , lng: , title: , infoWindow: } (infoWindow - html content)
        window.GoogleMapFunctionsContainer.drawRoute = function (markersInfo) {
            
            if (window.GoogleMapFunctionsContainer._map == null) {
                return;
            }
            
            var dirDisplay = new gm.DirectionsRenderer(),
                dirSrvice = new gm.DirectionsService(),
                cnt, startPoint, endPoint, arMarkersInfo = [], wayPoints = [], k;

            window.GoogleMapFunctionsContainer.createMarkersOptions(markersInfo);
            
            arMarkersInfo = window.GoogleMapFunctionsContainer._markersOptions;
            
            cnt = arMarkersInfo.length;
            
            if ( cnt <= 0 )
                return;
            
            dirDisplay.setMap(window.GoogleMapFunctionsContainer._map);
            dirDisplay.setOptions( { suppressMarkers: true, suppressInfoWindows: true } );

            window.GoogleMapFunctionsContainer.drawMarkers();
            
            startPoint = arMarkersInfo[0].position;
            endPoint = arMarkersInfo[cnt - 1].position;

            delete arMarkersInfo[0];
            delete arMarkersInfo[cnt - 1];
            
            for (k = 1; k <= (cnt - 2); k++) {
                wayPoints.push({location: arMarkersInfo[k].position});
            }
            
            dirSrvice.route({
                origin: startPoint,
                waypoints: wayPoints,
                destination: endPoint,
                travelMode: gm.TravelMode.DRIVING,
                unitSystem: gm.UnitSystem.METRIC,
                provideRouteAlternatives: true,
                avoidHighways: false,
                avoidTolls: true
            }, 
            function(result, status) {
                if (status == gm.DirectionsStatus.OK) {
                        dirDisplay.setDirections(result);
                }
            });
            
        }

    }
    
})(jQuery, window, google.maps);


