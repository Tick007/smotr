///////////////Подкраски фичи с геометрией на карте
function highlightFeature(ft){
	var features = vector.getSource().getFeatures();
	features.forEach(function(f) {
		// console.log(f);
		//f.setStyle(ordinaryStyle);	///////////Всё стопорится после такого, перестает возращать фичу в месте клика
		
	});

	///////////////нужно поробовать грохать коллекцию фич и создовать новую.
	////////https://openlayers.org/en/latest/apidoc/module-ol_VectorTile-VectorTile.html#setFeatures
	
	
	//ft.setStyle(highlightStyle);	// тоже стопорится когда все фичи прокликаны
	
/*
	console.log(ft);
	var features = vector.getSource().getFeatures();
	features.forEach(function(f) {
		// console.log(f);
	 	if(ft.getGeometry().ol_uid==f.getGeometry().ol_uid)f.setStyle(highlightStyle);	
		 else f.setStyle(ordinaryStyle);	
	});
	*/

}

///////////Выводим в боковую панель все нарисованные фигуры
///////////Только нужно событие создание фичи. Где оно ?
function updateFeaturesList(feature, base_url){
	//console.log(feature);
	//console.log(feature.getGeometry());
	$('.sidepanel2').css('display', 'block');	
	var htmlsrc = '<li id=f_'+feature.getGeometry().ol_uid+'><div class="extent">';
	htmlsrc += feature.getGeometry().extent_;
	htmlsrc += '</div><div><a class="delete_polygon" id=a_'+feature.getGeometry().ol_uid+'><img width="20" src="'+base_url+'/images/delete.png"></a></div>';
	htmlsrc += '</li>';
	$('#features_list').append(htmlsrc);	
}



/////////////Отключение возможности рисованя фигуур
function switchOffInteractions(){

	///////////ХЗ, но таким методом только после двоекратного вызова убирается интеракция
		 /*
		 var interactions = map.getInteractions();
	     interactions.forEach(function(interaction) {
	        //console.log(interaction);
	 		map.removeInteraction(interaction);
	     });
	     */
     
	map.removeInteraction(draw);//////////на удивление работает, каким фигом не понятно - если локальную не объявить с var
	delete draw;
	draw=null;
}

/*Метод для обращения к бэк энду на поиск имеющихся фоток*/
//style - стиль которым закрышивать пустые рамки
 function showMeArchive(extent, output, style){
	///////////Запрос на сервер
	
	url = '/map/searchArhive';
	jQuery.ajax({
		'type' : 'POST',
		'url' : url,
		'cache' : 'false',
		'return_ajax':1,
		//{name:name,email:email,lang:lang},
		'data' : {'extent':extent},
		'success' : function(response) {

		
		var json_data =JSON.parse(response);
		//console.log(json_data);

		if(output=='map'){//////////////////Вывод результатов на карту
			for (var i in json_data) {
				rec = json_data[i];
				addImageToMap(rec);
			 	//map.removeInteraction(draw); /////////работает но ХЗ как и почему без глобального draw. Вообще draw - это одна геометрия
			}
			}
			else {//////////////////вывод результатов в панель (считаем что пришёл id панели куда выводить)
				$('.'+output).css('display','block');
				/*<td>Идентификатор файла</td><td>Координаны</td><td>Дата</td><td>Легенда</td><td>Превью</td><td>Показать</td>*/

				for (var i in json_data) {
					rec = json_data[i];		
					fileslist.push(rec);
					//1./////// тут должен быть вызов создания фичи с координатами полученными по аджакс в поле extent
					feature = createFeaturefromExtent(rec['extent'], style);
					
					//2. //////////Добавляем записи в таблицу
					img_url_thumb = '/pictures/make_mini.php?create=0&height=30&imgname='+rec['imgpath'];
					var tr = '<tr><td>';
					tr+= rec['file_id']+'</td><td>';
					tr+=  rec['extent']+'</td><td>';
					tr+=  rec['shot_dt']+'</td><td>';
					tr+=  rec['legend']+'</td><td>';
					tr+=  '<img src="'+img_url_thumb +'"></td><td align="center">';
					tr+= '<input class="feature_icon_show" type="checkbox" file_id="'+rec['file_id']+'" id="is_'+feature.getGeometry().ol_uid+'"></td>';
					tr+= '<td align="center"><div id="file_'+rec['file_id']+'" class="cartbutton ';
					if(rec['incart']==false) tr+=' cart_empty';
					else tr+=' cart_full';
					tr+= '"></div>';
					tr+= '</td></tr>'; 
					$('.'+output+' tbody').append(tr);
					
					

				}
				
			}
			
		},
		'error' : function(error) {

		}
		
	});
	
	
	/*
	console.log(extent);
	var carLayer = new ol.layer.Image({
        source: new ol.source.ImageStatic({
			   url:'http://localhost:8080/JavaBridge/smotr/images/2122.png',
            crossOrigin: '',
          <!--Даем картинке координаты в EPSG:4326 и автоматом конвертим её координаты в EPSG:3857-->
             projection: 'EPSG:3857',
             imageExtent: extent
         })
    });
 	map.addLayer(carLayer);
 	*/
 	
}
 
 ////////Функция для добавления картинки на карту
 function addImageToMap(rec){
	 var carLayer = new ol.layer.Image({
	        source: new ol.source.ImageStatic({ ///////////родной вариант
				 url:'/images/'+rec['filename'],
	             crossOrigin: '',
	             projection: 'EPSG:3857',
	             imageExtent:  rec['extent'],
    			      	////Тут как-то нужно тащить другие параметры снимка, такие как время пролета и съемки КА (rec['shot_dt'])
	         }),
	         shot_dt:rec['shot_dt'],/////////Добавляем в объект слой ключи которых в нем нет для передачи дополнительных полей
	         file_id:rec['file_id'],
	         legend: rec['legend'],
	    });
		//carLayer.source.set('shot_dt', rec['shot_dt']); // https://openlayers.org/en/latest/apidoc/module-ol_source_Source-Source.html#setAttributions
	 	
	 	map.addLayer(carLayer);
 }
 
 
 /////// Создание пустой фичи на карте с координатами extent
 ///////Предпологаем что vector это глобальная переменная карты
 function createFeaturefromExtent(extent, style){
	 
	 var feature = new ol.Feature({
		 geometry: new ol.geom.Polygon.fromExtent(extent),////////////Вот оно
		});
	   if(style!=null)feature.setStyle(style);
	   vector.getSource().addFeature(feature);
	   return feature;

 }
 

 function transformTo3857(extent) {
	    return new ol.proj.transformExtent(extent, 'EPSG:4326', 'EPSG:3857');
	  }

function addInteraction() {
	var value = typeSelect.value;
    if (value !== 'None') {
      var geometryFunction;
    if (value === 'Square') {
      value = 'Circle';
      geometryFunction = createRegularPolygon(4);
    } else if (value === 'Box') {
      value = 'Circle';
      geometryFunction = ol.interaction.Draw.createBox();
    } else if (value === 'Star') {
      value = 'Circle';
      geometryFunction = function(coordinates, geometry) {
        var center = coordinates[0];
        var last = coordinates[1];
        var dx = center[0] - last[0];
        var dy = center[1] - last[1];
        var radius = Math.sqrt(dx * dx + dy * dy);
        var rotation = Math.atan2(dy, dx);
        var newCoordinates = [];
        var numPoints = 12;
        for (var i = 0; i < numPoints; ++i) {
          var angle = rotation + i * 2 * Math.PI / numPoints;
          var fraction = i % 2 === 0 ? 1 : 0.5;
          var offsetX = radius * fraction * Math.cos(angle);
          var offsetY = radius * fraction * Math.sin(angle);
          newCoordinates.push([center[0] + offsetX, center[1] + offsetY]);
        }
        newCoordinates.push(newCoordinates[0].slice());
        if (!geometry) {
          geometry = new Polygon([newCoordinates]);
        } else {
          geometry.setCoordinates([newCoordinates]);
        }
        return geometry;
      };
    }
    draw = new ol.interaction.Draw({ /////////////глобальная объявлена заранее
    //var draw = new ol.interaction.Draw({ /////////////без var ведет себя как глабальная
      source: source,
      type: value/*typeSelect.value*/,
      geometryFunction: geometryFunction,
      projection: 'EPSG:4326',
    });
    map.addInteraction(draw);


   // snap = new ol.interaction.Snap({source: source});
   // map.addInteraction(snap);
    }
  }