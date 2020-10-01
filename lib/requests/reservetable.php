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
	$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1 ;
	$start = NUM_ROWS * ($page - 1);

	$roomid = $_GET['roomid'] ? $_GET['roomid'] : 0; 
		
	global $sroot, $profile_id;

	$searchres = $_POST['searchres'] ? $_POST['searchres'] : 0;

	$reservation = $main->get_reservations(0, $start, NUM_ROWS, 0, 0, $roomid, $searchres);
	$reservation_count = $main->get_reservations(0, 0, 0, 0, 0, $roomid, $searchres, 1);
	$rooms = $main->get_rooms(0);
	$locations = $main->get_locations(0);

	$pages = $main->pagination("reservation", $reservation_count[0]['rescount'], NUM_ROWS, 9);
?>	
						<a name="restable"></a>
						<table class="margintop15" width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td><form action="#restable" method="POST" enctype="multipart/form-data">Search Reservation&nbsp;<input type="text" name="searchres" />&nbsp;<input type="submit" name="btnressearch" value="Search" class="btn" /></form></td>
                            </tr>
                            <?php if ($searchres) { ?>
                            <tr>
                                <td>Your search result for &quot;<b><?php echo $searchres; ?></b>&quot;</td>
                            </tr>
                            <?php } ?>
                        </table>
                        <table class="tdata" width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th width="5%">Reservation ID</td>
                                <th width="25%">Event Name</td>                            
                                <th width="20%">Room</td>
                                <th width="25%">Duration</td>
                                <th width="15%">Reserved by</td>
                                <?php if ($profile_level == 9) { ?>
                                <th width="10%" colspan="2">Manage</td>
                                <?php } ?>    
                            </tr>
                            <?php if ($reservation) { ?>
                            <?php foreach ($reservation as $key => $value) { ?>
                            <tr style="background: <?php echo $value['room_color']; ?> !important">
                                <td><?php echo $value['reserve_id']; ?></td>
                                <td><?php echo $value['reserve_eventname']; ?></td>                            
                                <td><?php echo $value['room_name']; ?></td>
                                <td><?php echo date("M j g:ia", $value['reserve_checkin']); ?> to <?php echo date("g:ia", $value['reserve_checkout']); ?></td>
                                <td><?php echo $value['user_fullname']; ?> (<?php echo $value['user_dept']; ?>)</td>                            
                                <?php if ($profile_level == 9) { ?>
                                <td align="center"><div attribute="<?php echo $value['reserve_id']; ?>" attribute2="<?php echo $value['room_color']; ?>" class="editEvDetail cursorpoint">Edit Reserve</div></td>
                                <td align="center"><div attribute="<?php echo $value['reserve_id']; ?>" attribute2="<?php echo $value['room_color']; ?>" class="delEvDetail cursorpoint">Delete Reserve</div></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                            <?php if ($pages) { ?>
                            <tr>
                                <td colspan="7" align="center" class="whitebg"><?php echo $pages; ?></td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                                <td colspan="7" align="center" class="whitebg">No reservation has been made<?php echo $_GET['roomid'] ? " on this room" : ""; ?></td>
                            </tr>
                            <?php } ?>
                        </table>		