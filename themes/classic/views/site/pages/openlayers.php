<?php
// https://www.intelligence-airbusds.com/en/4871-ordering - пример
// https://openlayers.org/en/latest/doc/tutorials/bundle.html
// $cs = Yii::app()->clientScript;
// $cs->scriptMap['jquery.js'] = '/JavaBridge/smotr/js/jquery-2.2.3.js';
// $cs->scriptMap['jquery.min.js'] = '/JavaBridge/smotr//js/jquery-2.2.3.min.js';

?>
<script src="http://localhost:8080/openlayers/build/ol.js"></script>
<link rel="stylesheet"
	href="http://localhost:8080/openlayers/css/ol.css">
<script src="<?php echo Yii::app()->theme->baseUrl?>/js/kvp_functions.js"></script>
<link rel="stylesheet"	href="<?php echo Yii::app()->theme->baseUrl?>/css/kvp.css">

<!-- <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>-->


<!-- 
<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">-->



<div class="sidepanel" style="display: none">
	<span class="sidepanel-title">Детальная информация</span> <br>
	<div id="panel_content">
		<table class="panel_table">
			<tr>
				<td>Урл</td>
				<td id="url"></td>
			</tr>
			<tr>
				<td>Координаны</td>
				<td id="coords"></td>
			</tr>
			<tr>
				<td>Дата</td>
				<td id="imgdt"></td>
			</tr>
			<tr>
				<td>Идентификатор файла</td>
				<td id="imgfile"></td>
			</tr>
			<tr>
				<td>Легенда</td>
				<td id="imglegend"></td>
			</tr>
			<tr>
				<td>Превью</td>
				<td id="imgicon"></td>
			</tr>
		</table>
	</div>
</div>

<div class="sidepanel2" style="display: none">
	<span class="sidepanel-title">Инфо о выбранной области</span>
	<div id="panel2_content"></div>
	<div>Список выбранных: областей
	<ul id="features_list"></ul>
	</div>
</div>

<div style="width: 100%; height: 500px; border: 1px solid black"
	id="map"></div>

<div id="mouse-position"></div>
<form class="form-inline">
	<label>Geometry type &nbsp;</label> <select id="type">
		<option value="Box">Box</option>
		<option value="Point">Point</option>
		<option value="LineString">LineString</option>
		<option value="Polygon">Polygon</option>
		<option value="Circle">Circle</option>


	</select> <br>
	<br> <input type="button" value="Добавить слой с картинкой"
		id="addLayer2" /> <br>
	<br> <input type="button"
		value="Отключить расование, типа после поиска фоток"
		id="switchOfffigures" /> <br>
	<br> <input type="button" value="Получить координаты областей"
		id="getGeometries" />

</form>
<script>
$( document ).ready(function() {

	//import 'ol/ol.css';
//	import {Map, View} from 'ol';
	//import TileLayer from 'ol/layer/Tile';
	//import OSM from 'ol/source/OSM';

/*  работает
	const map = new ol.Map({
		  target: 'map',
		  layers: [
		    new ol.layer.Tile({
		      source: new ol.source.OSM()
		    })
		  ],
		  view: new ol.View({
		    center: [0, 0],
		    zoom: 2
		  })
		});
	    */

 /* работает
	var map = new ol.Map({
		  target: 'map',
		  view: new ol.View({
		    projection: 'EPSG:3857', //HERE IS THE VIEW PROJECTION
		    center: [0, 0],
		    zoom: 2
		  }),
		  layers: [
		    new ol.layer.Tile({
			    source: new ol.source.TileWMS({
		        projection: 'EPSG:4326', //HERE IS THE DATA SOURCE PROJECTION
		        url: 'http://demo.boundlessgeo.com/geoserver/wms',
		        params: {
		          'LAYERS': 'ne:NE1_HR_LC_SR_W_DR'
		        }
		      })
		      
		    })
		  ]
		});
	*/


	/* Работает
	var map = new ol.Map({
		  target: 'map',
		  view: new ol.View({
		    projection: 'EPSG:3857', //HERE IS THE VIEW PROJECTION
		    center: [0, 0],
		    zoom: 1
		  }),
		  layers: [
		    new ol.layer.Tile({
			    source: new ol.source.TileWMS({
		        projection: 'EPSG:4326', //HERE IS THE DATA SOURCE PROJECTION
		        url: 'http://localhost:8080/geoserver/topp/wms?service=WMS',
		      //т.е. фактически запрос слоев идет с любого домена
		      // И можно в apache Tomcat всё не пихать, а
		      // пользоваться встроенным в geoserver Web сервером
		      // А php соответственно на простомм Apache
		        params: {
		          'LAYERS':  'topp:ne_10m_land_scale_rank, topp:states'
		        }
		      })
		      
		    })
		  ]
		});
	*/


    ///////Событие нажатия кнопки удаления полигона в списке на правой панели
    $('.delete_polygon').live( "click", function() {
   	 var li = $(this).closest('li');
   	 var geometry_id = $(li).attr('id');
   	 console.log(geometry_id);
   	 var ol_uid = geometry_id.replace('f_', '');
  	 deleteFeature(ol_uid);
    });


	/////////Удаление полигона с карты
	function deleteFeature(ol_uid){
		//////////Собственно нужно почистить сам полигон из коллекции features и затереть пункт в списке
		//////или как то сделать у фичи он делете -> удалить пункт в списке
		///// но такого у неё нет: https://openlayers.org/en/latest/apidoc/module-ol_Feature-Feature.html
		var features = vector.getSource().getFeatures();
	    features.forEach(function(feature) {
		    if(feature.getGeometry().ol_uid == ol_uid) {
				console.log(feature);
		    	vector.getSource().removeFeature(feature); ////Если последняя фича то обновляет с задержкой
		    	$('#f_'+ol_uid).remove();
			}
	    });
	}

	//////////////Закрываем окошко инфо при уходе мышки
	$('.sidepanel, .sidepanel2').mouseleave(function() {
		  $(this).css('display', 'none');
	});
	

	//////////Отслеживание координат мыши
	mousePositionControl = new ol.control.MousePosition({
        coordinateFormat: ol.coordinate.createStringXY(4),
        projection: 'EPSG:3857',
        // comment the following two lines to have the mouse position
        // be placed within the map.
        className: 'custom-mouse-position',
        target: document.getElementById('mouse-position'),
        undefinedHTML: '&nbsp;'
      });
	

	/////Слой карты
	 var raster = new ol.layer.Tile({
	       // source: new ol.source.OSM()
		   source: new ol.source.TileWMS({
		        //url: 'http://localhost:8080/geoserver/topp/wms?service=WMS',
		        url:'http://localhost:8080/geoserver/wms?service=WMS',
		        projection: 'EPSG:4326', ///Это указано в свойствах слой в geoserver. 
		        /*
		        GEOGCS["WGS 84", 
				  DATUM["World Geodetic System 1984", 
				    SPHEROID["WGS 84", 6378137.0, 298.257223563, AUTHORITY["EPSG","7030"]], 
				    AUTHORITY["EPSG","6326"]], 
				  PRIMEM["Greenwich", 0.0, AUTHORITY["EPSG","8901"]], 
				  UNIT["degree", 0.017453292519943295], 
				  AXIS["Geodetic longitude", EAST], 
				  AXIS["Geodetic latitude", NORTH], 
				  AUTHORITY["EPSG","4326"]]
		        */
		        params: {
		          //'LAYERS':  'topp:ne_10m_land_scale_rank, topp:states'
			        //'LAYERS':'cite:world_map',
			       'LAYERS': 'osm_group2'  ,
		        }
		      })
	      });

	 source = new ol.source.Vector();
	    vector = new ol.layer.Vector({
    	source: source,
        style: new ol.style.Style({
          fill: new ol.style.Fill({
            color: 'rgba(255, 255, 255, 0.2)'
          }),
          stroke: new ol.style.Stroke({
            color: '#ffcc33',
            width: 2
          }),
          image: new ol.style.Circle({
            radius: 7,
            fill: new ol.style.Fill({
              color: '#ffcc33'
            })
          })
        })
      });


	      
     //[left, bottom,  right, top] – координаты углов изображения на вашей карте
   var imageExtent = [0, 50, 10, 60];///Для EPSG:4326 - соответсвенно наложили где-то к востоку от британии через гринвич
   /////////Слой с машинкой
	carLayer = new ol.layer.Image({
        source: new ol.source.ImageStatic({
            // url: 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/' +
                   // 'British_National_Grid.svg/2000px-British_National_Grid.svg.png',
			   url:'http://localhost:8080/JavaBridge/smotr/images/2122.png',
             crossOrigin: '',

             <!--Даем картинке координаты в EPSG:4326 и автоматом конвертим её координаты в EPSG:3857-->
             projection: 'EPSG:3857',
             imageExtent: transformTo3857(imageExtent),
             
             //imageExtent: imageExtent,
             //projection: 'EPSG:3857',
             //projection: 'EPSG:4326',
             
           })
       });


   
   ///////Собственно инициализация карты с массивом слоев
   var center = ol.proj.transform([0,50], 'EPSG:4326', 'EPSG:3857');
    map = new ol.Map({
    	controls: ol.control.defaults().extend([mousePositionControl]),
        layers: [raster, vector, carLayer],
        target: 'map',
        view: new ol.View({
          center: center,
          zoom: 3,
          //center: [0, 50],
          //projection: 'EPSG:4326',
        })
      });



    ordinaryStyle = new ol.style.Style({
    stroke: new ol.style.Stroke({
        //color: '#ffcc33',
        color: '#00f',
        width: 2
      }),
    });

    highlightStyle = new ol.style.Style({
        stroke: new ol.style.Stroke({
          color: '#f00',
          width: 3
        }),
/*
          fill: new ol.style.Fill({
          color: 'rgba(255,0,0,0.1)'
        }),
        text: new ol.style.Stroke({
          font: '12px Calibri,sans-serif',
          fill: new ol.style.Fill({
            color: '#000'
          }),
          stroke: new ol.style.Stroke({
            color: '#f00',
            width: 3
          })
        })
    */
      });
    

//var highlightStyle = "2px solid #F00";


		/*
		Работает, зашибись, но если включены интеракции для рисования - тогда возвращаются только векторные слои
		По идее после рисования фигур нужно отключать рисование фигур 
		*/
    	map.on('click', function(event) {

    	    map.forEachLayerAtPixel(event.pixel, function(layer) {
        	    if(layer.type=='IMAGE'){
	    	    	var source = layer.getSource();
					/*
	                console.log('source = ');
	                console.log(source);
	                console.log('layer = ');
	                console.log(layer);
	                */
	                
	                $('#url').text(source.url_);
					$('#coords').text(source.imageExtent_);
					$('#imgdt').text(layer.values_.shot_dt);
					$('#imgfile').text(layer.values_.file_id);
					$('#imglegend').text(layer.values_.legend);
					$('#imgicon').html('<img src="'+source.url_+'">');


					$('.sidepanel').css('display', 'block');
					////source.set('qweqweqw','12312312'); ///работает, попадает в values_
	                //console.log(source.imageExtent_);
	       			 ///////Тут вызываем запрос на детали по ка
        	    }
        	    if(layer.type=='VECTOR'){ ///////////На карте один векторный слой, поэтому сколько бы областе не поставить
            	    /////////////////////////////////координаты extent будут одни и теже
            	    //console.log('vector');
        	       var source = layer.getSource();
	               if(source!==null) { ///как то на null не срабатывает, поэтому оборачиваем в try catch
	            	   try {
				
	            		   var extent=source.getExtent();
							//console.log('extent = '+extent);
							//$('.sidepanel2').css('display', 'block');
							//$('#panel2_content').html('Координаты области: '+extent);
							/////Пока грузим сюда картинку. Вообще должна быть кнопка по поиску картинок по облостям
							//showMeArchive(extent);

	            		 } catch (err) {

	            		   // обработка ошибки

	            		 }
						
		           }
            	}
                return true;
    	    });


    	    //////Определяем фичи (геометрические полигоны налодженные динамически)
    	    var feature = map.forEachFeatureAtPixel(event.pixel, function(feature) {
    	       //  console.log('feature = ');
    	       // console.log(feature.getGeometry());
				
				var geometry = feature.getGeometry();
				//geom = feature;
				//console.log(geometry.getType());
				//if(geometry.getType()==='Polygon'){ //////Так фильтровать не получится, поскольку после второй точки формируется poligon
				if(draw == null){ ///////////////////оэтот объект убиваем когда отключаем анимацию
									////////////////// т.е. окошко с информацией о фиче будем открывать тольк когда выключена интеракция
					//console.log('open_right_panel');
					$('.sidepanel2').css('display', 'block');
					
					//Тут нужно выделять те что уже в списке а не просто писать координаты выбранной
					//$('#panel2_content').html('Координаты области: '+geometry.extent_);
					$('#features_list').children().each(function () {
						//console.log($(this).attr('id'));
						//console.log(geometry.ol_uid);
						if($(this).attr('id')==('f_'+geometry.ol_uid)){
							$(this).addClass('selected');
						}
						else $(this).removeClass('selected');
						/////И нужно стиль краев поменять 
						//geometry.style = highlightStyle;
						
					});
					
					//highlightFeature(feature); //////////////Подсвечиваем фичу геометрии
					///////////////////////////////////Добавили методом как здесь https://stackoverflow.com/questions/33322722/cannot-set-a-style-for-clicked-features-in-vector-layer
					///////////////////////////////////// см. ниже
				}
				/*
				 var features = vector.getSource().getFeatures();
				 // Go through this array and get coordinates of their geometry.
				 features.forEach(function(feature) {}
				*/
    	        
    	      });
    	    
    	});


		
		
		/////Блок для превращения обычного курсора мышки в руку при наведении на картинки которые были загружены
    	var cursorHoverStyle = "pointer";
    	var target = map.getTarget();
    	//target returned might be the DOM element or the ID of this element dependeing on how the map was initialized
    	//either way get a jQuery object for it
    	var jTarget = typeof target === "string" ? $("#"+target) : $(target); //это div карты
    	map.on("pointermove", function (event) {
    	    var mouseCoordInMapPixels = [event.originalEvent.offsetX, event.originalEvent.offsetY];
    	    //detect feature at mouse coords
    	    var hit = map.forEachLayerAtPixel(event.pixel, function(layer) {
    	    	// console.log(layer.getProperties());
    	    	if(layer.type=='IMAGE') {
    	    		mlayer = layer;
    	    		
        	    	return true;
        	    	}
    	    	else return false;
    	    });
    	    if (hit) {
    	        jTarget.css("cursor", cursorHoverStyle);
    	    } else {
    	        jTarget.css("cursor", "");
	   	    }
    	});


	////Это редактировае уже нарисованных фигур
	/*
    var modify = new ol.interaction.Modify({source: source});
    map.addInteraction(modify);
*/

      ////////////////////Это рисование фигур///////////одноразовый запуск
      addInteraction();


      typeSelect.onchange = function() {
          map.removeInteraction(draw);
          addInteraction();
        };

		
        $('#addLayer2').click(function() {
        	   //[left, bottom,  right, top] – координаты углов изображения на вашей карте
        	   var imageExtent = [20, 45, 30, 55];///Для EPSG:4326 - соответсвенно наложили где-то к востоку от британии через гринвич
        	   /////////Слой с машинкой
        		carLayer2 = new ol.layer.Image({
        	        source: new ol.source.ImageStatic({
        				   url:'http://localhost:8080/JavaBridge/smotr/images/2122.png',
        	               crossOrigin: '',
        	             <!--Даем картинке координаты в EPSG:4326 и автоматом конвертим её координаты в EPSG:3857-->
	       	             projection: 'EPSG:3857',
        	             imageExtent: transformTo3857(imageExtent)
        	             
        	           })
        	       });
     	       map.addLayer(carLayer2);
       });

        $('#switchOfffigures').click(function() {
        	switchOffInteractions();
       });


     ////////////////Собираем выделенные области на карте
	 $('#getGeometries').click(function() {
		// Get the array of features
		 var features = vector.getSource().getFeatures();

		 // Go through this array and get coordinates of their geometry.
		
		 features.forEach(function(feature) {
		    //console.log(feature.getGeometry().getExtent());
		    //console.log(feature.getGeometry());
		    ////////Покая для каждой области отдельный запрос, там посмотрим
		    showMeArchive(feature.getGeometry().getExtent(), 'map');

		 });
								
		 switchOffInteractions();//////////////Отключаем рисование областей
		// showMeArchive();
       });



	 draw.on('drawend', function (event) {
		 //https://gis.stackexchange.com/questions/268811/get-feature-from-ol-interaction-draw-on-drawend
		 //https://openlayers.org/en/latest/apidoc/module-ol_interaction_Draw.html#~DrawEventType
		  var feature = event.feature;
		  //geom = feature;
		  updateFeaturesList(feature, '<?php echo Yii::app()->baseUrl?>');
		});


	 //https://stackoverflow.com/questions/33322722/cannot-set-a-style-for-clicked-features-in-vector-layer
	 //фиг его знает но как то работает для выделения фич
	 //https://openlayers.org/en/latest/apidoc/module-ol_interaction_Select-Select.html
	 var select = new ol.interaction.Select();
	 map.addInteraction(select);
	 //highlightFeature() - не нужно вызывать, только 2 вышестоящие строчки ныжны
    	
});
var mlayer;
var geom; //////// временная для обращения к геометрии выбранной
var draw; ////// Объевляем один раз, все время перезаписывам, зато потом один раз вызываем для очистки интеракции
var map,  snap, source, vector, carLayer, mousePositionControl; // global so we can remove them later
var typeSelect = document.getElementById('type');




//////////Подсветка слоев
var highlight;
var displayFeatureInfo = function(pixel) {

  var feature = map.forEachFeatureAtPixel(pixel, function(feature) {
    return feature;
  });
  

};



//Слои bing в массиве https://openlayers.org/en/latest/examples/bing-maps.html
///Переключение между слоями разного стиля

//Вектронык кружки на карте. https://openlayers.org/en/latest/examples/cluster.html

//Передвигаемые объекты https://openlayers.org/en/latest/examples/custom-interactions.html
//Слой tileJson?
		
//https://openlayers.org/en/latest/examples/drag-and-drop.html
//Драг и Дроп на карту файла

////https://openlayers.org/en/latest/examples/dynamic-data.html
// Динамичные данные - для траектории спутника ?

/// Анимация маркера по маршруту
//https://openlayers.org/en/latest/examples/feature-move-animation.html

///https://openlayers.org/en/latest/examples/geojson.html
//geoJson, типа запрошенный и отрисованный по координатам
//Может так нужно реальные фотки накладывать ?
		
//https://openlayers.org/en/latest/examples/graticule.html
//Сетка поверх карты

//https://openlayers.org/en/latest/examples/image-vector-layer.html
//Типа векторная карта

//https://openlayers.org/en/latest/examples/layer-spy.html
////Как бы лупа - маска вокруг мыши - просмотр одного слоя над другим

//https://openlayers.org/en/latest/examples/layer-z-index.html
////Z-index

///https://openlayers.org/en/latest/examples/magnify.html
///Ещё лупа

////https://openlayers.org/en/latest/examples/modify-features.html
///Выделяемые области общее наложение карты

////////////https://openlayers.org/en/latest/examples/min-max-resolution.html
////Накладка нового слоя при зуме

////https://openlayers.org/en/latest/examples/accessible.html
///Зум по кнопкам

////https://openlayers.org/en/latest/examples/min-zoom.html
////мин зум

//https://openlayers.org/en/latest/examples/mouse-position.html
//Позиция мыши в разных системах

/////https://openlayers.org/en/latest/examples/reprojection.html
//////Разные системы отображения

/////////https://openlayers.org/en/latest/examples/layer-extent.html
//////////Тут есть функция для преобразования из одной системы координат в другую

///https://openlayers.org/en/latest/examples/reprojection-image.html
//Вроде как есть наложение, но ХЗ

///////https://openlayers.org/en/latest/examples/reusable-source.html
////Перезагрузка плитки слоя

////https://openlayers.org/en/latest/examples/select-features.html
///Динамическая подвязка событий к областям карты
///Есть hover


//https://openlayers.org/en/latest/examples/scaleline-indiana-east.html
//https://openlayers.org/en/latest/examples/scale-line.html
//Полоска масштаба


//https://openlayers.org/en/latest/examples/static-image.html
//Статическая картинка. Зачем?
	
///https://openlayers.org/en/latest/examples/sphere-mollweide.html
////Сферическая карта Моллвейда с сеткой

//https://openlayers.org/en/latest/examples/street-labels.html
//Названия улиц

///////https://openlayers.org/en/latest/examples/translate-features.html
////Перетаскиваем области по карте

///////https://openlayers.org/en/latest/examples/vector-label-decluttering.html
///Автоматическое сокрытие названий мелких областей при зуме

///https://openlayers.org/en/latest/examples/vector-layer.html
///Реакция на ховер с отображением информации о области

///////https://openlayers.org/en/latest/examples/vector-wfs.html
///Наладывает какие-то контуры на карту

/////https://openlayers.org/en/latest/examples/box-selection.html
//Выбор множества областей мышкой (рамкой)

///https://openlayers.org/en/latest/examples/full-screen-source.html
//Боковая панелька на карте

/////https://openlayers.org/en/latest/examples/popup.html
////Popup
//Попап с выводом координат

//https://openlayers.org/en/latest/examples/measure.html
//Расстояние по карте

//https://openlayers.org/en/latest/examples/overviewmap-custom.html
//Миникарта


////https://openlayers.org/en/latest/examples/side-by-side.html
////webGL vs Canvas

////https://openlayers.org/en/latest/examples/tile-load-events.html
///progress bar


////https://openlayers.org/en/latest/examples/zoomslider.html
///Зуммирование со слайдерами


//////////https://openlayers.org/en/latest/examples/layer-extent.html
///Выделение областей

/////Карта в EPSG:4326
////////https://openlayers.org/en/latest/examples/epsg-4326.html


/////http://openlayersbook.github.io/ch11-creating-web-map-apps/example-08.html
/////ВЫбор слоев

/////https://openlayers.org/en/latest/examples/geojson.html
/////Рисование Box

</script>
























