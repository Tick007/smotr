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
			this.$destroy();
			this.$el.parentNode.removeChild(this.$el);
			return false;	
    	  }
      },
	  template: `
	  
<div v-bind:id="'userWindow_'+id" class="mydiv"  @mousedown="updateZ($event.target)" :style="{'z-index':zindexlocal}">
  <div v-bind:id="'userWindow_'+id+'header'" class="mydivheader">{{ firstName }} {{ lastName }}:свойства {{ zindexlocal }} 
  <button  type="button"  @click="dispose" style="float:right;">x</button>
  </div>
<form><div class="formcontents">
          <h3> <input type="text" v-model="firstName" name="firstName"/> <input type="text" v-model:value="lastName" name="lastName"/></h3>
          <div>Ид пользователя: {{ id }}</div>
          <div>Позиция пользователя: <input type="text" v-model="position"  name="position"/></div>
          <div>ЗП пользователя: <input type="text" v-model="salary" name="salary"/></div>
          <!-- <div><input type="button"  v-on:click="$emit('user-save', $event.target.form)"  value="Сохранить">--><br>
          <button type="button" @click="update">Сохранить</button>
          </div><!-- обязательно дефиз "user-click" -->


 </form>
 </div>

          `
	};	