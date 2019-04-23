<?php

	function lang($phrase)
	{
		static $lang = array
			(
				// Page Title 
				'Default' 		=> 'الطبيعى', 
				
				// Homepages
				'Message' => 'اهلا' ,
				'admin' => 'محمد خليل',
				
				// Dashboard
				'Home' => 'الصفحه الرئسيه',
				"Edit Profile" => "تعديل الايميل",
				"Visit Shop"	=> "زياره المتجر",
				"Settings" => "اعدادت",
				"Logout" => "تسجيل خروج",
				"Category" => "الأصناف",
				"Items" 		=> "السلع",
				"Members" 		=> "الاعضاء",
				"Comments"		=> "التعليقات",
				"Statistics"  	=> "الاحصائيات",
				"Logs"			=> "المسجلات"
			);
		return $lang[$phrase];
	}
?>