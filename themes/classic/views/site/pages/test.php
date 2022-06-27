<?php 



//$models = Ka::model()->findAll();
//$models = Ka::model()->with('katype')->findAll();
//$model = Ka::model()->with('katype')->findByPk(1)



$criteria=new CDbCriteria;
//$criteria->select=array("t.*", "katype.name AS ktname");
//$criteria->condition="spacecrafts.name like 'YAMAL%'";




//$models = KaTypes::model()->with('spacecrafts')->findAll($criteria);

echo '<br><hr><br>';
//print_r($models[0]->attributes);
/*
for($i=0;$i<count($models);$i++){
	echo count($models[$i]->spacecrafts).'<br>';
	if(isset($models[$i]->spacecrafts->hardware))echo $models[$i]->spacecrafts->hardware[0];
}
*/
$page = Yii::app()->getRequest()->getParam('page', NULL);
echo 'Страница = '.$page.'<br>';

$count=Payload::model()->count($criteria);

$pages=new CPagination($count);
$pages->pageSize=5;
//$pages->setCurrentPage($page) ;
//echo 'currentPage = '.$pages->currentPage.'<br>';
//echo '$pages->pageSize = '.$pages->pageSize.'<br>';

$pages->applyLimit($criteria);

$models = Payload::model()->findAll($criteria);

echo 'Количество = '.count($models).'<br>';

echo '<pre>';
print_r($models[0]->attributes);
echo '</pre>';
//$models[0]->save();
//echo '<br><hr><br><pre>';
//print_r($models[0]);
//echo '</pre>';
?>