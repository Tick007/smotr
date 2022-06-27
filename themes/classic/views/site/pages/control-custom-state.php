<?php 
$this->pageTitle = "GMAP test";
Yii::app()->clientScript->registerMetaTag('initial-scale=1.0, user-scalable=no', 'viewport');
//Yii::app()->clientScript->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=AIzaSyDRlSZFXdtVl9vqMgLLbAD-JclEahUmgIg&sensor=TRUE');
Yii::app()->clientScript->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=AIzaSyDRlSZFXdtVl9vqMgLLbAD-JclEahUmgIg&sensor=false&language=ru&region=RU');
?>
 <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      .content{ height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
   <?php
   Yii::app()->clientScript->registerScript('script', "

	var map;
var chicago = new google.maps.LatLng(41.85, -87.65);

/**
 * The CenterControl adds a control to the map that recenters the map on Chicago.
 * This constructor takes the control DIV as an argument.
 * @constructor
 */
function CenterControl(controlDiv, map) {

  // Set CSS for the control border
  var controlUI = document.createElement('div');
  controlUI.style.backgroundColor = '#fff';
  controlUI.style.border = '2px solid #fff';
  controlUI.style.borderRadius = '3px';
  controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
  controlUI.style.cursor = 'pointer';
  controlUI.style.marginBottom = '22px';
  controlUI.style.textAlign = 'center';
  controlUI.title = 'Click to recenter the map';
  controlUI.data={name:'Some data'}	;
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('div');
  controlText.style.color = 'rgb(25,25,25)';
  controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
  controlText.style.fontSize = '16px';
  controlText.style.lineHeight = '38px';
  controlText.style.paddingLeft = '5px';
  controlText.style.paddingRight = '5px';
  controlText.innerHTML = 'Center Map';
  controlUI.appendChild(controlText);

  // Setup the click event listeners: simply set the map to
  // Chicago
  google.maps.event.addDomListener(controlUI, 'click', function() {
		alert(this.data.name);
    map.setCenter(chicago);
  });

}

function initialize() {
		$( document ).ready(function() {
		  var mapDiv = document.getElementById('map-canvas');
		  var mapOptions = {
		    zoom: 12,
		    center: chicago
		  };
		  
		  map = new google.maps.Map(mapDiv, mapOptions);
		
		  // Create the DIV to hold the control and
		  // call the CenterControl() constructor passing
		  // in this DIV.
		  var centerControlDiv = document.createElement('div');
		  var centerControl = new CenterControl(centerControlDiv, map);
		
		  centerControlDiv.index = 1;
		  map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(centerControlDiv);
		
		
		var styleArray = [
		  {
		    featureType: 'all',
		    stylers: [
		      { saturation: -50 }
		    ]
		  },{
		    featureType: 'road.arterial',
		    elementType: 'geometry',
		    stylers: [
		      { hue: '#00ffee' },
		      { saturation: 50 }
		    ]
		  },{
		    featureType: 'poi.business',
		    elementType: 'labels',
		    stylers: [
		      { visibility: 'off' }
		    ]
		  }
		];
		
		map.setOptions({styles: styleArray});
		
		});
}

google.maps.event.addDomListener(window, 'load', initialize);
		
		", CClientScript::POS_HEAD);
      ?>

    
      
    
    
     <div id="map-canvas" style="width:100%; height:100%; min-height:700px"></div>
     