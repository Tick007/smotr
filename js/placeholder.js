(function($){
	$.fn.placeholderLabel = function(){
		var i = document.createElement('input');
		if('placeholder' in i) return true;
		
		$('INPUT:text, INPUT:password, TEXTAREA', this).each(function(){
			var placeholder = $(this).attr('placeholder');
			var label = $('<label />').css({
					position: 'absolute',
					display    : 'block',
					width      : '100%',
					height     : '100%',
					left       : '0',
					top        : '0',
					paddingTop : '4px',
					color      : '#999',
					cursor     : 'text'
				});
			
			if(placeholder){
				$(this).data('placeholder', placeholder).removeAttr('placeholder').focus(function(){
					$(this).next('LABEL').remove();
					
					if($(this).val() == placeholder){
						$(this).val('');
					}
					
					$(this).removeClass('placeholder');
				}).blur(function(){
					var v = $(this).val();
					if(v == '' || v == ' ' || v == placeholder){
						//$(this).addClass('placeholder').val(placeholder);
						$(this).after(label.html(placeholder));
					}
				}).trigger('blur');
				
				$(this).parent().undelegate().delegate('LABEL', 'mousedown', function(){
					$(this).parent().find('INPUT').focus();
				});
			}
		});
		
		$(this).each(function(){
			if(this.tagName == 'FORM'){
				var f = this;
			}else{
				var f = $(this).closest('FORM');
			}
			
			if(f && f.tagName == 'FORM'){
				$(f).submit(function(){
					$('.placeholder', this).each(function(){
						var placeholder = $(this).data('placeholder');
						if($(this).val() == placeholder){
							$(this).val('');
						}
					});
				});
			}
		});
		
		return this;
	};
})(jQuery);