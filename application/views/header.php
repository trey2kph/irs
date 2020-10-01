<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $page_title; ?>&nbsp;|&nbsp;<?php echo SITENAME; ?></title>
        <meta name="description" content="Online Requestion System for Megaworld employee">
        <meta name="keywords" content="megaworld, irs, requisition, ism, admin">
        <meta name="author" content="JZI - Megaworld Corp. ISM">
        
        <!-- FAVICON -->
        
        <link rel="shortcut icon" href="<?php echo WEB; ?>/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo WEB; ?>/favicon.ico" type="image/x-icon">
        
        <!-- VIEWPORT -->
        <meta name="viewport" content="width=1284" />
        
        <!-- CSS STYLES -->
        <link rel="stylesheet" href="<?php echo CSS; ?>/style_irs.css"> 
        <link rel="stylesheet" href="<?php echo CSS; ?>/lightbox.css">        
        <link rel="stylesheet" href="<?php echo CSS; ?>/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo CSS; ?>/fullcalendar.css">
        <link rel="stylesheet" href="<?php echo CSS; ?>/colorpicker.css">
		<link rel="stylesheet" href="<?php echo CSS; ?>/font-awesome.min.css">
        
        <!-- JQUERY -->        
        <script src="<?php echo JS; ?>/jquery-1.7.2.min.js"></script>      
        <script type="text/javascript" src="<?php echo JS; ?>/chart.min.js"></script>
        
        <?php if ($status_data) : ?>
        <!-- CHART -->
        <!--script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">
            google.load("visualization", "1", {packages:["corechart"]});
            google.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Status', 'Percentage'],
                    <?php $i = 0; ?>
                    <?php foreach ($status_data as $row) : ?>
                        <?php echo $i > 0 ? ", " : ""; ?>
                        ['<?php echo $this->Core->display_status($row->trans_status, 9); ?>', <?php echo $row->transcount; ?>]
                        <?php $i++; ?>
                    <?php endforeach; ?>
                ]);
                
                var options = {
                    title: 'Requisition Status Chart',
                    pieHole: 0.4,
                };
                
                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script-->
        <?php endif; ?>
        
    </head>
    <body>  
        
        <div id="transDiv" class="transdiv invisible">            
            <div id="transDivCon" class="transdivcon">
                <div class="closemanagetrans cursorpoint"><i class="fa fa-times-circle fa-3x redtext"></i></div>
                <div id="transDivContent"></div>
            </div>
        </div>
        
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        
		<div id="main" class="main">
        	
          <div id="hupper" class="hupper" style="display: none;">
            <div class="wrapper">       
            	<div id="maincontainer" class="maincontainer clearfix">            
                  <div id="header" class="hheader">   
                    <img src="https://portal.megaworldcorp.com/email_image/mwheadhr2s.png" />
                  </div>     
                  <div id="loginheader" class="hloginheader">
                    <div id="ltitle" class="lowerlist robotobold hugetext whitetext"><?php echo SYSTEMNAME; ?></div> 
                    <?php if ($session_data) { ?>  
                    <div class="logbox">
                        <i class="fa fa-user-circle-o whitetext"></i><span class="whitetext"> <b>Hello <?php echo $session_data['session_fullname']; ?> (<?php echo $session_data['megav2_user']; ?>)</b> | <a href="<?php echo WEB; ?>/profile" class="whitetext"><i class="fa fa-user"></i> Manage Account</a> | <a href="<?php echo WEB; ?>/irs/logout" class="whitetext"><i class="fa fa-key"></i> Logout</a></span>
                    </div>
                    <?php } ?>
                  </div>     
                </div>
            </div>				        
          </div>
              
          <div id="upper" class="upper">
            <div class="wrapper">       
            	<div id="maincontainer" class="maincontainer clearfix">            
                  <div id="header" class="header">   
                    <img src="https://portal.megaworldcorp.com/email_image/mwheadhr2.png" />
                  </div>     
                  <div id="loginheader" class="loginheader">
                    <div id="ltitle" class="lowerlist robotobold hugetext whitetext2"><?php echo SYSTEMNAME; ?></div> 
                  </div>     
                </div>
            </div>				        
          </div>
          
          <div id="middle" class="middle">
        	<div class="wrapper">
            <div id="maincontainer" class="maincontainer clearfix borderradiustop borderradiusbottom">
                <div id="loginbox" class="logcontainer righttalign">
                    <?php if ($session_data) { ?>
                    <div class="logbox">
                        <i class="fa fa-user-circle-o"></i> <b>Hello <?php echo $session_data['session_fullname']; ?> (<?php echo $session_data['megav2_user']; ?>)</b> | <a href="<?php echo WEB; ?>/profile" class="whitetext"><i class="fa fa-user"></i> Manage Account</a> | <a href="<?php echo WEB; ?>/irs/logout" class="whitetext"><i class="fa fa-key"></i> Logout</a>
                    </div>
                    <?php } ?>
                </div>