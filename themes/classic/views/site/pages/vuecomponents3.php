<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
//$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vue/vuetestcomp.js?ver='.rand(), CClientScript::POS_END );
?>
///////////////////////////components3/////////////////////////////////////////<br>

    
           <br><hr>app<br>
   <script>
	Vue.component ('navigation-link',{
		props:['url'],
		template:`<div><h1>{{url}}</h1><a v-bind:href="url"
			  class="nav-link"
			><slot>Отправить</slot></a>
			<inner-data dat="d4">{{url}}</inner-data>
			</div>`
	});

	Vue.component('inner-data',{
		props:['dat'],
		template:`<div class="indata">This is innerData - {{dat}}, <slot></slot></div>`
	});
	
	</script>
<div id="app">	
<navigation-link url="/profile">
  Ваш профиль
</navigation-link>  
<br>
<inner-data dat="d1"></inner-data>
<inner-data dat="d2"></inner-data>
<inner-data dat="d3"></inner-data>
<br>
<navigation-link url="/profile2">
  
</navigation-link>  

</div>
<script>
    new Vue({el: '#app'})
</script>
       
       

   