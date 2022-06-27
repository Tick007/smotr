var order_type =  {/////////https://ru.vuejs.org/v2/guide/components-props.html#%D0%9F%D0%B5%D1%80%D0%B5%D0%B4%D0%B0%D1%87%D0%B0-%D1%81%D0%B2%D0%BE%D0%B9%D1%81%D1%82%D0%B2-%D0%BE%D0%B1%D1%8A%D0%B5%D0%BA%D1%82%D0%B0
    	data: function () {
    	    return {
	   	    	//zindexlocal:1
    	    }
    	    },
	    
      //locFontSize: 1,
      props: ['id', 'recept_date', 'id_client', 'client_name', 'contents'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
      methods: {////////https://websofter.ru/svjaz-mejdu-componentami-v-vue-js/
    	  update() {
    	    	this.$emit('updated', JSON.stringify(this.$props)); /////////работает кагда подписка через HTML
    	    	return false;
    	    },
	      dispose() {
			this.$destroy();
			this.$el.parentNode.removeChild(this.$el);
			return false;	
    	  }
      },
      mounted(){
    	  ////////Эмитим headerTxt
    	  this.$emit('loaded', {'headerTxt': 'Order №'+this.id+' от '+this.recept_date}); 
      },
	  template: `
	  

<div > 
          <h3> Заказ № {{id}}, от {{client_name}}</h3>
          <div align="left">
          <ul class="product_list" v-if="contents">
                	<li v-for="prod in contents">
                	 <div>{{recept_date}}</div>
                	  <span @click="showproduct(prod.id)" style="cursor: pointer; color:blue">ид {{ prod.id }}</span>,  {{prod.product}}, {{prod.price}} у.е., {{prod.quantity}} шт
			       </li>
                </ul>
                <div v-else>
				  <h3>EMPTY</h3>
				</div>
          </div>
          
</div>




          `
	};	