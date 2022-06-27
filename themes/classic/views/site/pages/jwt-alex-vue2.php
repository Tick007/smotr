
<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/select/vue-select.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/vue/select/vue-select.css?v=' . rand());
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/userpostcomponent2.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/ordercomponent2.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/WindowHelperComponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/AntennaDeviceComponent.js?v=' . rand(), CClientScript::POS_HEAD);

// svg https://icons8.com/preloaders/
?>
<style>
@font-face {
  font-family: "LCD";
  src: local("LCD"),
  url(/themes/classic/fonts/lcd/lcd2.ttf) format("truetype");
}

body{
    font-size:12px;
}

.mydiv {
	position: absolute;
	z-index: 9;
	background-color: #f1f1f1;
	border: 1px solid #d3d3d3;
	text-align: center;
	-webkit-box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, 0.75);
	-moz-box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, 0.75);
	box-shadow: 10px 10px 5px 0px rgba(0, 0, 0, 0.75);
}

.mydivheader {
	padding: 10px;
	cursor: move;
	/*z-index: 10;*/
	background-color: #2196F3;
	color: #fff;
}

.formcontents {
	margin: 10px;
}

.orders_list {
	max-height: 200px;
	overflow-y: scroll;
}

.grid-container {
	display: grid;
	grid-template-columns: auto auto auto auto;
	background-color: #2196F3;
	padding: 10px;
}

.grid-item {
	background-color: rgba(255, 255, 255, 0.8);
	border: 1px solid rgba(0, 0, 0, 0.8);
	padding: 20px;
	font-size: 18px;
	text-align: center;
}

.antSchema {
	-webkit-box-shadow: 5px 5px 15px 5px #000000;
	box-shadow: 5px 5px 15px 5px #000000;
	background-color: #FFF;
	border: 5px groove #1C6EA4;
	border-radius: 10px;
	cursor: pointer;
	width: 75%;
	margin: 0 auto;
}

.clock-container{
    font-size:3em;
    font-family: LCD;
    
}

.clock-container {
  overflow: hidden;
  display: grid;
  width: 100%;
  height: 80px;
  font-family: LCD;
  color: #00d6ff;
  border-radius: 7px;
}

.background-clock {
  box-shadow: inset 0px 0px 8px -1px #47d0ee;
  z-index: 1;
  grid-row: 1;
  grid-column: 1;
  display: grid;
  background: #0d394e;
  border-radius: 7px;
  width: 97%;
  height: 95%;
  justify-self: center;
  align-self: center;
}

.rotate-background {

 animation-name: clock;
 animation-duration: 12s; /* or: Xms */
 animation-iteration-count: infinite ;
 animation-direction: normal; /* or: normal */
 animation-timing-function: linear; /* or: ease, ease-in, ease-in-out, linear, cubic-bezier(x1, y1, x2, y2) */
 animation-fill-mode: forwards; /* or: backwards, both, none */
 animation-delay: 0s; /* or: Xms */

  z-index: 1;
  grid-row: 1;
  grid-column: 1;
  width: 200%;
  height: 800%;
  justify-self: center;
  align-self: center;
  background:  linear-gradient(to right, rgba(13,57,78,1) 0%, rgba(13,57,78,1) 45%, rgba(0,213,255,0.98) 49%, rgba(0,213,255,1) 51%, rgba(13,57,78,0.98) 55%, rgba(13,57,78,0.96) 100%);
}
@-webkit-keyframes clock
{
0% {-webkit-transform:rotate(0deg);}
100% {-webkit-transform:rotate(360deg);}
}

.time-back{
    grid-row:1;
    grid-column:1;
    opacity:0.1;
    align-self:center;
    justify-self:center;

}
.time{
     grid-row:1;
     grid-column:1;
     color: #00d6ff;
     align-self:center;
     justify-self:center;

}
</style>


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
		</tr>
		<tr bgcolor="#FFFFFF">
			<td bgcolor="#FFFFFF" align="center">Запуск</td>
			<td bgcolor="#FFFFFF" align="center">Убить</td>
			<td bgcolor="#FFFFFF" colspan="6">&nbsp;</td>
			<td bgcolor="#FFFFFF" align="center"><div id="livetag"></div></td>
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
	<hr>
	<div ref="container" class="wincont">
		<!--<user_post v-bind="selectedUser"  v-on:user-save="saveUser"  v-on:updated="saveUser2"></user-post>-->
		<input type="checkbox" id="qqq" v-model="show_ws_frame"> <label
			for="qqq">Отображать кадр WS</label>
		<div class="ws_frame">
			<textarea rows="10" cols="100" v-model="wsFrame"
				placeholder="Кадр по websocket"></textarea>
		</div>
	</div>
	<hr>
	<div class="grid-container">
		<div class="grid-item">
			<antenna_device v-bind="antennaData2" mode="flat"
				v-on:open="getAntenna(2)"></antenna_device>
		</div>
		<div class="grid-item">2</div>
		<div class="grid-item">
			<antenna_device v-bind="antennaData1" mode="flat"
				v-on:open="getAntenna(1)"></antenna_device>
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
		<div class="grid-item">6</div>
		<div class="grid-item">7</div>
		<div class="grid-item">8</div>
		<div class="grid-item">9</div>
		<div class="grid-item">10</div>
		<div class="grid-item">11</div>
		<div class="grid-item">12</div>
	</div>
	<hr>
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



$( document ).ready(function() {


	display_c();
	

	Vue.component('v-select', VueSelect.VueSelect);

	Vue.component('user_post' , user_post_type);/////////////Создаем компонент для отображения на схеме

	Vue.component('antenna_device' , antenna_device_type);/////////////Создаем компонент для отображения на схеме

	//Vue.use(Vuex);
	
	var app = new Vue({
		  el: '#vuetable',
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
		    authApi: 'http://yii-site/nomenklatura/getjwt',
		    usersApi:'http://yii-site/nomenklatura/jwtusers',
		    ordersApi:'http://yii-site/nomenklatura/jwtorders',
		    antennaApi:'http://yii-site/nomenklatura/smotrantenna',
		    antennaUpdateApi:'http://yii-site/nomenklatura/smotrantennaupdate',
		    //userUpd: 'http://10.10.0.99:7070/api/v1/developers',/////////Для апдейта пользователей
		    jwtToken:'',
		    show_ws_frame:false,
    		wsFrame:'',
		    //zindex:1, ////////////z-index 
		    selectedUser:null,
		    selectedValue:null, ////////////транзитная переменная для clients
		    antennaData1:null,////////////Переменная куда будет грузиться объект антенны для схемы
		    antennaData2:null,////////////Переменная куда будет грузиться объект антенны для схемы
		   	selectedOrder:null,
		    //zindex: 10, //////////Глобальный индекс высоты для ноывх окон
		    wincont:'wincont',
		    mon_indicator:"360 64 64",
		    livetag_lasttime: new Date(),

		    
    
		  },

		  methods:{
					//////////////////////////Открытие полного диалога, присутствуещего на схеме
			  getAntenna:function (id){
					//console.log(type);
					getUsersF(this.antennaApi+'/'+id, 'POST',  app.openAntennaUI, this.jwtToken);
			  },
			  ///////////////////////////////Сюда возвращаемся, когда загружена антенная система
			  openAntennaUI:function (Data){
				  //console.log(value);
				  let WindowHelperClass = Vue.extend(window_helper_type);
					let instance = new WindowHelperClass({
			            propsData: {'obj':Data},
								
			        });
					instance.initialize("ant"); ///////////Вызываем там создание объекта, получаем идентификатор
			        let id=instance.getWindow_id(); ////////идентификатор окна
					let exists = document.getElementById(id);
					if(exists==null || exists=='undefined'){////////////Создаем окно только если нет с таким же идентификатором
    			        instance.zindexlocal = parseInt(this.zindex())+1;  
    					instance.$mount(); // pass nothing;
						let payload = instance.initializeObject(antenna_device_type, this); ///////////Строим переданный целевой объект
						payload.$on('updated', value => { //////////////////////////////И подписываем его на событие обновления
							 if(value.datatype=='antenna_device_type') {
								 saveDevice(this.antennaUpdateApi+'/'+value.id, 'POST', app.deviceTryUpdate, this.jwtToken, value.data);
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
					this.socket  = new WebSocket('ws://localhost:8080');
					this.socket.onmessage = function(e) {
							app.parsewsmsg(e.data);
					    }
					},
				parsewsmsg:function(data){
					json = JSON.parse(data);
					//console.log(json);
					//console.log(json.livetag);
					
					//////////////////////////////////////Отображаем данные прилетевшие через WS
					if(this.show_ws_frame==true) {
						this.wsFrame=data;
						
					}
					
					if(json.livetag!==undefined && json.livetag!==null) {
						$('#livetag').html(json.livetag);
					   app.mon_indicator="0 64 64"
					   app.livetag_lasttime = new Date;
						
						
					}
					if(json.device_params!==undefined && json.device_params!==null){
						$('#p1').html(json.device_params.param1);
						$('#p2').html(json.device_params.param2);
						$('#p3').html(json.device_params.param3);

						
					}
					if(json.DeviceParameters!==undefined && json.DeviceParameters!==null){
						//console.log(json);
						//console.log(json.DeviceParameters);
						//console.log(json.DeviceParameters.MSHUDeviceData1);
						//console.log(json.DeviceParameters.antennaDeviceData);

						if(json.DeviceParameters.antennaDeviceDataById!==undefined && json.DeviceParameters.antennaDeviceDataById!==null){
							//console.log(json);
							//console.log(json.DeviceParameters.antennaDeviceDataById);
							this.antennaData1 = json.DeviceParameters.antennaDeviceDataById[1];
							this.antennaData2 = json.DeviceParameters.antennaDeviceDataById[2];
							app.ws_received(json.DeviceParameters.antennaDeviceDataById);
						}
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
						  app.mon_indicator="360 64 64";
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

</script>

<!-- resizible
https://codepen.io/jkasun/pen/QrLjXP
 -->



