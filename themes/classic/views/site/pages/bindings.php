https://learn.javascript.ru/bind

<script>

function bind(func, context) {
	  return function() { // (*)
	    return func.apply(context, arguments);
	  };
	}

$( document ).ready(function() {




var user = {
		  firstName: "Вася",
		  sayHi: function() {
		    console.log(this.firstName );
		  }
		};

setTimeout(user.sayHi, 2000); // undefined (не Вася!)
//console.log(user.sayHi);
setTimeout(bind(user.sayHi, user), 2000);

});
</script>
