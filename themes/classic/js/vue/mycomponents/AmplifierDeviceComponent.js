var amplifier_device_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	domReference:null,
	   	    	InputFrequency:0,
	   	    	AttenuationValue:0,
	   	    	OutputFrequency:0,
    	    }
    	    },
	 
      //locFontSize: 1,
     //props: ['id', 'title', 'workmode', 'angle', 'azimut', 'signal_lvl', 'scanner','mode', 'modes'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
    props:{
    	ampParametrs:{
    		type:Object,
    		default:{}
                
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
    	
    	num(){
       		if (this.mode=='flat'){//////////////////////////Выриант на схеме
       			if(this.convParametrs.id==20) return 1;
    			else return 2;
    		}
       		else{////////////////////////////////////////////Вариант в окне
       			if(this.id==20) return 1;
    			else return 2;
       		}
    	},

       	
       	
     },
     updated(){
    	 //console.log(this.deviceParametrs);
    	 //console.log('updated');
    	 //console.log('mode: '+this.mode);
    	 if(this.mode!='flat'){
    		 //console.log(this.$store);
    		 //this.InputFrequency= this.store.state.dnconverter.object[this.id].deviceParameters.InputFrequency.valueParameter;
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
    		  //console.log(this.id);
    		  //console.log(this.store.state.dnconverter.object);
    		  
    		  $updobj = {
    				  inpfreq:this.InputFrequency,
    				  atten:this.AttenuationValue,
    				  outfreq:this.OutputFrequency,
    				  
    		  };
    		  
    		  
    		  this.store.commit('amlifier/updatebyId',{obj:$updobj, id:this.id});
    	  },
    	  
    	  testCont(){
    		 
    	/////////Для встроенного в HTML код используем this.$store. Для нарезанного this.store.
    		  
    	  },
    	  

	      dispose() {
			  this.$el.parentNode.removeChild(this.$el);
    		  this.$destroy();
    		  return false;	
    	  },
    	  stop_timers(){
    		  //////////////////Вызывается в парент
    	  },
    	  start_timers(domReference){
    		  this.domReference = domReference;
    		  if(this.mode!='flat') {
        		  //this.timer = setInterval(() => this.tick(), 1000);
        		  //if(this.mode!='flat')this.display_c();
        	  }  
    	  },

    	  


      },
      mounted(){
    	  ////////Эмитим headerTxt
    	 //console.log('mounted mode: '+this.mode);
    	  
    	  if(this.mode!='flat') {
    		  //this.InputFrequency= this.store.state.dnconverter.object[this.id].deviceParameters.InputFrequency.valueParameter;
    		  //this.AttenuationValue= this.store.state.dnconverter.object[this.id].deviceParameters.AttenuationValue.valueParameter;
    		  //this.OutputFrequency= this.store.state.dnconverter.object[this.id].deviceParameters.OutputFrequency.valueParameter;
    		  this.$emit('loaded', {'headerTxt': this.title+' ('+this.id+')'}); 
    	  }
    	  
    	  
      },
      beforeDestroy(){
    	//clearInterval(this.timer);  
    	//clearInterval(this.mytime);  
      },
      
	  template: `
	  <div v-if="mode=='flat'"  align="center">
		  <div class="compSchema ampSchema"  @click="open" align="center">

				<table width="auto" border="0" cellspacing="1" cellpadding="1">
				  <tbody>
				  <tr bgcolor="#FFFFFF">
				  	<td colspan="2" align="center"><div class="devstate">Mode: {{this.$store.state.amplifier.object.configuration.devstate.value_list[ampParametrs.deviceParameters.devstate.valueParameter]}}</div></td>
				  </tr>
				 <tr bgcolor="#FFFFFF"><td colspan="2" align="center">&nbsp;</td></tr>
				  <tr bgcolor="#FFFFFF">
				    <td><div class="row">
				    <div class="p_name">Мощность (Вт): </div>
				    <div class="p_value">{{ampParametrs.deviceParameters.power_rf.valueParameter}} &nbsp;</div>
				</div></td>
				    <td><div class="row">
				      <div class="p_name"> Канал: </div>
				      <div class="p_value" >{{ampParametrs.deviceParameters.channelnumber.valueParameter}} </div>
				    </div></td>
				    </tr>
				  <tr bgcolor="#FFFFFF">
				    <td><div class="row">
				    <div class="p_name">ALC, Вт</div>
				    <div class="p_value" >{{ampParametrs.deviceParameters.alcpower.valueParameter}} </div>
				</div>
				
				<div class="row">
				    <div class="p_name">  t° на выходе: </div>
				    <div class="p_value">{{ampParametrs.deviceParameters.outlet_temperature.valueParameter}}</div>
				</div>
				
				<div class="row">
				    <div class="p_name">  t° на входе: </div>
		  <div class="p_value" >{{ampParametrs.deviceParameters.inlet_temperature.valueParameter}}</div>
				</div>
				</td>
				    <td>  <div class="eg">
				      
				      <a class="polygon r3 gradient_grey block_shadow" id="device_90">
				        <b class="trapezoid aa"><span>УМ1</span></b>
				        </a>
				      
				    </div></td>
				    </tr>
				  <tr bgcolor="#FFFFFF">
				    <td>&nbsp;</td>
				    <td>&nbsp;</td>
				    </tr>
				</tbody></table>
		 
		  </div>
	  </div>
	  <div v-else><form><div class="formcontents">
		  	<h3>{{title}}</h3>
          


 </form></div>

          `
	};	