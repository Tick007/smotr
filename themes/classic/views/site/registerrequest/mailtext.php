Здравствуйте!
Поступила заявка на на регистрацию:<br><br>
<?php
$labels = $contact->attributeLabels();
foreach ($contact->attributes as $attribute_name=>$attribute_value){
/*
if($attribute_name=='education') {
	$attribute_value = $contact->educationlist[$attribute_value];
}
*/
if(isset($labels[$attribute_name]))echo $labels[$attribute_name].': '.$attribute_value.'<br>';

}
?>