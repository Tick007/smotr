   <script src="https://cdnjs.cloudflare.com/ajax/libs/mocha/2.3.4/mocha.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chai/3.4.1/chai.min.js"></script>
https://scrimba.com/learn/vuex/test-vuex-mutations-actions-and-getters-with-mocha-and-chai-cPGkpJhq<br>
 нихрена не понятно. Тестирование мутаций.
<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
//$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/vuex_test1.js?v=' . rand(), CClientScript::POS_HEAD, array('type'=>'module'));
?>
<!-- commit для мутаций -->
<!-- dispatch для экшинов -->
<div id="mocha"></div>   
<script>

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
      count: 0 ,
      products: [
          { id: 1, title: 'Apple', category: 'fruit' },
          { id: 2, title: 'Orange', category: 'fruit' },
          { id: 3, title: 'Carrot', category: 'vegetable' }
      ]
    },
    mutations: {
      increment (state) { state.count++ }  
    }
})



// Testing
mocha.setup('bdd');
let assert = chai.assert;
let expect = chai.expect;

describe('mutations', () => {
    it('INCREMENT', () => {
        store.commit('increment');
        expect(store.state.count).to.equal(1)
    })
})

mocha.run();  
</script>
