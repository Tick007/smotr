https://codesandbox.io/s/vue-template-r11zy?fontsize=14&file=/src/components/WindowPortal.vue:1396-1449
var window_helper_type2 =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	zindexlocal:1
    	    }
    	    },
	    
      //locFontSize: 1,
      props: ['obj', 'window_id', 'instance', 'headerTxt', 'footerTxt'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
      methods: {////////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  	initialize(type){ ///////////////Самоинициализация
    	  		if(this.obj.id!=null || this.obj.id!=undefined) objid = this.obj.id;
    	  		else {
    	  			objid = Math.floor(Math.random() * 999999999);
    	  		}
    	  		this.window_id = type+'_' + objid;
    	  		
    	  	},
    	  	initializeObject(objType, vueapp){////////////Инициализация переданного объекта
    	  		var ComponentClass = Vue.extend(objType);
    	  		
    	  		//console.log(this.obj);
				this.instance = new ComponentClass({
		            propsData: this.obj, //////////Впринципе чисто для титле теперь
		        });
				
//				//console.log(this.instance);
				
				this.instance.store = this.store;
				this.instance.$on('loaded', value =>{ ////////Подписываемся на событие компонента loaded
						if(value.headerTxt!=null && value.headerTxt!=undefined){
							this.headerTxt = value.headerTxt;
						}
	  				});
	  				
				if(vueapp!='undefined' & vueapp!=null) vueapp.$on('wsreceived', value=>{
					  this.instance.dataupdate(value);
		    	    });
				vueapp=null;
				
	  			this.instance.$mount(); // pass nothing;
	  			this.$refs.objcontainer.appendChild(this.instance.$el);
	  			
				return this.instance;
    	  	},
    	  	getWindow_id(){
    	  		return 'window_'+ this.window_id;
    	  	},
    	    updateZ(el){ //////////////Обновление Z-index. Здесь нужно локуальный индекс увеличить на глобальный + 1
        	    //this.$emit('updatedZ', this.zindexlocal );/////////Или передовать в главное приложение посредством события
        	    /////////////или как у юрца шерстить документ в поисках максимального z-index

    	    	var divs = document.getElementsByClassName('wincont')[0].children;
    	    	var highest = 0;
                for (var i = 0; i < divs.length; i++)
                {
                    var zindex = parseInt(divs[i].style.zIndex);
                    if (zindex > highest) {
                        highest = parseInt(zindex);
                    }
                }
				//console.log('h= '+highest);
				if(this.zindexlocal<highest){
					highest++;
					this.zindexlocal = highest;
				}
        	    
    	    },
    	   
	      dispose() {
    	    	///////////////////////Сначала удаляем объект
    	    this.instance.$el.parentNode.removeChild(this.instance.$el);
    	    this.instance.$destroy();
    	    this.instance=null;
    	    
    	   // console.log(instance);
    	    ////////////////////////Потом удаляем окно
			this.$destroy();
			this.$el.parentNode.removeChild(this.$el);
			return false;	
    	  },
    	  ////////////////////Метод для открытия объекта в новом броузерном окне
    	  newWindow(width=600,height=300, left=-1000, top=500) {
    		let windowObjectReference;
  	  		let windowFeatures = "menubar=no,location=no,resizable=no,left="+left+",top="+top+", width="+width+",height="+height+",scrollbars=yes,status=yes,toolbar=no,controls=no ";
  	  		
  	  		//console.log(this.$el);
  	  		//console.log(this);
    		this.$el.parentNode.removeChild(this.$el);////////////Выпилили HTML из DOM
    		
    		////При существующем окне, оно активируется и тут же его закрываем
  	  		windowObjectReference = window.open("", this.getWindow_id(), windowFeatures);
    		//windowObjectReference = window.open("", "testframe", windowFeatures);
  	  		windowObjectReference.close();
  	  		
  	  		////////////Нарезаем новое окно броузера
  	  		windowObjectReference = window.open("about:blank", this.getWindow_id(), windowFeatures);
  	  		//windowObjectReference = window.open("about:blank", "testframe");
  	  		////////////style
  	  		let headnode = document.createElement("link"); 
  	  	    headnode.setAttribute("rel", "stylesheet");
  	  	    headnode.setAttribute("type", "text/css");
  	  		headnode.setAttribute("href", "http://smotr/themes/classic/css/vue_test3.css?v="+Math.random());
  	  		windowObjectReference.document.head.appendChild(headnode);
  	  		headnode = document.createElement("link"); 
	  	    headnode.setAttribute("rel", "stylesheet");
	  	    headnode.setAttribute("type", "text/css");
	  		headnode.setAttribute("href", "http://smotr/themes/classic/css/main.css?v="+Math.random());
	  		windowObjectReference.document.head.appendChild(headnode);
  	  		///////title
  	  		let titlenode = document.createElement("title"); 
  	  		titlenode.appendChild(document.createTextNode(this.headerTxt));
  	  		windowObjectReference.document.head.appendChild(titlenode);
  	  		//////////vue object
  	  		let node = document.createElement("div"); 
  	  		node.setAttribute("id", "parcont");
  	  		windowObjectReference.document.body.appendChild(node);
  	  		//console.log(this.instance);
  	  	
  	  	    this.instance.stop_timers();
  	  		windowObjectReference['instance'] = Object.assign({},this.instance);

  	  		//////////////Передаем инстанс в новое окно
  	  	    node.appendChild(windowObjectReference['instance'].$el);
  	  	    ///////////////////Запускаем там таймеры для часов(как пример), передавая туда ссылку на HTML самого себя,
  	  	    /////////////////// т.к. дочернее окно не знает что оно отдельное и продалжает искать элементы HTML в основном
  	  	    windowObjectReference['instance'].start_timers(windowObjectReference);
  	  	    
  	  	   
  	  	    //////////////////Закрытие окна броузера. Всё-таки попадает
  	  	    windowObjectReference.onbeforeunload = function(){ 
  	  	    	/////////Что нить можно делать
  	  	    	windowObjectReference['instance'].dispose();
  	  	    	windowObjectReference['instance']=null;
  	  	    }
  	  	    
  	  	    //this.instance.$destroy();
  	  	    //this.instance=null;
  	  	    this.$destroy();
  	  	    return false;	
  	  	    
    	  }
      },
      mounted(){
    	
    	//////////////Создаем и убиваем окно (если оно не было закрыто c  прошлого раза)
	  	let windowFeatures = "width=1,height=1,left=-9999,top=9999";
  		windowObjectReference = window.open("", this.getWindow_id(), windowFeatures);
  		windowObjectReference.close();
    	  
 	     let elmnt = this.$el;
 	     var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
 	     let h = elmnt.id + "header";
 	     let is = document.getElementById(h);

 	   	  if (document.getElementById(elmnt.id + "header")) {
 	   	    // if present, the header is where you move the DIV from:
 	   	    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;

 	   	  } else {
 	   	    // otherwise, move the DIV from anywhere inside the DIV:
 	   		//elmnt.children[0].onmousedown = dragMouseDown;
 	   		elmnt.children[0].addEventListener("mousedown", (event) => dragMouseDown(event, this));
 	   	  }

 	   	  function dragMouseDown(e, arg) {
 	   	    e = e || window.event;
 	   	    e.preventDefault();
 	   	    // get the mouse cursor position at startup:
 	   	    pos3 = e.clientX;
 	   	    pos4 = e.clientY;
 	   	   // document.onmouseup = closeDragElement;
 	   	   // console.log('подписка');
 	   	    //console.log(event);
 	   	    //arg.$el.addEventListener("mouseup", (event) => closeDragElement(event, arg));
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
 	   	   //arg.$el.removeEventListener('mouseup',  null);
 	   	    
 	   	    /////////////////////////////////////Игорь. Пробуем автоматом нарезать окно броузера
 	   	   /* 
 	   	   e = e || window.event;
	   	    e.preventDefault();
	   	    // get the mouse cursor position at startup:
	   	    pos3 = e.clientX;
	   	    pos4 = e.clientY;
   	    
 	   	    if (pos3>(window.innerWidth-200)) {
 	   	    	arg.newWindow(500, 300, pos3, pos4);
 	   	    }
 	   	    */
 	   	    
 	   	  }
 	    },
 	    
	  template: `
<div v-bind:id="getWindow_id()" class="mydiv"  @mousedown="updateZ($event.target)" :style="{'z-index':zindexlocal}">
  <div v-bind:id="getWindow_id()+'header'" class="mydivheader">{{headerTxt}}: свойства
  <button  type="button"  @click="dispose" style="float:right;" class="closeMe">x</button>
  <button  type="button"  @click="newWindow(600,300)" style="float:right;">«</button>
  </div>
<div class="objcontainer" ref="objcontainer">
 </div>
 </div>

          `
	};	