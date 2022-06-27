<?php
date_default_timezone_set('Europe/Moscow');

$wsdl_url = "https://apitest.merlion.com/dl/mlservice3?wsdl";
$params = array('login' => "TC0028924|API",
		 'password' => "123456",
		 'encoding' => "UTF-8",
		 'features' => SOAP_SINGLE_ELEMENT_ARRAYS
);

$merlion = array();
$DEBUG = false;
if(isset($_GET['debug']) && $_GET['debug'] == 1){
	$DEBUG = true;
}

try {
	$client = new SoapClient($wsdl_url, $params);

	
	
	$date = date_create(date('Y-m-d'));
	date_modify($date, '+1 day');
	$checkDate = date_format($date, 'Y-m-d');

	//$cat = $client->getCatalog(); ////////Дерево каталога
	//$cat = $client->getItemsAvail('B10902','ДОСТАВКА', $checkDate,'',''); //ХЗ, но почему-то не работает
	//$cat = $client->getItems('B10902');
	//$cat = $client->getItemsAvail('B10902');
	
	//$cat = $client->getOrderLines("VB324370"); ////////пустой объект прилетает
	/*setOrderHeaderCommand - создать заказ,
	 * setOrderLineCommand или setAddOrderLineCommand - добавить в заказ товары
	 * */
	////$cat = $client->getPackingTypes();
	//$cat = $client->getShipmentMethods();
	//$cat = $client->getCounterAgent();// показывает меня
	//$cat = $client->getShipmentAgents(); // что - то показывает
	//$cat = $client->getRepresentative(); //  что - то показывает
	//$cat = $client->getEndPointDelivery();  // пустой объект
	//$cat = $client->getPackingTypes(); //дает список упаковок
	//$cat = $client->getOrdersList(); //дает заказ какой-то [document_no] => VB324370
	
	$cat = $client->setOrderHeaderCommand('34534', 'Самовывоз', $checkDate, '101259', 'КРАВЦ_МП', 'qeqw qwe qw qwee', 'ТЕСТ соап 1','101259', 1, 'СКОТЧ' );
	
	
} catch (SoapFault $E) {
	echo $E->faultstring;
	echo '<pre>';
	print_r($E);
	echo '</pre>';
}


echo '<pre>';
if(isset($cat)) print_r($cat);
echo '</pre>';




?>
<pre>
<xsd:element name="setOrderHeaderCommand">
<xsd:complexType>
<xsd:sequence>
<xsd:element name="document_no" type="xsd:string" nillable="true"/>
<xsd:element name="shipment_method" type="xsd:string" nillable="true"/>
<xsd:element name="shipment_date" type="xsd:string" nillable="true"/>
<xsd:element name="counter_agent" type="xsd:string" nillable="true"/>
<xsd:element name="shipment_agent" type="xsd:string" nillable="true"/>
<xsd:element name="end_customer" type="xsd:string" nillable="true"/>
<xsd:element name="comment" type="xsd:string" nillable="true"/>
<xsd:element name="representative" type="xsd:string" nillable="true"/>
<xsd:element name="endpoint_delivery_id" type="xsd:int" nillable="true"/>
<xsd:element name="packing_type" type="xsd:string" nillable="true"/>
</xsd:sequence>
</xsd:complexType>
</xsd:element>


stdClass Object
(
    [getPackingTypesResult] => stdClass Object
        (
            [item] => Array
                (
                    [0] => stdClass Object
                        (
                            [Code] => БАНДАЖ
                            [Description] => БАНДАЖ
                        )

                    [1] => stdClass Object
                        (
                            [Code] => ПАЛЛЕТ
                            [Description] => ПАЛЛЕТ
                        )

                    [2] => stdClass Object
                        (
                            [Code] => СКОТЧ
                            [Description] => СКОТЧ
                        )

                )

        )

)
stdClass Object
(
    [getShipmentMethodsResult] => stdClass Object
        (
            [item] => Array
                (
                    [0] => stdClass Object
                        (
                            [Code] => ДОСТАВКА
                            [Description] => Доставка со склада МСК
                            [IsDefault] => 1
                        )

                    [1] => stdClass Object
                        (
                            [Code] => КР_СМР_Д
                            [Description] => Доставка REGSMR_5
                            [IsDefault] => 0
                        )

                    [2] => stdClass Object
                        (
                            [Code] => КР_СМР_С
                            [Description] => Самовывоз REGSMR_5
                            [IsDefault] => 0
                        )

                    [3] => stdClass Object
                        (
                            [Code] => РЕКБ_2_ДС
                            [Description] => Доставка со склада РЕКБ_2
                            [IsDefault] => 0
                        )

                    [4] => stdClass Object
                        (
                            [Code] => РЕКБ_2_СВ
                            [Description] => Самовывоз со склада РЕКБ_2
                            [IsDefault] => 0
                        )

                    [5] => stdClass Object
                        (
                            [Code] => С/В
                            [Description] => Самовывоз
                            [IsDefault] => 0
                        )

                )

        )

)

stdClass Object
(
    [getCounterAgentResult] => stdClass Object
        (
            [item] => Array
                (
                    [0] => stdClass Object
                        (
                            [Code] => 101259
                            [Description] => ИП Кравцов Алексей (Владимирович) СЧЕТ
                        )

                    [1] => stdClass Object
                        (
                            [Code] => 60804
                            [Description] => ИП Кравцов Алексей (Владимирович) ЭФ13-0187
                        )

                )

        )

)

stdClass Object
(
    [getShipmentAgentsResult] => stdClass Object
        (
            [item] => Array
                (
                    [0] => stdClass Object
                        (
                            [Code] => КРАВЦ_МП
                            [Description] => Кравцов А.В. ИП
                        )

                    [1] => stdClass Object
                        (
                            [Code] => КРАВЦ_МЫТ
                            [Description] => Кравцов А.В. ИП
                        )

                    [2] => stdClass Object
                        (
                            [Code] => КРАВЦ_МЫТИ
                            [Description] => Кравцов А.В. ИП
                        )

                    [3] => stdClass Object
                        (
                            [Code] => КРАВЦ_ПЛ
                            [Description] => Кравцов А.В. ИП
                        )

                    [4] => stdClass Object
                        (
                            [Code] => КРАВЦ_ЧАС
                            [Description] => Кравцов А.В. ИП
                        )

                    [5] => stdClass Object
                        (
                            [Code] => КРАВЦОВ_МЫ
                            [Description] => Кравцов А.В. ИП
                        )

                    [6] => stdClass Object
                        (
                            [Code] => КРАВЦОВ1
                            [Description] => Кравцов А.В. ИП
                        )

                )

        )

)

stdClass Object
(
    [getRepresentativeResult] => stdClass Object
        (
            [item] => Array
                (
                    [0] => stdClass Object
                        (
                            [Representative] => Кравцов Алексей Владимирович
                            [CounterAgentCode] => 101259
                            [StartDate] => 2015-10-30
                            [EndDate] => 2016-10-30
                        )

                    [1] => stdClass Object
                        (
                            [Representative] => Кравцов Алексей Владимирович
                            [CounterAgentCode] => 60804
                            [StartDate] => 2013-02-28
                            [EndDate] => 2016-12-31
                        )

                )

        )

)

stdClass Object
(
    [getEndPointDeliveryResult] => stdClass Object
        (
            [item] => Array
                (
                    [0] => stdClass Object
                        (
                            [ID] => 
                            [Endpoint_address] => 
                            [Endpoint_contact] => 
                            [ShippingAgentCode] => 
                            [City] => 
                        )

                )

        )

)



</pre>