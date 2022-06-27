ewrwerew
<?php
$clientScript = Yii::app ()->clientScript;
$clientScript->registerCssFile ( Yii::app ()->baseUrl . '/js/kladrapi/jquery.kladr.min.css' );
$clientScript->registerScriptFile(Yii::app ()->baseUrl.'/js/kladrapi/jquery.kladr.min.js', CClientScript::POS_HEAD);

?>


  <div id="simple">
            <a name="simple"></a>
            <div class ="address">
                <h2>Простой пример</h2>
                <div class="field">
                    <input name="city" type="text" value="" placeholder="Город">
                </div>
            </div>
        </div>

<script>


$( document ).ready(function() {
	// Simple example
	
		var $container = $(document.getElementById('simple'));

		$container
			.find('[name="city"]')
			.kladr({
				type: $.kladr.type.city
			});
	
});

</script>