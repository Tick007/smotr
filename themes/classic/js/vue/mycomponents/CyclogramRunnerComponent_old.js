//https://mavo.io/demos/svgpath/
var cyclogram_runner_type =  {// ///////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	domReference:null,
	   	    	selected:{}, ////////////Выбранная циклограмма
	   	    	selectedCommand:{},//////Выбранная комманда
	   	    	selectedEtalon:{},///////Неизменяемая комманда
	   	    	timer:null,
	   	    	unitnumCompareEqual:false,//////////////////Сравнение номера устройства в выбранной команде
	   	    	comnumCompareEqual:false,//////////////////Сравнение номера команды в выбранной команде
	   	    	paramCompareEqual:false, //////////////////Сравнение параметра команды в выбранной команде
	   	    	cantSaveCommand: true,
	   	    	status:{}
	   	      	    }
    	    },
	 
        props:{
    	
        cyclogramParametrs:{
    		type:Object,
    		default:{}
        },
        
        devlist:{
        	type:Object,
			default:{}
        },
    	
        cyclogramStatus:{
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

    	comnumCompare(){
    		return false;
    	},
    	
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
    	
    	getStatus(n){
    		return "qqq_"+this.statusGet(n);
    	},
    	
    	
    	
    	test(){
    		/*
    		  
    		console.log('test in cyclogramms');
    		console.log(this.store.state.cyclogramstatus);
    		return this.store.state.cyclogramstatus;
    		*/
    	}
    	 

       
       	
     },
     watch:{ ////////////Работает гораздо быстрее чем  updated(){
    	 cyclogramParametrs(){

    	 },
    	 cyclogramStatus(){
    	 console.log('data'); 
    	 },
    	 //}
     },
     
     updated(){
    	 	
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
    	  
    	  
    	  
    	  isEmptySelected() {
    		    for(var prop in this.selectedCommand) {
    		        if(this.selectedCommand.hasOwnProperty(prop))
    		            return false;
    		    }

    		    return true;
    		},
    	  
    	  //////////////////////////////////// Проверяем статус строки
  /*
    	  checkStatus(num_in_cyclogram){
    		console.log(num_in_cyclogram);  
    		if(this.store.state.cyclogramstatus!==null && this.store.state.cyclogramstatus!==undefined){
    			return 'dd';
    		}
    		else return 'n\a';
    	  },
*/			
    	  commandChange(){ /////////////////////Реакция на изменение полей команды
    		//console.log('deviceChange');  
    		//console.log(this.selectedCommand);
    		this.unitnumCompareEqual = (this.selectedCommand.UnitNum != this.selectedEtalon.UnitNum);
    		this.comnumCompareEqual = (this.selectedCommand.CommandNum != this.selectedEtalon.CommandNum);
    		this.paramCompareEqual = (this.selectedCommand.Params != this.selectedEtalon.Params);
    		
    		//console.log(this.unitnumCompareEqual);
    		//console.log(this.comnumCompareEqual);
    		//console.log(this.paramCompareEqual);
    		
    		this.cantSaveCommand = this.selected.readonly==1 || (!( this.unitnumCompareEqual || this.comnumCompareEqual || this.paramCompareEqual) && this.selectedCommand.CommandNum >0);
    		
    		//console.log(this.cantSaveCommand);
    		//console.log(this.selectedCommand.CommandNum);
    		
    	  },
    	  
    	  updateStore() {// ///////////Метод вызывания мутауции в store
    		  //this.store.commit('matrix/updatebyId',{obj:this.matrixParametrs.deviceParameters, id:this.id});
    	  },
    	  
    	  loadCyclo(id){ ///////////
    		  //console.log(this.matrixParametrs.deviceParameters);
	      	  this.unitnumCompareEqual = false;
	    	  this.comnumCompareEqual = false;
	    	  this.paramCompareEqual = false;
    		  this.cantSaveCommand = true;
    		  this.selectedCommand={};
    		  this.selectedEtalon = {};
    		  this.selected=this.cyclogramParametrs[id];
    		 // this.selected = this.store.state.cyclogram.list[id];
    	  },
    	  
    	  selectCommand(command){
    		  //this.status.statuses=null;
    		  this.selectedCommand = JSON.parse(JSON.stringify(command));
    		  this.selectedEtalon = command;
    		  //console.log('selectCommand: ');
    		  //console.log(this.selectedCommand);
    	  },
    	  
    	  whatSelected(command){
      		if(command==this.selectedCommand) return 'selectedCommand'
      		else return '';
      		},
      		
      		
      		saveCommand(){//////////////////Сохранение текущей выбранной комманды
      			let payload = {
      					'command':{
      						'command': this.selectedCommand,
      					    'cyclogram_name':this.selected.name,
      					    'cyclogram_id':this.selected.id,
                            'executionType':'saveCommand',
      					}
      					};
      			this.store.dispatch('cyclogram/executeCyclogram', payload);
      			//setTimeout("reloadList()", 2000);
      			id=this.selected.id;
      			//NumInCicl = this.selectedCommand.NumInCicl;
      			setTimeout(() => this.reloadList(id), 1500);//////////// через 1.5 секунды заново считываем содержимое циклограммы
      			//this.selectedEtalon = this.seletedCommand;
      			//this.commandChange();
      		},
      		
      		executeSingleCommand(){/////////////Выполнение текущей выбранной комманды
      			this.status.statuses=null;
      			this.timer = setInterval(() => this.tick(), 200);
      			let payload = {
      					'command':{
      						'command': this.selectedCommand,
      					    'cyclogram_name':this.selected.name,
      					    'cyclogram_id':this.selected.id,
                            'executionType':'singleCommand',
      					}
      					};
      			this.store.dispatch('cyclogram/executeCyclogram', payload);
      			//clearInterval(this.timer);
      		},
      		executeFullCyclogram() { ///////////Выполнение всей циклограммы
      			this.status.statuses=null;
      			this.timer = setInterval(() => this.tick(), 500);
      			let payload = {
      					 'command':{
      						 'cyclogram_name':this.selected.name,
      						 'cyclogram_id':this.selected.id,
      						 'executionType':'fullCyclogram'
     						 }
     					};
     			this.store.dispatch('cyclogram/executeCyclogram', payload);
      		},
      		
      	  reloadList(id){//////////Перезагружаем циклограмму
      			this.cyclogramParametrs = this.store.state.cyclogram.list;
      			this.selected = this.store.state.cyclogram.list[id];
      		}	,
      		
    	  tick(){
      		console.log('in tick');	
      		//console.log(this.store.state.cyclogram);	
      		
      		obj = this.store.state.cyclogram;
      		if(obj.cyclogramstatus!==null && obj.cyclogramstatus!==undefined){
      			
      			this.status = obj.cyclogramstatus;
      			
      			if(obj.cyclogramstatus.exucution_status!==null && obj.cyclogramstatus.exucution_status!==undefined){
      				if(obj.cyclogramstatus.exucution_status=='finished'){
      					clearInterval(this.timer);
      					this.timer=null;
      				}
      			}
      			
      			
      			
      		}
      		else {
      			console.log('else');
      			this.status = {};
      			//stop_timers();
      		}
      		            
      	  },
      	  
      	  statusGet(n){
      		  if(this.status.statuses!==null && this.status.statuses!==undefined) return 'cyclorow_'+this.status.statuses[n];
      		  else return 'cyclorow__na';
        	},

	      dispose() {
      		  stop_timers();
			  this.$el.parentNode.removeChild(this.$el);
    		  this.$destroy();
    		  return false;	
    	  },
    	  stop_timers(){
    		  // ////////////////Вызывается в парент
    		  clearInterval(this.timer);
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
    	  	  //this.cyclogramStatus = this.store.state.cyclogramstatus;////не реагирует
    		  this.cyclogramParametrs = this.store.state.cyclogram.list;
    		  this.devlist = this.store.state.cyclogram.devlist;
    		  this.$emit('loaded', {'headerTxt': 'Циклограммы'}); 
    		  //this.store.dispatch('cyclogram/turnOffButton'); ///////Даем команду на отключение передачи списки циклограм
    		  /////////////////////////////////////////////////////////это устарело
      },

      beforeDestroy(){

    	  //this.store.dispatch('cyclogram/turnOffButton'); ///////////Перенес в mounted, что бы все время данные не херачили
    	  
      },
      
	  template: `
	  <div class="cyclogramWindow">
<form><div class="formcontents">
		  	<h3>Окно циклограмм</h3>
		 <table class="cyclogramList">
		 <thead>
		  <tr>
		  	<td>№ id</td><td>Имя циклограммы</td><td>Дата создания</td><td>Описание</td>
		  </tr>
		 </thead>
		 <tbody>
		  <tr v-for="c in this.cyclogramParametrs" v-bind:class="c.readonly==1? 'readonly' : 'normal' " @click="loadCyclo(c.id)" >
		  	<td>{{c.id}}</td>
		  	<td>{{c.name }}</td>
		  	<td>{{c.created}}</td>
		  	<td>{{c.descr}}</td>
		  </tr>
		 </tbody>
		 </table>
<div width="100%" align="left">
		 <div class="cyclogramHolder">
		  <table class="cyclogramContent">
		 <thead>
		  <tr>
		  	<td>№</td>
		  	<td>Имя устройства</td>
		  	<td>Имя команды</td>
		  	<td>Номер устройства</td>
		  	<td>Устанавливаемое значение</td>
		  	<td>Адрес</td>
		  	<td>Таймаут</td>
		  	<td>Статус</td>

		  </tr>
		 </thead>
		 <tbody>
		   <tr v-for="command in this.selected.cyclodata" @click="selectCommand(command)" v-bind:class="whatSelected(command)">
		  	<td>{{command.NumInCicl}}</td>
		  	<td>{{command.unit_type}}</td>
		  	<td>{{command.command_txt}} ({{command.CommandNum}})</td>
		  	<td style="text-align:center">{{command.UnitNum}}</td>
		  	<td style="text-align:center">{{command.Params}}</td>
		  	<td></td>
		  	<td>{{command.TimeOut}}</td>
		  	<td align="center"><div :class="[statusGet(command.NumInCicl)]">&nbsp;</div></td>
		  </tr>
		 </tbody>
		 </table>
		 {{this.status}}
		 </div>
<div class="cyclogramControl">Управление:<br>
<div class="commandDetails">
<!--<pre>
{{this.selectedCommand}}
</pre>-->
 <div v-if="isEmptySelected()==false" >

 <br>
 Устройство: <!--{{this.selectedCommand.unit_type}}({{this.selectedCommand.UnitNum}})<br>-->
 

 <select v-model="selectedCommand.UnitNum" v-bind:class="{ notequal: unitnumCompareEqual}" @change="commandChange()">
	          <option v-for="(item, index) in this.devlist" v-bind:value="index">
		      {{ item.NameEq }}
		    </option>
          </select>
 <br>
 
Команда: <!--{{this.selectedCommand.command_txt}}({{this.selectedCommand.CommandNum}})<i><br>-->
 <select v-model="selectedCommand.CommandNum" v-bind:class="{ notequal: comnumCompareEqual}"  @change="commandChange()">
	          <option v-for="(item, index) in this.devlist[selectedCommand.UnitNum].comlist" v-bind:value="index">
		      {{ item.NAMECMD}} ({{item.NUMCMD}})
		    </option>
          </select>


<br>

Параметр: <br>
<input v-model="selectedCommand.Params" v-bind:class="{ notequal: paramCompareEqual}" @change="commandChange()">
</div>
<br>
<div>
<table>
		  <tr>
		  	<td><button type="button" @click="executeSingleCommand">Выполнить одиночную комманду</button></td>
		  	<td><button type="button" @click="executeFullCyclogram">Выполнить всю циклограмму</button></td>
		  </tr>
		  <tr><td colspan="2">&nbsp;</td>
		  </tr>
		  <tr>
		  	<td><button type="button" @click="saveCommand()" :disabled=cantSaveCommand>Сохранить комманду</button></td>
		  	<td></td>
		  </tr>
</table>

<br><br>

<br><br>

</div>
</div>

 </form></div>
</div>
          `

};	