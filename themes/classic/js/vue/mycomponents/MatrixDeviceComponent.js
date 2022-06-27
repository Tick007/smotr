//https://mavo.io/demos/svgpath/
var matrix_device_type =  {// ///////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	domReference:null,
	   	    	svg_width:'200px',
	   	    	svg_width_big:'400px',
	   	    	svg_height:'200px',
	   	    	svg_height_big:'400px',
	   	    	output:[],
    	    	webcolors:['Black', 'Red', 'Green', 'Fuchsia', 'Teal', 'Yellow', 'Aqua', 'Orange'],
    	    	dar:{
    	    		'Output1':'0',
    	    		'Output2':'0', 
    	    		'Output3':'0', 
    	    		'Output4':'0', 
    	    		'Output5':'0', 
    	    		'Output6':'0', 
    	    		'Output7':'0', 
    	    		'Output8':'0'
    	    			},

	   	      	    }
    	    },
	 
    props:{
    	matrixParametrs:{
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
			default:1
    	},
    	
    	
	},

    computed: {
    	
    	num(){
       		if (this.mode=='flat'){// ////////////////////////Выриант на схеме
       			if(this.matrixParametrs.id==30) return 1;
    			else return 2;
    		}
       		else{// //////////////////////////////////////////Вариант в окне
       			if(this.id==30) return 1;
    			else return 2;
       		}
    	},
    	/*
    	output2(){
    		
    		 return this.output;
        	
    	},
    	*/ 

       
       	
     },
     watch:{ ////////////Работает гораздо быстрее чем  updated(){
    	 matrixParametrs(){
    		 if(this.mode!='flat'){ ////////Когда нарезано, нет постоянного обновления
    			 //console.log('watch matrixParametrs change');
    		 }
    		 //if(this.mode=='flat'){
    		this.generatePaths();
    	 },

    	 //}
     },
     
     updated(){
    	 // console.log(this.matrixParametrs.deviceParameters);
    	 // console.log(this.output);
    	 // console.log('mode: '+this.mode);
    	 if(this.mode!='flat'){
    		
    		 // this.InputFrequency=
				// this.store.state.dnconverter.object[this.id].deviceParameters.InputFrequency.valueParameter;
    	 }
     },
     methods: {// //////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  open() {
    	    	this.$emit('open', JSON.stringify(this.$props)); // ///////работает
																	// кагда
																	// подписка
																	// через
																	// HTML
    	    	// console.log('open');
    	    	return false;
    	    },
    	  update() { // ////////////Для кнопки было <button type="button"
						// @click="update">Сохранить</button><br>
    	    	/*
				 * this.$emit('updated', { datatype : "antenna_device_type",
				 * data : JSON.stringify(this.$props), id: this.id } );
				 * /////////работает кагда подписка через HTML ???? return
				 * false;
				 */
    	  },
    	  
    	  generatePaths(){///////////////Генерация путей SVG
    		  let output = [];
    		  if(this.mode!='flat') { 
    			  //console.log('generatePaths');
    		  }
    		  k=1;
         	 step=30;
         	 matrix = this.matrixParametrs.deviceParameters;
         	if(this.mode!='flat') { 
         		//console.log("generatePaths matrix: ");
         		//console.log(matrix);
         	}
         	if(matrix!=undefined && matrix!=null){
         	 for (var key of Object.keys(matrix)) {
         		  y_out=k*step;
         	 start_y_height = (matrix[key].valueParameter)*step;
         	 
         	 if(start_y_height>0) c=this.webcolors[k-1];
         	 else c='transparent';
         	 
         	 out_name = "Output"+k;
         	 dar = this.dar[out_name];
         	 //console.log(dar);
         	 
         	 d={'path': "m 0 "+(270-y_out).toString()+" l "+(start_y_height).toString()+" 0 l 0 "+ (y_out), 'color': c, 'dar':dar};
         	 //d={'path': "m 0 "+(y_out).toString()+" l "+(start_y_height).toString()+" 0 l 0 "+ (270-y_out), 'color': c};
         	
         	 //else d={'path':null, 'color':'transparent'};
         	 output[8-k]=d;
         	 k++;
         	
         		}
    	  }
    	  else output = null;
    	  
         this.output = output; 
      
         	
         	 
    	  },
    	  
    	  updateStore() {// ///////////Метод вызывания мутауции в store
    		  this.store.commit('matrix/updatebyId',{obj:this.matrixParametrs.deviceParameters, id:this.id});
    		  //console.log(this.matrixParametrs.deviceParameters);
    		  
    		  /*  Как то надо перечитать из сторе через некоторое время, закрасив все линии нормально
    			setTimeout(function(){  //////////Делаем так, потому что биндинг запаздывает
         			//console.log(output);
         			this.output = output; 
         		}.bind(this, output), 50);
    		  */
    		  
    	  },
    	  
    	  updateMatrix(key){ ///////////Тут нужно перерисовать матрицу
    		  //console.log(this.matrixParametrs.deviceParameters);
    		 
    		 this.dar[key] = '20,10,5,5,5,10'; //////////////Делаем пунктир
             this.generatePaths();

    	  },
    	  
    	  testCont(){
    		 
    	// ///////Для встроенного в HTML код используем this.$store. Для
		// нарезанного this.store.
    		  
    	  },
    	  

	      dispose() {
			  this.$el.parentNode.removeChild(this.$el);
    		  this.$destroy();
    		  return false;	
    	  },
    	  stop_timers(){
    		  // ////////////////Вызывается в парент
    	  },
    	  start_timers(domReference){
    		  this.domReference = domReference;
    		  if(this.mode!='flat') {
        		  // this.timer = setInterval(() => this.tick(), 1000);
        		  // if(this.mode!='flat')this.display_c();
        	  }  
    	  },

    	  


      },
      mounted(){
    	  // //////Эмитим headerTxt
    	 // console.log('mounted mode: '+this.mode);
    	  // console.log(this.id);
    	  
    	  if(this.mode!='flat') { //////////Происходит один раз только при нарезке
    		  //console.log("store content: ");
    		  //console.log(this.store.state.matrix.object[this.num]);
    		  this.matrixParametrs = this.store.state.matrix.object[this.num];
    		  
    		  //console.log(this.matrixParametrs);
		  
    		 // this.InputFrequency=
				// this.store.state.dnconverter.object[this.num].deviceParameters.InputFrequency.valueParameter;
    		 // this.AttenuationValue=
				// this.store.state.dnconverter.object[this.num].deviceParameters.AttenuationValue.valueParameter;
    		 // this.OutputFrequency=
				// this.store.state.dnconverter.object[this.num].deviceParameters.OutputFrequency.valueParameter;
    		  this.$emit('loaded', {'headerTxt': this.title+' ('+this.id+')'}); 
    	  }
    	  
    	  
      },
      beforeDestroy(){
    	// clearInterval(this.timer);
    	// clearInterval(this.mytime);
      },
      
	  template: `
	  <div v-if="mode=='flat'"  align="center">
		  <div class="compSchema conventerSchema"  @click="open" align="center">
		  <h3>Матрица {{num}}</h3>
		  {{svg_width}}
		  <svg    v-bind:id="num" v-bind:width="svg_width"  v-bind:height="svg_height" viewBox="0 0 270 270" style="enable-background:new" style="background-color:#D7DBDD">
		  	
		  	<path v-for="d in this.output" fill="none" stroke-width="2px"  v-bind:stroke ="d.color"       v-bind:d="d.path"></path>

		  	
		  	</svg>
		  </div>
		  
	  </div>
	  <div v-else><form><div class="formcontents">
		  	<h3>{{title}}</h3>
		  <table>
		  <tr>
		  <td>Вых.</td><td>Матрица</td><td>Управление</td>
		  </tr>
		  <tr>
		  <td style="font-size: 14px; line-height: 45px;">8
		  <br>
		  7<br>
		  6<br>
		  5<br>
		  4<br>
		  3<br>
		  2<br>
		  1<br>
		  
		  
		  </td>
		  	<td>
		  	 <svg   v-bind:width="svg_width_big"  v-bind:height="svg_height_big" viewBox="0 0 270 270" style="enable-background:new" style="background-color:#D7DBDD">
		  	<path v-for="d in this.output" fill="none" stroke-width="2px"  v-bind:stroke ="d.color" v-bind:stroke-dasharray="d.dar"   v-bind:d="d.path"></path>
		  	</svg>
		  	</td>
		  	<td valign="top">
		  	<ul>
			  <li v-for="(input, key) in this.matrixParametrs.deviceParameters">
			    {{ key}}
			    <input type="text" v-bind:name="key" v-model="input.valueParameter"  type="number" min="0" max="8" @change="updateMatrix(key)">
			  </li>
			</ul>
			<hr>
<button type="button" @click="updateStore">Сохранить через store</button>
		  	</td>
		  </tr>
		  <tr>
		  	<td></td>
		  	<td class="matrixinputs" >
		  	<span>1</span>
		  	<span>2</span>
		  	<span>3</span>
		  	<span>4</span>
		  	<span>5</span>
		  	<span>6</span>
		  	<span>7</span>
		  	<span>8</span>
		  	</td>
		  	<td></td>
		  </tr>
		  </table>
</div>

 </form></div>

          `

};	