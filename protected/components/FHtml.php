<?php

class FHtml extends CHtml{
	
	public static function get_files($path, $order = 0, $mask = '*')
	{
		
		$sdir = array();
			// получим все файлы из дирректории
			if (false !== ($files = scandir($path, $order)))
			{  
					foreach ($files as $i => $entry) 
					{
						   // если имя файла подходит под маску поика      
						   if ($entry != '.' && $entry != '..' && strstr($entry, $mask)) 
						   {
								  $sdir[] = $entry;
						   }
				}
			}
		return ($sdir);
	}
	
	public static function mb_ucfirst($str, $encoding='UTF-8')
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
               mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
	
	public static function getRootBreadcrumb()
	{
		return array('Главная'=>'/');
	}
	
	public static function correct_long_breadcrumbs($cat_path ){
			
			//echo count($cat_path);
			//echo '<pre>';
			//print_r($cat_path);
			//echo '</pre>';
			//echo Yii::app()->urlManager->urlSuffix.'<br>';
			
			//for ($k=1;$k<count($cat_path)-1; $k++ ) $cat_aliases[] = str_replace('/', '', $cat_path[$k]);
			$k=0;
			foreach($cat_path as $key=>$value) {

			/*
				if(isset(Yii::app()->params['coreElementDescr'])) {
						if ($key==Yii::app()->params['coreElementDescr']['old']) $key = Yii::app()->params['coreElementDescr']['new'];
					}
				*/
				//echo  "$key=>$value<br>";
				If($k!=0 AND $k<count($cat_path)-1) {
					if ($key=="Марки") $key = "Каталог";
				//	echo 'val = '.$value.'<br>';
				//	@print_r($pure_aliases);
				//	echo '<br>';
					if (isset($pure_aliases[$k-1])) $pure_aliases[]=$pure_aliases[$k-1].'/'.str_replace('/', '', $value);
					else $pure_aliases[$k]=str_replace('/', '', $value);
					$new_breadcr[$key] = '/'.'catalog/'.$pure_aliases[$k].'/';
				}
				else $new_breadcr[$key]=$value;
				$k++;
			}////foreach($cat_path as $key=>$value) {
			//echo '<br><br><pre>';
			//@print_r($new_breadcr);
		//	echo '</pre>';
			
			return $new_breadcr;
	}////////public staticfunction correct_long_breadcrumbs($cat_path ){

	public static function get_breadcrumbs_urls($path){
		
		//print_r($path);
		//echo '<br>';
		foreach($path as $key => $value) {
			//echo  "$key => $value";
			if($key!='0' AND $value!='/') {
				$temp_path[]=str_replace('/', '', $value);
				/////////////Если в масмсиве 1 член, то там alias б и ссылку на главную категори
				$cont_act = Yii::app()->params['url_correction']['controller'].'/'.Yii::app()->params['url_correction']['view'];
				if(count($temp_path)==1) {
					$url=Yii::app()->createUrl($cont_act, array('alias'=>str_replace('/', '',$value)));
					$new_path[$key]=urldecode($url);
				}
				///////////////////Если более, то последний член это алиас, остальные - путь
				else if(count($temp_path)>1) {
					$temp = $temp_path;
					array_pop($temp);
					  if(isset(Yii::app()->params['use_long_urls']) AND Yii::app()->params['use_long_urls']==true)  $url=Yii::app()->createUrl($cont_act, array('alias'=>str_replace('/', '',$value), 'path'=>implode('/', $temp)));
					  else $url=Yii::app()->createUrl($cont_act, array('alias'=>str_replace('/', '',$value)));
					$new_path[$key]=urldecode($url);
				}
				 
				
			}
			else $new_path[$key]=$value;
			
		}
		//echo '<br>';
		//print_r($new_path);
		
		return $new_path;
	}

	/*
	public static function urlpath($path){//////////////Делаем промежуточный путь
	$cat_path = array_values(unserialize($path));
	
	//print_r($cat_path);
	
	
	for ($k=2;$k<count($cat_path)-1; $k++ ) $cat_aliases[] = str_replace('/', '', $cat_path[$k]);
	//print_r($cat_aliases);
	if (isset($cat_aliases)) return implode('/', $cat_aliases);

	}/////////////public static function urlpath($path){//////////
	*/
	
	public static function urlpath($path){//////////////Делаем промежуточный путь
	//print_r(unserialize($path));
	/*
	if(is_array($path)==false) { 
		echo 'path = ' .$path;
		//exit();
		return NULL;
	}
	*/
	$qqq = unserialize($path);
	if(is_array($qqq)==false) {
		//echo 'path = ' .$path;
		//exit();
		return NULL;
	}
	$cat_path = array_values($qqq);
	for ($k=1;$k<count($cat_path)-1; $k++ ) $cat_aliases[] = str_replace('/', '', $cat_path[$k]);
	//print_r($cat_aliases);
	if (isset($cat_aliases)) return implode('/', $cat_aliases);

	}/////////////public static function urlpath($path){//////////
	

	public static function get_productiya_path($parent){
				
				//echo '$parent ='. $parent;
				
				if(isset(Yii::app()->params['main_tree_root'])) $root_level = Yii::app()->params['main_tree_root'];
				else  $root_level = 0;
				
				///////////////////////////////Вычисляем путь по дереву продукции
				$Path = Categories::model()->findbyPk($parent);
				$parent_id = $Path->parent;
				/////////Yii::app()->params->drupal_vars['taxonomy_catalog_level'] - нулевой уровень каталога
				//$path_text = CHtml::link($Path->category_name, array('/adminproducts/','group'=>$Path->category_id), $htmlOptions=array ('encode'=>false));
				$path_info = array (
				$Path->category_name,
				);
				$cat_name=$Path->category_name;
				
				$path_info=$path_info1;
				/*
				echo '<br>'.$root_level.'<br>';
				echo 'parent_id = '.$parent_id.'<br>';
				exit();
				*/
				while ($parent_id!=$root_level AND $parent_id!=0) {
				//echo 'wewer';
				//exit();
				$Path = Categories::model()->findbyPk($parent_id);
				$parent_id = $Path->parent;
						//echo '$Path->alias = '.$Path->alias.'<br>';
						//if (trim($Path->alias)!='') $path_info[$Path->category_name]=Yii::app()->createUrl('/'.$Path->alias);
						if (trim($Path->alias)!='') $path_info[$Path->category_name]='/'.$Path->alias;
						
						else $path_info[$Path->category_name]=Yii::app()->createUrl('/'.$Path->category_id);
						
				}///////while
				//$path_info = array_merge($path_info, array('Маркет'=>Yii::app()->request->baseUrl, 'Главная'=>'/') );
				$path_info = array_merge($path_info, array('Главная'=>'/') );
				$path_info = array_reverse($path_info);
				
				if(empty($path_info)==false)$path_info = array_merge($path_info, array(0=>$cat_name));
				else $path_info = array('Главная'=>'/', 0=>$cat_name);
				
				//print_r($path_info);
				//exit();
				
				return $path_info;
	}/////////////
	
	public static function get_path($path){
		
	}
	

	public static function getkeywords($keywords)	{
	//echo $keywords;
			$stoplist=array('для');
						///////////////////////////////////////////////////////////////Вычисляем ключевые слова
			$keywords_arr=explode(' ', $keywords);//////////////////Разбили
			//print_r($keywords_arr);
			$new_arr=NULL;
			foreach ($keywords_arr AS $key=>$value):///////////////////////////////////////Считаем сколько раз считается каждое слово
				if(strlen($value)>4 AND in_array($value, $stoplist) == false) {
					if (@array_key_exists($value, $new_arr)) $new_arr[$value]=$new_arr[$value]+1;
					else $new_arr[$value]=1;
				}
			endforeach;
			if (is_array($new_arr)==true) {
					foreach ($new_arr as $word=>$number):
							if (strlen($word)>4) {
							$data[] = array('word' => $word, 'number' => $number);
							$words[]  =$word;
							$numbers[] = $number;
							}
					endforeach;
					array_multisort($numbers, SORT_DESC, $words, SORT_ASC, $data);
					$keywords = NULL;
					$c=0;
					foreach($data as $key=>$row):
					
							if($c<8) {
							$keywords[] .=str_replace (',', '', str_replace ('.', '', $row['word']));//////////////////Удалили всякие точки и запятые
							$c++;
							}
							else break;
					endforeach;
					$keywords=implode(', ', $keywords);
					
					//echo '<hr>'.$keywords;
					
					return  mb_strtolower($keywords);
			}//////////////if (is_array($new_arr)==true) {
			else return '';
	}//////////////public static function getkewwords($kewords);

	public static function translit($word)
	{
		return strtr($word, array(
			'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ж'=>'g', 'з'=>'z',
			'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p',
			'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i', 'э'=>'e', 'А'=>'a',
			'Б'=>'b', 'В'=>'v', 'Г'=>'g', 'Д'=>'d', 'Е'=>'e', 'Ж'=>'g', 'З'=>'z', 'И'=>'i',
			'Й'=>'y', 'К'=>'k', 'Л'=>'l', 'М'=>'m', 'Н'=>'n', 'О'=>'o', 'П'=>'p', 'Р'=>'r',
			'С'=>'s', 'Т'=>'t', 'У'=>'u', 'Ф'=>'f', 'Ы'=>'i', 'Э'=>'e', 'ё'=>"yo", 'х'=>"h",
			'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"sch", 'ъ'=>"", 'ь'=>"", 'ю'=>"yu", 'я'=>"ya",
			'Ё'=>"yo", 'Х'=>"h", 'Ц'=>"ts", 'Ч'=>"ch", 'Ш'=>"sh", 'Щ'=>"sch", 'Ъ'=>"", 'Ь'=>"",
			'Ю'=>"yu", 'Я'=>"ya"
			)
		);
	}
	
	public function shortText($text, $chars= 100)
	{
		mb_internal_encoding("UTF-8");
		$text=strip_tags($this->text);
		if ($pos= mb_strpos($text, ' ', $chars))
			return mb_substr($text, 0, $pos).'...';
		else
			return $text;
		
	}
	
	
	public function CLTextArea($model, $attribute, $options=array(), $htmlOptions=array())
	{
		if (isset($htmlOptions['class']))
		{
			$htmlOptions['class']=$htmlOptions['class'].' CLTextArea';
		}
		else
			$htmlOptions['class']='CLTextArea';
		
		$options=array_merge( array(
			'width'=>800, // width not including margins, borders or padding
			'height'=>500, // height not including margins, borders or padding
			'controls'=>     // controls to add to the toolbar
					"bold italic underline strikethrough subscript superscript | font size " .
					"style | color highlight removeformat | bullets numbering | outdent " .
					"indent | alignleft center alignright justify | undo redo | " .
					"rule image link unlink | cut copy paste pastetext | print source",
		   'colors'=>       // colors in the color popup
					 "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " .
					 "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " .
					 "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " .
					 "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " .
					 "666 900 C60 C93 990 090 399 33F 60C 939 " .
					 "333 600 930 963 660 060 366 009 339 636 " .
					 "000 300 630 633 330 030 033 006 309 303",    
			'fonts'=>        // font names in the font popup
					"Tahoma",
			'sizes'=>        // sizes in the font size popup
					"1,2,3,4,5,6,7",
			'styles'=>       // styles in the style popup
					array(array("Paragraph", "<p>"), array("Header 1", "<h1>"), array("Header 2", "<h2>"),
					 array("Header 3", "<h3>"),  array("Header 4","<h4>"),  array("Header 5","<h5>"),
					 array("Header 6","<h6>")),
			 'useCSS'=>       true, // use CSS to style HTML when possible (not supported in ie)
			),
			$options);

			Yii::import('application.extension.CLEditor.jqClEditor');

			jqClEditor::clEditor('.CLTextArea',$options);

			return parent::activeTextArea($model, $attribute, $htmlOptions);


	}
	
	public function declinate($nom, $case)
	{
		if (!$word=Words::model()->findByAttributes(array('nom'=>$nom)))
		{
			$word= new Words;
			
			// Building Request URL
			$url = 'http://export.yandex.ru/inflect.xml?name='.urlencode($nom);

			// Processing CURL Request
			$curl = curl_init( $url );
			curl_setopt( $curl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.6.30 Version/10.61' ); // Just for fun, or ...
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			$result = curl_exec( $curl );
			curl_close( $curl );
					
			// Preparing Inflections
			$cases = array ();
			preg_match_all( '#\<inflection\s+case\=\"([0-9]+)\"\>(.*?)\<\/inflection\>#si', $result, $m );
			
			// Creating Inflection List
			if ( count($m[0]) ) 
			{
				foreach ( $m[1] as $i => &$id ) {
					$cases[ (int) $id ] = $m[2][$i];
				} unset ( $id );
				$word->nom=(isset($cases[1]))?$cases[1]:$nom;
				$word->gen=(isset($cases[2]))?$cases[2]:$nom;
				$word->dat=(isset($cases[3]))?$cases[3]:$nom;
				$word->acc=(isset($cases[4]))?$cases[4]:$nom;
				$word->str=(isset($cases[5]))?$cases[5]:$nom;
				$word->prep=(isset($cases[6]))?$cases[6]:$nom;
			}
			else
			{
				$word->nom=$nom;
				$word->gen=$nom;
				$word->dat=$nom;
				$word->acc=$nom;
				$word->str=$nom;
				$word->prep=$nom;
			}
			$word->save();
			
		}
		return $word->$case;
	}
	
	public function activeTextFieldHistory($model, $attribute, $htmlOptions=null)
	{

		$mod=$model;
		$attr=$attribute;
		self::resolveNameID($mod,$attr,$htmlOptions);
		$route=array('/admin/default/search', 'model'=>get_class($model), 'attribute'=>$attr);
		if (isset($htmlOptions['filter']))
			$route['filter']=$htmlOptions['filter'];
		$this->widget('zii.widgets.jui.CJuiAutoComplete', 
			array('model'=>$model,
				  'attribute'=>$attr, 
				  'htmlOptions'=>$htmlOptions,
				  'sourceUrl'=>$route));
	}

	public static function enum($model, $attribute)
	{
		$mod=$model;
		$attr=$attribute;
		self::resolveNameID($mod,$attr,$htmlOptions);
		$enum=$model->tableSchema->columns[$attr]->dbType;
		$off=strpos($enum,"(");
		$enum=substr($enum, $off+1, strlen($enum)-$off-2);
		$keys=str_replace("'",null,explode(",",$enum));
		for($i=0;$i<sizeof($keys);$i++)
			$values[$keys[$i]]=Yii::t('enumItem',$keys[$i]);
		return $values;
	
	}

	public static function enumDropDownList($model,$attribute, $htmlOptions=null)
	{
		$mod=$model;
		$attr=$attribute;
		self::resolveNameID($mod,$attr,$htmlOptions);
		$enum=$model->tableSchema->columns[$attr]->dbType;
		$off=strpos($enum,"(");
		$enum=substr($enum, $off+1, strlen($enum)-$off-2);
		$keys=str_replace("'",null,explode(",",$enum));
		for($i=0;$i<sizeof($keys);$i++)
			$values[$keys[$i]]=Yii::t('enumItem',$keys[$i]);
		$htmlOptions['prompt']='';
		return CHtml::activeDropDownList($model,$attr,
				$values,
				$htmlOptions);	
		
	}

	
	public static function FAjaxSubmitButton($label, $url, $div, $options=array())
	{

		return CHtml::submitButton(
			$label,
			array_merge($options, 
				array(
					'onClick'=>CHtml::ajax(array
					(
						'id'=>$url,
						'update'=>$div,
						'type'=>'POST',
						'beforeSend' => 'function(){
								$("'.$div.'").addClass("loading");
							}',
						'complete' => 'function(){
								$("'.$div.'").removeClass("loading");
							}',			
					)).'return false;', 
					'style'=>(isset($options['style']))?$options['style']."cursor:pointer;":"cursor:pointer;"
				)
			)
		);

	}	
		
	public static function FAjax($url, $div, $options=array())
	{
		return CHtml::ajax(array_merge($options,
		array
		(
			'id'=>$url,
			'update'=>$div,
			'type'=>'POST',
				'beforeSend' => 'function(){
					$("'.$div.'").addClass("loading");
				}',
			'complete' => 'function(){
					$("'.$div.'").removeClass("loading");
				}',			
		)));	
	}
	

	public static function FAjaxButton($label, $url, $div, $options=array())
	{
		$beforeAjax='';
		if (isset($options['params']))
		{
		
			foreach ($options['params'] as $key=>$value)
			{
				$beforeAjax.="jQuery(this).parents('form').append(\"<input type='hidden' name='$key' value='$value' class='deleteImmediatly' />\"); ";
			}
			
			unset ($options['params']);
		}		
		return CHtml::link(
			$label,
			'',
			array_merge($options, 
				array(
					'onClick'=>$beforeAjax.CHtml::ajax(array
					(
						'id'=>$url,
						'update'=>$div,
						'type'=>'POST',
							'beforeSend' => 'function(){
								$("'.$div.'").addClass("loading");
							}',
						'complete' => 'function(){
								$("'.$div.'").removeClass("loading");
							}',			
					)).'$(".deleteImmediatly").detach();', 
					'style'=>"cursor:pointer;"
				)
			)
		);

	}


	
	public static function foreignDropDownList($model,$attribute,$htmlOption=null)
	{	
		
		$mod=$model;
		$attr=$attribute;
		self::resolveNameID($mod,$attr,$htmlOptions);
	
		$htmlOption['prompt']='';
		$relations=$model->relations();
		$column=$model->tableSchema->columns[$attr];
		if (isset($relations['fk_'.$column->name]))
			$table=$relations['fk_'.$column->name][1];
		if (isset($relations[$column->name.'0']))
			$table=$relations[$column->name.'0'][1];
		$primarykey=CActiveRecord::model($table)->tableSchema->primaryKey;
		$criteria=new CDbCriteria;
		if (isset($htmlOption['condition']))
		{
			$criteria->condition=$htmlOption['condition'];
			unset($htmlOption['condition']);
		}
		if (isset($htmlOption['order']))
		{
			$criteria->order=$htmlOption['order'];
			unset($htmlOption['order']);
		}

			
		return CHtml::activeDropDownList($model,$attr,
			CHtml::listData(CActiveRecord::model($table)->findAll($criteria),
			$primarykey,'recordDescription'),
			$htmlOption);		
	}

	public static function XMail( $from, $to, $subj, $text, $filename)
		{
		$f         = fopen($filename,"rb");
		$un        = strtoupper(uniqid(time()));
		$head      = "From: $from\n";
		$head     .= "To: $to\n";
		$head     .= "Subject: $subj\n";
		$head     .= "X-Mailer: PHPMail Tool\n";
		$head     .= "Reply-To: $from\n";
		$head     .= "Mime-Version: 1.0\n";
		$head     .= "Content-Type:multipart/mixed;";
		$head     .= "boundary=\"----------".$un."\"\n\n";
		$zag       = "------------".$un."\nContent-Type:text/html;\n";
		$zag      .= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
		$zag      .= "------------".$un."\n";
		$zag      .= "Content-Type: application/octet-stream;";
		$zag      .= "name=\"".basename($filename)."\"\n";
		$zag      .= "Content-Transfer-Encoding:base64\n";
		$zag      .= "Content-Disposition:attachment;";
		$zag      .= "filename=\"".basename($filename)."\"\n\n";
		$zag      .= chunk_split(base64_encode(fread($f,filesize($filename))))."\n";
		
		if (!@mail("$to", "$subj", $zag, $head))
		 return 0;
		else
		 return 1;
		}

	
	public static function encodeValuta($data, $valuta)
	{		
		return CHtml::encode(Yii::app()->locale->numberFormatter->formatCurrency($data, $valuta));
	}

	public static function encodeDate($data, $dateWidth='full')
	{
		return CHtml::encode(Yii::app()->locale->dateFormatter->formatDateTime(strtotime($data),$dateWidth, null));
	}

	public static function encodeBoolean($data)
	{
		if ($data)
			return Yii::t('enumItem', 'sì');
		else
			return Yii::t('enumItem', 'no');
	}
	
	public function encodeEnum($data)
	{
		return Yii::t('enumItem',$data);
	}
	
	public function datePicker($model, $attribute, $htmlOptions=array())
	{
		CHtml::resolveNameId($model, $attribute, $htmlOptions);
		$language= Yii::app()->language;
		if ($language=='en')
			$language='';
		return $this->widget('zii.widgets.jui.CJuiDatePicker', 
			array(
				'model'=>$model,
				'attribute'=>$attribute,
				'language'=>$language,
				'htmlOptions'=>$htmlOptions,
				'options'=>array('dateFormat' => 'dd.mm.y')
			), true);
	}
	
	
		function product_ostatok_by_store ($product_id_arr,  $store_id) {
$query= "SELECT (IF (store1.prihod IS NULL, 0,store1.prihod)  - IF(store1.rashod IS NULL, 0, store1.rashod) ) AS prod_quant, products.id 
FROM products
LEFT JOIN (
SELECT SUM( series.num ) AS prihod, series_movement_temp.rashod, products.id AS product_id 
FROM series
LEFT JOIN (
SELECT SUM( series_movement.num ) AS rashod, series_movement.product_id
FROM series_movement
WHERE series_movement.store_id = :store_id
GROUP BY series_movement.product_id
)series_movement_temp ON series.product_id = series_movement_temp.product_id
JOIN products ON series.product_id = products.id
JOIN categories categories ON categories.category_id = products.category_belong
JOIN categories parent_categories ON categories.parent = parent_categories.category_id
WHERE series.store_id = :store_id
AND products.id IN (".implode(',',$product_id_arr ).")
GROUP BY products.product_name
ORDER BY products.product_name, series.arrive_dt, products.id
)store1 ON products.id = store1.product_id 
WHERE products.id IN (".implode(',',$product_id_arr ).") ";
//echo "$query<br>";
/*
if (!$res=mysql_query($query, $cn)) echo mysql_error();
$def=mysql_fetch_row($res);
return $def[0];
*/
$connection=Yii::app()->db;
$command=$connection->createCommand($query)	;
$command->params=array(':store_id'=>$store_id);
$dataReader=$command->query();
$res = $dataReader->readAll();

return($res);

}///////function product_ostatok ($id, $cn, $store_id) {
	
	public static function changespan_to_link($text, $id, $cont_action, $linkidentifier, $class=NULL) {////////Замена в тексте содержимого спан на ссылку
		preg_match_all ('|<span>(.*)</span>|isU', $text, $content2, PREG_SET_ORDER);
		 if(isset($content2['0']['1'])) $link_text = $content2['0']['1'];
		 if(isset($link_text)){
	
			$link = CHtml::link($link_text, array($cont_action, $linkidentifier=>$id), array('class'=>$class));
			return str_replace($link_text, $link, $text);
			//echo $model->short_descr;
		}
		else return CHtml::link($text, array($cont_action, $linkidentifier=>$id), array('class'=>$class));
	}////////public static function changespan_to_link($text, $id, $cont_action, $linkidentifier, $class=NULL) {////////З
	
	public static function get_russion_months(){
		return array('январь', 'февраль', 'март', 'апрель' , 'май', 'июнь' , 'июль',  'август',  'сентябрь',  'октября',  'ноябрь',  'декабрь');
	}
	
	public static function generate_password($number)
	  {
		$arr = array('a','b','c','d','e','f',
					 'g','h','i','j','k','l',
					 'm','n','o','p','r','s',
					 't','u','v','x','y','z',
					 'A','B','C','D','E','F',
					 'G','H','I','J','K','L',
					 'M','N','O','P','R','S',
					 'T','U','V','X','Y','Z',
					 '1','2','3','4','5','6',
					 '7','8','9','0','.',',',
					 '(',')','[',']','!','?',
					 '&','^','%','@','*','$',
					 '<','>','/','|','+','-',
					 '{','}','`','~');
		// Генерируем пароль
		$pass = "";
		for($i = 0; $i < $number; $i++)
		{
		  // Вычисляем случайный индекс массива
		  $index = rand(0, count($arr) - 1);
		  $pass .= $arr[$index];
		}
		return $pass;
	  }

public static function get_cart_content($cartstr){
	$qqq=explode("#",$cartstr);
	//print_r($qqq);
	for ($i=0; $i<count($qqq); $i++) {//////////////
		$qqq2=explode(":",$qqq[$i]);
		if(is_numeric($qqq2[0]) AND is_numeric($qqq2[1])){
			$id[]=$qqq2[0];
			$num[]=$qqq2[1];
		}
	}
	if(isset($id) AND is_array($id) AND empty($id)==false) {
		$cart = array_combine($id, $num);
		$criteria=new CDbCriteria;
		$criteria->order = ' t.id ';
		$criteria->condition = "t.product_visible=1 AND t.product_price>0 AND t.id IN (".implode(',',$id ).")";
		$models = Products::model()->findAll($criteria);
		if(isset($models)){
			$sum = 0;
			for($i=0; $i<count($models); $i++) {
			   if($models[$i]->product_sellout == 1 AND $models[$i]->sellout_price>0) $curprice = $models[$i]->sellout_price;
			   else $curprice = $models[$i]->product_price;
			   $sum+=($curprice*$cart[$models[$i]->id]);
			}//////for($i=0; $i<count($models); $i++) {
			$ret = 'Товаров: '.$i.'<br>';
			$ret.= '<nobr>На сумму: '.$sum.' Руб.</nobr><br>';
			$ret.= CHtml::link('оформить', '/cart');	
			return $ret;
		}///////if(isset($models)){
	}///////if(isset($id) AND is_array(
	//echo '<pre>';
	//print_r($cart);
	//echo '</pre>';
	
}

public static function reverse($income)
{
	$outcome = '';
	//echo strlen($income);
	/*
	 for($i=0; $i<strlen($income); $i++){
	echo $i.': '.ord($income[$i]).'<br>';
	}
	*/if(strlen($income)==4) {
	$qqq = $income[3];
	$qqq.= $income[2];
	$qqq.= $income[1];
	$qqq.= $income[0];
	}
	if(strlen($income)==8){
		$qqq = $income[7];
		$qqq.= $income[6];
		$qqq.= $income[5];
		$qqq.= $income[4];
		$qqq = $income[3];
		$qqq.= $income[2];
		$qqq.= $income[1];
		$qqq.= $income[0];
	}
	if(isset($qqq)) return $qqq;
	else {
		echo 'error while reverse data: ';
		var_dump($income);
		exit();
	}
}
 
public static function InttoIP($iprdaft){
	$iprdaft = intval($iprdaft);
	$iprdaft_bin = pack("I", $iprdaft);
	$ipstring = unpack("c", $iprdaft_bin[0])[1].'.'.unpack("c", $iprdaft_bin[1])[1].'.'.unpack("c", $iprdaft_bin[2])[1].'.'.unpack("c", $iprdaft_bin[3])[1];
	return $ipstring;
	
	
	//return long2ip($iprdaft); 
	
}
public static function IPtoInt($ipstring){
	/*
	
	print_r($ipparts);
	$binint = '';
	for ($i=0; $i<4; $i++){
		$binint.=pack('x', $ipstring[$i]);
	}
	//$binint=FHtml::reverse($binint);
	$binipint = unpack("L", $binint);
	return $binipint[1];
	*/
	$ipparts = explode('.', $ipstring);
	$ipparts_reverse[0] = $ipparts[3];
	$ipparts_reverse[1] = $ipparts[2];
	$ipparts_reverse[2] = $ipparts[1];
	$ipparts_reverse[3] = $ipparts[0];
	

	return ip2long(implode('.', $ipparts_reverse));

}



	
}//////////////////class

?>