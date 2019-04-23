<?php

	/*
		* getTitle Ver 1.0
		* Title function that Echo the page title in case the page has 
		* the Variable $pageTitle and echo Default Title For Other Pages  
	*/

	function getTitle()
	{
		global $pageTitle;
		if(isset($pageTitle))
		{
			echo $pageTitle;
		}
		else 
		{
			echo lang('Default');
		}
	}



/*
	* msgType function Ver 1.0
	* this function will Accept 2 Parameters
	* $msgContain Text Of Message (body) example (you can't access this page ) 
	* $type Msg type example (danger , alert , info , ..)
	* $size of message of headers (h1 , h2 , h3 , h4 , h5 ,h6)
	* return Message with alert 
*/
	function msgType($msgContain,$type ='danger',$size = 'h4')
	{
		$msg = '<div class="container text-center alert alert-'.$type."\">".'<'.$size.'>'.$msgContain.'</'.$size.'>'.'</div>';
		return $msg;
	}



/*
	* Home Redirect Function Ver 3.0
	* this function will Accept 4 Parameters
	* $ErrorMsg = Echo Error Message 
	* $url which link goes to Optinal
	* $seconds = Seconds before redirection Optional
	* $msgAlert = type of alert to Message Appear
	* redirect user to Home Page after a specific seconds 
*/

	function redirectHome($msg , $url = null ,$seconds =3 , $msgAlert)
	{
		
		if($url === null)
		{
			$url ='index.php';
		}
		else
		{
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !=='')
			{
				$url = $_SERVER['HTTP_REFERER'];
			}
			else 
			{
				$url = "index.php";
			}
		}
			
		echo msgType($msg,$msgAlert);
		//echo "<div class='container text-center alert alert-danger'>$errormsg</div>";
		//echo "<div class='container text-center alert alert-info'>you will redirect to HomePage after $seconds Seconds.</div>";
		echo msgType("you will redirect after $seconds Seconds","info");
		header("refresh:$seconds;url=$url");
		exit();
	}


/*
	* checkItem Function Ver 1.0
	* this function will Accept 3 Parameters
	* $select => the Item to Select [ user , item , catagory]
	* $from => the table to select from
	* $value => the value of Select to search
	* function to check item in Database or not
	* return true if there's item | false if there's no item
*/

	function checkItem($select , $form , $value)
	{
		global $con;
		$statement = $con->prepare("Select $select from $form Where $select=?");
		$statement->execute(array($value));
		$valid = $statement->rowCount();
		return $valid;
	}


/*
	* countItems function Ver 1.0 
	* this function will Accept 2 Parameters
	* $item  => item to count 
	* $table => where's item will be counted
	* this function will counter the number of items in the special table 
*/

	function countItems($item,$table)
	{
		global $con;
		$stmt = $con->prepare("Select COUNT($item) from $table");
		$stmt->execute();
		return $stmt->fetchColumn();
	}


/*
   * getLatest function Ver 1.0
   * this function accept 4 parameters
   * $select => which variables need from table
   * $table  => table which choose from it
   * $order  => this variable that order by 
   * $limit  => number of items you need to get 
   * this function get latest items in specific table
*/


	function getLatest ($select , $table , $order , $limit)
	{
		global $con ;
		$stmt = $con->prepare("SELECT $select from $table ORDER BY $order DESC LIMIT $limit");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		return $rows;
	}

/*
	* getLatestWith2Join function 
	* accept 6 paramters
	* $select	 => Select Query 
	* $table1	 => first table 
	* $table2 	 => second Table
	* $conditon  => On Condition which join 
	* $order     => which attri want to sort them
	* $limit     => how many items need 

*/

	function getLatestWith2Join ($select , $table1 , $table2 , $condition , $order , $limit )
	{ 
		global $con;
	/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
		/*$stmt = $con->prepare("Select comments.*,items.Name as Name, users.Username  as Member from comments inner join items ON comments.Item_ID = items.Item_ID 
		inner join users on comments.User_ID =users.UserID");*/
		echo "SELECT $select from $table1 inner join $table2 ON
		$condition ORDER BY $order DESC LIMIT $limit";
		
		$stmt = $con->prepare("SELECT $select from $table1 inner join $table2 ON
		$condition ORDER BY $order DESC LIMIT $limit");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		return $rows;
		
	}


	/*
	* getLatestWith2Join function 
	* accept 6 paramters
	* $select	 => Select Query 
	* $table1	 => first table 
	* $table2 	 => second Table
	* $conditon1 => On Condition1 which join 
	* $conditon2 => On Condition2 which join 
	* $order     => which attri want to sort them
	* $limit     => how many items need 

*/

	function getLatestWith3Join ($select , $table1 , $table2 , $table3 , $condition1 , $condition2 , $order , $limit )
	{ 
		global $con;
	/*Perpare Data into Query get all Users From DataBase expect Admin Previliege	*/
		
		$stmt = $con->prepare("SELECT $select from $table1 
			  				   inner join $table2 ON $condition1 
			  				   inner join $table3 ON $condition2
			  				   ORDER BY $order DESC LIMIT $limit");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		return $rows;
		
	}





















