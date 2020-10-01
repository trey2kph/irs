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
	$id = $_POST['resid'];

	$single_reservation = $main->get_reservations($id);
    $rooms = $main->get_rooms(0);
    ?>

	<?php foreach ($single_reservation as $key => $value) { 

    $dateinbreak = explode(" ", date("Y-m-d H:i:s", $value['reserve_checkin']));
    $dateoutbreak = explode(" ", date("Y-m-d H:i:s", $value['reserve_checkout']));
    $dateinval = $dateinbreak[0];
    $timeinval = $dateinbreak[1];
    $timeoutval = $dateoutbreak[1];

    ?>

	<div id="ltitle" class="robotobold cattext2 <?php if ($_POST['delete']) { ?>redtext <?php } else { ?>dbluetext <?php } ?>marginbottom12"><?php if ($_POST['delete']) { ?>Are you sure you want to cancel <?php } elseif ($_POST['post']) { ?>Are you sure you want to approve <?php } ?><?php echo $value['reserve_eventname']; ?><?php if ($_POST['delete'] || $_POST['post']) { ?>?<?php } ?></div>                        

    <?php if ($_POST['edit']) { ?>

    <table class="tdataform2 rightmargin margintop10 vsmalltext" width="100%" border="0" cellpadding="0" cellspacing="0">
        <form name="edit_reserve" method="POST" enctype="multipart/form-data">
        <tr>
            <td>Room</td>
            <td class="roomdiv">
                <select name="reserve_roomid" id="reserve_roomid" class="reserve_roomid select90">
                    <?php foreach ($rooms as $k => $v) { ?>
                    <option value="<?php echo $v['room_id']; ?>" <?php if ($v['room_id'] == $value['reserve_roomid']) { ?>selected="selected"<?php } ?>><?php echo $v['room_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Event Name</td>
            <td><input type="text" name="reserve_eventname" id="reserve_eventname" value="<?php echo $value['reserve_eventname']; ?>" /></td>
        </tr>
        <tr>
            <td>Date-in</td>
            <td>
                <input type="text" name="reserve_datein" id="reserve_datein" class="checkindate" value="<?php echo $dateinval; ?>" />
            </td>
        </tr>
        <tr>
            <td>Time-in</td>
            <td>
                <select name="reserve_timein" id="reserve_timein" class="reserve_timein">       
                <?php foreach ($timearray as $k => $v) { 
                    if ($k != "00:00:00") { ?><option value="<?php echo $k; ?>" <?php if ($k == $timeinval) { ?>selected="selected"<?php } ?>><?php echo $v; ?></option><?php } ?>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Time-out</td>
            <td>
                <select name="reserve_timeout" id="reserve_timeout" class="reserve_timeout">       
                <?php foreach ($timearray as $k => $v) {                                 
                    if ($k != "06:00:00") { ?><option value="<?php echo $k; ?>" <?php if ($k == $timeoutval) { ?>selected="selected"<?php } ?>><?php echo $v; ?></option><?php } ?>
                <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Person/s</td>
            <td>
                <select name="reserve_person" id="reserve_person" class="reserve_person">       
                <?php for($i = 2; $i <= 9; $i++) { ?>                                 
                    <option value="<?php echo $i; ?>" <?php echo $i == $value['reserve_person'] ? "selected" : ""; ?>><?php echo $i; ?></option>
                <?php } ?>
                <option value="10" <?php echo $value['reserve_person'] == 10 ? "selected" : ""; ?>>10 or more</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Notes</td>
            <td><input type="text" name="reserve_notes" id="reserve_notes" value="<?php echo $value['reserve_notes']; ?>" /></td>
        </tr>
        <?php if ($profile_level == 9) { ?>
        <tr>
            <td>Valid Update Reason</td>
            <td><input type="text" name="reserve_reason" id="reserve_reason" /></td>
        </tr>
        <?php } ?>
        <tr>
            <td>Reserve by</td>
            <td>
                <b><?php echo $value['user_fullname']; ?></b>
                <input type="hidden" name="reserve_user" id="reserve_user" value="<?php echo $value['reserve_user']; ?>" />
                <input type="hidden" name="user_fullname" value="<?php echo $value['user_fullname']; ?>" />
                <input type="hidden" name="user_email" value="<?php echo $value['user_email']; ?>" />
            </td>
        </tr>
        </form>
    </table>           

    <?php } else { ?>

    <table class="tdataform2 rightmargin margintop10 vsmalltext" width="100%" border="0" cellpadding="0" cellspacing="0">        
        <form name="frmrmanage" action="" method="POST" class="smalltext">                        
            <tr>
                <td colspan = "2">
                    <div class="fields">
                        <div class="lfield valigntop">Reservation Details</div>
                        <div class="rfield valigntop">
                            <b>ID No.:</b> <?php echo $value['reserve_id']; ?><br />
                            <b>Room:</b> <?php echo $value['room_name']; ?><br />
                            <b>Check-in:</b> <?php echo date("F j, Y - g:ia", $value['reserve_checkin']); ?><br />
                            <b>Check-out:</b> <?php echo date("F j, Y - g:ia", $value['reserve_checkout']); ?><br />                                
                            <b>Person/s:</b> <?php echo $value['reserve_person'] == 10 ? "10 or more" : $value['reserve_person']; ?><br />
                            <b>Notes:</b><br /><?php echo $value['reserve_notes'] ? $value['reserve_notes'] : "n/a"; ?><br />
                            <b>Status:</b> <?php echo $value['reserve_status'] == 1 ? "Pending" : "Approved"; ?>
                        </div>
                    </div>
                    <div class="fields">
                        <div class="lfield valigntop">Reserved by</div>
                        <div class="rfield valigntop">
                            <b>Name:</b> <?php echo $value['user_fullname']; ?><br />
                            <b>Department:</b> <?php echo $value['user_dept']; ?><br />
                            <b>Contact #:</b> <?php echo $value['user_telno']; ?><br />
                            <b>Email Address:</b> <?php echo $value['user_email']; ?><br />     
                        </div>
                    </div>
                </td>
            </tr>
            <?php if ($_POST['delete'] && $profile_level == 9) { ?>
            <tr>
                <td width="25%">Valid Update Reason</td>
                <td width="75%">
                    <input type="text" name="reserve_reason" id="reserve_reason" />
                    <input type="hidden" name="user_fullname" value="<?php echo $value['user_fullname']; ?>" />
                    <input type="hidden" name="user_email" value="<?php echo $value['user_email']; ?>" />
                </td>
            </tr>
            <?php } ?>            
        </form> 
    </table>

    <?php } ?>

    <?php } ?>

<script type="text/javascript" src="<?php echo JSCRIPT; ?>/plugins.php"></script>           