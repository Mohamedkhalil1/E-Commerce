<?php

	$noNavBar ='';
	$pageTitle ='Login';
	$_GLOBAL['$errorMessage'] = '';
	session_start();
	if(isset($_SESSION['Username']))
	{
		header('location:dashboard.php');
	}

	include "init.php";

	// Check if user coming from http post request	
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$username = $_POST['user'];
		$password = $_POST['password'];
		$hashpass = sha1($password);
		
		
		$stmt = $con->prepare("select UserID ,Username , Password from users where Username = ? and Password =? limit 1");
	    $stmt->execute(array($username,$hashpass));
		$row = $stmt->fetch();
		$valid = $stmt->rowCount();
		
		/* if Valid is larger than 0 there's data about the User 
			Valid Sign In
		*/
		
		if($valid)
		{
			$_SESSION['Username']=$username;
			$_SESSION['UserID'] =$row[UserID];
			header('location:dashboard.php');
			exit(); 
		}
		else 
		{
			$_GLOBAL['$errorMessage'] = 'Wrong Password or UserName';
		}
	}
?>	

	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST'>
		<?php 
			if($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				echo '<div class="alert alert-danger" role="alert">'.$_GLOBAL['$errorMessage'].'</div>';
			}
		?>
		<img id = "login_Image" src='layout/images/login_image.png' alt="Logo Photo Login" height="291" width="297">

		<label for ="lusername"> UserName </label>
		<input id ="lusername" class="form-control" type ="text"
			   name="user" autocomplete="off">

		<label for ="lpassword"> Password </label>
		<input id ="lpassword" class="form-control" type ="password"
			   name="password" autocomplete="new-password">

		<input class="btn btn-primary" type="submit"
			   value="login">
	</form>


<?php include $template.'footer.php'; ?>