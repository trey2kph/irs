<?php include("../../config.php"); ?>
<?php	
	$locid = $_POST['locid'];

	$rooms = $main->get_rooms_by_loc($locid);

	$sel = '';
	if ($rooms) {	
		foreach ($rooms as $k => $v) {
			$sel .= '<option value="'.$v['room_id'].'" >'.$v['room_name'].'</option>';
		}	
	}
	else
	{
		$sel .= 'No room from this location';
	}

    echo $sel;                         
?>