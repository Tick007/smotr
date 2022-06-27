
<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/select/vue-select.js', CClientScript::POS_HEAD);
$clientScript->registerCssFile('/js/vue/select/vue-select.css?v=' . rand());
$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/userpostcomponent.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->scriptMap = array(

    // 'jquery.js'=>false,

    /*
 * import VueSelect from './components/Select.vue'
 * import mixins from './mixins/index'
 *
 * export default VueSelect
 * export { VueSelect, mixins }
 */
);

?>
<style>
.mydiv {
	position: absolute;
	z-index: 9;
	background-color: #f1f1f1;
	border: 1px solid #d3d3d3;
	text-align: center;
	
	-webkit-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
    -moz-box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
    box-shadow: 10px 10px 5px 0px rgba(0,0,0,0.75);
}

.mydivheader {
	padding: 10px;
	cursor: move;
	/*z-index: 10;*/
	background-color: #2196F3;
	color: #fff;
}

.formcontents{
    margin:10px;
}

</style>


<div id="vuetable">



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
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>

	</table>
	<br> <br>
	<hr>
	<div ref="container" class="wincont">
		<!--<user_post v-bind="selectedUser"  v-on:user-save="saveUser"  v-on:updated="saveUser2"></user-post>-->
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



$( document ).ready(function() {

	Vue.component('v-select', VueSelect.VueSelect);
/*
	Vue.component('user_post' , {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
    	    	locFontSize: 1

    	    }

    	},
      
      props: ['id', 'firstName', 'lastName', 'salary', 'position'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
      methods: {////////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  update() {
        	  	//this.$root.$emit('send', 'Из потомка')
    	    	this.$emit('updated', JSON.stringify(this.$props));
    	    	return false;
    	    },
    	 
      },

	  template: `
		<?php
//$this->renderPartial('templates/userTemplate');
?>
          `
	});	
*/
/*
	var user_post_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	zindexlocal:1
    	    }
    	    },
	    
      //locFontSize: 1,
      props: ['id', 'firstName', 'lastName', 'salary', 'position'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
      methods: {////////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  update() {
    	    	this.$emit('updated', JSON.stringify(this.$props)); /////////работает кагда подписка через HTML
    	    	return false;
    	    },
    	    updateZ(el){ //////////////Обновление Z-index. Здесь нужно локуальный индекс увеличить на глобальный + 1
        	    //this.$emit('updatedZ', this.zindexlocal );/////////Или передовать в главное приложение посредством события
        	    /////////////или как у юрца шерстить документ в поисках максимального z-index

    	    	var divs = document.getElementsByClassName('wincont')[0].children;
    	    	var highest = 0;
                for (var i = 0; i < divs .length; i++)
                {
                	var zindex = parseInt(divs[i].style.zIndex);
                    if (zindex > highest) {
                        highest = parseInt(zindex);
                    }
                }
				console.log(highest);
				if(this.zindexlocal!=highest){
					highest++;
					this.zindexlocal = highest;
				}
        	    
    	    },
	      dispose() {
			this.$destroy();
			this.$el.parentNode.removeChild(this.$el);
			return false;	
    	  }
      },
	  template: `
		<?php
//$this->renderPartial('templates/userTemplate');
?>
          `
	};	
	*/



	
	var app = new Vue({
		  el: '#vuetable',
		  data: {
		  	options: [
    	        'no data avilable'
		    ],
		    //Лёха
		    /*
		    authApi: 'http://10.10.0.99:7070/api/v1/auth/login',/////////Получение токена
		    usersApi:'http://10.10.0.99:7070/api/v1/developers', ///////Получене списка/пользователя
		    */
		    authApi: 'http://yii-site/nomenklatura/getjwt',
		    usersApi:'http://yii-site/nomenklatura/jwtusers',
		    //userUpd: 'http://10.10.0.99:7070/api/v1/developers',/////////Для апдейта пользователей
		    jwtToken:'',
		    //zindex:1, ////////////z-index 
		    selectedUser:null,
		    selectedValue:null, ////////////транзитная переменная
		    //zindex: 10, //////////Глобальный индекс высоты для ноывх окон
		    wincont:'wincont',
		  },

		  methods:{

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
				console.log(highest);
	            return highest;
	          },
			  
			    Authorise: function(){
				  this.jwtToken='',
				  getToken(this.authApi, 'POST', app.authRequestAnswer);
			  	},
			  	authRequestAnswer:function(answer){ ///////////Передается в функцию которая фетчит данные с сервера
				  this.jwtToken = answer;
				},
				
			  	getUsers: function(){
			  		this.options = [
		    	        'no data avilable'];
			  		getUsersF(this.usersApi, 'GET', app.updateVSelect, this.jwtToken);
			  	},
			  	updateVSelect:function(usersData){
					this.options = usersData;
				},

				setSelected(value) { ///////////////////////////////////Выбор элемента в списке
					//getUsersF(this.usersApi+'/'+value.id, 'GET', app.loadUser, this.jwtToken);
					this.selectedValue = value;
				  },
				  
				loadUser:	function(userData){
					//////////Тут динамически создаем компонент. Теперь нужно подписаться на его событие (v-on:updated="saveUser2")
					//console.log(userData);
					//this.selectedUser = userData;
					
					////////////////Сначала смотрм есть ли уже такое div
					var id='userWindow_'+userData.id;
				    var exists = document.getElementById(id);

					if(exists==null || exists=='undefined'){
    					var ComponentClass = Vue.extend(user_post_type);
    					const instance = new ComponentClass({
    			            propsData: userData,
  
    			        });
    					instance.zindexlocal = parseInt(this.zindex())+1;  //тут нужно сделать calculated property с функционалом вычисления макс z-index в заданом контейнере
    					/////////////////////////////// без этого у всех одно значение
    					instance.$on('updated', value => {
    						  this.saveUser2(instance.$props);
    						});
						instance.$on('updatedZ', value =>{ ///////////перенес в компонент
							/*
							if(value!=this.zindex){
								this.zindex++;   
								instance.zindexlocal = this.zindex;
							}
							*/
							});
						instance.$mount(); // pass nothing;
    					this.$refs.container.appendChild(instance.$el);
    					dragElement(document.getElementById("userWindow_"+userData.id));
					}
					else {
						 document.getElementById(id).focus();
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

				addOnfly:function(){
					getUsersF(this.usersApi+'/'+this.selectedValue.id, 'GET', app.loadUser, this.jwtToken);
	
				}
				
			  },
		})
	

	
	app.jwtToken = getCookie("jwttoken");


    
	


});

</script>

<!-- resizible
https://codepen.io/jkasun/pen/QrLjXP
 -->

<!--drugabble-->
<script>
function dragElement(elmnt) {
	//console.log(elmnt);
	  var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
	  if (document.getElementById(elmnt.id + "header")) {
	    // if present, the header is where you move the DIV from:
	    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
	  } else {
	    // otherwise, move the DIV from anywhere inside the DIV:
	    elmnt.onmousedown = dragMouseDown;
	  }

	  function dragMouseDown(e) {
	    e = e || window.event;
	    e.preventDefault();
	    // get the mouse cursor position at startup:
	    pos3 = e.clientX;
	    pos4 = e.clientY;
	    document.onmouseup = closeDragElement;
	    // call a function whenever the cursor moves:
	    document.onmousemove = elementDrag;
	  }

	  function elementDrag(e) {
	    e = e || window.event;
	    e.preventDefault();
	    // calculate the new cursor position:
	    pos1 = pos3 - e.clientX;
	    pos2 = pos4 - e.clientY;
	    pos3 = e.clientX;
	    pos4 = e.clientY;
	    // set the element's new position:
	    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
	    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
	  }

	  function closeDragElement() {
	    // stop moving when mouse button is released:
	    document.onmouseup = null;
	    document.onmousemove = null;
	  }
	}
</script>
