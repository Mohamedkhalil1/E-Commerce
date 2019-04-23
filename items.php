<?php	
	ob_start();
	$pageTitle ='Item Show';
	session_start();
	include "init.php";


	 /*  Check UserID Is Correct (validation)  */
			$item_id = isset($_GET['item_id']) && is_numeric($_GET['item_id']) ? intval($_GET['item_id']) : 0 ; 
			  /*   Prepare Data from Database Base On User_ID  */
			$stmt = $con->prepare("SELECT items.* , categories.Name as Cat_Name , users.Username  from items
				inner join categories on items.Cat_ID = categories.ID
				inner join users on users.UserID =items.Members_ID
				where Item_ID = ? 
				AND Approve = 1");
			
			  /* Execute the Query */
			$stmt->execute(array($item_id));
			/* bring the data from Data Base */
			$row = $stmt->fetch();

			/* Check there's Data in Database or Not */
			$valid = $stmt->rowCount();
			/* IF there Data Then GO in Form */
			if($valid)
			{?>
				<?php if($_SERVER['REQUEST_METHOD'] == 'POST')
						{
							$comment= Filter_var($_POST['comment-area'],FILTER_SANITIZE_STRING);
							$itemid = $row['Item_ID'];
							$userid = $_SESSION['User_ID'];
							
							if(!empty($comment))
							{
								/* Insert Query for comment */
								$stmt = $con->prepare('INSERT INTO comments 
								(Comment, Status,Comment_Date,Item_ID,User_ID) VALUES
								(:zcomment,0,now(),:zitemid,:zuserid)
								');

								/* Execute Query */
								$stmt->execute(array(
									'zcomment' => $comment,
									'zitemid' => $itemid,
									'zuserid'  => $userid

								));
								if($stmt)
								{
									echo msgType('Congratz Comment is Added','success','h4');
								}
							}
							else 
							{
								echo msgType('Comment is Empty','warning','h4');
							}
						} ?>
				<h1 class='text-center'><?php echo $row['Name']; ?> </h1>
				<div class='container'>
					<div class='row'>
						<div class='col-md-3'>
							<img  class ="img-respnsive img-thumbnail img-tblock-center" src="layout\images\login_image.png"'.'alt ="Item Picture" />
						</div>
						<div class='col-md-9 item-info'>
							
							<h2><?php echo $row['Name'] ?></h2>
							
							<p><?php echo $row['Description']?></p>
							
							<ul class='list-unstyled'>
								<li><i class='fa fa-money-bill-wave fa-fw'></i>
									<span>Price</span>: <?php echo $row['Price'] ?></li>

								<li><i class='fa fa-building fa-fw'></i>
									<span>Countery-Made</span>: <?php echo $row['Country_Made']?> </li>

								<li><i class='fa fa-warehouse fa-fw'></i>
									<span>Category</span>: <a href='categories.php?catid=<?php echo $row['Cat_ID'] ?>'><?php echo $row['Cat_Name']?></a></li>

								<li><i class='fa fa-user fa-fw'></i>
									<span>Added By</span>: <a href='#'><?php echo $row['Username'] ?></a></li>

								<li><i class='fa fa-calendar-alt fa-fw'></i>
									<span>Add Date</span>: <?php echo $row['Add_Date'] ?> </li>
							</ul>
							
						</div>
					</div>
					<?php
							
							/* Prepare Query */
							$stmt = $con->prepare("SELECT comments.*, users.Username as Member from comments
							inner join users on comments.User_ID =users.UserID
							WHERE Status = 1 AND Item_ID = ?");
			 				/* Execute Query */
			 				$stmt->execute(array($row['Item_ID']));
			 				$getcomments = $stmt->fetchAll();
					?>
					<?php if (isset($_SESSION['Member']))
					{?>	
						<hr class='custom-hr'>
						<!-- Start Active-Comment Part-->
						<div class='row'>
							<div class='col-md-offset-3 add-comment'>
								<h3>Add Your Comment</h3>
								<form action ="<?php echo $_SERVER['PHP_SELF'].'?item_id='.$item_id ?>"
									  method = 'POST'>
									<textarea name='comment-area' required></textarea>
									<input type='submit' class='btn btn-primary' value ='Add Comment'>
								</form>
							</div>
						</div>
						<!-- End Comment Part -->
					<?php 
					}
			 		else
					{
						echo msgType('<a href="login.php">login</a> or <a href="login.php">register</a> to Add Comment ','default','h5');
					}
					foreach($getcomments as $comment)
					{?>
						<hr class='custom-hr'>
						<div class='comment-box'>
							<div class="row">
							
							<div class="col-sm-2 text-center">
								<img  class ="img-respnsive img-thumbnail img-circle center-block" src="layout\images\login_image.png"'.'alt ="profile Picture" />
								<?php echo $comment['Member']; ?>
							</div>
								
							<div class="col-sm-10">
								<p class='lead'>
									<?php echo $comment['Comment']; ?>
								</p>
							</div>
						</div>
						</div>
					<?php
					}
					?>	
	<?php
		}
		else 
		{
		  echo msgType('there\'t no Such ID or This Item is waiting Approval ');
		}
	?>					
</div>



<?php
	include $template.'footer.php'; 
	ob_end_flush();
?>
	