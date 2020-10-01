<?php

	$cookiename = 'mega1_user';

	$username = $_SESSION[$cookiename];	
	
	if ($username) {	
		$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$_SESSION['login_url'] = $redirectUrl;
		$_SESSION['logout_url'] = $redirectUrl;	
		
		$checkname = $register->check_user($username);
		
		if (!$checkname) 
		{
			$logged = 0;		
		}
		else 
		{
			$userdata = $register->get_member($username);
			
			$logged = 1;
			$logfname = $userdata[0]['user_fullname'];
			$logname = $userdata[0]['user_uname'];
			$userid = $userdata[0]['user_id'];	
			$email = $userdata[0]['user_email'];		
			$level = $userdata[0]['user_level'];		
		}		
	}
	else
	{
		$logged = 0;
	}

?>