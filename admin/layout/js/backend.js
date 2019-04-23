$(function()
 {
	
	
	$("input").each(function()
			   {
				  if ($(this).attr("required") === "required")
					  {
						  $(this).after('<span class="astrisk">*</span>');					  
					  }
			   });
	
	$(".show-pass").hover(
		function()
		{
		  $('.password').attr("type","text");
		},
		function()
		{
		$('.password').attr("type","password");
		});
	
	$('.confirm').click(
		function()
		{
			return confirm("Are you Sure ");
		});
	
	$('.categories .cat h3').click(
		 function()
		  {
			 $(this).next('.full-view').fadeToggle(); 
		  });
});























