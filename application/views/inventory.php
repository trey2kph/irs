    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Inventory as of <?php echo mdate("%M %j, %Y - %g:%i%a", time()); ?></div>                    
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/inventory/" method="POST" enctype="multipart/form-data">
                                Search Item&nbsp;<input type="text" name="searchinv" id="searchinv" value="<?php echo $post['searchinv'] ?>" placeholder="by name..." class="searchinv txtbox width100" />&nbsp;<?php echo $this->Form->icat_dropdown('searchcat', 'searchcat', $post['searchcat']); ?>&nbsp;<button type="submit" name="btninvsearch" class="btn"><i class="fa fa-search"></i> Search</button><?php if ($post['searchinv']) : ?>&nbsp;<button type="button" name="btninvall" onClick="window.location.href = window.location.href;" class="btn"><i class="fa fa-refresh"></i> View All</button><?php endif; ?>&nbsp;<button type="button" name="btninvprint" class="btn invprint"><i class="fa fa-file-pdf-o"></i> PDF Report</button>&nbsp;<button type="button" name="btninvcsv" class="btn invcsv"><i class="fa fa-file-excel-o"></i> XLS Report</button>
                            </form>
                        </div>
                    </div>
                    <div class="inv_list">
                        <table class="tdatahead" width="700px">                            
                            <tr>
                                <th width="25px">ID</th>
                                <th width="95px">Category</th>
                                <th width="175px">Item Name</th>
                                <th width="200px">Description</th>              
                                <th width="55px">Quantity</th>
                                <th width="35px">Unit</th>
                                <!--th width="80px">Price per Unit</th>
                                <th width="80px">Total Price</th-->
                            </tr>
                        </table>
                        <div id="inv_list_inside" class="tblscroll">
                        <table class="tdatamid" width="700px">                                        
                            <?php $total_price = 0; ?>
                            <?php foreach ($inventory_data as $row) : ?>
                                <tr>
                                    <td width="25px"><?php echo $row->item_id; ?></td>
                                    <td width="95px" class="tablewrap"><?php echo $row->cat_name; ?></td>
                                    <td width="175px" class="tablewrap"><?php echo $row->item_name; ?></td>
                                    <td width="200px" class="tablewrap"><?php echo $row->item_desc; ?></td>
                                    <td width="55px"><?php echo $row->item_quantity; ?></td>
                                    <td width="35px"><?php echo $row->unit_name; ?></td>
                                    <!--td width="80px" class="righttalign tablewrap"><?php //echo number_format($row->item_price, 2); ?></td>
                                    <td width="80px" class="righttalign tablewrap"><?php //echo number_format($row->item_price * $row->item_quantity, 2); ?></td-->
                                </tr>
                                <?php $total_price += floatval($row->item_price * $row->item_quantity); ?>
                            <?php endforeach; ?>
                        </table>
                        </div>
                        <table class="tdatafoot" width="700px">
                            <tr>
                                <td width="670px" class="righttalign">&nbsp;</td>
                                <!--td width="536px" class="righttalign">Total</td>
                                <td width="134px" id="totalquan" class="righttalign bold"><?php //echo number_format($total_price, 2); ?></td-->
                            </tr>
                        </table>
                    </div>
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS In and Out Inventory on <?php echo $post['inout_date_from'] ? mdate("%M %j, %Y", strtotime($post['inout_date_from'])) : mdate("%M %j, %Y", strtotime('last month')); ?> to <?php echo mdate("%M %j, %Y", strtotime($post['inout_date_to'])); ?></div>                    
                    <div class="data_console">
                        <div class="data_search">                            
                            <form id="frminvdate" action="<?php echo WEB; ?>/inventory/#inout" method="POST">
                                From&nbsp;<input type="text" name="inout_date_from" id="inout_date_from" class="inout_date_from width100 txtbox" value="<?php echo $post['inout_date_from'] ? $post['inout_date_from'] : mdate("%Y-%m-%d", strtotime("last month")); ?>" />&nbsp;To&nbsp;<input type="text" name="inout_date_to" id="inout_date_to" class="inout_date_to width100 txtbox" value="<?php echo $post['inout_date_to'] ? $post['inout_date_to'] : mdate("%Y-%m-%d"); ?>" /><?php if ($inout_data) : ?>&nbsp;<button type="button" name="btnprint" class="btn inoutprint"><i class="fa fa-file-pdf-o"></i> PDF Report</button>&nbsp;<button type="button" name="btncsv" class="btn inoutcsv"><i class="fa fa-file-excel-o"></i> XLS Report</button><?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <a name="inout"></a>
                    <div class="inout_list">
                        <table class="tdatahead" width="700px">                            
                            <tr>
                                <th width="25px">ID</th>
                                <th width="95px">Item Name</th>
                                <th width="155px">Description</th>              
                                <th width="60px">Beginning Balance</th>              
                                <th width="40px">In</th>
                                <th width="40px">Out</th>
                                <th width="60px">Difference</th>
                                <th width="60px">Ending Balance</th>
                                <th width="55px">Current Quantity</th>
                            </tr>
                        </table>
                        <div id="inout_list_inside" class="tblscroll">
                        <table class="tdatamid" width="700px"> 
                            <?php if ($inout_data) : ?>
                            <?php $total_price = 0; ?>
                            <?php foreach ($inout_data as $row) : ?>
                                <tr>
                                    <?php
                                        $stock_in = $this->Core->get_stock('IN', $row->item_id, $searchdate ? $searchdate : mdate('%Y-%m-%d', strtotime('last month')), $searchdateto ? $searchdateto : mdate('%Y-%m-%d'), 1);
                                        $stock_out = $this->Core->get_stock('OUT', $row->item_id, $searchdate ? $searchdate : mdate('%Y-%m-%d', strtotime('last month')), $searchdateto ? $searchdateto : mdate('%Y-%m-%d'), 1);
                                        $s_in = $stock_in[0]['iqty'] ? $stock_in[0]['iqty'] : 0;
                                        $s_out = $stock_out[0]['iqty'] ? $stock_out[0]['iqty'] : 0;
                                        $s_diff = $s_in - $s_out;
                                        $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', $searchdate ? mdate('%Y-%m-%d', strtotime($searchdate) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                                        $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', $searchdate ? mdate('%Y-%m-%d', strtotime($searchdate) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                                        $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
                                        $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
                                        $b_diff = $b_in - $b_out;
                                        $e_diff = $b_diff + $s_diff;
                                    ?>
                                    <td width="25px"><?php echo $row->item_id; ?></td>
                                    <td width="95px"><?php echo $row->item_name; ?></td>
                                    <td width="155px"><?php echo $row->item_desc; ?></td>
                                    <td width="60px" class="centertalign"><?php echo $b_diff; ?></td>
                                    <td width="40px" class="centertalign"><?php echo $s_in; ?></td>
                                    <td width="40px" class="centertalign"><?php echo $s_out; ?></td>
                                    <td width="60px" class="centertalign">
                                    <?php 
                                        if ($s_diff > 0) :
                                            echo '<span class="greentext"><i class="fa fa-caret-up"></i> '.$s_diff.'</span>';
                                        elseif ($s_diff < 0) : 
                                            echo '<span class="redtext"><i class="fa fa-caret-down"></i> '.abs($s_diff).'</span>'; 
                                        else :
                                            echo 0;
                                        endif;
                                    ?>
                                    </td>
                                    <td width="60px" class="centertalign"><?php echo $e_diff; ?></td>
                                    <td width="55px" class="centertalign"><?php echo $row->item_quantity; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td width="680px" class="centertalign">No in and out record found for this day</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        </div>
                        <table class="tdatafoot" width="700px">
                            <tr>
                                <td width="680px" class="righttalign">&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Consumption on <?php echo mdate("%M %j, %Y", strtotime($post['consumpt_date_from'])); ?> to <?php echo mdate("%M %j, %Y", strtotime($post['consumpt_date_to'])); ?></div>                    
                    <div class="data_console">
                        <div class="data_search">                            
                            <form id="frmconsumpt" action="<?php echo WEB; ?>/inventory/#consumpt" method="POST">
                                From&nbsp;<input type="text" name="consumpt_date_from" id="consumpt_date_from" class="consumpt_date_from width100 txtbox" value="<?php echo $post['consumpt_date_from'] ? $post['consumpt_date_from'] : mdate("%Y-%m-%d"); ?>" />&nbsp;To&nbsp;<input type="text" name="consumpt_date_to" id="consumpt_date_to" class="consumpt_date_to width100 txtbox" value="<?php echo $post['consumpt_date_to'] ? $post['consumpt_date_to'] : mdate("%Y-%m-%d"); ?>" />&nbsp;<?php echo $this->Form->dept_dropdown('consumptdept', 'consumptdept', $consumptdept); ?>&nbsp;<button type="button" name="btncsv" class="btn consumptcsv"><i class="fa fa-file-excel-o"></i> XLS Report</button>
                            </form>
                        </div>
                    </div>
                    <a name="consumpt"></a>
                    <div class="consumpt_list">
                        
                        
                        <table class="tdatahead">                            
                            <tr>
                                <th width="100px">Item</th>
                                <th width="140px">Description</th>
                                <?php 
                                $consumpt_hdept = $this->Core->get_dept($consumptdept); 
                                ?>
                                <th width="50px"><?php echo $consumpt_hdept['dept_abbr'] ? $consumpt_hdept['dept_abbr'] : 'ADMIN'; ?>'s Consumption</th>
                                <th width="50px">Current Balance</th>
                        
                            </tr>
                        </table>
                        <div id="consumpt_list_inside" class="tblscroll">
                        <table class="tdatamid">                            
                
                            <?php 

                                $consumpt_cat = $this->Core->get_cat();
                        
                                foreach ($consumpt_cat as $ccat) :
                                    $consumpt_data = $this->Core->get_item(1, 0, 0, 0, $ccat->cat_id, 0, 0, 0); 
                                    if ($consumpt_data) :
                                        foreach ($consumpt_data as $rowdata) :
                                            ?>                                    
                                            <tr>
                                            <td width="100px"><?php echo $rowdata->item_name; ?></td>
                                            <td width="140px"><?php echo $rowdata->item_desc; ?></td>
                                            
                                            <?php
                                            $consumpt_dept = $this->Core->get_dept($consumptdept);
                                            ?>
                                            <td align="center" width="50px">
                                            <?php
                                            $consumpt_item = $this->Core->get_idept_from_log(($searchcdate ? $searchcdate : mdate('%Y-%m-%d')), ($searchcdateto ? $searchcdateto : mdate('%Y-%m-%d')), $rowdata->item_id, $consumpt_dept['dept_id']);                         
                                            if ($consumpt_item) :
                                                foreach ($consumpt_item as $rowitem) :
                                                    echo $rowitem->qty_total;
                                                endforeach;
                                            else :
                                                echo '0';
                                            endif;
                                            ?>
                                            </td>
                                            <td align="center" width="50px"><?php echo $rowdata->item_quantity; ?></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                    
                                endforeach;       
                            ?>
                        </table>
                        </div>
                        <table class="tdatafoot" width="700px">
                            <tr>
                                <td width="680px" class="righttalign">
                                    <?php
                                    $consumpt_fdept = $this->Core->get_dept($consumptdept);      
                                    $consumpt_total_dept = $this->Core->get_ddept_from_log(($searchcdate ? $searchcdate : mdate('%Y-%m-%d')), ($searchcdateto ? $searchcdateto : mdate('%Y-%m-%d')), 0, $consumpt_fdept['dept_id']);                         
                                
                                    if ($consumpt_total_dept) :
                                        foreach ($consumpt_total_dept as $total_dept) :                                
                                            ?><b>Total: <?php echo $total_dept->qty_total; ?></b><?php
                                        endforeach;
                                    else :
                                        ?><b>Total: 0</b><?php
                                    endif;
                                    ?>
                                </td>
                            </tr>
                        </table>                           
                    </div>
                    
                </div>
            </div>
        </div>
    </div>