<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js?v=' . rand(), CClientScript::POS_HEAD);
$clientScript->registerScriptFile('/js/vue/vuex.js?v=' . rand(), CClientScript::POS_HEAD);
//$clientScript->registerScriptFile('/themes/classic/js/vue/mycomponents/vuex_test1.js?v=' . rand(), CClientScript::POS_HEAD, array('type'=>'module'));
?>

<div id="app">
            {{count}}
        </div>

<script>
const store = new Vuex.Store({
    state: {
        count: 8
    }
});


let mapState = Vuex.mapState;

new Vue({ 
    el: '#app',
    store,
    data() {
        return {
            localCount: 5
        }
    },
    //computed:mapState({
    computed: mapState([
        'count'
        ])
});

/*
 {
        count: state => state.count,
        countAlias: 'count',
        countPlusLocalState (state) {
            return state.count + this.localCount
        }
    }
 */
</script>