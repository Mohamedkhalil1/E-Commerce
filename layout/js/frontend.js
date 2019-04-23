$(function()
 {
	
	
	$("input").each(function()
			   {
				  if ($(this).attr("required") === "required")
					  {
						  $(this).after('<span class="astrisk">*</span>');					  
					  }
			   });
	
	$('.categories .cat h3').click(
		 function()
		  {
			 $(this).next('.full-view').fadeToggle(); 
		  });
	
	$('.login-page h1 span').click(function()
			{
				$(this).addClass('selected').siblings().removeClass('selected');
				$('.login-page form').hide();
				$('.'+$(this).data('class')).show();
			});
	
	$('.live-name').keyup(function()
			{
				
				$('.live-preview .caption h3').text($(this).val());
			});
	
	$('.live-disc').keyup(function()
			{
				
				$('.live-preview .caption p').text($(this).val());
			});
	
	$('.live-price').keyup(function()
			{
				
				$('.live-preview .item-tag').text('$'+$(this).val());
			});
	
});























