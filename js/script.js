
jQuery(document).ready(function(){
	
		cuSel({
		changedEl: '#brand',
		cuwidth      : 240,
		visRows: 20,
		scrollArrows : true
	});
	
			cuSel({
		changedEl: '#model',
		cuwidth      : 240,
		visRows      : 12,
		scrollArrows : true
	});
	
/*
	$('.3d1rotate').hover(
    function () {$(this).stop().rotate3Di('flip', 500);},
    function () {$(this).stop().rotate3Di('unflip', 500);}
);
*/
	


	$('#brand').change(function(){
				//alert($('#brand').val() );
				if ($('#brand').val()>0) {
						jQuery.ajax({'type':'POST','url':'/constructcatalog/getgroups',
						'cache':false,
						'dataType':'json',
						'data':$('#brand').serialize(),
						'success':function(html){		
						//var z =html.split("##");
						//alert(html);
						//document.getElementById('compare_pad').innerHTML=z[0];
						//document.getElementById(ele).innerHTML=z[1];
								if (html != '' & html != null) {
									//	$("#model").html(html.models);
										//$("#dropdownA").html(data.dropdownA);
										//alert('qweqwe');
										//console.log(html.models);
										/* id контенера, где содержаться "option" получаем соединяя префикс cusel-scroll- и id select */
										//html = '<span val="4">Слон</span><span val="5">Жираф африканский</span>';
										//console.log($("#cusel-scroll-model"));
										$("#cusel-scroll-model").children().detach();
										$("#cusel-scroll-model").append(html.models); 
										//console.log($("#cusel-scroll-model"));
										cuSelRefresh( {
											refreshEl: "#model",
											visRows: 12
										});
										//$('.cuselText').text($($('#cusel-scroll-model').children()[0]).val);
										sel_mod = $('#cusel-scroll-model').children()[0];
										$('#cuselFrame-model .cuselText').text($(sel_mod).text());
										$(sel_mod).addClass('cuselActive');

								}///if (html != '') {
						
						}});
				}///////if ($('#brand').val()>0) {
		});////$('#brand').change(function(){

	$('#model').change(function(){
		//alert($(this).val());	
		//console.log($(this));
		if ($(this).val() !=0) document.location=$(this).val();
	});
	
	$('.faq_title').click(function(){
		$(this).parent().children('.faq_body').toggle('slow');
	});
	
});

function switch_row(el){///////////////Скрываем показывам пункты меню слева
	
	tr = $(el).parent().parent().next();
	i=$(el).parent().prev().children('i');
	if (i.hasClass('right') ) i.removeClass('right').addClass('down');
	else  i.removeClass('down').addClass('right');
	tr.toggle();
	
}//////function switch_row

function fac_popup() {/////////////////////////////Функция для вывда окошка с выбором региона и передачи ему ссылки
					
					console.log($(this));
				
						
																cont = '<iframe style="border:none" style="overflow::hidden" width="500" height="380" src="/page/ask">';
																
																$('.paulund_modal_2').paulund_modal_box({
																	title:'Задать вопрос:',
																	width: '100%',
																	height: '520',
																	description:cont
																});
																$('.paulund_modal_2').click();		
														
				
					
			}
