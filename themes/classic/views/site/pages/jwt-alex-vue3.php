<?php


$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/select/vue-select.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/vue/select/vue-select.css?v=' . rand());
$clientScript->registerCssFile('/themes/classic/css/vue_test3.css?v=' . rand());
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/userpostcomponent2.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/buttoncomponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/ordercomponent2.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/WindowHelperComponent_v2.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/AntennaDeviceComponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/ConverterDeviceComponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/AmplifierDeviceComponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/MatrixDeviceComponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/CyclogramRunnerComponent.js?v=' . rand(), CClientScript::POS_HEAD);

// svg https://icons8.com/preloaders/

//print_r($this->browser);
$unic_id=md5($this->browser['platform'].$this->browser['agent_details'].time());
//echo $unic_id;
?>

<?php 
require (dirname(__FILE__).'\..\..\..\..\..\protected\commands\app\BaseDevice.php'); // /////////
require (dirname(__FILE__).'\..\..\..\..\..\protected\commands\app\K4c75_amplifier.php'); // ////////
require (dirname(__FILE__).'\..\..\..\..\..\protected\commands\app\DNConverter.php'); // ////////
require (dirname(__FILE__).'\..\..\..\..\..\protected\commands\app\keydown_3204_matrix.php'); // ////////
use MyApp\K4c75_amplifier;
use MyApp\K4c75_redundancy_control;
use MyApp\DNConverter;
use MyApp\keydown_3204_matrix;

$amp = new K4c75_amplifier(null, 0);
$redundancy_amp = new K4c75_redundancy_control();
$dnconv = new DNConverter(null, 0);
$matrix = new keydown_3204_matrix(null, 0);
$jsn_strct_amp = json_encode($amp->getDefaultValues(true));
$jsn_conf_amp = json_encode($amp->getUnitsData(true));
$jsn_red_conf_amp = json_encode($redundancy_amp->getUnitsData(true));
$jsn_strct_dnconv = json_encode($dnconv->getDefaultValues(true));
$jsn_conf_dnconv  = json_encode($dnconv->getUnitsData());
$jsn_strct_matrix =  json_encode($matrix->getDefaultValues(true));

?>
<div style="width:100">
<div id="vuetable">

	<table width="auto" border="0" cellpadding="1" cellspacing="1"
		bgcolor="#333333">
		<tr>
			<td colspan="2" bgcolor="#FFFFFF">Управление сервером</td>
			<td bgcolor="#FFFFFF" id="log2">Лог</td>
			<td colspan="2" bgcolor="#FFFFFF">Управление соединением</td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
			<td bgcolor="#FFFFFF">Мониторинг</td>
			<td bgcolor="#FFFFFF">Удаленное</td>
		</tr>
		<tr>
			<td align="center" bgcolor="#FFFFFF">
				<button @click="startmon" type="button" class="omstartbut"></button>
			</td>
			<td align="center" bgcolor="#FFFFFF"><button @click="shutdownmon"
					type="button" class="omstopbut"></button></td>
			<td width="200" bgcolor="#FFFFFF"><div id="log"
					style="overflow-y: scroll; word-break: break-all; max-height: 100px; max-width: 200px;">Log</div></td>
			<td bgcolor="#FFFFFF"><?php
echo CHtml::button('startArmSockets', array(
    'value' => 'Подключение к ЗС',
    'id' => 'startArmSockets'
));
?></td>
			<td bgcolor="#FFFFFF"><?php
echo CHtml::button('switchTrigger', array(
    'value' => 'Отключиться от ЗС',
    'id' => 'switchTrigger'
));
?></td>
			<td bgcolor="#FFFFFF"><div id="p1"></div></td>
			<td bgcolor="#FFFFFF"><div id="p2"></div></td>
			<td bgcolor="#FFFFFF"><div id="p3"></div></td>
			<td bgcolor="#FFFFFF"><svg xmlns:svg="http://www.w3.org/2000/svg"
					xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0"
					width="100px" height="100px" viewBox="0 0 128 128"
					xml:space="preserve" id="myanim">
		<g>
				<linearGradient id="linear-gradient">
				<stop offset="0%" stop-color="#000" />
		<stop offset="100%" stop-color="#0090fe" /></linearGradient>
		<linearGradient id="linear-gradient2">
				<stop offset="0%" stop-color="#000" />
		<stop offset="100%" stop-color="#90e6fe" />
		</linearGradient>
		<path
						d="M64 .98A63.02 63.02 0 1 1 .98 64 63.02 63.02 0 0 1 64 .98zm0 15.76A47.26 47.26 0 1 1 16.74 64 47.26 47.26 0 0 1 64 16.74z"
						fill-rule="evenodd" fill="url(#linear-gradient)" />
		<path
						d="M64.12 125.54A61.54 61.54 0 1 1 125.66 64a61.54 61.54 0 0 1-61.54 61.54zm0-121.1A59.57 59.57 0 1 0 123.7 64 59.57 59.57 0 0 0 64.1 4.43zM64 115.56a51.7 51.7 0 1 1 51.7-51.7 51.7 51.7 0 0 1-51.7 51.7zM64 14.4a49.48 49.48 0 1 0 49.48 49.48A49.48 49.48 0 0 0 64 14.4z"
						fill-rule="evenodd" fill="url(#linear-gradient2)" />
		<animateTransform attributeName="transform" type="rotate" id="antr"
						v-bind:from="mon_indicator" to="360 64 64" dur="1800ms"
						repeatCount="indefinite">
		</animateTransform></g></svg></td>
		<td bgcolor="#FFFFFF" align="center"><input type="button" value="Открыть"  id="cyclogram_switch" @click="cyclogram_switch_click()" ></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td bgcolor="#FFFFFF" align="center">Запуск</td>
			<td bgcolor="#FFFFFF" align="center">Убить</td>
			<td bgcolor="#FFFFFF" colspan="6">&nbsp;</td>
			<td bgcolor="#FFFFFF" align="center"><div id="livetag"></div></td>
			<td bgcolor="#FFFFFF" >управление</td>
		</tr>
	</table>






	<table padding="1" spacing="1" border="0" width="100%">
		<tr>
			<td>Api login</td>
			<td>&nbsp;</td>
			<td>Token</td>
			<td>&nbsp;</td>
			<td>Api next</td>
			<td>&nbsp;</td>
			<td>Answer</td>
			<td>&nbsp;</td>
			<td>Открыть</td>
		</tr>
		<tr>
			<td><input v-model="authApi"></td>
			<td>&nbsp;</td>
			<td><textarea rows="5" cols="50" v-model="jwtToken"></textarea></td>
			<td>&nbsp;</td>
			<td><input v-model="usersApi"></td>
			<td>&nbsp;</td>
			<td><v-select style="width:200px" :options="options"
					label="firstName" code="id" @input="setSelected"></v-select></td>
			<td>&nbsp;</td>
			<td><input type="button" v-on:click="addOnfly" value="Добавить" /></td>
		</tr>
		<tr>
			<td><input type="button" v-on:click="Authorise" value="get tokken" /></td>
			<td>&nbsp;</td>
			<td></td>
			<td>&nbsp;</td>
			<td><input type="button" v-on:click="getUsers" value="get users" /></td>
			<td><input type="button" v-on:click="getOrders" value="get orders" /></td>
			<td><v-select style="width:200px" :options="orders" label="id"
					code="id" @input="setOrder"></v-select></td>
			<td>&nbsp;</td>
			<td><input type="button" v-on:click="addOnflyOrder" value="Добавить" /></td>
		</tr>

	</table>
	<br> <br>

<div ref="container" class="wincont"></div>

<table class="scalabletable" style=" transform-origin: top left;">
<tr>
<td><!-- left cell -->
&nbsp;
</td>
<td><!-- main cell -->
	<div class="grid-container">
		<div class="grid-item">
			1
		</div>
		<div class="grid-item">2</div>
		<div class="grid-item">
			3
		</div>
		<div class="grid-item">

			<div id="app" class="clock-container">
			<script>
			function display_c(){
				var refresh=1000; // Refresh rate in milli seconds
				mytime=setTimeout('display_ct()',refresh)
				}
			function display_ct() {
				var x = new Date()
				//var x1=x.toUTCString();// changing the display to UTC string
				x1 =   x.getHours( )+ ":" +  x.getMinutes() + ":" +  x.getSeconds();
				document.getElementById('ct').innerHTML = x1;
				tt=display_c();
				 }
			</script>
				<div id="rotate-background" class="rotate-background"
					style="transform: translate3d(0px, 0px, 0px) rotate(282.996deg);"></div>
				<div class="background-clock">
					<div class="time time-back">00:00:00</div>
					<div class="time"><span id='ct' ></span></div>
				</div>
			</div>

		</div>
		<div class="grid-item">
			<user_post v-bind="selectedValue" mode="flat"></user_post>
		</div>
		<div class="grid-item"><!-- <antenna_device :antenna-parametrs="antennaData1" mode="flat" v-on:open="getAntenna(1)"></antenna_device>--></div>
		<div class="grid-item"><amplifier_device :amp-parametrs="amplifierData1" mode="flat"  v-on:open="getAmplifierer(1)"></amplifier_device></div>
		<div class="grid-item">8</div>
		<div class="grid-item"><amplifier_device :amp-parametrs="amplifierData2" mode="flat"  v-on:open="getAmplifierer(2)"></amplifier_device></div>
		<div class="grid-item">10</div>
		<div class="grid-item">11</div>
		<div class="grid-item">12</div>
		<div class="grid-item">13</div> 
		<div class="grid-item">14</div>
		<div class="grid-item">15</div>
		<div class="grid-item"><converter_device :conv-parametrs="converterData1" mode="flat"  v-on:open="getConverter(1)"></converter_device></div>
		<div class="grid-item">17</div>
		<div class="grid-item"><converter_device :conv-parametrs="converterData2" mode="flat" v-on:open="getConverter(2)"></converter_device></div>
		<div class="grid-item">19</div>
		<div class="grid-item">20</div>
		<div class="grid-item"><matrix_device :matrix-parametrs="matrixData1" mode="flat" v-on:open="getMatrix(1)"></matrix_device></div>
		<div class="grid-item"><matrix_device :matrix-parametrs="matrixData2" mode="flat" v-on:open="getMatrix(2)"></matrix_device></div>
		<div class="grid-item">23</div>
		<div class="grid-item">24</div>
		<div class="grid-item">25</div>
		<div class="grid-item">26</div>
		<div class="grid-item">27</div>
		<div class="grid-item">28</div>
		<div class="grid-item">29</div>
		<div class="grid-item">30</div>
	</div>
</td>
<td width="250" valign="top"><!-- right cell -->

<table border="1" width="100%">

<tr>
<td ><input type="checkbox" id="ws_switch" v-model="ws_switch" @change="checkMonStatus()" >
<label for="ws_switch"  style="cursor: pointer;">
<svg xmlns:svg="http://www.w3.org/2000/svg"
					xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink" version="1.0"
					width="100px" height="100px" viewBox="0 0 128 128"
					xml:space="preserve" id="myanim2" >
		<g>
				<linearGradient id="linear-gradient">
				<stop offset="0%" stop-color="#000" />
		<stop offset="100%" stop-color="#0090fe" /></linearGradient>
		<linearGradient id="linear-gradient2">
				<stop offset="0%" stop-color="#000" />
		<stop offset="100%" stop-color="#90e6fe" />
		</linearGradient>
		<path
						d="M64 .98A63.02 63.02 0 1 1 .98 64 63.02 63.02 0 0 1 64 .98zm0 15.76A47.26 47.26 0 1 1 16.74 64 47.26 47.26 0 0 1 64 16.74z"
						fill-rule="evenodd" fill="url(#linear-gradient)" />
		<path
						d="M64.12 125.54A61.54 61.54 0 1 1 125.66 64a61.54 61.54 0 0 1-61.54 61.54zm0-121.1A59.57 59.57 0 1 0 123.7 64 59.57 59.57 0 0 0 64.1 4.43zM64 115.56a51.7 51.7 0 1 1 51.7-51.7 51.7 51.7 0 0 1-51.7 51.7zM64 14.4a49.48 49.48 0 1 0 49.48 49.48A49.48 49.48 0 0 0 64 14.4z"
						fill-rule="evenodd" fill="url(#linear-gradient2)" />
		<animateTransform attributeName="transform" type="rotate" id="antr"
						v-bind:from="mon_indicator2" to="360 64 64" dur="1800ms"
						repeatCount="indefinite">
		</animateTransform></g></svg>
</label></td>
<td>&nbsp;</td>
</tr>
</table>

</td> 
</tr>
</table>
</div>
	
		<hr>
	
		<!--<user_post v-bind="selectedUser"  v-on:user-save="saveUser"  v-on:updated="saveUser2"></user-post>-->
		<input type="checkbox" id="qqq" v-model="show_ws_frame"> <label
			for="qqq">Отображать кадр WS</label>
		<div class="ws_frame">
			<textarea rows="10" cols="100" v-model="wsFrame"
				placeholder="Кадр по websocket"></textarea>
		</div>

	
</div>





<script>




function getCookie(name){
    var pattern = RegExp(name + "=.[^;]*")
    var matched = document.cookie.match(pattern)
    if(matched){
        var cookie = matched[0].split('=')
        return cookie[1]
    }
    return false
}




function getToken(urlApi, method, caller){
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    var raw = JSON.stringify({"email":"admin@mail.com","password":"admin"});
    
    
    var requestOptions = {
      method: method,
      headers: myHeaders,
      body: raw,
      redirect: 'follow'
    };
    
    fetch(urlApi, requestOptions)
      .then(response => response.json())
      .then(result =>{ 
          //alert('ewrwer');
          //console.log(result);
    	  	if(result!='undefined' & result.token!='undefined'){
        	  	//console.log(result.token);
        		document.cookie = "jwttoken="+result.token;
        		//return result.token;
        		caller(result.token);
    	 	 }
    	  })
      .catch(error =>{ 
		  //alert('Произошла ошибка, детали в консоли');
		  document.cookie = "jwttoken=";
		  caller(error);
          //console.log('error', error);
          });
}


function getUsersF(urlApi, method, caller, jwttok, body=null) {
	if(jwttok!='undefined'){
		var myHeaders = new Headers();		
		myHeaders.append("Content-Type", "application/json");
		myHeaders.append("Authorization", jwttok);

		var requestOptions = {
				  method: method,
				  headers: myHeaders,
				  body: body,
				  redirect: 'follow'
				};

		fetch(urlApi, requestOptions)
		  .then(response => response.json())
		  .then(result =>{ 
			  if(result.token!='undefined'){
				  //console.log(result);
		          if(caller!=null & caller!='undefined') caller(result);
				}
			  })
		  .catch(error =>{
			 // alert('Ошибка');
			  console.log('error', error);
			  });
	}
	
}
function saveDevice(urlApi, method, caller, jwttok, body) {
	
	if(jwttok!='undefined'){
		var myHeaders = new Headers();		
		myHeaders.append("Content-Type", "application/json");
		myHeaders.append("Authorization", jwttok);

		var requestOptions = {
				  method: method,
				  headers: myHeaders,
				  body: body,
				  redirect: 'follow'
				};

		fetch(urlApi, requestOptions)
		  .then(response => response.json())
		  .then(result =>{ 
			  if(result.token!='undefined'){
				  //console.log(result);
		          if(caller!=null & caller!='undefined') caller(result);
				}
			  })
		  .catch(error =>{
			 // alert('Ошибка');
			  console.log('error', error);
			  });
	}
}

//////////////Функция для отправки команд на контроллер управления сокет сервером
function wsStatusControl(urlApi, method, jwttok, command) {
	var myHeaders = new Headers();		
	myHeaders.append("Content-Type", "application/json");
	myHeaders.append("Authorization", jwttok);

    let body=JSON.stringify(command);
	
	var requestOptions = {
			  method: method,
			  headers: myHeaders,
			  body: body,
			  redirect: 'follow'
			};

	fetch(urlApi, requestOptions)
	  .then(response => response.json())
	  .then(result =>{ 
		  if(result.token!='undefined'){
			  console.log(result);
	          //if(caller!=null & caller!='undefined') caller(result);
			}
		  })
	  .catch(error =>{
		 // alert('Ошибка');
		  console.log('error', error);
		  });
}



$( document ).ready(function() {

	const moduleAntenna = {
			 namespaced: true,
		    state: { 
		    	 object:{"azimut":{"nameParameter":"\u0410\u0437\u0438\u043c\u0443\u0442","valueParameter":0},"angle":{"nameParameter":"\u0423\u0433\u043e\u043b \u043c\u0435\u0441\u0442\u0430","valueParameter":0},"scanner":{"nameParameter":"\u0421\u043a\u0430\u043d\u043d\u0435\u0440","valueParameter":"\u0412\u043a\u043b\u044e\u0447\u0435\u043d"},"workmode":{"nameParameter":"\u0420\u0430\u0431\u043e\u0447\u0438\u0439 \u0440\u0435\u0436\u0438\u043c","valueParameter":0,"val_list":["\u0410\u0432\u0442\u043e\u043c\u0430\u0442","\u041f\u043e \u043f\u0440\u043e\u0433\u0440\u0430\u043c\u043c\u0435"]},"title":{"nameParameter":"\u041d\u0430\u0438\u043c\u0435\u043d\u043e\u0432\u0430\u043d\u0438\u0435","valueParameter":"\u0410\u043d\u0442\u0435\u043d\u043d\u0430 \u21163 \u0422\u041a\u0426 (\u044f\u043c\u0430\u043b 601)"},"id":{"nameParameter":"\u0418\u0434\u0435\u043d\u0442\u0438\u0444\u0438\u043a\u0430\u0442\u043e\u0440","valueParameter":1},"signal_lvl":{"nameParameter":"\u0423\u0440\u043e\u0432\u0435\u043d\u044c \u0441\u0438\u0433\u043d\u0430\u043b\u0430","valueParameter":0}
				        
			        },
		        updateApi: 'http://yii-site/nomenklatura/smotrantennaupdate',
		    },
		    mutations: {
		    	 update (state, payload) {
			    	 //console.log(payload);
			    	 state.object = payload;
		         },
		         updatebyId (state, payload) {
		             state.object[payload.workmode] = payload.obj.workmode;
		             state.object[payload.angle] = payload.obj.angle;
		             state.object[payload.azimut] = payload.obj.azimut;
		             saveDevice(state.updateApi+'/1', 'POST', null, this.jwtToken, JSON.stringify(payload.obj));
		         }
		    },
		    getters: {
		    	antennaData1: (state) => {
		            return state.object;
		        },
		        
		    },
		    actions: {
		        
		    }
		}

	const moduleDnConverter = {
			 namespaced: true,
		    state: { 
		    	object:{
			    	1:{"deviceParameters":<?php echo $jsn_strct_dnconv?>,"title":"DNConv 10","id":10},
			        2:{"deviceParameters":<?php echo $jsn_strct_dnconv?>,"title":"DNConv 20","id":20},
			        "configuration":<?php echo $jsn_conf_dnconv?>,
		        },

		        updateApi: 'http://yii-site/nomenklatura/smotrconverterupdate',
		    },
		    mutations: {
		    	 update (state, payload) {
			    	 //console.log(payload);
			    	 state.object[1] = payload[1];
			    	 state.object[2] = payload[2];
		         },
		         updateConf(state, payload){
		        	 state.object.configuration = payload;
			     },
		         updatebyId (state, payload) {
		             //console.log('in store commit');
		             saveDevice(state.updateApi+'/'+payload.id, 'POST', null, this.jwtToken, JSON.stringify(payload.obj));
		         },
		         resetState(state){
					/////////////Перебираем объекты в state и зануляем всё
		        	 Object.entries(state.object).forEach(function callback(obj, index) {
			        	if(obj[1].deviceParameters!==null && obj[1].deviceParameters!==undefined) Object.entries(obj[1].deviceParameters).forEach(function callback(value, key) {
			        		  value[1].valueParameter = 0;
			        	});
		        	 });
		        	 
				    }
		    },
		    getters: {
		    	converterData1: (state) => {
		            return state.object[1];
		        },
		        converterData2: (state) => {
		            return state.object[2];
		        },
		    },
		    actions: {
		        
		    }
		}
	const moduleAmplifier = {
			 namespaced: true,
		    state: { 
		    	object:{ //////////Тут нужно сделать php включение
				    1:{"deviceParameters":<?php echo $jsn_strct_amp?>, 'id':90},
					2:{"deviceParameters":<?php echo $jsn_strct_amp?>, 'id':91},
					"configuration":<?php echo $jsn_conf_amp?>,
					"redundancy_configuration":<?php echo $jsn_red_conf_amp?>,		
		         },

		        updateApi: 'http://yii-site/nomenklatura/smotramplifierupdate',
		    },
		    mutations: {
		    	 update (state, payload) {
			    	 //console.log(payload);
			    	 state.object[1] = payload[1];
			    	 state.object[2] = payload[2];
		         },
		         updatebyId (state, payload) {
		             //console.log('in store commit');
		             saveDevice(state.updateApi+'/'+payload.id, 'POST', null, this.jwtToken, JSON.stringify(payload.obj));
		         },
		         resetState(state){
					/////////////Перебираем объекты в state и зануляем всё
		        	 Object.entries(state.object).forEach(function callback(obj, index) {
			        	Object.entries(obj[1].deviceParameters).forEach(function callback(value, key) {
			        		  value[1].valueParameter = 0;
			        	});
		        	 });
		        	 
				    }
		    },
		    getters: {
		    	amplifierData1: (state) => {
		            return state.object[1];
		        },
		        amplifierData2: (state) => {
		            return state.object[2];
		        },
		    },
		    actions: {
		        
		    }
		}
	const muduleCyclogram ={
		namespaced: true,
		state: {
			list:{},
			cyclogramstatus:{},
			cyclogramApi: 'http://smotr/site/cyclogramapi',
			
			},
			 mutations: {
			    	update (state, payload) {
				    	 state.list = payload.list;
				    	 state.devlist = payload.devlist;
			         },
			         saveLink(state, instance) {
			        	 state.instanceLink = instance;
					 },
					 updatestatus(state, payload) {
				    	 state.cyclogramstatus = payload;
			         },
			 },	
            actions: {
                /*
            	turnOffButton({commit, state}){ ///////////Устарело
                    payload={
              			 'cyclogram':{'dialog':'close', 'clientid':app.client_id}
              			 };
          		
          			saveDevice(state.cyclogramApi, 'POST', null, this.jwtToken, JSON.stringify(payload));
            		},*/
            		executeCyclogram({commit, state}, payload){
						//console.log(payload);
						payload.command.clientid = app.client_id;
						//payload.clientid=app.client_id;
            			saveDevice(state.cyclogramApi, 'POST', null, this.jwtToken, JSON.stringify(payload));
                	},
            	}
	} 

	const moduleCortex = {
			 namespaced: true,
			 state:{
					object:{
							1:{},
							2:{},
						},
					updateApi: 'http://yii-site/nomenklatura/smotrcortexupdate',
				 },
				 mutations:{
						update(state, payload){
								state.object[1] = payload[1];
					    		state.object[2] = payload[2];
					    		//console.log(payload[2]);
							},
					 },
			}
	
	const moduleMatrix = {
			 namespaced: true,
		    state: { 
		    	object:{ //////////Тут нужно сделать php включение
				    1:{"deviceParameters":<?php echo $jsn_strct_matrix?>, 'id':30},
					2:{"deviceParameters":<?php echo $jsn_strct_matrix?>, 'id':31},
					configuration:{},
		         },

		        updateApi: 'http://yii-site/nomenklatura/smotrmatrixupdate',
		    },
		    mutations: {
		    	update (state, payload) {
			    	 //console.log(payload);
			    	 state.object[1] = payload[1];
			    	 state.object[2] = payload[2];
		         },
		         updateConf(state, payload){
		        	 state.object.configuration = payload;
			     },
		         updatebyId (state, payload) {
		             //console.log('in store commit');
		             saveDevice(state.updateApi+'/'+payload.id, 'POST', null, this.jwtToken, JSON.stringify(payload.obj));
		         },
		         resetState(state){
					/////////////Перебираем объекты в state и зануляем всё
		        	 Object.entries(state.object).forEach(function callback(obj, index) {
			        	Object.entries(obj[1].deviceParameters).forEach(function callback(value, key) {
			        		  value[1].valueParameter = 0;
			        	});
		        	 });
		        	 
				    }
		    },
		    getters: {
		    	matrixData1: (state) => {
		            return state.object[1];
		        },
		        matrixData2: (state) => {
		            return state.object[2];
		        },
		    },
		    actions: {
		        
		    }
		}

	const store = new Vuex.Store({
	    modules: {
	        antenna: moduleAntenna,
	        dnconverter: moduleDnConverter,
	        amplifier: moduleAmplifier,
	        matrix: moduleMatrix,
	        cyclogram: muduleCyclogram,
	        cortex: moduleCortex,
	        //upconverter: moduleConverter,

	    },
	    state: {

	    },
	    mutations: {
	        
	    },
	    getters: {
	    	
	    },
	    actions: {
	        
	    }
	});

	//console.log(store.state.antenna.object); //Так добираться, getter типа но не getter


	display_c();
	

	Vue.component('v-select', VueSelect.VueSelect);
	Vue.component('button_type', button_type);

	Vue.component('user_post' , user_post_type);/////////////Создаем компонент для отображения на схеме

	Vue.component('antenna_device' , antenna_device_type);/////////////Создаем компонент для отображения на схеме

	Vue.component('converter_device' , converter_device_type);//////
	Vue.component('amplifier_device' , amplifier_device_type);//////
	Vue.component('matrix_device', matrix_device_type);
	Vue.component('cyclogram_ui', cyclogram_runner_type);
	

	//Vue.use(Vuex);
	
	var app = new Vue({
		  el: '#vuetable',
		  store,
		  data: {
		  	options: [
    	        'no data avilable'
		    ],
		    orders:['no data avilable'],
		    //Лёха
		    /*
		    authApi: 'http://10.10.0.99:7070/api/v1/auth/login',/////////Получение токена
		    usersApi:'http://10.10.0.99:7070/api/v1/developers', ///////Получене списка/пользователя
		    */

			client_id: '<?php echo $unic_id?>', /////////////Уникальный идентификатор, что бы можно было отличать соединения на сервере
		    
		    authApi: 'http://yii-site/nomenklatura/getjwt',
		    usersApi:'http://yii-site/nomenklatura/jwtusers',
		    ordersApi:'http://yii-site/nomenklatura/jwtorders',
		    antennaApi:'http://yii-site/nomenklatura/smotrantenna',
		    monitoringState:'http://smotr/site/MonitoringState',
		    
		    //antennaUpdateApi:'http://yii-site/nomenklatura/smotrantennaupdate',
		    //userUpd: 'http://10.10.0.99:7070/api/v1/developers',/////////Для апдейта пользователей
		    jwtToken:'',
		    show_ws_frame:false,
		    ws_switch: false,
    		wsFrame:'',
		    //zindex:1, ////////////z-index 
		    selectedUser:null,
		    selectedValue:null, ////////////транзитная переменная для clients
		    ////////Убираем, пробуем работать через store
		    //antennaData1:null,////////////Переменная куда будет грузиться объект антенны для схемы
		    //antennaData2:null,////////////Переменная куда будет грузиться объект антенны для схемы
		   	selectedOrder:null,
		    //zindex: 10, //////////Глобальный индекс высоты для ноывх окон
		    wincont:'wincont',
		    mon_indicator:"360 64 64",
		    mon_indicator2:"360 64 64",
		    
		    livetag_lasttime: new Date(),
		  },

		  //////////////Однонаправленное
		  /*
		  computed: Vuex.mapGetters([
		        'antennaData1', 'antennaData2'
		        ]), ///////
		  */
		        
		////////////Двунаправленное
		
        computed: {
        	antennaData1: {
        	    get () {
            	    //console.log(this.$store.state.antenna.object[1]);
        	      return this.$store.state.antenna.object;
        	    },
        	    set (value) {
        	      //this.$store.commit('update', value)
        	    }
        	  },

          	  converterData1(){
          			return this.$store.getters['dnconverter/converterData1'];
                },
              converterData2(){
            	    return this.$store.getters['dnconverter/converterData2'];
                },

                amplifierData1(){
          			return this.$store.getters['amplifier/amplifierData1'];
                },
                amplifierData2(){
          			return this.$store.getters['amplifier/amplifierData2'];
                },

                matrixData1(){
          			return this.$store.getters['matrix/matrixData1'];
                },
                matrixData2(){
          			return this.$store.getters['matrix/matrixData2'];
                },
                

        	},


        	watch:{ ////////////

        	},
  
		  methods:{

			  cyclogram_switch_click(){
				  payload={
             			 'cyclogram':{'dialog':'open', 'clientid':app.client_id}
             			 };
				  saveDevice(store.state.cyclogram.cyclogramApi, 'POST', null, this.jwtToken, JSON.stringify(payload));
			  },

			  stopMonitoring:function(){
				    this.ws_switch=false;
					this.mon_indicator2 = "360 64 64";
					/////Тут надо учистить показания всех приборов, т.е. в сторе всё занулить
					store.commit('dnconverter/resetState');
					store.commit('amplifier/resetState');
				
			  },

              checkMonStatus:function(){/////////////////Отслеживание "кнопки" мониторинга ЗС
                  if(this.socket.readyState==1){ ///1 - ок, 3 - disconnected
                  	if(this.ws_switch==true) {

                  		    wsStatusControl(this.monitoringState, 'POST',  this.jwtToken, {"state":true, "type":"type"});
                      	
                      		this.mon_indicator2 = "0 64 64";
                      	}
                  	else {
                      	this.mon_indicator2 = "360 64 64"; //////////////Для новой кнопки справа
                      		wsStatusControl(this.monitoringState, 'POST',  this.jwtToken, {"state":false, "type":"gsMonitoring"});
                  		}
                  }
                  else {
                	  //this.ws_switch=false
                      //console.log(this.ws_switch);
                      
                      this.mon_indicator2= "360 64 64";
                      setTimeout(() => {
                          this.ws_switch = false;
                        }, 50);
                      alert('No connection to WS server');
                  }
              },

			  
					//////////////////////////Открытие полного диалога, присутствуещего на схеме
			  getAntenna:function (id){
					//console.log(type);
					getUsersF(this.antennaApi+'/'+id, 'POST',  app.openAntennaUI, this.jwtToken);
			  },

				/////////////Прям тут обновляем
			  getConverter:function(dev_num){
			        let WindowHelperClass = Vue.extend(window_helper_type2);
					let instance = new WindowHelperClass({
			            propsData: {'obj':store.state.dnconverter.object[dev_num]},
						//propsData: {'obj':store.state.antenna.object[1]},
								
			        });
					instance.initialize("conv"); ///////////Вызываем там создание объекта, получаем идентификатор
					instance.store = store;
			        let id=instance.getWindow_id(); ////////идентификатор окна
					let exists = document.getElementById(id);
					if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
      			        instance.zindexlocal = parseInt(this.zindex())+1;  
      					instance.$mount(); // pass nothing;
    					let payload = instance.initializeObject(converter_device_type, this); ///////////Строим переданный целевой объект

    					this.$refs.container.appendChild(instance.$el);
      					winid = instance.getWindow_id();
					}
			  },
			  ///////////////////////////////////Открытия окна матрицы
			  getMatrix:function(dev_num){
				  let WindowHelperClass = Vue.extend(window_helper_type2);
				  let instance = new WindowHelperClass({
			            propsData: {'obj':store.state.matrix.object[dev_num]},
						//propsData: {'obj':store.state.antenna.object[1]},
								
			        });
				  instance.initialize("matrix"); ///////////
				  instance.store = store;
			      let id=instance.getWindow_id(); ////////идентификатор окна
			      //console.log(id);
			      let exists = document.getElementById(id);
			      if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
    			        instance.zindexlocal = parseInt(this.zindex())+1;  
    					instance.$mount(); // pass nothing;
  					let payload = instance.initializeObject(matrix_device_type, this); ///////////Строим переданный целевой объект

  					this.$refs.container.appendChild(instance.$el);
    					winid = instance.getWindow_id();
  					}
			  },
			  ///////////////////////////////Сюда возвращаемся, когда загружена антенная система
			  openAntennaUI:function (Data){
				  //console.log(value);
				  let WindowHelperClass = Vue.extend(window_helper_type2);
					let instance = new WindowHelperClass({
			            propsData: {'obj':Data},
						//propsData: {'obj':store.state.antenna.object[1]},
								
			        });
					instance.initialize("ant"); ///////////Вызываем там создание объекта, получаем идентификатор
					instance.store = store;
			        let id=instance.getWindow_id(); ////////идентификатор окна
					let exists = document.getElementById(id);
					if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
    			        instance.zindexlocal = parseInt(this.zindex())+1;  
    					instance.$mount(); // pass nothing;
						let payload = instance.initializeObject(antenna_device_type, this); ///////////Строим переданный целевой объект
						payload.$on('updated', value => { //////////////////////////////И подписываем его на событие обновления
							 if(value.datatype=='antenna_device_type') {
								 saveDevice(this.updateApi+'/'+value.id, 'POST', app.deviceTryUpdate, this.jwtToken, value.data);
							 }
							 //getUsersF(this.ordersApi+'/'+value, 'GET', app.loadOrder, this.jwtToken);
  						});
						this.$refs.container.appendChild(instance.$el);
    					winid = instance.getWindow_id();
					}
			  },

			  zindex: function() {
				  var highest = 1;
				  var divs = document.getElementsByClassName(this.wincont)[0].children;
	              for (var i = 0; i < divs .length; i++)
	              {
	                  var zindexloc = divs[i].style.zIndex;
	                  if (zindexloc > highest) {
	                        highest = zindexloc;
	                  }
	              }
				//console.log(highest);
	            return highest;
	          },
			  
			    Authorise: function(){
				  this.jwtToken='',
				  getToken(this.authApi, 'POST', app.authRequestAnswer);
			  	},
			  	authRequestAnswer:function(answer){ ///////////Передается в функцию которая фетчит данные с сервера
				  this.jwtToken = answer;
				},

				//////////////////////////////Clients
			  	getUsers: function(){
			  		this.options = ['no data avilable'];
			  		getUsersF(this.usersApi, 'GET', app.updateVSelect, this.jwtToken);
			  	},
			  	updateVSelect:function(usersData){
					this.options = usersData;
				},

				///////////////////////////Orders
				getOrders: function(){
					getUsersF(this.ordersApi, 'GET', app.updateOrdSelect, this.jwtToken);
				},
				updateOrdSelect:function(ordersData){
					this.orders = ordersData;
				},

				//////////////////////Сюда прилетает результат по обновлению устройства
				deviceTryUpdate:function(data){
					//alert(data); ///////////alert модальный, всё стопорит
				},

				openCyclogramUI: function(data){/////////////////Открываем, закрываем окно циклограмм
					//console.log(store.state.cyclogram);
					
					if(data=="opening") {
          		  	let WindowHelperClass = Vue.extend(window_helper_type2);
     				  let instance = new WindowHelperClass({
     			            propsData: {'obj':{'id':''}},
     						//propsData: {'obj':store.state.antenna.object[1]},
     								
     			        });

     				  instance.initialize("cyclogram"); ///////////
     				  
     				  instance.store = store;
     			      let id='window_cyclogram_'; ////////идентификатор окна
     			      //console.log(store.state.cyclogram.list);
     			      //console.log(id);
     			      let exists = document.getElementById(id);
     			      //console.log(exists);
     			      if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
         			        instance.zindexlocal = parseInt(this.zindex())+1;  
         					instance.$mount(); // pass nothing;
       					let payload = instance.initializeObject(cyclogram_runner_type, this); ///////////Строим переданный целевой объект

       					this.$refs.container.appendChild(instance.$el);
       						//console.log(instance);
       						//console.log(instance.$el);
         					winid = 'window_cyclogram';
         					//store.commit('cyclogram/saveLink', instance);
       					}

				}
					if(data=="closing") {
						//if(store.state.cyclogram.instanceLink!==null && store.state.cyclogram.instanceLink!==undefined && store.state.cyclogram.instanceLink!==null){
					    // 	store.state.cyclogram.instanceLink.dispose();
					    // 	store.state.cyclogram.instanceLink=null;
						//}
					}
				},

				setSelected(value) { ///////////////////////////////////Выбор элемента в списке
					//getUsersF(this.usersApi+'/'+value.id, 'GET', app.loadUser, this.jwtToken);
					
					this.selectedValue = value;
				},

				setOrder(value){
					this.selectedOrder = value;
				},
				  
				loadUser:	function(Data){
					//////////Тут динамически создаем компонент. Теперь нужно подписаться на его событие (v-on:updated="saveUser2")
    					let WindowHelperClass = Vue.extend(window_helper_type);
    					let instance = new WindowHelperClass({
    			            propsData: {'obj':Data},
  
    			        });
    			        //console.log(instance);
    			        instance.initialize("user"); ///////////Вызываем там создание объекта, получаем идентификатор
    			        let id=instance.getWindow_id(); ////////идентификатор окна
    					let exists = document.getElementById(id);
    					if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
        			        instance.zindexlocal = parseInt(this.zindex())+1;  
        					instance.$mount(); // pass nothing;
    						let payload = instance.initializeObject(user_post_type); ///////////Строим переданный целевой объект
    						payload.$on('updated', value => { //////////////////////////////И подписываем его на событие обновления
      						  this.saveUser2(payload.$props); ////// ну раз это функция loadUser, то подписка на saveUser2
      						});
    						payload.$on('orderClicked', value =>{ /////////Подписка на клик по номеру заказа в шаблоне компонента пользователей
      							getUsersF(this.ordersApi+'/'+value, 'GET', app.loadOrder, this.jwtToken);
      	  	  				}),
    						
    						this.$refs.container.appendChild(instance.$el);
        					winid = instance.getWindow_id();
        					//console.log(winid);
        					//instance.makeDragable();
    					}
			
				},


				

				loadOrder: function(Data){
					let WindowHelperClass = Vue.extend(window_helper_type);
					let instance = new WindowHelperClass({
			            propsData: {'obj':Data},

			        });
			        //console.log(instance);
			        instance.initialize("order"); ///////////Вызываем там создание объекта, получаем идентификатор
			        let id=instance.getWindow_id(); ////////идентификатор окна
					let exists = document.getElementById(id);
					if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
    			        instance.zindexlocal = parseInt(this.zindex())+1;  
    					instance.$mount(); // pass nothing;
						let payload = instance.initializeObject(order_type); ///////////Строим переданный целевой объект
						payload.$on('updated', value => { //////////////////////////////И подписываем его на событие обновления
  						  this.saveOrder2(payload.$props); ////// ну раз это функция loadUser, то подписка на saveUser2
  						});
  						
						
						this.$refs.container.appendChild(instance.$el);
    					winid = instance.getWindow_id();
    					//console.log(winid);
    					//instance.makeDragable();
    					
    					//instanceLink
					}
				},
				
				//////////Старый вариант
				saveUser: function (el) {////////////Нажали на кнопку правки пользователя
					//////////////////////////////////////Так работает, но это спускание на уровень DOM/HTML
					elements = $(el).find("input");
					var jsonData = {};
					$.each( elements, function( index, value ) {
						if(value.name!='' & value.name!='undefined'){
							key = value.name;
							jsonData[key] = $(value).val();
						}
					});
					getUsersF(this.usersApi+'/'+this.selectedUser.id, 'POST',  null, this.jwtToken, JSON.stringify(jsonData));
				} ,
				saveUser2:function(el){
					getUsersF(this.usersApi+'/'+el.id, 'POST',  null, this.jwtToken, JSON.stringify(el));
				},
				/////////////////////////////////Сохранение параметров устройств


				addOnfly:function(){
					getUsersF(this.usersApi+'/'+this.selectedValue.id, 'GET', app.loadUser, this.jwtToken);
	
				}, 

				addOnflyOrder:function(){ /////////Запуск по кнопке на главной форме
					getUsersF(this.ordersApi+'/'+this.selectedOrder.id, 'GET', app.loadOrder, this.jwtToken);
	
				}, 
				
				socketInit:function(){
					//this.socket  = new WebSocket('ws://10.10.0.11:5000');
					//this.socket  = new WebSocket('ws://damp-forest-69158.herokuapp.com');
					this.socket  = new WebSocket('ws://10.10.0.16:8081');
					
					this.socket.onmessage = function(e) {
							app.parsewsmsg(e.data);
					    },
					this.socket.onclose = function(event) {
					    	  console.log("WebSocket is closed now.");
					    	     //  stopMonitoring
					    	  app.stopMonitoring();
					 },
					this.socket.onopen = function(el){
						//alert(app.client_id);	
						this.send(JSON.stringify({"clientid":app.client_id}));
					};
					 
					},
				parsewsmsg:function(data){
					json = JSON.parse(data);
					//console.log(this.show_ws_frame);
					//console.log(json);
					//console.log(json.livetag);
					
					//////////////////////////////////////Отображаем данные прилетевшие через WS
					if(this.show_ws_frame==true) {
						//if(json.DeviceParameters!=null && json.DeviceParameters!='undefined')
						 this.wsFrame=data;
						
					}



					/////////////////////Смотрим включен ли мониторинг по кадру с сервера
					if(json.state!==undefined && json.state!==null){
						//console.log(json.state);
						if(json.state.toLowerCase()=='off') {
						/////////////////Выключаем кнопку мониторинга
							this.stopMonitoring();
							
						}
						if(json.state.toLowerCase()=='on') {
							this.ws_switch=true;
							this.mon_indicator2 = "0 64 64";
						}
					}
					
					if(json.livetag!==undefined && json.livetag!==null) {
						$('#livetag').html(json.livetag);
					   app.mon_indicator="0 64 64"
					   app.livetag_lasttime = new Date;
						
						
					}
					//https://scrimba.com/learn/vuex/structure-and-manage-vuex-store-with-modules-cqKK4psq
					if(json.DeviceParameters!==undefined && json.DeviceParameters!==null){
						if(json.DeviceParameters.AntennaSystem!==undefined && json.DeviceParameters.AntennaSystem!==null){
							store.commit('antenna/update', json.DeviceParameters.AntennaSystem);
						}
						if(json.DeviceParameters.downConverterDeviceData!==undefined && json.DeviceParameters.downConverterDeviceData!==null){
							store.commit('dnconverter/update', json.DeviceParameters.downConverterDeviceData);
							//console.log(json.DeviceParameters.downConverterDeviceData);
						} 
						if(json.DeviceParameters.upConverterDeviceData!==undefined && json.DeviceParameters.upConverterDeviceData!==null){
							//store.commit('dnconverter/update', json.DeviceParameters.upConverterDeviceData);
						} 

						if(json.DeviceParameters.amplifierDeviceData!==undefined && json.DeviceParameters.amplifierDeviceData!==null){
							store.commit('amplifier/update', json.DeviceParameters.amplifierDeviceData);
							//console.log();
						} 

						if(json.DeviceParameters.matrixDeviceData!==undefined && json.DeviceParameters.matrixDeviceData!==null){
							store.commit('matrix/update', json.DeviceParameters.matrixDeviceData);
							//console.log();
						} 
					}
					if(json.cyclogram!==undefined && json.cyclogram!==null){///////////////Данные для окна циклограмм
						//console.log("calling cyclo mutating");
						store.commit('cyclogram/update', json.cyclogram)
						//console.log(json.cyclogram);
						app.openCyclogramUI("opening");
					}
					if(json.cyclogramstatus!==undefined && json.cyclogramstatus!==null){///////////////Данные для окна циклограмм
							//console.log('Статус прелетел (главный файл): ');	
							//console.log(json.cyclogramstatus);
							store.commit('cyclogram/updatestatus', json.cyclogramstatus)
							
					}
					if(json.cortex!=undefined && json.cortex!=null){
						//console.log('прилетели кортексы');
						store.commit('cortex/update', json.cortex);
					}
					
					if(json.configuration!==undefined && json.configuration!==null){/////////////Обработка списков и единиц измерения
						//console.log(json.configuration);

						/*
						 Object.entries(json.configuration).forEach(function callback(obj, index) {
							 console.log(obj[0]);
							 
							 Object.entries(obj[1]).forEach(function callback(field, i) {
									 	console.log(field);
								 });
						  });
						  */
						  /*
						  if(json.configuration.DownConverter!==undefined && json.configuration.DownConverter!==null){
							  store.commit('dnconverter/updateConf', json.configuration.DownConverter);
							  
						  }
						  */
						 
					}
					
					
					},	
					////////////////Метод для излучения события при поступлении данных по websocket
					 ws_received: function(frame){
				    	  ////////Эмитим headerTxt
				    	  this.$emit('wsreceived', {'frame': frame}); 
				      },

					 startmon: function(){

						$('#log').html(''); 
						this.socketInit();
					},



					shutdownmon:function(){
						this.socket.send("control:::shutdown" );
					}
				
			  },
			  created(){
				  //this.socket  = new WebSocket('ws://localhost:8080');

				  this.socketInit();
				  setInterval(() => {
					  let moment = new Date();
					  let milsecdif = (moment.getTime() - app.livetag_lasttime.getTime());
					  //console.log(milsecdif);
					  if(milsecdif>3000){
						  app.mon_indicator="360 64 64"; ///////////Индикатор мониторинга
						  ////////////////// тут еще нужно вырубить значок мониторинга и привести все поля
						  this.stopMonitoring();
					   }
					}, 4000)
		      },
		     
		      
		      
		})
	

	
	app.jwtToken = getCookie("jwttoken");


    
	


});


function startmon(){

	$('#log').html(''); 
	socketInit();
}



function shutdownmon(){
	socket.send("control:::shutdown" );
}

$( window ).resize(function() {
	checkScale(this);
	});
$( window ).load (function() {
	checkScale(this);
});

function checkScale(win){
	 console.log(win.innerWidth );
	  let scale = win.innerWidth/1920;
	  $('.scalabletable').css('transform', 'scale('+scale+')')
}
	
</script>

<!-- resizible
https://codepen.io/jkasun/pen/QrLjXP
 -->



