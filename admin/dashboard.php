<?php
	
	ob_start();
	$pageTitle ='DashBoard';
	session_start();
	
	if(isset($_SESSION['Username']))
	{
		include "init.php"; 
		/* Start DashBoard */
		
	/* Number of users want to get */	
		$latestUser = 5; 
	/* function get latest users */
		$latest 		= getLatest('UserID,username,RegStatus' , 'users' , 'userID' , $latestUser);
	/* function get latest Items */	
		$latestitems 	= getLatest('Item_ID,Name,Approve' , 'items' , 'Item_ID' , $latestUser);
	/* function get latest Comments */	
		$latestComments = getLatestWith3Join('comments.Comment_ID,comments.Comment as comment, comments.Status,
											comments.Comment_Date,comments.Item_ID,comments.User_ID ,items.Name as Name, users.Fullname as Member',
									   'comments','items','users',
									   'comments.Item_ID = items.Item_ID','comments.User_ID = users.UserID',
									   'comments.Comment_ID',$latestUser);
		?>
		<div class="container text-center home-state">	
			<h1>Dashboard</h1>
			
			<div class="row">
				
				<div class="col-md-3">
					<div class="state st-members">
						<i class="fa fa-users"></i>
						<div class="info">
							Total Numbers
							<a href="members.php"><span><?php echo countItems('UserID','users'); ?></span></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="state st-pending">
						<i class="fa fa-user-plus"></i>
						<div class="info">
							Pending Members
							<a href="members.php?do=Manage&page=pending">
								<span><?php echo checkItem('RegStatus','users',0); ?></span></a>
						</div>	
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="state st-items">
						<i class="fa fa-tag"></i>
						<div class="info">
							Total Items
							<a href="items.php?do=Manage">
								<span><?php echo countItems('Item_ID','items'); ?></span></a>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="state st-comments">
						<i class="fa fa-comments"></i>
						<div class="info">
							Total Comment
							<a href='comments.php?do=Manage'>
							<span><?php echo countItems('Comment_ID','comments'); ?></span></a>
						</div>
					</div>
				</div>

			</div>
		</div>	
		
		<div class="container latest">
			<div class="row">
				
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-users"></i> latest <?php echo $latestUser ?> Registerd Users
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-user">
								<?php
									foreach($latest as $item)
									{
										echo '<li>'.$item['username'];
										echo'<a href="members.php?do=Edit&UserID='.$item['UserID'].'"> 
											<span class="btn btn-success pull-right"><i class="fa fa-edit"> 
											</i>Edit</span></a>'; 
											if($item['RegStatus'] === '0')
											{
												echo '<a class="btn btn-info pull-right active" href="members.php?do=Activate&UserID='.$item['UserID'].'"><i class="fa fa-bell-slash"></i>Activate</a>';
											}
										echo '</li>';
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-tag"></i> latest <?php echo $latestUser; ?> Items Added
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-user">
								<?php
									foreach($latestitems as $item)
									{
										echo '<li>'.$item['Name'];
										echo'<a href="items.php?do=Edit&items_id='.$item['Item_ID'].'"> 
											<span class="btn btn-success pull-right"><i class="fa fa-edit"> 
											</i>Edit</span></a>'; 
											if($item['Approve'] === '0')
											{
												echo '<a class="btn btn-info pull-right active" href="items.php?do=Approve&itemid='.$item['Item_ID'].'"><i class="fa fa-check"></i>Approve</a>';
											}
										echo '</li>';
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				
				
				<div class="col-sm-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-comments"></i> latest <?php echo $latestUser; ?> Comments Added
						</div>
						<div class="panel-body">
							<ul class="list-unstyled latest-user">
								<?php
									foreach($latestComments as $item)
									{
										echo '<li> <div class="comment-box"> 
										<span class="member-n">'.$item['Member'].'</span>';
										echo '<p class="member-c">'.$item['comment'].'</p></div>';
										
										
										echo'<a href="comments.php?do=Edit&commentid='.$item['Comment_ID'].'"> 
											<span class="btn btn-success pull-right"><i class="fa fa-edit"> 
											</i>Edit</span></a>'; 
											if($item['Status'] === '0')
											{
												echo '<a class="btn btn-info pull-right active" href="comments.php?do=Approve&commentid='.$item['Comment_ID'].'"><i class="fa fa-check"></i>Approve</a>';
											}
										echo '</li>';
										echo '<hr/>';
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				
				
				
			</div>
		</div>
	
		<?php
	  	/* End DashBoard*/		
		
		
	}
	else 
	{
		header('location:index.php');
		exit();
	}

	ob_end_flush();
