<?php	
	$pageTitle ='Login';
	session_start();
	if(isset($_SESSION['Member']))
		{
			header('location:index.php');
		}

	include "init.php";	
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isset($_POST['login']))
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
			$hashpass = sha1($password);


			$stmt = $con->prepare("select UserID ,Username , Password from users where Username = ? and Password =? limit 1");
			$stmt->execute(array($username,$hashpass));
			$getUser = $stmt->fetch();
			$valid = $stmt->rowCount();

			/* if Valid is larger than 0 there's data about the User 
				Valid Sign In
			*/

			if($valid)
			{
				$_SESSION['Member']=$username;
				$_SESSION['User_ID']=$getUser['UserID'];
				header('location:index.php');
				exit(); 
			}
		}
		else 
		{
			
			$formerrors = array();
			$member = $_POST['username'];
			$password = sha1($_POST['password']);
			$email = $_POST['email'];
			
			if(isset($_POST['username']))
			{
				$filteduser = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
				if(empty($filteduser))
				{
					$formerrors [] = 'Username can\'t be <strong>Empty</strong>';
				}
				elseif(strlen($filteduser) > 15 || strlen($filteduser) < 4)
				{
					$formerrors [] = 'Username must be 4 or more <strong>letters</strong> and less than 15 <strong>letters</strong>';
				}
			}
			if(isset($_POST['password']) && isset($_POST['password-again']))
			{
				$pass1 = sha1($_POST['password']);
				$pass2 = sha1($_POST['password-again']);
				if(empty($_POST['password']))
				{
					$formerrors [] = 'Password can\'t be <strong>Empty</strong>';
				}
				elseif($pass1 !== $pass2)
				{
					$formerrors [] = 'Password isn\'t <strong>Matching</strong>';
				}
			}
			if(isset($_POST['email']))
			{
				$filteremail = filter_var($_POST['email'],FILTER_SANITIZE_STRING);
				if(filter_var($filteremail,FILTER_VALIDATE_EMAIL) != true)
				{
					$formerrors [] = 'Email is invalid';
				}
				else
				{
					if(empty($filteremail))
					{
						$formerrors [] = 'Email can\'t be <strong>Empty</strong>';	
					}
				}
			}
			if(empty($formerrors))
						{
						/* Check if the User Name is exit in database or not */
							$valid = checkItem("Username","users",$member);
							if(!($valid))
							{
								/*   Prepare Data from Database Base On User_ID  */
									$stmt = $con->prepare("Insert INTO users (Username, Email, Password,RegDate,RegStatus)
									VALUES(:zuser,:zemail,:zpassword,now(),0)");


								/* Execute the Query */
									$stmt->execute(array(
									'zuser' 	=>  $member,
									'zemail' 	=>	$email,
									'zpassword' =>	$password
									));

								/* Check there's Data in Database or Not */
									$valid = $stmt->rowCount();

								/* IF there Data Then GO in Form */
									if($valid)
									{
										$successmsg = 'Congratz You are Registed';
									}
									else
									{
										$formerrors[] = "there's error in insert Information";
									}
								}
							else 
							{
								$formerrors[]= "Username is already exist";
							}
						}

		}
	}
	?>
	<div class ='container login-page'>
		<h1 class='text-center'>
				<span class='selected' data-class='login'>LOGIN |</span>
				<span  data-class='signup'> SIGNUP</span>
		</h1>
		
		<!--  START LOGIN FORM -->		
		<form class='login' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST' >
			<div class='input-container'>
				<input class='form-control' type='text' name='username' autocomplete="off"
					   placeholder = 'Username ' required />
			</div>
			
			<div class='input-container'>
				<input class='form-control' type='password' name='password' autocomplete="new-password"
					   placeholder = 'Password' required />
			</div>
			
			<input class='btn btn-primary btn-block btn-lg' name ='login' type='submit' value='Log In' >
		</form>
		<!--  END  LOGIN FORM -->
		
		<!--  START SIGNUP FORM -->		
		<form class='signup' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='POST'>
			
			<div class='input-container'>
				<input pattern= '.{4,15}' title='username should be 4-15 letters' class='form-control' type='text' name='username' autocomplete="off"
					   placeholder = 'Username' required />
			</div>
			
			<div class='input-container'>
				<input minlength="6" class='form-control' type='password' name='password' autocomplete="new-password"
					   placeholder = 'Password' required />
			</div>
			
			<div class='input-container'>
				<input minlength="6" class='form-control' type='password' name='password-again' autocomplete="new-password"
					   placeholder = 'Confirm Password' required />
			</div>
			
			<div class='input-container'>
				<input class='form-control' type='email' name='email' placeholder ='Your Email Address'
					  required />
			</div>
			
			<input class='btn btn-success btn-block btn-lg' name ='signup' type='submit' value='Sign Up' >
			
		</form>
		<!--  END SIGNUP FORM -->	
	</div>
	<div class='text-center the-errors'>
		<?php
			if(!(empty($formerrors)))
			{
				foreach($formerrors as $error)
				{
					echo '<p class="msg">'.$error.'</p>';
				}
			}
			if(isset($successmsg))
			{
				echo '<p class="the-errors successmsg">'.$successmsg.'<p>';
			}
		?>
	</div>

<!--  END LOGIN FORM -->
<?php
	include $template.'footer.php'; 
?>
