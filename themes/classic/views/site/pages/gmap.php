<?php 
$this->pageTitle = "GMAP test";
Yii::app()->clientScript->registerMetaTag('initial-scale=1.0, user-scalable=no', 'viewport');
//Yii::app()->clientScript->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=AIzaSyDRlSZFXdtVl9vqMgLLbAD-JclEahUmgIg&sensor=TRUE');
Yii::app()->clientScript->registerScriptFile('http://maps.googleapis.com/maps/api/js?key=AIzaSyDRlSZFXdtVl9vqMgLLbAD-JclEahUmgIg&sensor=false&language=ru&region=RU');

echo $this->id;


?>
 <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      .content{ height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
   <?php
   Yii::app()->clientScript->registerScript('script', "

	var i=0;	
	var map;
		
	  function placeMarker(location) {
		i++;
		
		  var marker = new google.maps.Marker({
		      position: location,
		      map: map
		  });
		//alert(i);
		 //map.setCenter(location);
		marker.setTitle('Marker № '+i)
		attachSecretMessage(marker, i);
	  }
		
	function attachSecretMessage(marker, number) {
	  var message = ['This','is','the','secret','message'];
	  var infowindow = new google.maps.InfoWindow(
	      { content: marker.getTitle()+'<br>'+message[number],
	        size: new google.maps.Size(50,50)
	      });
	  google.maps.event.addListener(marker, 'click', function() {
	    infowindow.open(map,marker);
	  });
	}
		
	function initialize() {
	   var useragent = navigator.userAgent;
       //console.log(useragent);
        var mapOptions = {
          center: new google.maps.LatLng(-34.397, 150.644),
		  //disableDefaultUI: true,//Это свойство отключает автоматическое поведение пользовательского интерфейса, настроенного по типу API Google Карт.
          zoom: 8,
		//panControl: true,
          mapTypeId: google.maps.MapTypeId.ROAD,
	      zoomControl: true,
		  zoomControlOptions: {
		    style: google.maps.ZoomControlStyle.DEFAULT  
		  },
		overviewMapControl:true,
		OverviewMapControlOptions:{
			opened:true,
			position: google.maps.ControlPosition.RIGHT_CENTER ,
		},
		
		 mapTypeControl: true,
	     	mapTypeControlOptions: {
	     style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
	    },

        };
        map = new google.maps.Map(document.getElementById('map_canvas'),
            mapOptions);
		
		
		///LatLngBounds это прямоугольная область на карте
		// Add 5 markers to the map at random locations.
		  var southWest = new google.maps.LatLng(-31.203405,125.244141);
		  var northEast = new google.maps.LatLng(-25.363882,131.044922);
		  var bounds = new google.maps.LatLngBounds(southWest,northEast);
		  map.fitBounds(bounds);
		  var lngSpan = northEast.lng() - southWest.lng();
		  var latSpan = northEast.lat() - southWest.lat();
		  for (var i = 0; i < 5; i++) {
		    var location = new google.maps.LatLng(southWest.lat() + latSpan * Math.random(),
		        southWest.lng() + lngSpan * Math.random());
		    var marker = new google.maps.Marker({
		        position: location,
		        map: map
		    });
		    var j = i + 1;
		    marker.setTitle(j.toString());
		    attachSecretMessage(marker, i);
		  }
		
		
		///Создание маркера
		var marker = new google.maps.Marker({ 
	    	position: map.getCenter(),
	    	map: map,
	    	title: 'Click to zoom'
		});
		
		
		/////////////////Подписка на событие изменения центра карты
		/* google.maps.event.addListener(map, 'center_changed', function() {
		    // 3 seconds after the center of the map has changed, pan back to the
		    // marker.
		    window.setTimeout(function() {
		      map.panTo(marker.getPosition());
		    }, 3000);
		  });
		*/
		
		//////////////Клик на именном маркере
		google.maps.event.addListener(marker, 'click', function() {
		  map.setZoom(8);
		  //map.setCenter(marker.getPosition());///// эта команда резко переставляет на координаты
		map.panTo(marker.getPosition());
		});

		
		
		google.maps.event.addListener(map, 'click', function(event) {
			
		    placeMarker(event.latLng);
		});
		
		/*
		var myLatLng = new google.maps.LatLng(-25.363882, 131.044922);
		
		/////////Информационное окошко
		var infowindow = new google.maps.InfoWindow({
		    content: 'Change the zoom level',
		    position: myLatLng
		});
		
		infowindow.open(map);
		
		//Подписка на событие изменения зума
		google.maps.event.addListener(map, 'zoom_changed', function() {
		    var zoomLevel = map.getZoom();
		    map.setCenter(myLatLng);
		    infowindow.setContent('Zoom: ' + zoomLevel);
		});
		*/
		
		
		
		// Create a div to hold the control.
		var controlDiv = document.createElement('div');
		
		// Set CSS styles for the DIV containing the control
		// Setting padding to 5 px will offset the control
		// from the edge of the map.
		controlDiv.style.padding = '5px';
		
		// Set CSS for the control border.
		var controlUI = document.createElement('div');
		controlUI.style.backgroundColor = 'white';
		controlUI.style.borderStyle = 'solid';
		controlUI.style.borderWidth = '2px';
		controlUI.style.cursor = 'pointer';
		controlUI.style.textAlign = 'center';
		controlUI.title = 'Click to set the map to Home';
		controlDiv.appendChild(controlUI);
		
		// Set CSS for the control interior.
		var controlText = document.createElement('div');
		controlText.style.fontFamily = 'Arial,sans-serif';
		controlText.style.fontSize = '12px';
		controlText.style.paddingLeft = '4px';
		controlText.style.paddingRight = '4px';
		controlText.innerHTML = '<strong>Home</strong>';
		controlUI.appendChild(controlText);
		
		map.controls[google.maps.ControlPosition.RIGHT_CENTER].push(controlDiv);
		
		
		google.maps.event.addDomListener(controlDiv, 'click', function() {
		  var chicago = new google.maps.LatLng(41.850033, -87.6500523);
			map.setCenter(chicago)
		});
		
      };
		google.maps.event.addDomListener(window, 'load', initialize);
		
		
		
		
		


		
		
		", CClientScript::POS_HEAD);
      ?>

    
    
    
    
    
     <div id="map_canvas" style="width:100%; height:100%; min-height:700px"></div>
     