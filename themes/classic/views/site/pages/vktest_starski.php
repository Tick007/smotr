<script src="http://vk.com/js/api/openapi.js"></script>
<script>

function checkVk() {
    VK.init({
        apiId: '5114001'
    });
    VK.Widgets.Auth("vk_auth", {});
}
checkVk();

function getHtml() {
  console.log($("html").html());
}

setTimeout(getHtml, 3000);

</script>


<h1>click here</h1>
<div id="overlay">
	<div id="wrap1">
		<div id="wrap2">
			<div id="vk_auth"></div>
		</div>
	</div>
</div>