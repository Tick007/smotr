<?php
$clientScript = Yii::app()->clientScript;
$clientScript->registerScriptFile('/js/vue/vue.min.js', CClientScript::POS_HEAD);
$clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vue/vuetestcomp.js?ver='.rand(), CClientScript::POS_END );
?>
///////////////////////////components/////////////////////////////////////////<br>
https://www.freecodecamp.org/news/how-to-create-and-publish-a-vue-component-library/ - from Alexey<br>
https://ru.vuejs.org/v2/guide/components.html<br>
//////////////////////////////////////////////////////////////////////////////<br>

<div id="components-demo">
  <button-counter></button-counter><br>
  <button-counter></button-counter><br>
  <button-counter></button-counter><br>
</div>
<br>
/////////////////////////////////////////////////////////////////
<br>
<div id="blog-posts-events-demo">
  <div :style="{ fontSize: postFontSize + 'em' }">
    <blog-post
      v-on:enlarge-text="postFontSize += 0.1" v-on:ensmall-text="onEnsmallText" 
      v-for="post in posts"
      v-bind:key="post.id"
      v-bind:post="post"
    ></blog-post>
  </div>
</div>
<br>
/////////////////////////////////////////////////////////////////
<br>
<div id="components-input-demo">
<custom-input v-model="searchText" ></custom-input>
</div>
<br>
/////////////////////////////////////////////////////////////////////////
<br>
<div id="alerttest">
<alert-box>
  Произошло что-то плохое.
</alert-box>
</div>
<br>
////////////////////////////////////////////////////////////////////////////////
<br>
<style>
      .tab-button {
        padding: 6px 10px;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        border: 1px solid #ccc;
        cursor: pointer;
        background: #f0f0f0;
        margin-bottom: -1px;
        margin-right: -1px;
      }
      .tab-button:hover {
        background: #e0e0e0;
      }
      .tab-button.active {
        background: #e0e0e0;
      }
      .tab {
        border: 1px solid #ccc;
        padding: 10px;
        min-height:20px;
      }
    </style>

    <div id="dynamic-component-demo" class="demo">
      <button
        v-for="tab in tabs"
        v-bind:key="tab"
        v-bind:class="['tab-button', { active: currentTab === tab }]"
        v-on:click="currentTab = tab"
      >
        {{ tab }}
      </button>

      <component v-bind:is="currentTabComponent" class="tab"></component>
    </div>

    <script>
      Vue.component("tab-home", {
        template: "<div>Home component</div>"
      });
      Vue.component("tab-posts", {
        template: "<div>Posts component</div>"
      });
      Vue.component("tab-archive", {
        template: "<div>Archive component</div>"
      });

      new Vue({
        el: "#dynamic-component-demo",
        data: {
          currentTab: "Home",
          tabs: ["Home", "Posts", "Archive"]
        },
        computed: {
          currentTabComponent: function() {
            return "tab-" + this.currentTab.toLowerCase();
          }
        }
      });
    </script>
    <br>
    https://ru.vuejs.org/v2/guide/components-registration.html
    <br>
     <div id="dynamic-component-demo2" class="demo">
      <button
        v-for="tab in tabs"
        v-bind:key="tab.name"
        v-bind:class="['tab-button', { active: currentTab.name === tab.name }]"
        v-on:click="currentTab = tab"
      >
        {{ tab.name }}
      </button>

      <component v-bind:is="currentTab.component" class="tab"></component>
    </div>

    <script>
      var tabs = [
        {
          name: "Home",
          component: Vue.component("tab-home", {
              template: "<div>Home component</div>"
          })
        },
        {
          name: "Posts", /////////////ХЗ как https://codesandbox.io/s/github/vuejs/vuejs.org/tree/master/src/v2/examples/vue-20-dynamic-components-with-binding работает
          component: { ///////////////но у меня не работает
            template: "<div>Posts component</div>"
          }
        },
        {
          name: "Archive",
          component: Vue.component("tab-archive", {
              template: "<div>Archive component</div>"
          })
        }
      ];

      var dynamic_component_demo2 = new Vue({
        el: "#dynamic-component-demo2",
        data: {
          tabs: tabs,
          currentTab: tabs[0]
        }
      });
    </script>
    <br>
    <br>
 
    