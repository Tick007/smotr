<?php
$xmlstr = "<order>";
$xmltel="<span>+7 (906)7456278 <: !-> </span>
		<b>werwer</b>";

//$xmlenc = htmlspecialchars($xmltel, ENT_XML1, 'UTF-8');

$xmlenc = htmlentities($xmltel,ENT_QUOTES,'UTF-8');

//$xmlenc = trim(str_replace('+', '', (strip_tags(preg_replace('/\s\s+/', ' ', $xmltel)))));

$xmlstr.= "<host>" . $_SERVER ['HTTP_HOST'] . "</host>";
$xmlstr.='<tel>'.$xmlenc.'</tel></order>';

$xml = simplexml_load_string ( $xmlstr );

echo '<pre>';
print_r($xml);
echo '</pre>';
?>