<?php	
	ob_start();
	session_start();
	$pageTitle ='HomePage';
	include "init.php";

$items = getALlFrom('items','Item_ID' , 'WHERE Approve = 1');
	?>
		<div class="container categories">
			<div class="row">
				<?php  
					foreach($items as $item)
						{
							echo '<div class="col-xs-6  col-sm-6 col-md-3">';
								echo '<div class="thumbnail homepage">';
									 echo '<span class="item-price">'.$item['Price'].'</span>';
									echo '<img  class ="img-respnsive" src="layout\images\login_image.png"'.'alt ="A7A" />';
									echo '<div class="caption">';
										echo '<h3><a href="items.php?item_id='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>';
										echo '<textarea>'.$item['Description'].'</textarea>';
										echo '<span class = "item-date">'.$item['Add_Date'].'</span>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
						}
					?>
				</div>
		 </div>			

<?php
	include $template.'footer.php'; 
	ob_end_flush();
?>
	