



<table padding="1" spacing="1" border="0" width="100%">
<tr>
	<td>Api login</td><td>&nbsp;</td><td>Token</td><td>&nbsp;</td><td>Api next</td><td>&nbsp;</td><td>Answer</td>
</tr>
<tr>
	<td><input id="loginAPI" type="text"></td><td>&nbsp;</td><td><textarea rows="5" cols="50" id="token"></textarea></td><td>&nbsp;</td>
	<td><input id="nextAPI" type="text"></td><td>&nbsp;</td><td><textarea rows="5" cols="50" id="nextdata"></textarea></td>
</tr>
<tr>
	<td><input type="button" id="gettok" value="get tokken"/></td><td>&nbsp;</td><td></td><td>&nbsp;</td>
	<td><input type="button" id="getnext" value="get data"/></td><td>&nbsp;</td><td>&nbsp;</td>
</tr>

</table>

10.10.0.99:7070/api/v1/auth/login

{
    "email":"admin@mail.com",
    "password":"admin"
}


<script>

function getCookie(name){
    var pattern = RegExp(name + "=.[^;]*")
    var matched = document.cookie.match(pattern)
    if(matched){
        var cookie = matched[0].split('=')
        return cookie[1]
    }
    return false
}




$( document ).ready(function() {

	
	$('#token').text(getCookie("jwttoken"));
	$('#nextdata').text('');

	$( "#getnext" ).on( "click", function() {
		var jwttok = getCookie("jwttoken");
		if(jwttok!='undefined'){
			var myHeaders = new Headers();		
			myHeaders.append("Content-Type", "application/json");
			myHeaders.append("Authorization", jwttok);

			var requestOptions = {
					  method: 'GET',
					  headers: myHeaders,
					  body: null,
					  redirect: 'follow'
					};


			fetch("http://10.10.0.99:7070/api/v1/developers", requestOptions)
			  .then(response => response.json())
			  .then(result =>{ 
				  //console.log("result = ")
				  //console.log(result.token)
				  if(result.token!='undefined'){
					  console.log(result);
					  var str = '';
					  for (let i = 0; i < result.length; i++) {
						  
						  str+=JSON.stringify(result[i])+'\r\n'
						  //console.log(str);
						}
					  $('#nextdata').text(str);
						
					  	 //$('#nextdata').text(result);

				  }
				  })
			  .catch(error => console.log('error', error));

			
		}
		
	});
	

	$( "#gettok" ).on( "click", function() {
		var myHeaders = new Headers();
		myHeaders.append("Content-Type", "application/json");

		var raw = JSON.stringify({"email":"admin@mail.com","password":"admin"});

		var requestOptions = {
		  method: 'POST',
		  headers: myHeaders,
		  body: raw,
		  redirect: 'follow'
		};

		fetch("http://10.10.0.99:7070/api/v1/auth/login", requestOptions)
		  .then(response => response.json())
		  .then(result =>{ 
			  //console.log("result = ")
			  //console.log(result.token)
			  if(result.token!='undefined'){
			  	$('#token').text(result.token);
			  	document.cookie = "jwttoken="+result.token;
			  }
			  })
		  .catch(error => console.log('error', error));
		});
	/*
var data = {
	    "email":"admin@mail.com",
	    "password":"admin"
	};
	
	$.ajax({
	url: 'http://10.10.0.99:7070/api/v1/auth/login',
	method: 'POST',
	dataType:'json',
	data: JSON.stringify(data),
	contentType:'application/json',
	success: function(response){
		//ret = JSON.parse(response);
    	 console.log(response.token);
    	// el._data.items=ret;
  },
  error:function(xhr,status,error){
	  alert (error);
  }
});
	*/

/*
	
*/





});

</script>
