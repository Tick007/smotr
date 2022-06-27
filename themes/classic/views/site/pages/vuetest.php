
<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js', CClientScript::POS_HEAD);
$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vue/vuetest.js?ver='.rand(), CClientScript::POS_END );

$clientScript->scriptMap=array(
    
    //'jquery.js'=>false,
    
);

?>


<div id="app">
  {{ message }}
</div>

<br>

<div id="app-2">
  <span v-bind:title="message"> 
    Hover your mouse over me for a few seconds
    to see my dynamically bound title!
  </span>
</div>

<div id="app-4">
  <ol>
    <li v-for="todo in todos">
      {{ todo.text }}
    </li>
  </ol>
</div>

<div id="app-5">
  <p>{{ message }}</p>
  <button v-on:click="reverseMessage">Reverse Message</button>
</div>
<br>
///////////////////////////////////////<br>
app-6<br>
<div id="app-6">
  <p>{{ message }}</p>
  <input v-model="message">
</div>


<div id="app-7">
  <ol>
    <!--
      Now we provide each todo-item with the todo object
      it's representing, so that its content can be dynamic.
      We also need to provide each component with a "key",
      which will be explained later.
    -->
    <todo-item
      v-for="item in groceryList"
      v-bind:todo="item"
      v-bind:key="item.id"
    ></todo-item>
  </ol>
</div>



<div id="app-9">
  <p>{{ foo }}</p>
  <!-- this will no longer update `foo`! -->
  <button v-on:click="foo = 'baz'">Change it</button>
  
  <p v-if="seen">Now you see me</p>
  <p v-if="!seen">Now you not</p>
  
  <button v-bind:disabled="isButtonDisabled" v-on:click="doSmth">Button</button>
  <button  v-on:click="isButtonDisabled=!isButtonDisabled">change enable</button>
<br><br><br>
<form v-on:submit.prevent="onSubmit">
<input type="hidden" value="someVal">
<button  type="submit">submit</button>
</form>

</div>

<br><br>
<div id="example">
  <p>Original message: "{{ message }}"</p>
  <p>Computed reversed message: "{{ reversedMessage }}"</p>
</div>


<br><br>


<div id="watch-example">
  <p>
    Ask a yes/no question:
    <input v-model="question">
  </p>
  <p>{{ answer }}</p>
</div>

<br><br>

//////////////////////////////////////////////////watches
<script src="/js/vue/axios.min.js"></script>
<script src="/js/vue/lodash.min.js"></script>
<script>
var watchExampleVM = new Vue({
  el: '#watch-example',
  data: {
    question: '',
    answer: 'I cannot give you an answer until you ask a question!'
  },
  watch: {
    // whenever question changes, this function will run
    question: function (newQuestion, oldQuestion) {
      this.answer = 'Waiting for you to stop typing...'
      this.debouncedGetAnswer()
    }
  },
  created: function () {
    // _.debounce is a function provided by lodash to limit how
    // often a particularly expensive operation can be run.
    // In this case, we want to limit how often we access
    // yesno.wtf/api, waiting until the user has completely
    // finished typing before making the ajax request. To learn
    // more about the _.debounce function (and its cousin
    // _.throttle), visit: https://lodash.com/docs#debounce
    this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
  },
  methods: {
    getAnswer: function () {
      if (this.question.indexOf('?') === -1) {
        this.answer = 'Questions usually contain a question mark. ;-)';
        return;
      }
      this.answer = 'Thinking...'
      var vm = this;
      //axios.get('https://yesno.wtf/api',  {crossDomain: false})
     
      axios.get('http://yii-site/nomenklatura/getgroupe/'+this.question)
      .then(function (response) {
          vm.answer = _.capitalize(response.data.answer);
        })
        .catch(function (error) {
          vm.answer = 'Error! Could not reach the API. ' + error;
        })

    }
  }
})
</script>



<br>
//////////////////////////////////Class and Style Bindings/////////////////////////////////////
<style>
.active{
    background-color:red;
}
</style>
<div id="classexample" ><span v-bind:class="{ active: isActive }">q	wq	w</span>
<br><br>

<div v-bind:class="classObject">classObject</div>
</div>
<br>

/////////////////////////////components///////////////////////
<my-component class="baz boo">d</my-component>
<br>
///////////////////conditional rendering/////////////
<div id="app-cond">
<h1 v-if="awesome">Vue is awesome!</h1>
<h1 v-else>Oh no 😢</h1>


<template v-if="ok">
  <h1>Title</h1>
  <p>Paragraph 1</p>
  <p>Paragraph 2</p>
</template>
<template v-else>
Strange template v-else
</template>

</div>


//////////////////////cicles //////////////////
<div id="example-1">
<ul >
  <li v-for="item in items2" :key="item.message"  v-bind:key="item.id">
    {{ item.name }} ({{ item.id }})
  </li>
</ul>
<ul >
  <li v-for="value in object">
    {{ value }}
  </li>
</ul>
<div v-for="(value, name) in object">
  {{ name }}: {{ value }}
</div>
</div>
<br><br>
//////////////////forms///////////////////
https://freefrontend.com/css-toggle-switches<br>
https://proto.io/freebies/onoff/ - генератор кнопок<br>
<div id="formexample-1">
<input type="checkbox" id="jack" value="Джек" v-model="checkedNames">
<label for="jack">Джек</label>
<input type="checkbox" id="john" value="Джон" v-model="checkedNames">
<label for="john">Джон</label>
<input type="checkbox" id="mike" value="Майк" v-model="checkedNames">
<label for="mike">Майк</label>
<br>
<span>Отмеченные имена: {{ checkedNames }}</span>
<br>
<input type="radio" id="one" value="Один" v-model="picked">
<label for="one">Один</label>
<br>
<input type="radio" id="two" value="Два" v-model="picked">
<label for="two">Два</label>
<br>
<span>Выбрано: {{ picked }}</span>
<br>
<!-- 
<select v-model="selected">
  <option disabled value="">Выберите один из вариантов</option>
  <option>А</option>
  <option>Б</option>
  <option>В</option>
</select>-->
<br><br>

<select v-model="selected">
  <option v-for="option in options" v-bind:value="option.value">
    {{ option.text }}
  </option>
</select>
<span>Выбрано: {{ selected }}</span>

<br><br>
Чекбокс
<input
  type="checkbox"
  v-model="toggle"
  true-value="да"
  false-value="нет"
>

</div>
<br><br>




