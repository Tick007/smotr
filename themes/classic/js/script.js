$(document).ready(function () {
	
	$('#ciclo a').click(function() {/////////////////////////////Функция для вывда окошка с выбором региона и передачи ему ссылки
			
			//console.log($(this));
		
			 jQuery.ajax({
								'type':'POST',
								'url':'/ciclogram/ciclogrampup',
								'cache':false,
								'data': {'link':$(this).attr('id')},
								'success':function(response){
											if(response!='') {
												resp = response;
												//console.log($('.paulund_block_page'));
												if (resp.length>0) {
														
														
														
														$('.paulund_modal_2').paulund_modal_box({
															title:'Выбор циклограммы:',
															width: '1280',
															height: '900',
															left:  '200px',
															top: '20px',
																description:resp
														});
														$('.paulund_modal_2').click();		
												}
												else document.location=resp;
											}
									},
								'error':function(){
									resp = 'error';
								}	
		  					});
		
			
	});
	
	

	
});