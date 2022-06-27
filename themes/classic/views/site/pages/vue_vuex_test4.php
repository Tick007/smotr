https://scrimba.com/learn/vuex/handle-forms-with-vuex-cqKRgEC9
<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
//$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/vuex_test1.js?v=' . rand(), CClientScript::POS_HEAD, array('type'=>'module'));
?>
<!-- commit для мутаций -->
<!-- dispatch для экшинов -->
<div id="app">
            {{message}}<br>
            <!-- <input v-model='message' />  -->
            <input v-model='message' />
        </div>

<script>
Vue.use(Vuex);

const store = new Vuex.Store({
    strict: true,
    state: {
        message: 'Hello Vuex',
    },
    mutations: {
    	updateMessage (state, message) {
            state.message = message
        }
    }
})

//import { mapState } from 'vuex';

new Vue({ 
    el: '#app',
    store,
    data: {
    },
    computed: {
    	message: {
            get () {
                return this.$store.state.message
            },
            set (value) {
                this.$store.commit('updateMessage', value)
            }
        }
    },
    methods: {
     //   updateMessage (e) {
      //      this.$store.commit('updateMessage', e.target.value)
      //  }
    }
})
</script>