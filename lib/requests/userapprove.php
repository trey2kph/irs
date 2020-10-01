<?php 

	include("../../config.php"); 
	//**************** USER MANAGEMENT - START ****************\\

	include(LIB."/login/chklog.php");

	$profile_full = $logfname;
	$profile_name = $logname;
	$profile_id = $userid;
	$profile_level = $level;
	
	//***************** USER MANAGEMENT - END *****************\\
?>

<?php			
	//AUDIT TRAIL
	$log = $main->log_action("APPROVE_USER", $profile_id);

	$id = $_POST['userid'];

	$user_approve = $main->user_action($_POST, 'approve', $id);
	$user_info = $main->get_users($id, 0, 0, NULL);

	echo '<a class="approveUser cursorpoint" attribute="'.$id.'" attribute2="'.$user_approve.'">'.($user_approve == 2 ? 'Approved' : '<span class="redtext">Unapprove</span>').'</a>';

	$message = "Hi ".$user_info[0]['user_fullname'].",\n\n";
	$message .= "You've username ".$user_info[0]['user_uname']." has been ".($user_approve == 2 ? 'APPROVED' : 'DISAPPROVED')." on our system by administrator.\n\n";
	$message .= "Thanks,\n";
	$message .= "iRoom Admin";

	//$sendmail = mail($user_info[0]['user_email'], "iRoom Account Update", $message, "From: noreply@megaworldcorp.com");
?>			