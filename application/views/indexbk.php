    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <?php if ($level > 2) : ?>
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                <?php else : ?>    
                <div class="lowerrightbig">
                <?php endif; ?>
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS<?php if ($level == 1 || $level == 9) : ?> Requestor<?php elseif ($level == 2) : ?> Approver<?php elseif ($level == 6) : ?> Administrator (Read Only)<?php elseif ($level == 7) : ?> Administrator Assistant<?php elseif ($level == 8) : ?> Administrator<?php endif; ?> Dashboard</div>
                    <?php if ($level == 1 || $level == 2) : ?>
                        <?php if ($ann['ann_text'] != NULL || trim($ann['ann_text']) != '') : ?>
                            <!--table width="100%" class="tdataform marginbottom12"> 
                                <tr>
                                    <th width="100%">Announcement</th>
                                </tr>
                                <tr>
                                    <td><?php //echo $ann['ann_text']; ?></td>
                                </tr>
                            </table-->
                        <?php endif; ?>
                    <?php else : ?>
                            <!--table width="100%" class="tdataform marginbottom12"> 
                                <tr>
                                    <th width="100%">Edit Announcement</th>
                                </tr>
                                <tr>
                                    <td><textarea rows="2" cols="80" class="txtannounce txtbox"><?php //echo $ann['ann_text']; ?></textarea></td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <div id="annstat" class="redtext righttalign">Last Update: <?php //echo mdate('%M %j, %Y %g:%i%a', $ann['ann_date']); ?></div>
                                        <input type="button" name="btnupdateannounce" value="Update" class="btn btnupdateannounce" />
                                    </td>
                                </tr>
                            </table-->
                    <?php endif; ?>
                    <?php if ($level == 2) : ?>
                        <div id="menu" class="smalltext marginbottom12">
                            <a href="<?php echo WEB; ?>"><?php echo $trans_sec == 'for approval' ? '<b>Approval ('.$count_approval.')</b>' : 'Approval ('.$count_approval.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/approved"><?php echo $trans_sec == 'approved' ? '<b>Approve ('.$count_approved.')</b>' : 'Approve ('.$count_approved.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/rejected"><?php echo $trans_sec == 'rejected' ? '<b>Reject ('.$count_reject.')</b>' : 'Reject ('.$count_reject.')'; ?></a> 
                        </div>
                    <?php elseif ($level == 6 || $level == 7) : ?>
                        <div id="menu" class="smalltext marginbottom12">
                            <a href="<?php echo WEB; ?>"><?php echo $trans_sec == 'admin approve' ? '<b>Admin Approved ('.$count_admin_approve.')</b>' : 'Admin Approved ('.$count_admin_approve.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/release"><?php echo $trans_sec == 'release' ? '<b>Released ('.$count_release.')</b>' : 'Released ('.$count_release.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/close"><?php echo $trans_sec == 'close' ? '<b>Closed ('.$count_close.')</b>' : 'Closed ('.$count_close.')'; ?></a> 
                        </div>
                    <?php elseif ($level == 8) : ?>
                        <div id="menu" class="smalltext marginbottom12">
                            <a href="<?php echo WEB; ?>"><?php echo $trans_sec == 'endorse' ? '<b>Endorse ('.$count_endorse.')</b>' : 'Endorse ('.$count_endorse.')'; ?></a> | 
                            <a href="<?php echo WEB; ?>/irs/admin_approve"><?php echo $trans_sec == 'admin approve' ? '<b>Approved ('.$count_admin_approve.')</b>' : 'Approved ('.$count_admin_approve.')'; ?></a> | 
                            <!--a href="<?php echo WEB; ?>/irs/pending"><?php echo $trans_sec == 'pending' ? '<b>Pending ('.$count_pending.')</b>' : 'Pending ('.$count_pending.')'; ?></a> | --> 
                            <a href="<?php echo WEB; ?>/irs/admin_reject"><?php echo $trans_sec == 'admin reject' ? '<b>Rejected ('.$count_admin_reject.')</b>' : 'Rejected ('.$count_admin_reject.')'; ?></a> 
                        </div>
                    <?php endif; ?>
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/<?php echo $trans_sec != 'for approval' ? "irs/".$trans_sec : ""; ?>" method="POST" enctype="multipart/form-data">
                                Search Transaction&nbsp;<input type="text" name="searchtrans" id="searchtrans" value="<?php echo $post['searchtrans']; ?>" placeholder="by ID or details..." class="txtbox width100" />
                                <?php if ($level == 1 || $level == 9) : ?>
                                &nbsp;<?php echo $this->Form->tstatus_dropdown('statustrans', 'statustrans', $post['statustrans']); ?>
                                <?php endif; ?>
                                &nbsp;<input type="submit" name="btntranssearch" value="Search" class="btn" />
                                <?php if ($post['searchtrans']) : ?>&nbsp;<input type="button" name="btntransall" id="transall" value="View All" class="btn" /><?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <div id="dboard_list" class="dboard_list">
                        <table class="tdata"> 
                            <tr>
                                <th width="5%">Transaction ID</th>
                                <?php if ($level == 1 || $level == 9) : ?>
                                <th width="15%">Date</th>                             
                                <th width="55%">Details</th>                    
                                <?php elseif ($level == 2 || $level == 6 || $level == 7 || $level == 8) : ?>
                                <th width="15%">Request by</th>                             
                                <th width="15%">Date</th>                             
                                <?php if ($trans_sec == 'for approval') : ?>
                                <th width="40%">Details</th>   
                                <?php else : ?>
                                <th width="50%">Details</th>   
                                <?php endif; ?>
                                <?php endif; ?>
                                <th width="15%">Status</th>   
                                <?php if (($trans_sec == 'for approval' && $level == 2) || $level == 1 || ($level == 7 && $trans_sec == 'admin approve') || ($level == 8 && ($trans_sec == 'endorse' || $trans_sec == 'pending')) || $level == 9) : ?>
                                <th width="10%"<?php if ($level == 2 || $level == 7) : ?> colspan="2"<?php endif; ?>>Manage</th>
                                <?php endif; ?>
                            </tr>
                            <?php if ($trans_data) : ?>
                            <?php foreach ($trans_data as $row) : ?>
                            <tr <?php if (($level == 1 || $level == 9) && $row->trans_status == 4 && !$post['statustrans']) : echo 'class="bold blinked"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 2 && !$post['statustrans']) : echo 'class="bluetext"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 3 && !$post['statustrans']) : echo 'class="redtext"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 8 && !$post['statustrans']) : echo 'class="lgraytext"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 9 && !$post['statustrans']) : echo 'class="greentext"'; endif; ?>>
                                <td><?php echo $row->trans_id; ?></td>                                
                                <?php if ($level == 2 || $level == 6 || $level == 7 || $level == 8) : ?>                                
                                <td><?php echo $row->user_fullname; ?></td>
                                <?php endif; ?>
                                <td><?php echo mdate("%M %j, %Y<br />%g:%i%a", $row->trans_date); ?></td>
                                <?php
                                    $order_value = html_entity_decode($row->trans_order, ENT_QUOTES); 
                                    $order_value = unserialize($order_value);
                                ?>
                                <td>
                                    <?php $cart_item = ""; ?>
                                    <?php $exceed_count = 0; ?>
                                    <?php foreach ($order_value as $orderrow) : ?>
                                        <?php if ($level == 8) : ?>
                                        <?php $exceed = $this->Core->check_if_exceed($orderrow['id'], $orderrow['qty']); ?>
                                        <?php if (($exceed && $trans_sec == 'endorse') || ($exceed && $trans_sec == 'pending')) : 
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
                                        <?php if ($level == 8) : ?>
                                        <?php if (($exceed && $trans_sec == 'endorse') || ($exceed && $trans_sec == 'pending')) $cart_item .= ' *</span><br /><i>The stock have '.$exceed.' </i> - <a href="'.WEB.'/stock/edit/id/'.$orderrow['id'].'" class="underlined">Manage</a>'; ?>
                                        <?php endif; ?>
                                        <?php $cart_item .= '<br />'; ?>
                                    <?php endforeach; ?>  
                                    <?php echo $cart_item; ?>      
                                </td>
                                <td><?php echo $this->Core->display_status($row->trans_status, $level); ?></td>
                                <?php if (($trans_sec == 'for approval' && $level == 2) || $level == 1 || ($level == 7 && $trans_sec == 'admin approve') || ($level == 8 && ($trans_sec == 'endorse' || $trans_sec == 'pending')) || $level == 9) : ?>
                                <td align="center" class="managediv<?php echo $row->trans_id; ?>">
                                    <?php if ($level == 2) : ?>                                
                                        <a class="apprTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Approve</a>
                                    <?php elseif ($level == 8) : ?>                    
                                        <?php if ($exceed_count > 0) : ?>                    
                                        <?php if ($trans_sec == 'endorse') : ?>                                                            
                                            <a class="pendTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Place on Pending</a>
                                        <?php endif; ?>
                                        <?php else : ?>                                                            
                                            <a class="adminAppTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Approve for Release</a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if ($row->trans_status == 1 || $row->trans_status == 8) : ?>
                                        <a title="Cancel Request for Transaction No.: <?php echo $row->trans_id; ?>" class="delTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>"><i class="fa fa-times-circle fa-lg redtext"></i></a>
                                        <?php elseif ($row->trans_status == 4) : ?>
                                        <a class="closeTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Close</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <?php if ($level == 2) : ?>                                
                                <td align="center" class="manage2div<?php echo $row->trans_id; ?>">                                    
                                    <a class="dapprTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Reject</a>
                                </td>
                                <?php elseif ($level == 8) : ?>                                
                                    <?php if ($exceed_count > 0) : ?>                    
                                        <?php if ($trans_sec == 'endorse') : ?>                                                        
                                            <td align="center" class="manage2div<?php echo $row->trans_id; ?>">
                                                <a class="adminRejTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Reject</a>    
                                            </td>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php endif; ?>
                            </tr>    
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>                                
                                <td colspan="
                                <?php
                                    if ($level == 8) :
                                        echo "6";
                                    elseif ($level == 1 || $level == 9 || ($level == 2 && $trans_sec != "for approval")) :
                                        echo "5";
                                    else :
                                        echo "7";
                                    endif;
                                ?>
                                " align="center">No <?php echo $trans_sec; ?> request found</td>
                            </tr>   
                            <?php endif; ?>
                        </table>
                        
                    </div>
                    <?php if (($level == 8 && $trans_sec == 'endorse') || ($level == 8 && $trans_sec == 'pending')) : ?><i class="redtext">* beyond order point</i><?php endif; ?>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>