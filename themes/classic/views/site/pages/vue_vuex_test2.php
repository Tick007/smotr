<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
//$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/vuex_test1.js?v=' . rand(), CClientScript::POS_HEAD, array('type'=>'module'));
?>

<div id="app">
            Completed Todos: {{ doneTodosCount }}
        </div>

<script>
const store = new Vuex.Store({
	   state: {
	       todos: [
	           { id: 1, text: '...', done: true },
	           { id: 2, text: 'не выполненный', done: false },
	           { id: 3, text: 'выполненный №3', done: true },
	           { id: 4, text: 'выполненный №4', done: true },
	       ]
	   },
	   getters: {
	       doneTodos: state => {
	           return state.todos.filter(todo => todo.done);
	       },
	       doneTodosCount: (state, getters) => {
	           return getters.doneTodos.length
	       },
	       getTodoById: (state) => (id) => {
	           return state.todos.find(todo => todo.id === id)
	       }
	   }
	});

	new Vue({ 
	    el: '#app',
	    store,
	    data: {
	    },
	    computed: Vuex.mapGetters([
	        'doneTodos', 'doneTodosCount', 'getTodoById'
	        ])
		     /*{
	        doneTodosCount () {
	            return this.$store.getters.doneTodosCount
	        }*/
	    
	});

	console.log(store.getters.doneTodosCount)
	console.log(store.getters.getTodoById(2))
</script>