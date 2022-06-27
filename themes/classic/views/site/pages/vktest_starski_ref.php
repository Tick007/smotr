<h1>click here</h1>

<div id="mydiv"><iframe id="frame" src="http://vk.com/widget_auth.php?app=5114001">
   </iframe>
</div>
<input id ="btnSubmit" type="submit" value="Get"/>
<script>

$( "#btnSubmit" ).click(function() {
  alert($("#frame").contents());
});
</script>