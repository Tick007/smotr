var user_post_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	//zindexlocal:1
    	    }
    	    },
	    
      //locFontSize: 1,
      props: ['id', 'firstName', 'lastName', 'salary', 'position', 'orders', 'mode'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
      methods: {////////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  update() {
    	    	this.$emit('updated', JSON.stringify(this.$props)); /////////работает кагда подписка через HTML
    	    	return false;
    	    },
    	  showorder(order_id) {
    	    	//console.log ('incomp:' + order_id);
    	    	this.$emit('orderClicked', order_id); //////////эмитим событие клика по номеру заказа
    	    	//return false;
    	    },
	      dispose() {
			this.$destroy();
			this.$el.parentNode.removeChild(this.$el);
			return false;	
    	  }
      },
      mounted(){
    	  ////////Эмитим headerTxt
    	  this.$emit('loaded', {'headerTxt': 'User '+this.firstName+' '+this.lastName}); 
      },
	  template: `
	  <div v-if="mode=='flat'">
		  Display USER only
		  <h3>
		  	{{firstName}} {{lastName}}
		  </h3>
		  <div>salary: {{salary}}</div>
	  </div>
	  <div v-else>
<form><div class="formcontents">
          <h3> <input type="text" v-model="firstName" name="firstName"/> <input type="text" v-model:value="lastName" name="lastName"/></h3>
          <div>Ид пользователя: {{ id }}</div>
          <div>Позиция пользователя: <input type="text" v-model="position"  name="position"/></div>
          <div>ЗП пользователя: <input type="text" v-model="salary" name="salary"/></div>
          <!-- <div><input type="button"  v-on:click="$emit('user-save', $event.target.form)"  value="Сохранить">--><br>
          <button type="button" @click="update">Сохранить</button>
          <div align="left" v-if="orders">
                <h2>Список заказов</h2>
                <ul class="orders_list">
                	<li v-for="order in orders">
			         <span @click="showorder(order.id)" style="cursor: pointer; color:blue">заказ {{ order.id }}</span>, дата: {{order.recept_date}}, сумма{{order.summa_pokupok}}
			       </li>
                </ul>
          </div>
          </div><!-- обязательно дефиз "user-click" -->


 </form></div>

          `
	};	