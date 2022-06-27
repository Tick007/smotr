var window_helper_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
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
				this.instance = new ComponentClass({
		            propsData: this.obj,
					//propsData:this.store.state.antenna.object[1],
		            //propsData: vueapp.antennaData1,
		            //vueapp: vueapp
		            
		        });
				this.instance.store = this.store;
				//this.instance.$props = this.store.state.antenna.object[1];
				
				//console.log(this.obj);
				//console.log(vueapp.antennaData1);
				
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
    	    this.instance.$destroy();
    	    this.instance.$el.parentNode.removeChild(this.instance.$el);
    	    	////////////////////////Потом удаляем окно
			this.$destroy();
			this.$el.parentNode.removeChild(this.$el);
			return false;	
    	  }
      },
      mounted(){
 	     let elmnt = this.$el;
 	     var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
 	     let h = elmnt.id + "header";
 	     let is = document.getElementById(h);

 	   	  if (document.getElementById(elmnt.id + "header")) {
 	   	    // if present, the header is where you move the DIV from:
 	   	    document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;

 	   	  } else {
 	   	    // otherwise, move the DIV from anywhere inside the DIV:
 	   		elmnt.children[0].onmousedown = dragMouseDown;
 	   	    //elmnt.onmousedown = dragMouseDown;
 	   		
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
 	    },
 	    
	  template: `
<div v-bind:id="getWindow_id()" class="mydiv"  @mousedown="updateZ($event.target)" :style="{'z-index':zindexlocal}">
  <div v-bind:id="getWindow_id()+'header'" class="mydivheader">{{headerTxt}}: свойства
  <button  type="button"  @click="dispose" style="float:right;">x</button>
  </div>
<div class="objcontainer" ref="objcontainer">
 </div>
 </div>

          `
	};	