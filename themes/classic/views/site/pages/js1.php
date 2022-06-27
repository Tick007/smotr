<script>
$('body').on('click', 'form.order-popup__form button', function(){
	var name = $('form.order-popup__form input[name=person-name]').val();
	var phone = $('form.order-popup__form input[name=person-phone]').val();
	ig()
	var id = $('form.order-popup__form input[name=item-id]').val();
	var amount = parseInt($('.order-popup__count-val b').html());
	$.ajax({
		url: 'ajax/oneclick.php',
		data: { name:name, phone:phone, id:id, amount:amount },
		type: 'post',
		dataType: 'json',
		success: function(data){
			//$('form.order-popup__form').replaceWith('Номер вашего заказа '+data.order_id);
			window.location.href = 'http://sodastore.ru/order/'+data.url;
		}
	});
});
</script>