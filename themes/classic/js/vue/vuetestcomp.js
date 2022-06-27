Vue.component('button-counter', {
  data: function () {
    return {
      count: 0
    }
  },
 
  template: '<button v-on:click="count++">Счётчик кликов — {{ count }}</button>'
})

 new Vue({ el: '#components-demo'})


Vue.component('blog-post', {
	  props: ['post'],
	  template: `
	    <div class="blog-post">
	      <h3>{{ post.title }}</h3>
	      <button v-on:click="$emit('enlarge-text')">
	        Увеличить размер текста
	      </button>
	      <button v-on:click="$emit('ensmall-text', 0.1)">
	        Уменьшить размер текста
	      </button>
	      <div v-html="post.content"></div>
	    </div>
	  `
	});
	
new Vue({
	 el: '#blog-posts-events-demo',
	  data: {
	    posts: [
	      { id: 1, title: 'My journey with Vue', content:'ipsum dolor asdasd' },
	      { id: 2, title: 'Blogging with Vue', content:'ipsum dolor asdasd'  },
	      { id: 3, title: 'Why Vue is so fun', content:'ipsum dolor asdasd'  }
	    ],
	    postFontSize: 1
	  },
	  methods: {
		  onEnsmallText: function (enlargeAmount) {
			  this.postFontSize -= enlargeAmount*2
		  }
		}
	})


Vue.component('custom-input', {
	  props: ['value'],
	  template: `
	    <input
	      v-bind:value="value"
	      v-on:input="$emit('input', $event.target.value)"
	    >
	  `
	})

 new Vue({ el: '#components-input-demo',
	 
	 methods: {
		  qqq: function (val) {
			  console.log(val);
		  }
		}
 
 })



Vue.component('alert-box', {
	  template: `
	    <div class="demo-alert-box">
	      <strong>Ошибка!</strong>
	      <slot></slot>
	    </div>
	  `
	})

 new Vue({ el: '#alerttest'});




















