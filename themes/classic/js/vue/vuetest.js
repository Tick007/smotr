var app = new Vue({
	  el: '#app',
	  data: {
	    message: 'Hello Vue!'
	  }
	})
	
var app2 = new Vue({
  el: '#app-2',
  data: {
    message: 'You loaded this page on ' + new Date().toLocaleString()
  }
})

var app4 = new Vue({
  el: '#app-4',
  data: {
    todos: [
      { text: 'Learn JavaScript' },
      { text: 'Learn Vue' },
      { text: 'Build something awesome' }
    ]
  }
})

var app5 = new Vue({
  el: '#app-5',
  data: {
    message: 'Hello Vue.js!'
  },
  methods: {
    reverseMessage: function () {
      this.message = this.message.split('').reverse().join('')
    }
  }
})

var app6 = new Vue({
  el: '#app-6',
  data: {
    message: 'Hello Vue!'
  }
})


Vue.component('todo-item', {
  props: ['todo'], //////////////Список / хэш атрибутов, которые могут принимать данные от родительского компонента.
  template: '<li>{{ todo.text }}&nbsp;{{ todo.weight }}</li>'
})

var app7 = new Vue({
  el: '#app-7',
  data: {
    groceryList: [
      { id: 0, text: 'Vegetables' , 'weight':'10'},
      { id: 1, text: 'Cheese', 'weight':'15' },
      { id: 2, text: 'Whatever else humans are supposed to eat' , 'weight':'20'}
    ],
    created: function () {
    	this.groceryList.push({ id: 3, text: 'Wweans are supposed to eat' , 'weight':'25'}); //////Почему то не добавляет, хотя из консоли работает
    }
  }
})

////////////////////////

var obj = {
  foo: 'bar',
  seen: true,
  isButtonDisabled: false
}
//Object.freeze(obj)

var app9 = new Vue({
  el: '#app-9',
  data: obj, 
  methods:{
  	doSmth:function(){
  		alert('qqq');
  	},
  onSubmit: function(){
  		console.log('submit interrupted');
  	}
  },
  created: function () {
    // `this` points to the vm instance
    console.log('foo is: ' + this.foo);
  }
})

var vm = new Vue({
  el: '#example',
  data: {
    message: 'Hello'
  },
  computed: {
    // a computed getter
    reversedMessage: function () {
      // `this` points to the vm instance
      return this.message.split('').reverse().join('')
    }
  }
})


var vmclex = new Vue({
  el: '#classexample',
  data: {
	message: 'Hello classexample!',
	isActive: true,
	hasError: false,
	classObject: {
		active: true,
	    'text-danger': false
	  }
}
})
 
Vue.component('my-component', {
	  template: '<p class="foo bar">Hi</p>'
	}) 


var vmccond = new Vue({
  el: '#app-cond',
  data: {
	awesome: false,
	ok: false
	
  }
})

async function getGroupesData(el){
	
	//alert('axios');
	
	var ret;
	var params = {};
	params['name'] = '23423423';
	params['anotherName'] = '23424234234';
	
	 const config = {
		        method: 'get',
		        url: el.url,
		        headers: {'tertert':'ertert','Auth':'sdfw2432'},
		        data:params
		    };
	 //axios.get(el.url, params)
	 axios({
		    method: 'post',
		    url: el.url,
		    data: params,
		    headers: {'tertert':'ertert','Auth':'sdfw2432'},
		    //headers: {'Content-Type': 'multipart/form-data' }
		    })
	 //let res = await axios(config, params)
	 .then(function (response) {
    	 ret = response.data;
    	 console.log(ret);
    	 el._data.items=ret;
       })
       .catch(function (error) {
         //vm.answer = 'Error! Could not reach the API. ' + error;
       })
	 

}

function getGroupesDataJquery(el){
	
	//alert('getGroupesDataJquery');
	
	$.ajax({
		url: el.url,
		method: 'POST',
		data:{'aaaa':412321, 'bbbb':2234234},
		password:'123456',
		username:'testuser',
		//contentType:'multipart/form-data',
		success: function(response){
			ret = response.data;
	    	 console.log(ret);
	    	 el._data.items=ret;
	  },
	  error:function(xhr,status,error){
		  alert (error);
	  }
	});
}



var example1 = new Vue({
	
	  el: '#example-1',
	  
	  data: {
		url: 'http://yii-site/nomenklatura/getgroupes/5',
	    items: null, //////////////////Исходный набор данных
	    items_filtered:null, ///////////////Фильрованный набор данных для последующего биндинга
	    object: {
		      title: 'How to do lists in Vue',
		      author: 'Jane Doe',
		      publishedAt: '2016-04-10'
		    },
	  },
	  computed: {
		  items2 :function () {/////////////// Набор для биндинга в HTML
			  return this.items_filtered;
		  }
	  },
	  created:function(){
		  getGroupesData(this);
		  //getGroupesDataJquery(this);
	  },
	  watch: {
		    // эта функция запускается при любом изменении вопроса
		  url:  function (newQuestion, oldQuestion) {
			  //getGroupesData(this);
			  getGroupesDataJquery(this);
	  		},
	  	  items: function (){
	  		this.items_filtered = this.items;
	  	  }
	  },
	  methods:{
		  reset: function(){
			  this.items_filtered = this.items;
		  },
		  update:function(){//////////////Принудительное обновление
			  getGroupesDataJquery(this);
		  	},
		  	filt: function(str){/////////////Реализация метода для лиьтрации исходного набора данных
		  		this.items_filtered = this.items.filter(function (item) {
		  		  return item.name.match(str)
		  		})
		  	}
	  }
	})
//getData(example1);	


var formexample1 = new Vue({
	
	  el: '#formexample-1',
	  data: {
	    checkedNames: [],
	    picked:false,
	    selected: 'А',
	    options: [
	      { text: 'Один', value: 'А' },
	      { text: 'Два', value: 'Б' },
	      { text: 'Три', value: 'В' }
	    ],
	    toggle:'',
	  },
	watch: {

		checkedNames: function (){
			alert('hit');
		  },
	  toggle: function(){
		  console.log(this.toggle);
	  }
	},
	})