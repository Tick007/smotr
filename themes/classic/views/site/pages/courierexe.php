<?php
//include('Measoft.php');

$order_num = rand(118000, 119000);

$order = array(
		'orderno'=>$order_num ,//Номер заказа
		'barcode'=>'1234567890',//Штрих-код
		'company'=>'Иванов И.O.',//Компания-получатель. Должно быть заполнено company ИЛИ person!
		'person'=>'Иванов И.O.',//Контактное лицо. Должно быть заполнено company ИЛИ person!
		'phone'=>'89123456789',//Телефон. Можно указывать несколько телефонов
		'town'=>'Москва',//Город
		'address'=>'Сельскохозяйственная 22 - 87',//Адрес
		'date'=>date('Y-m-d'),//Дата доставки в формате "YYYY-MM-DD"
		'time_min'=>'12:00',//Желаемое время доставки в формате "HH:MM"
		'time_max'=>'20:00',//Желаемое время доставки в формате "HH:MM"
		'weight'=>5,//Общий вес заказа
		'quantity'=>1,//Количество мест
		'price'=>100,//Сумма заказа
		'inshprice'=>1000,//Объявленная стоимость
		'enclosure'=>'Это ТЕСТОВЫЙ заказ №'.$order_num ,//Наименование
		'instruction'=>'Комментарий №'.$order_num ,//Поручение
);

echo '<pre>';
print_r($order);
echo '</pre>';

$items = array(
		array(
				'name'=>'Наименование',//Название товара
				'quantity'=>2,//Количество мест
				'mass'=>1,//Масса единицы товара
				'retprice'=>35,//Цена единицы товара
		)
);

//Создаем экзепляр класса Меасофт
//$measoft = new Measoft('login', 'pass', 8);
$measoft = new Measoft('soda', 'Ujyb2Vjq!', 31);

//Пытаемся отправить заказ
if ($orderNumber = $measoft->orderRequest($order, $items)) {
	print 'Заказ '.$orderNumber.' успешно создан<br>';

	echo '<pre>';
	//print_r($status);
	echo '</pre>';
	
	if ($status = $measoft->statusRequest($orderNumber)) {
		print 'Заказ '.$orderNumber.' сейчас: '.$status;
	} else {
		print 'При получении статуса произошли ошибки:<br>';
		print_r($measoft->errors);
		echo '<pre>';
		print_r($measoft);
		echo '</pre>';
	}
} else {
	print 'При отправке заказа произошли ошибки:<br>';
	print_r($measoft->errors);
}



