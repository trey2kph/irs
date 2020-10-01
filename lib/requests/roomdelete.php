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
	$log = $main->log_action("DELETE_ROOM", $profile_id);
	
	$id = $_POST['roomid'];

	$delete_room = $main->room_action(NULL, 'delete', $id);
?>			