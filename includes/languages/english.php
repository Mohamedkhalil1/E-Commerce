
<?php
	function lang($phrase)
	{
		
		/*
		*	<li><a href="#"><?php echo lang("Items");?></a></li>
					  <li><a href="#"><?php echo lang("Members");?></a></li>
					  <li><a href="#"><?php echo lang("Statistics");?></a></li>
					  <li><a href="#"><?php echo lang("Logs");?></a></li>
		*/
		static $lang = array
			(
				
				// Page Title 
				'Default' 		=> 'Default', 
				
				
				// Homepages
				'Message' 		=> 'Welcome' ,
				'admin' 		=> 'Mohamed Khalil',
				
				// Dashboard
				'Home' 			=> 'Home',
				"Edit Profile" 	=> "Edit Profile",
				"Settings" 		=> "Settings",
				"Logout" 		=> "Logout",
				"Category" 		=> "Category",
				"Items" 		=> "Items",
				"Members" 		=> "Members",
				"Comments"		=> "Comments",
				"Statistics"  	=> "Statistics",
				"Logs"			=> "Logs"
				
			);
		return $lang[$phrase];
	}
?>