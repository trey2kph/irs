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
	$log = $main->log_action("EDIT_RESERVATION", $profile_id);

	//input validation and cleaner (anti-MySQL injection)
	$validation->fields = array("Room"		=>	$main->clean_variable($_POST['reserve_roomid']),
						"Event Name"		=>	$main->clean_variable($_POST['reserve_eventname']),
						"Event Date In"	 	=>	$main->clean_variable($_POST['reserve_datein']),
						"Event Time In"		=>	$main->clean_variable($_POST['reserve_timein']),
						"Event Time Out"	=>	$main->clean_variable($_POST['reserve_timeout'])
	);

	$validation->validate_required();
	$validation->validate_date($_POST['reserve_datein'], true);

	if(!empty($validation->message['error'])) {
		$message = $validation->message['error'];
		$err_message = "";
		foreach ($message as $key => $value) {
			$err_message .= $value.'\n';
		}
		echo $err_message; 
	} else {

		$datein = explode("-", $_POST['reserve_datein']);
		$timein = explode(":", $_POST['reserve_timein']);
		$timeout = explode(":", $_POST['reserve_timeout']);
		$reserve_checkin = mktime($timein[0], $timein[1], $timein[2], $datein[1], $datein[2], $datein[0]);
		$reserve_checkout = mktime($timeout[0], $timeout[1], $timeout[2], $datein[1], $datein[2], $datein[0]);

		if ($reserve_checkin >= $reserve_checkout) {
			echo "Time in is greater than time out";
		}
		else {
			if ($reserve_checkin > $unix3month) {
				echo "Reservation must be made 3 months from now.";
			}
			else
			{
				$check_res = $main->check_reservation($_POST['reserve_roomid'], $reserve_checkin, $reserve_checkout, $_POST['reserve_id']);
				if ($check_res)	{ 
					$edit_reservation = $main->reserve_action($_POST, 'edit');
					echo "Reservation has been successfully modified.";
				}
				else {
					echo "Someone already reserved that room on prescribed date and time. Please choose another.";	
				}
			}
		}
	}
?>			