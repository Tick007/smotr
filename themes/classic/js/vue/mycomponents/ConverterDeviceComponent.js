var converter_device_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
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
    	convParametrs:{
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

       	inpfreqCompare(){
    		//console.log(this.num);
    		return this.InputFrequency!=this.store.state.dnconverter.object[this.num].deviceParameters.InputFrequency.valueParameter;
    	},
    	outfreqCompare(){
    		return this.OutputFrequency!=this.store.state.dnconverter.object[this.num].deviceParameters.OutputFrequency.valueParameter;
    	},
    	attenCompare(){
    		return this.AttenuationValue!=this.store.state.dnconverter.object[this.num].deviceParameters.AttenuationValue.valueParameter;
    	}
       	
     },
     updated(){
    	 //console.log(this.convParametrs);
    	 //console.log(this.$store.state);
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
    		  
    		  
    		  this.store.commit('dnconverter/updatebyId',{obj:$updobj, id:this.id});
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
    	  //console.log(this.id);
    	  
    	  if(this.mode!='flat') {
    		  this.InputFrequency= this.store.state.dnconverter.object[this.num].deviceParameters.InputFrequency.valueParameter;
    		  this.AttenuationValue= this.store.state.dnconverter.object[this.num].deviceParameters.AttenuationValue.valueParameter;
    		  this.OutputFrequency= this.store.state.dnconverter.object[this.num].deviceParameters.OutputFrequency.valueParameter;
    		  this.$emit('loaded', {'headerTxt': this.title+' ('+this.id+')'}); 
    	  }
    	  
    	  
      },
      beforeDestroy(){
    	//clearInterval(this.timer);  
    	//clearInterval(this.mytime);  
      },
      
	  template: `
	  <div v-if="mode=='flat'"  align="center">
		  <div class="compSchema conventerSchema"  @click="open" align="center">
<table width="220" border="0" cellspacing="1" cellpadding="1">
				  <tbody>
				  <tr bgcolor="#FFFFFF">
				    <td align="left" colspan="3"><div class="row">
				      <div class="p_name">{{convParametrs.deviceParameters.InputFrequency.nameParameter}}: </div>
				      <div class="p_value">{{convParametrs.deviceParameters.InputFrequency.valueParameter}} </div><span>(Гц)</span>
				    </div></td>
				  </tr>
		  		<tr bgcolor="#FFFFFF">
				   <td>
		  				 <div class="p_name">Ослаб.(дБ):</div>
				      <div class="p_value" align="center">{{convParametrs.deviceParameters.AttenuationValue.valueParameter}}</div>
				   </td>
				   <td align="center"> <div class="conv gradient_grey block_shadow" id="device_10">
				  <span>DN{{num}} ({{convParametrs.id}})</span></div></td>
				  <td>
		  			<div class="p_name">Сопр. (Ом):</div>
				      <div class="p_value" align="center">
				      {{this.$store.state.dnconverter.object.configuration.ImpedanceValue.value_list[convParametrs.deviceParameters.ImpedanceValue.valueParameter]}}
				      </div>
				  </td>
				  </tr>
				  </tr>
				  <tr bgcolor="#FFFFFF">
				    <td align="left" colspan="3"><div class="row">
				     <div class="p_name">{{convParametrs.deviceParameters.OutputFrequency.nameParameter}}: </div>
				      <div class="p_value">
				      {{this.$store.state.dnconverter.object.configuration.OutputFrequency.value_list[OutputFrequency]}}
				      </div><span>(Гц)</span>
				    </div></td>
				  </tr>
				 </table>

		  </div>
	  </div>
	  <div v-else><form><div class="formcontents">
		  	<h3>{{title}}</h3>
          
 <div><span  class="pname">Вх. частота:</span> 
<input v-model="InputFrequency" v-bind:class="{ notequal: inpfreqCompare}" >
</div>
<br>
 <div><span  class="pname">Вых. частота:</span> 
<select v-model="OutputFrequency" v-bind:class="{ notequal: outfreqCompare}">
	          <option v-for="(item, index) in this.store.state.dnconverter.object[this.num].deviceParameters.OutputFrequency.val_list" v-bind:value="index">
		      {{ item }}
		    </option>
          </select>
</div>
<br>
<div><span  class="pname">Ослабление: </span>
<input v-model="AttenuationValue" v-bind:class="{ notequal: attenCompare}" >
</div><br>
<hr>
<button type="button" @click="updateStore">Сохранить через store</button>
		   <br><br>
</div>

 </form></div>

          `
	};	