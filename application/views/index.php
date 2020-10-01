    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="floatdiv" class="floatdiv invisible">   
                        
                <!-- VIEW ANNOUCEMENT - BEGIN --> 
                <div id="fview" class="fview invisible">
                    <div class="closebutton cursorpoint"><i class="fa fa-times-circle fa-3x redtext"></i></div>
                    <div id="fview_title" class="robotobold centertalign hugetext dbluetext">Welcome to iRS 2.0</div>
                    <div class="ftform centertalign margintop50">
                        <p class="lefttalign marginbottom12">New design layout has been implemented so some button are rearranged.</p>
                        <ul>
                            <li class="lefttalign">To start item requisition, click purple circle button with shopping cart icon on the persistent lower right corner</li>
                            <img src="<?php echo WEB; ?>/images/newirs01.png" class="margintopbottom10" />
                            <li class="lefttalign">To update or change your account password, click <b>Manage Account</b> in the upper right hand corner of the screen</li>
                            <img src="<?php echo WEB; ?>/images/newirs02.png" class="margintopbottom10" />
                        </ul>
                        <p class="lefttalign marginbottom12">If you have any concern, please email <a href="mailto:webdev@megaworldcorp.com?subject=iRS%202.0%20Concerns" class="bold">webdev@megaworldcorp.com</a>. Thank you.</p>
                        <p class="margintop50">
                        <input id="dismisspop" type="checkbox" name="dismisspop" value="1" /> <label for="dismisspop">Don't show this again</label>
                        </p>
                    </div>
                </div>
                <!-- VIEW RESERVE - END -->
            </div>
            
            
            <div id="lowerlist" class="lowerlist minheight150">
                <?php if ($level > 3) : ?>
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                <?php else : ?>    
                <?php if ($level == 2) : ?>
                <a href="<?php echo WEB?>/user" title="Manage you Users">
                    <div class="floatmainbutton cursorpoint"><i class="fa fa-users fa-2x whitetext mediumtext"></i></div>
                </a>
                <?php elseif ($level == 1 || $level == 3) : ?>
                <a href="<?php echo WEB?>/requisition" title="Start Requisit">
                    <div class="floatmainbutton cursorpoint"><i class="fa fa-shopping-cart fa-2x whitetext mediumtext"></i></div>
                </a>
                <?php endif; ?>
                <div class="lowerrightbig">
                <?php endif; ?>
                    
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS<?php if ($level == 1 || $level == 3 || $level == 9) : ?> Requestor<?php elseif ($level == 2) : ?> Approver<?php elseif ($level == 6) : ?> Administrations Head<?php elseif ($level == 7) : ?> Administration Assistant<?php elseif ($level == 8) : ?> Administrations<?php endif; ?> Dashboard</div>
                    
                    <?php if ($level == 8 && $zero_count) { ?>
                    <div id="oostock" class="oostock marginbottom12">Hi admin, you've <b><?php echo $zero_count; ?> out-of-stock and below the critical item<?php echo $zero_count > 1 ? 's' : ''; ?></b>. Click <a href="<?php echo WEB; ?>/stock" class="whitetext">here</a> to update your stock.</div>
                    <?php } ?>
                    
                    <?php if (ANNOUNCEMENT != "" && ($level == 1 || $level == 2)) : ?>
                    <div id="announcement" class="announcement marginbottom12"><?php echo ANNOUNCEMENT; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($level == 5 || $level == 6 || $level >= 8) : ?>
                    
                    <div class="halfchart marginbottom20">
                        <canvas id="chart01" class="marginbottom40"></canvas>
                        <canvas id="chart04"></canvas>
                    </div>
                    <div class="halfchart">
                        <canvas id="chart02" class="marginbottom20"></canvas>
                        <canvas id="chart03" class="marginbottom20"></canvas>
                        <canvas id="chart05"></canvas>
                    </div>
                    
                    
                    
                    <script type="text/javascript">
                        
                        var data = {
                            datasets: [{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->numtrans;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            //], backgroundColor: ['#00B9F2', '#FE0107', '#FCB03A', '#0DED01', '#EC018B'], }],
                            ], backgroundColor: ['#015601', '#d67500', '#EC018B', '#ea171f', '#022B5D'], }],
                            labels: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        if ($row->trans_status == 3) :
                                            echo '\'For Release\'';
                                        elseif ($row->trans_status == 4) :
                                            echo '\'Pending\'';
                                        elseif ($row->trans_status == 5) :
                                            echo '\'Released\'';
                                        elseif ($row->trans_status == 8) :
                                            echo '\'Declined\'';
                                        elseif ($row->trans_status == 9) :
                                            echo '\'Closed\'';
                                        endif;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ]
                        };
                        var ctx = document.getElementById('chart01').getContext('2d');
                        var myDoughnutChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: data,
                            options: {}
                        });
                        
                        var data2 = {
                            datasets: [{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart1 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->numtrans;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ], borderColor: '#022B5D', 
                            label: 'Closed'},{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart2 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->numtrans;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ], borderColor: '#EC018B', 
                            label: 'Released'},{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart3 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->numtrans;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ], borderColor: '#d67500', 
                            label: 'Pending'}], labels: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart2 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo '\''.$row->ndate.'\'';
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ]
                            
                        };
                        
                        var ctx2 = document.getElementById('chart02').getContext('2d');
                        var myLineChart = new Chart(ctx2, {
                            type: 'line',
                            data: data2,
                            options: {
                                title: {
                                    display: true,
                                    text: 'Last 5 Month Transaction Count'
                                }                                
                            }
                        });
                        
                        var data3 = {
                            datasets: [{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart4 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->transcount;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ], backgroundColor: ['#00a028', '#00a028', '#00a028', '#00a028', '#00a028']}], labels: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chart4 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo '\''.$row->dept_abbr.'\'';
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ]
                            
                        };
                        
                        var ctx3 = document.getElementById('chart03').getContext('2d');
                        var myLineChart = new Chart(ctx3, {
                            type: 'bar',
                            data: data3,
                            options: {
                                title: {
                                    display: true,
                                    text: 'Most Transaction Department'
                                },
                                legend: {
                                    display: false
                                },
                                scales:
                                {
                                    xAxes: [{
                                        display: false
                                    }]
                                }
                            }
                        });
                        
                        var data4 = {
                            datasets: [{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chartmain1 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->numtrans;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ], backgroundColor: '#03295a', 
                            label: 'Transactions'}], labels: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chartmain1 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo '\''.$row->ndate.'\'';
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ]
                            
                        };
                        
                        var ctx4 = document.getElementById('chart04').getContext('2d');
                        var myLineChart = new Chart(ctx4, {
                            type: 'bar',
                            data: data4,
                            options: {
                                title: {
                                    display: true,
                                    text: '2-week Transaction Count'
                                },
                                legend: {
                                    display: false
                                }, 
                                scales: {
                                    xAxes: [{
                                        display: false
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }           
                            }
                        });
                        
                        var data5 = {
                            datasets: [{data: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chartmain2 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo $row->numtrans;
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ], backgroundColor: '#d67500', 
                            label: 'Transactions'}], labels: [
                                <?php 
                                    $cnt = 0;
                                    foreach ($trans_count_chartmain2 as $row) :                                         
                                        echo ($cnt >= 1 ? ', ' : '');
                                        echo '\''.$row->ndate.'\'';
                                        $cnt++;
                                    endforeach; 
                                ?>      
                            ]
                            
                        };
                        
                        var ctx5 = document.getElementById('chart05').getContext('2d');
                        var myLineChart = new Chart(ctx5, {
                            type: 'bar',
                            data: data5,
                            options: {
                                title: {
                                    display: true,
                                    text: 'All-Time Pending Transaction Count'
                                },
                                legend: {
                                    display: false
                                }, 
                                scales: {
                                    xAxes: [{
                                        display: false
                                    }],
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                    
                    <?php else : ?>
                    
                    <?php if ($level == 2) : ?>
                        <div id="menu" class="smalltext marginbottom12">
                            <a href="<?php echo WEB; ?>"><?php echo $trans_sec == 'for approval' ? '<b>Approval ('.$count_approval.')</b>' : 'Approval ('.$count_approval.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/approved"><?php echo $trans_sec == 'approved' ? '<b>Approved ('.$count_approved.')</b>' : 'Approved ('.$count_approved.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/rejected"><?php echo $trans_sec == 'rejected' ? '<b>Declined ('.$count_reject.')</b>' : 'Declined ('.$count_reject.')'; ?></a> 
                        </div>
                    <?php elseif ($level == 7) : ?>
                        <div id="menu" class="smalltext marginbottom12">
                            <a href="<?php echo WEB; ?>"><?php echo $trans_sec == 'admin approve' ? '<b>For Release ('.$count_admin_approve.')</b>' : 'For Release ('.$count_admin_approve.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/release"><?php echo $trans_sec == 'release' ? '<b>Released ('.$count_release.')</b>' : 'Released ('.$count_release.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/close"><?php echo $trans_sec == 'close' ? '<b>Closed ('.$count_close.')</b>' : 'Closed ('.$count_close.')'; ?></a> 
                        </div>
                    <?php endif; ?>
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/<?php echo ($trans_sec != 'endorse' && $trans_sec != 'for approval' && $trans_sec != 'admin approve')  ? "irs/".str_replace(" ", "_", trim($trans_sec)) : ""; ?>" method="POST" enctype="multipart/form-data">
                                Search Transaction&nbsp;<input type="text" name="searchtrans" id="searchtrans" value="<?php echo $post['searchtrans']; ?>" placeholder="by ID<?php echo $level == 6 || $level == 7 ? ", requestor name" : ""  ?> or details..." class="txtbox" />
                                <?php if ($level == 1 || $level == 3) : ?>
                                &nbsp;<?php echo $this->Form->tstatus_dropdown('statustrans', 'statustrans', $post['statustrans']); ?>
                                <?php endif; ?>
                                &nbsp;<button name="btntranssearch" class="btn"><i class="fa fa-search"></i> Search</button>
                                <?php if ($post['searchtrans']) : ?>&nbsp;<input type="button" name="btntransall" id="transall" value="View All" class="btn" /><?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <div id="dboard_list" class="dboard_list">
                        <table class="tdata"> 
                            <tr>
                                <th width="5%">Transaction ID</th>
                                <?php if ($level == 1 || $level == 3) : ?>
                                    <th width="15%">Date</th>                             
                                    <th width="55%">Details</th>                    
                                <?php elseif ($level == 2) : ?>
                                    <th width="15%">Request by</th>     
                                    <th width="20%">Date</th>  
                                    <?php if ($trans_sec == 'for approval') : ?>
                                        <th width="35%">Details</th>   
                                    <?php else : ?>
                                        <th width="45%">Details</th>   
                                    <?php endif; ?>
                                <?php elseif ($level == 7) : ?>
                                    <th width="10%">Request by</th>                             
                                    <th width="15%">Date Approved</th>                   
                                    <th width="30%">Details</th>   
                                    <th width="25%">Remarks</th>   
                                <?php endif; ?>
                                <th width="15%">Status</th>   
                                <?php if (($trans_sec == 'for approval' && $level == 2) || $level == 1 || $level == 3 || ($level == 7 && $trans_sec == 'admin approve')) : ?>
                                <th width="10%"<?php if ($level == 2 || $level == 7) : ?> colspan="2"<?php endif; ?>>Manage</th>
                                <?php endif; ?>
                            </tr>
                            <?php if ($trans_data) : ?>
                            <?php foreach ($trans_data as $row) : ?>
                            <tr>
                                <td><?php echo $row->trans_dateid; ?></td>                                
                                <?php if ($level == 2 || $level == 7) : ?>                                
                                <td><b><?php echo strtoupper($row->user_fullname); ?></b><br><?php $dept_data = $this->Core->get_dept($row->user_dept); ?><?php echo $dept_data['dept_name']; ?>
                                </td>
                                <?php endif; ?>
                                <?php if ($level == 7) : ?>    
                                <td><?php echo $row->trans_approvedate ? mdate("%M %j, %Y | %g:%i%a", $row->trans_approvedate) : ''; ?></td>
                                <?php else : ?>
                                <td><?php echo mdate("%M %j, %Y | %g:%i%a", $row->trans_date); ?></td>
                                <?php endif; ?>
                                <?php
                                    $order_value = html_entity_decode($row->trans_order, ENT_QUOTES); 
                                    $order_value = unserialize($order_value);
                                ?>
                                <td>
                                    <?php $cart_item = ""; ?>
                                    <?php $exceed_count = 0; ?>
                                    <?php $partial_stock_count = 0; ?>
                                    <?php foreach ($order_value as $orderrow) : ?>
                                        <?php if ($level == 7) : ?>
                                            <?php 
                                                if ($trans_sec == 'endorse' || $trans_sec == 'pending') :
                                                    $exceed = $this->Core->check_if_qr_exceed($orderrow['id'], $orderrow['qty']); 
                                                    $exceed_nonunit = $this->Core->check_if_qr_exceed_nonunit($orderrow['id'], $orderrow['qty']); 
                                                else :
                                                    $exceed = $this->Core->check_if_exceed($orderrow['id'], $orderrow['qty']); 
                                                    $exceed_nonunit = $this->Core->check_if_exceed_nonunit($orderrow['id'], $orderrow['qty']); 
                                                endif;
                                                if ($exceed_nonunit == NULL || $exceed_nonunit != 0) : $partial_stock_count = 1; endif;
                                            ?>
                                            <?php if (($exceed && $trans_sec == 'endorse') || ($exceed && $trans_sec == 'pending') || ($exceed && $trans_sec == 'admin approve' && level == 7)) : 
                                                $cart_item .= '<span class="redtext blinked">'; 
                                                $exceed_count++;
                                            endif; ?>
                                        <?php endif; ?>
                                        <?php $cart_item .= $orderrow['qty']." - ";
                                        foreach ($orderrow['options'] as $option_name => $option_value):
                                            $option_value = ($orderrow['qty'] > 1 ? $option_value."s" : $option_value);
                                            $option_value = ($option_value == "boxs" ? "boxes" : $option_value);
                                            $option_value = ($option_value == "inchs" ? "inches" : $option_value);
                                            $cart_item .= $option_name == "unit" ? $option_value : "";
                                        endforeach;
                                        $cart_item .= ' of '.$orderrow['name']; ?>
                                        <?php if ($level == 7) : ?>
                                            <?php if (($exceed && $trans_sec == 'endorse') || ($exceed && $trans_sec == 'pending') || ($exceed && $trans_sec == 'admin approve' && level == 7)) :
                                                $exceedtext = $exceed_nonunit == 0 || $exceed_nonunit == "OO" ? '<i>Out of stock</i>' : '<i>The stock have '.$exceed.' </i>';
                                                $cart_item .= ' *</span><br />'.$exceedtext; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php $cart_item .= '<br />'; ?>
                                    <?php endforeach; ?>  
                                    <?php echo $cart_item; ?>  
                                    <?php echo trim($row->trans_reqremarks) ? '<b style="color: #F00;">Requestor\'s Remarks:</b> <span style="font-weight: normal !important;">'.$row->trans_reqremarks.'</span><br>' : ""; ?>
                                    <?php if (($level == 1 || $level == 3) && $row->trans_status == 2) : ?><?php echo trim($row->trans_appremarks) ? '<b style="color: #F00;">Approver\'s Remarks:</b> <span style="font-weight: normal !important;">'.$row->trans_appremarks.'</span><br>' : ""; ?><?php endif; ?>  
                                    <?php if (($level == 1 || $level == 3) && ($row->trans_status == 3 || $row->trans_status == 5)) : ?><?php echo $row->trans_approvedate ? '<b style="color: #F00;">Approver\'s Approved Date:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y | %g:%i%a", $row->trans_approvedate).'</span><br>' : ""; ?><?php echo trim($row->trans_remarks) ? '<b style="color: #F00;">Supplier Remarks:</b> <span style="font-weight: normal !important;">'.$row->trans_remarks.'</span><br>' : ""; ?><?php echo $row->trans_admindate ? '<b style="color: #F00;">Supplier Approved Date:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y | %g:%i%a", $row->trans_admindate).'</span><br>' : ""; ?><?php echo $row->trans_releasedate ? '<b style="color: #F00;">Released Date:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y | %g:%i%a", $row->trans_releasedate).'</span><br>' : ""; ?><?php endif; ?>  
                                    <?php if (($level == 1 || $level == 3) && $row->trans_status == 9) : ?><?php echo $row->trans_update ? '<b style="color: #F00;">Date Closed:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y | %g:%i%a", $row->trans_update).'</span><br>' : ""; ?><?php endif; ?>  
                                </td>
                                <?php if ($level == 7) : ?>      
                                <td><?php echo $row->trans_remarks; ?></td>
                                <?php endif; ?>
                                <td><?php echo $this->Core->display_status($row->trans_status, $level); ?></td>
                                <?php if (($trans_sec == 'for approval' && $level == 2) || $level == 1 || $level == 3 || ($level == 7 && $trans_sec == 'admin approve')) : ?>
                                <td align="center" class="managediv<?php echo $row->trans_id; ?>">
                                    <?php if ($level == 2) : ?>                                
                                        <a class="apprTrans cursorpoint underlined" title="Approve Request for Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-thumbs-up fa-lg greentext"></i></a>
                                    <?php elseif ($level == 7) : ?>                    
                                        <?php if ($exceed_count > 0) : ?>                    
                                            <?php if ($trans_sec == 'admin approve') : ?><a class="returnTrans cursorpoint underlined" title="Return Request for Review on Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-reply fa-lg dbluetext"></i></a><?php endif; ?>
                                        <?php else : ?>                                                            
                                            <a class="releaseTrans cursorpoint underlined" title="Release Request for Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-send fa-lg dbluetext"></i></a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if ($row->trans_status == 1 || $row->trans_status == 4 || $row->trans_status == 8) : ?>
                                        <a title="Cancel Request for Transaction No.: <?php echo $row->trans_dateid; ?>" class="delTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-times fa-lg redtext"></i></a>
                                        <?php elseif ($row->trans_status == 5) : ?>
                                        <a class="closeTrans cursorpoint underlined" title="Close Request for Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-times-circle fa-lg dbluetext"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <?php if ($level == 2) : ?>                                
                                <td align="center" class="manage2div<?php echo $row->trans_id; ?>">                                    
                                    <a class="dapprTrans cursorpoint underlined" title="Decline Request for Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-thumbs-down fa-lg redtext"></i></a>
                                </td>
                                <?php endif; ?>
                                <?php endif; ?>
                            </tr>    
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>                                
                                <td colspan="
                                <?php
                                    if ($level == 1 || $level == 3 || ($level == 2 && $trans_sec != "for approval")) :
                                        echo "5";
                                    else :
                                        echo "7";
                                    endif;
                                ?>
                                " align="center">No <?php echo $trans_sec == "admin approve" ? "for release" : $trans_sec; ?> request found</td>
                            </tr>   
                            <?php endif; ?>
                        </table>
                        
                    </div>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                    
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>