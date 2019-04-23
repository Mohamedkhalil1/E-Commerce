<?php	
	ob_start();
	$pageTitle ='Profile';
	session_start();
	include "init.php";
	if (isset($_SESSION['Member']))
	{
		$memberinfo = getMemberInfo($sessionMember);
	 ?>
	<!-- START DIVATION My Basic Information -->
	<div class='My-Information block'>
		<div class = "container">
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					My Information 
				</div>
				<div class="panel-body">
					<ul class='list-unstyled'>
						<li> <i class='fa fa-unlock-alt'></i>
							<span>UserName</span>		  : <?php echo $memberinfo['Username'].'<br>'; ?></li>
						<li><i class='fa fa-envelope'></i>
							<span>Email</span> 			  : <?php echo $memberinfo['Email'].'<br>'; 	 ?></li>
						<li><i class='fa fa-user'></i>
							<span>Full Name</span> 		  : <?php echo $memberinfo['Fullname'].'<br>'; ?></li>
						<li><i class='fa fa-calendar-alt'></i>
							<span>Registation Date</span> : <?php echo $memberinfo['RegDate'].'<br>'; ?></li>
						<li><i class='fa fa-tag'></i>
							<span>Favorite Categor</span> :</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- END DIVATION My Basic Information -->

	<!-- START DIVATION My ADS  -->
	<div class='My-Ads block'>
		<div class = "container">
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					My Ads 
				</div>
				<div class="panel-body">
					<?php $items = getUserItems($memberinfo['UserID']); 
					if (!empty($items)) 
					  {?>
							<div class="row">
								<?php  
									foreach($items as $item)
										{
											echo '<div class="col-xs-6  col-sm-6 col-md-3">';
												echo '<div class="thumbnail profile-item-box">';
											
													if($item['Approve'] == 0) 
													{
														echo '<span class = "approve-status"> Not Approve Yet</span>';
													}
											
													 echo '<span class="item-price">'.$item['Price'].'</span>';
											
													echo '<img  class ="img-respnsive" src="layout\images\login_image.png"'.'alt ="profile Picture" />';
											
													echo '<div class="caption">';
														echo '<h3><a href="items.php?item_id='.$item['Item_ID'].'">'.$item['Name'].'</h3></a>';
											
														echo '<textarea readonly class="item-desc">'.$item['Description'].'</textarea>';
											
														echo '<span class = "item-date">'.$item['Add_Date'].'</span>';
											
													echo '</div>';
												echo '</div>';
											echo '</div>';
										}
									?>
								</div>
					<?php }
					else 
					{
						echo 'There\'s no Ads ';	
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<!-- END DIVATION My ADS  -->

	<!-- START DIVATION My Latest Comments  -->
	<div class='My-comments block'>
		<div class = "container">
			<div class='panel panel-primary'>
				<div class='panel-heading'>
					Latest Comments 
				</div>
				<div class="panel-body">
					<?php
						$comments = '';
						$comments = getUserComments($memberinfo['UserID']);
						if(empty($comments))
						{
							echo "there's no Comments ";	
						}
						else 
						{
							echo '<ul class="list-unstyled">';
								foreach($comments as $comment)
								{
									echo '<li><span> <i class="fa fa-comment"></i>'.$comment['Comment'].'</span></li>';
								}
							echo '</div>';
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<!-- END DIVATION My Latest Comments  -->


<?php
	}
	else 
	{
		header('location: login.php');
		exit();
	}
	include $template.'footer.php'; 
	ob_end_flush();
?>
	