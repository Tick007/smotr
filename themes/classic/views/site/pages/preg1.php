<?php
$text = '<p><span class="dealer_adres">г.Самара, Аэропортовское шоссе, б/н</span><br /><span class="dealer_tel">+7 (846) 230-02-16</span><br>
<span class="dealer_fax">факс: +7 (846) 230-02-17, доб.5155</span>
</p>';
//preg_match("/\<span\sclass=\"dealer_tel\"\>(.*)<\/span\>/imsU", $text, $matches);
preg_match("/\<span\sclass=\"dealer_tel\"\>(.*)<\/span\>/imsU", $text, $matches);

print_r($matches);

echo '<br>'.$matches[0];