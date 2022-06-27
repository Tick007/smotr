<?php
///////////Тест проверки расчета стоимости доставки DPD для sodastore
// Получаем с высчетом товаров

$weight =5.5;
$volume = 0;
$products_prices = 1545;

$request = array();
$request['request']['auth'] = array( 'clientNumber' => '1001030212', 'clientKey' => '76CE6368D2D0E2DA4D5E461AF2397DA459AFF2E0' );
$request['request']['pickup'] = array('cityID' => '49694102', 'cityName' => 'Москва', 'regionCode' => '77', 'countryCode' => 'RU');

// Прочие настройки
$request['request']['selfPickup'] = false;
//$request['request']['selfDelivery'] = false;
//$request['request']['maxCost'] = '';
//$resuest['request']['maxDays'] = '7';
$request['request']['serviceCode'] = 'PCL';

//$request['request']['delivery'] = array('cityID' => '195733465', 'cityName' => 'Калуга', 'regionCode' => '40', 'countryCode' => 'RU');
//$request['request']['delivery'] = array('cityID' => '195950074', 'cityName' => 'Магадан', 'regionCode' => '49', 'countryCode' => 'RU');
//$request['request']['delivery'] = array('cityID' => '195702166', 'cityName' => 'Богучар', 'regionCode' => '36', 'countryCode' => 'RU');
//$request['request']['delivery'] = array('cityID' => '195855449', 'cityName' => 'Алатырь', 'regionCode' => '21', 'countryCode' => 'RU');
//$request['request']['delivery'] = array('cityID' => '195835617', 'cityName' => 'Адлер', 'regionCode' => '23', 'countryCode' => 'RU');
//$request['request']['delivery'] = array('cityID' => '49694102', 'cityName' => 'Москва', 'regionCode' => '77', 'countryCode' => 'RU');
$request['request']['delivery'] = array('cityID' => '48994107', 'cityName' => 'Екатеринбург', 'regionCode' => '66', 'countryCode' => 'RU');

$request['request']['weight'] = $weight + 1.5; // вес
$request['request']['volume'] = $volume; // объем
$request['request']['declaredValue'] = $products_prices;  // цена
$request['request']['selfDelivery'] = false;

$height =  pow($request['request']['weight'], 1/3);

$request['request']['parcel'] = array('weight' => $request['request']['weight'], 'quantity' => 1, 'length' => $height, 'width' => $height, 'height' => $height);

try{
	echo 'SOAP_CLIENT<br>';
	
	$SOAP_CLIENT = new SoapClient('http://ws.dpd.ru/services/calculator2?wsdl');

	$res = $SOAP_CLIENT->getServiceCostByParcels($request);
	
	print_r($res);
	echo '<pre>';
	print_r($request);
	echo '</pre>';
}
catch (Exception $e){
	echo '<pre>';
	print_r($e);
	echo '</pre>';
}
	?>
	
	<hr>
<!-- 	
	SoapFault Object
(
    [message:protected] => Недопустимое значение поля 
    [string:Exception:private] => 
    [code:protected] => 0
    [file:protected] => /home/soda/public_html/deliveries/dpd.php
    [line:protected] => 125
    [trace:Exception:private] => Array
        (
            [0] => Array
                (
                    [file] => /home/soda/public_html/deliveries/dpd.php
                    [line] => 125
                    [function] => __call
                    [class] => SoapClient
                    [type] => ->
                    [args] => Array
                        (
                            [0] => getServiceCostByParcels
                            [1] => Array
                                (
                                    [0] => Array
                                        (
                                            [request] => Array
                                                (
                                                    [auth] => Array
                                                        (
                                                            [clientNumber] => 1001030212
                                                            [clientKey] => 76CE6368D2D0E2DA4D5E461AF2397DA459AFF2E0
                                                        )

                                                    [delivery] => Array
                                                        (
                                                            [cityID] => 195733465
                                                            [cityName] => Калуга
                                                            [regionCode] => 40
                                                            [countryCode] => RU
                                                        )

                                                    [pickup] => Array
                                                        (
                                                            [cityID] => 49694102
                                                            [cityName] => Москва
                                                            [regionCode] => 77
                                                            [countryCode] => RU
                                                        )

                                                    [selfPickup] => 
                                                    [selfDelivery] => 
                                                    [serviceCode] => PCL
                                                    [weight] => 5.5
                                                    [volume] => 0.02
                                                    [declaredValue] => 6799
                                                    [parcel] => Array
                                                        (
                                                            [weight] => 5.5
                                                            [quantity] => 1
                                                            [length] => 0
                                                            [width] => 0
                                                            [height] => 0
                                                        )

                                                )

                                        )

                                )

                        )

                )

            [1] => Array
                (
                    [file] => /home/soda/public_html/deliveries/dpd.php
                    [line] => 125
                    [function] => getServiceCostByParcels
                    [class] => SoapClient
                    [type] => ->
                    [args] => Array
                        (
                            [0] => Array
                                (
                                    [request] => Array
                                        (
                                            [auth] => Array
                                                (
                                                    [clientNumber] => 1001030212
                                                    [clientKey] => 76CE6368D2D0E2DA4D5E461AF2397DA459AFF2E0
                                                )

                                            [delivery] => Array
                                                (
                                                    [cityID] => 195733465
                                                    [cityName] => Калуга
                                                    [regionCode] => 40
                                                    [countryCode] => RU
                                                )

                                            [pickup] => Array
                                                (
                                                    [cityID] => 49694102
                                                    [cityName] => Москва
                                                    [regionCode] => 77
                                                    [countryCode] => RU
                                                )

                                            [selfPickup] => 
                                            [selfDelivery] => 
                                            [serviceCode] => PCL
                                            [weight] => 5.5
                                            [volume] => 0.02
                                            [declaredValue] => 6799
                                            [parcel] => Array
                                                (
                                                    [weight] => 5.5
                                                    [quantity] => 1
                                                    [length] => 0
                                                    [width] => 0
                                                    [height] => 0
                                                )

                                        )

                                )

                        )

                )

            [2] => Array
                (
                    [file] => /home/soda/public_html/deliveries/dpd.php
                    [line] => 29
                    [function] => Calculate
                    [class] => dpdDelivery
                    [type] => ->
                    [args] => Array
                        (
                        )

                )

            [3] => Array
                (
                    [file] => /home/soda/public_html/view/CartView.php
                    [line] => 315
                    [function] => show
                    [class] => dpdDelivery
                    [type] => ->
                    [args] => Array
                        (
                            [0] => Array
                                (
                                    [delivery_id] => 7
                                )

                        )

                )

            [4] => Array
                (
                    [file] => /home/soda/public_html/view/IndexView.php
                    [line] => 105
                    [function] => fetch
                    [class] => CartView
                    [type] => ->
                    [args] => Array
                        (
                        )

                )

            [5] => Array
                (
                    [file] => /home/soda/public_html/index.php
                    [line] => 29
                    [function] => fetch
                    [class] => IndexView
                    [type] => ->
                    [args] => Array
                        (
                        )

                )

        )

    [previous:Exception:private] => 
    [faultstring] => Недопустимое значение поля 
    [faultcode] => S:Server
    [detail] => stdClass Object
        (
            [ServiceCostFault] => stdClass Object
                (
                    [code] => error-value
                    [message] => Недопустимое значение поля 
                )

        )

    [xdebug_message] => ( ! ) SoapFault: Недопустимое значение поля  in /home/soda/public_html/deliveries/dpd.php on line 125
Call Stack
#TimeMemoryFunctionLocation
10.0001668624{main}(  )../index.php:0
20.00952732288IndexView->fetch(  )../index.php:29
30.01523654336CartView->fetch(  )../IndexView.php:105
40.01593813024dpdDelivery->show(  )../CartView.php:315
50.01603817160dpdDelivery->Calculate(  )../dpd.php:29
60.01803937416getServiceCostByParcels
(  )../dpd.php:125

)
	-->