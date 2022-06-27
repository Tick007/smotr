<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
//$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/vuex_test1.js?v=' . rand(), CClientScript::POS_HEAD, array('type'=>'module'));



?>
<!-- commit для мутаций -->
<!-- dispatch для экшинов -->
<div id="app">
            {{count}}
             <button @click='increment'>+</button>&nbsp; <button @click='decrement'>-</button>
             <br><br>
              <button @click='testAction'>testAction</button>
testAction             
            
        </div>

<script>
const store = new Vuex.Store({
    state: {
        count: 10
    },
    mutations: {
        increment (state) {
            state.count++
        },
        incrementBy (state, n) {
            state.count += n
        },
        incrementByObj (state, payload) {
            state.count += payload.amount
        },
        decrement (state) {
            state.count--
        },
    },
    actions: {
        increment (context) {
            context.commit('increment')
        },
        incrementAsync ({ commit }) {
            setTimeout(() => {
                commit('increment')
            }, 1000)
        },
        actionA ({ commit }) {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    commit('decrement')
                    resolve()//////////////тут исполняется переданный код в then(() =>
                }, 1000)
            })
        },
        async actionC ({ commit }) { /////////////мутация gotData исполняется толко после исполнения getData
            commit('gotData', await getData())
        }
    }
});



new Vue({ 
    el: '#app',
    store,
    data: {
    },
    computed: Vuex.mapState([
        'count'
    ]),
    /*methods: {
        increment () {
            this.$store.commit('increment')
        }
    },*/
/*
    methods: Vuex.mapMutations([
        'increment',
        'incrementBy',
        'decrement'
    ])
    */

    methods: {
        increment () {
            this.$store.dispatch('incrementAsync');
        },
        decrement () {
            this.$store.commit('decrement');
        },
        testAction () {
            this.$store.dispatch('actionA').then(() => {
                alert('executed')
            })
        }
    }
    
});

store.commit('increment');
console.log(store.state.count);
store.commit('incrementByObj', { amount: 29 });
console.log(store.state.count);
</script>