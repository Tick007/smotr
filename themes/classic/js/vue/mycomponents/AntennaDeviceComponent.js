var antenna_device_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    //	vueapp:null
    	    	timer:null,
    	    	mytime:null,
    	    	checkval:false,
    	    	domReference:null,
    	    	workmode:0,
    	    	angle:0,
    	    	azimut:0,
  
    	    }
    	    },
	 
      //locFontSize: 1,
     //props: ['id', 'title', 'workmode', 'angle', 'azimut', 'signal_lvl', 'scanner','mode', 'modes'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
    props:{
    	antennaParametrs:{
    		type:Object,
    		default:null
    	},
    	mode:{
    		type:String,
    		default:""
    	},
    	title:{
    		type:String,
			default:"title"
    	},
    	id:{
    		type:Number,
			default:"1"
    	},
    	
    	
	},

    computed: {
    	 clock_id(){
    		 return 'ct_q_'+this.id;
    	 },
    workmodeCompare(){
    		 
    	return this.workmode!=this.store.state.antenna.object.workmode.valueParameter;

    	},
    angleCompare(){
    		return this.angle!=this.store.state.antenna.object.angle.valueParameter;
    	},
    	azimutCompare(){
    		return this.azimut!=this.store.state.antenna.object.azimut.valueParameter;
    	}
     },
     updated(){
    	 //console.log(this.mode);
    	 //console.log(this.antennaParametrs);
    	 if(this.workmode==0 && this.mode!='flat'){
    		 this.angle= this.store.state.antenna.object.angle.valueParameter;
   		    this.azimut= this.store.state.antenna.object.azimut.valueParameter;
    	 }
     },
     methods: {////////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  open() {
    	    	this.$emit('open', JSON.stringify(this.$props)); /////////работает кагда подписка через HTML
    	    	//console.log('open');
    	    	return false;
    	    },
    	  update() { //////////////Для кнопки было   <button type="button" @click="update">Сохранить</button><br>
    	    	/*
    	    	this.$emit('updated', {
    	    	    datatype : "antenna_device_type",
    	    	    data : JSON.stringify(this.$props),
    	    	    id: this.id
    	    	} ); /////////работает кагда подписка через HTML ????
    	    	return false;
    	    	*/
    	  },
    	  updateStore() {/////////////Метод вызывания мутауции в store
    		  $updobj = {
    				  workmode:this.workmode,
    				  angle:this.angle,
    				  azimut:this.azimut,
    				  id:this.id
    		  };
    		  this.store.commit('updatebyId',{obj:$updobj, id:this.id});
    	  },
    	  start_anim(){
    		  this.checkval = !this.checkval;
    	  },
	      dispose() {
			  this.$el.parentNode.removeChild(this.$el);
    		  this.$destroy();
    		  return false;	
    	  },
    	  stop_timers(){
    		  clearInterval(this.timer);  
    	      clearInterval(this.mytime);   
    	  },
    	  start_timers(domReference){
    		  this.domReference = domReference;
    		  if(this.mode!='flat') {
        		  this.timer = setInterval(() => this.tick(), 1000);
        		  if(this.mode!='flat')this.display_c();
        	  }  
    	  },
    	  tick(){
    		  //console.log(this.id+' I alive');
    	  },
    	  
    	  display_c(){
				var refresh=1000; // Refresh rate in milli seconds
				this.mytime=setTimeout(() =>this.display_ct(),refresh)
				},
		  display_ct() {
					
				var x = new Date()
				//var x1=x.toUTCString();// changing the display to UTC string
				x1 =   x.getHours( )+ ":" +  x.getMinutes() + ":" +  x.getSeconds();
				if(document.getElementById(this.clock_id)!=null) document.getElementById(this.clock_id).innerHTML = x1;
				else{
					if(this.domReference.document!=null){
						this.domReference.document.getElementById(this.clock_id).innerHTML = x1; /////////Если в отдельном окне
					}
				}
				tt=this.display_c();
				
		   },
    	  
    	  ///////////////////////////////Это работает когда имеется рабочая подписка на событие ws_received в главном коде
    	  /*
    	  dataupdate(frame){
    	    	 newdata = frame.frame[this.id];
    		   //newdata = this.store.state.antenna.object[this.id]
    	    	 if(newdata.angle!='undefined' & newdata.angle!=null){
    	    		 this.angle = newdata.angle;
    	    	 }
    	    	 if(newdata.signal_lvl!='undefined' & newdata.signal_lvl!=null){
    	    		 this.signal_lvl = newdata.signal_lvl;
    	    	 }
    	    	 if(newdata.azimut!='undefined' & newdata.azimut!=null){
    	    		 this.azimut = newdata.azimut;
    	    	 }
    	      },
    	      */
      },
      mounted(){
    	  ////////Эмитим headerTxt
    	 
    	  if(this.mode!='flat') {
    		  this.workmode= this.store.state.antenna.object.workmode.valueParameter;
    		  this.angle= this.store.state.antenna.object.angle.valueParameter;
    		  this.azimut= this.store.state.antenna.object.azimut.valueParameter;
    		  this.timer = setInterval(() => this.tick(), 1000);
    		  if(this.mode!='flat')this.display_c();
    	  }
    	  this.$emit('loaded', {'headerTxt': this.title+' ('+this.id+')'}); 
    	  
      },
      beforeDestroy(){
    	clearInterval(this.timer);  
    	clearInterval(this.mytime);  
      },
      
	  template: `
	  <div v-if="mode=='flat'">
		  <div class="compSchema"  @click="open">
		  <h3>
		  {{antennaParametrs.title}}
		  </h3>
		  
		  <div>Режим: {{antennaParametrs.workmode.val_list[antennaParametrs.workmode.valueParameter]}}</div>
		  <div>Угол места: {{antennaParametrs.angle.valueParameter}}</div>
		  <div>Азимут: {{antennaParametrs.azimut.valueParameter}}</div>
		  <div>Уровень сигнала: {{antennaParametrs.signal_lvl.valueParameter}}</div>
		  </div>
	  </div>
	  <div v-else>
<form><div class="formcontents">
         <h3>{{title}}</h3>
          <div id="app" class="clock-container" :class="{'rotate_clock':checkval}">
				<div id="rotate-background" class="rotate-background" 
					style="transform: translate3d(0px, 0px, 0px) rotate(282.996deg);"></div>
				<div class="background-clock">
					<div class="time time-back">00:00:00</div>
					<div class="time"><span v-bind:id="clock_id"></span></div>
				</div>
			</div><label>Запуск анимации</label>
         <input type="checkbox" id="checkbox" v-model="checkval"><br><br>
          Режим:<br>
		  <select v-model="workmode" v-bind:class="{ notequal: workmodeCompare}">
	          <option v-for="(item, index) in this.store.state.antenna.object.workmode.val_list" v-bind:value="index">
		      {{ item }}
		    </option>
          </select>
		  <br>
		  <!-- при работе со стор обращаемся через this.store.state.antenna.object[this.id] напрямую к стор-->
		  <div>Угол места: 
		  <input v-model="angle" :disabled="this.store.state.antenna.object.workmode.valueParameter==0"  placeholder="{{this.store.state.antenna.object.angle.nameParameter}}" v-bind:class="{ notequal: angleCompare}">
		  </div>
		  <div>Азимут: 
<input v-model="azimut"  :disabled="this.store.state.antenna.object.workmode.valueParameter==0"   placeholder="{{this.store.state.antenna.object.azimut.nameParameter}}" v-bind:class="{ notequal: azimutCompare}">
</div>
		  <div>Уровень сигнала: {{this.store.state.antenna.object.signal_lvl.valueParameter}}</div>
		  
		   <button type="button" @click="updateStore">Сохранить через store</button>
		   <br><br>
		   <button type="button" @click="start_anim">Старт анимации</button>
		   <br>
		   <!--<button_type  @btnClick="start_anim">-->
          </div>



 </form></div>

          `
	};	