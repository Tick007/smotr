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

function initialize() {		
		var myLatlng = new google.maps.LatLng(55.73948169869349,37.6336669921875);
var mapOptions = {
  zoom: 4,
  center: myLatlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP,
}
var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

var marker = new google.maps.Marker({
    position: myLatlng,
    title:'Hello World!'
});
		
		
google.maps.event.addListener(map, 'click', function(event) {
		  //alert(this.position);
		console.log(event.latLng);
		});

// To add the marker to the map, call setMap();
marker.setMap(map);
		}

google.maps.event.addDomListener(window, 'load', initialize);
		
		", CClientScript::POS_HEAD);
      ?>

    
      
    
    
     <div id="map_canvas" style="width:100%; height:100%; min-height:700px"></div>
     