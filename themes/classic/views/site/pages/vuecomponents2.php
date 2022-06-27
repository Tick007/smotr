<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vue/vuetestcomp.js?ver='.rand(), CClientScript::POS_END );
?>
///////////////////////////components/////////////////////////////////////////<br>
https://www.freecodecamp.org/news/how-to-create-and-publish-a-vue-component-library/ - from Alexey<br>
https://ru.vuejs.org/v2/guide/components.html<br>

   ///////////////////////////////////////////////////////////////////////////////////////////////////////
    <br>https://ru.vuejs.org/v2/guide/components-props.html
    <script>
    Vue.component('ncounter', {
          props:['title'],
    	  data: function () {
    	    return {
    	      count: 0
    	    }
    	  },
    	  template: '<div>{{ title }}</div><button v-on:click="count++">Счётчик кликов — {{ count }}</button><br>'
    	})
    	
    Vue.component('blog-post', {
    	data: function () {
    	    return {
    	    	locFontSize: 1
    	    }},
      //locFontSize: 1,
      props: ['title', 'content', 'jopa'], ////////////////////То что тутперечислено становится полями объекта и досиупно через {{...}}
      ////////////////////////////////////// при этом эти ключи нужно присвоить в секции blog-post или через v-html="content" прям в шаблоне
      //////////////////////////////////// Осталные переданные атрибуты становятся атрибутами того что в template
      ///////////////////////////////////но если в шаблоне несколько тэгов - то изначальные атрибуты просто пропадают - бред сука, потеря данных
      template: `
		<div style="font-size:{{locFontSize}}em">
          <h3>{{ title }}</h3></h4>
          <div alt="{{ jopa }}">{{ content }}</div>
          <div v-html="content"></div>
          <button v-on:click="$emit('enlarge-text')">Увеличить размер текста</button>
          <br>
          <button v-on:click="locFontSize += 0.1">Увеличить размер текста компонента в компоненте</button>////////////Чтобы это работало locFontSize должен возращаться
          ////////////////////в секции data через function
          <button v-on:click="$emit('change-fontsize', this)">Увеличить размер текста компонента через событие</button>
          ///////////////////Так мы увеличиваем передавая в метод в главном VUE этот компонент как объект
</div>
          `
    	      
    })	
	    	
    </script>
<br>

<br>
<div id="MyCoomp1">
  <ncounter title="Это компонент 1"></ncounter><br>
   <ncounter title="Это компонент 2"></ncounter>
   <br><div  :style="{ fontSize: postFontSize + 'em' }">
   <blog-post 
  v-for="post in posts"
  v-bind:key="post.id"
  v-bind:jopa="post.content"
  v-bind:alt="post.title"
  v-bind:title="post.title"
  v-bind:content="post.content" 
  v-on:enlarge-text="postFontSize += 0.1"
  v-on:change-fontsize="onEnlargeText"
></blog-post>
   
</div></div>

    <script>
    var eee;
    var qqq = new Vue({ el: '#MyCoomp1',
    	data: {
    	    posts: [
    	      { id: 1, title: 'My jour 231 ney with Vue' , content:'cont og blog first'},
    	      { id: 2, title: 'Blogging with Vue', content: 'second blog content' },
    	      { id: 3, title: 'Why Vue is so fun' , 'content': 'third blog message'}
    	    ],
    	    postFontSize: 1
    	    
    	  },
    	  methods: {
    		  onEnlargeText: function (el) {
    			  el.locFontSize += 0.1;
    		  }
    		}
         })
    </script>
    
    <br>/////////////////////////////////////Локальная регистрация/////////////////////////////////////////
    <script>
    var ComponentA = {
      props:['title'],
  	  template: '<div><strong>component {{ title }}</strong></div><br>'
  	};
    //import ComponentB from '/themes/classic/js/vue/mycomponents/ComponentB'  	;
</script>
 <script src="/themes/classic/js/vue/mycomponents/ComponentB.js"></script>
<div id="appXX">23423
<component-a title="это мой локальный компонент"></component-a>
<component-b title="компонент Б почти через импорт" h1="Б!!!"></component-a>
</div>
<script>
    new Vue({
    	  el: '#appXX',
    	  components: {
    	    'component-a': ComponentA,
    	    ComponentB, ////////////Объявлен в импортированном скрипте
    	    

    	  }
    	})

       </script>


    
           <br><hr>appXfor<br>
   <script>
	Vue.component ('navigation-link',{
		props:['qqq'],
		template:`<a
			  v-bind:href="url"
			  class="nav-link"
			>
			  <slot></slot>
			</a>`
	});
	</script>
<div id="appXfor">	
<navigation-link url="/profile">
  Ваш профиль
</navigation-link>  
	
</div>
<script>
    new Vue({el: '#appXfor'})
</script>
       
       

   