<?php	
	ob_start();
	$pageTitle ='Login';
	$_GLOBAL['$errorMessage'] = '';
	session_start();
	include "init.php";
	$catid 	 = $_GET['catid'];
	$items = getItems($catid);
	?>
		<div class="container categories">
			<h1 class="text-center"><?php echo 'Category Name ' ?></h1> 
			<div class="row">
				
				<?php  
					foreach($items as $item)
						{
							echo '<div class="col-xs-6  col-sm-6 col-md-3">';
								echo '<div class="thumbnail">';
									 echo '<span class="item-price">'.$item['Price'].'</span>';
									echo '<img  class ="img-respnsive" src="layout\images\login_image.png"'.'alt ="A7A" />';
									echo '<div class="caption">';
										echo '<h3><a href="items.php?item_id='.$item['Item_ID'].'">'.$item['Name'].'</a></h3>';
										echo '<p>'.$item['Description'].'</p>';
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