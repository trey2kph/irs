                        <table class="tdata"> 
                            <tr>
                                <th width="5%">Transaction ID</th>
                                <?php if ($level == 9) : ?>
                                    <th width="15%">Date</th>                             
                                    <th width="55%">Details</th>               
                                <?php elseif ($level == 6) : ?>
                                    <th width="15%">Request by</th>     
                                    <th width="20%">Date</th>  
                                    <?php if ($trans_sec == 'for approval') : ?>
                                        <th width="35%">Details</th>   
                                    <?php else : ?>
                                        <th width="45%">Details</th>   
                                    <?php endif; ?>                    
                                <?php elseif ($level == 8) : ?>
                                    <th width="15%">Request by</th>                             
                                    <?php if ($trans_sec == 'pending') : ?>
                                    <th width="20%">Date Pend</th>  
                                    <?php else : ?>
                                    <th width="20%">Date Approved</th>  
                                    <?php endif; ?>
                                    <?php if ($trans_sec == 'for approval') : ?>
                                        <th width="35%">Details</th>   
                                    <?php else : ?>
                                        <th width="45%">Details</th>   
                                    <?php endif; ?>
                                <?php endif; ?>
                                <th width="15%">Status</th>   
                                <?php if (($level == 8 && ($trans_sec == 'endorse' || $trans_sec == 'pending')) || $level == 9) : ?>
                                <th width="10%"<?php if ($level == 8) : ?> colspan="2"<?php endif; ?>>Manage</th>
                                <?php endif; ?>
                            </tr>
                            <?php if ($trans_data) : ?>
                            <?php foreach ($trans_data as $row) : ?>
                            <tr>
                                <td><?php echo $row->trans_dateid; ?></td>  
                                <?php if ($level == 6) : ?>                                
                                <td><b><?php echo strtoupper($row->user_fullname); ?></b><br><?php $dept_data = $this->Core->get_dept($row->user_dept); ?><?php echo $dept_data['dept_name']; ?>
                                </td>
                                <?php endif; ?>
                                <?php if ($level == 8) : ?>                                
                                <td><b><?php echo strtoupper($row->user_fullname); ?></b><br><?php $dept_data = $this->Core->get_dept($row->user_dept); ?><?php echo $dept_data['dept_name']; ?>
                                </td>
                                <?php endif; ?>
                                <?php if ($level == 8) : ?>    
                                <?php if ($trans_sec == 'pending') : ?>    
                                <td><?php echo mdate("%M %j, %Y<br />%g:%i%a", $row->trans_date); ?></td>
                                <?php else : ?>
                                <td><?php echo $row->trans_approvedate ? mdate("%M %j, %Y<br />%g:%i%a", $row->trans_approvedate) : ''; ?></td>
                                <?php endif; ?>
                                <?php else : ?>
                                <td><?php echo mdate("%M %j, %Y<br />%g:%i%a", $row->trans_date); ?></td>
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
                                        <?php if ($level == 8) : ?>
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
                                            <?php if (($exceed && $trans_sec == 'endorse') || ($exceed && $trans_sec == 'pending')) :
                                                $exceedtext = $exceed_nonunit == 0 || $exceed_nonunit == "OO" ? '<i>Out of stock</i>' : '<i>The stock have '.$exceed.' </i>';
                                                $cart_item .= ' *</span><br />'.$exceedtext; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php $cart_item .= '<br />'; ?>
                                    <?php endforeach; ?>  
                                    <?php echo $cart_item; ?>  
                                    <?php echo trim($row->trans_reqremarks) ? '<b style="color: #F00;">Requestor\'s Remarks:</b> <span style="font-weight: normal !important;">'.$row->trans_reqremarks.'</span><br>' : ""; ?>
                                    <?php if (($level == 8 || $level == 9) && $row->trans_status == 2) : ?><?php echo trim($row->trans_appremarks) ? '<b style="color: #F00;">Approver\'s Remarks:</b> <span style="font-weight: normal !important;">'.$row->trans_appremarks.'</span><br>' : ""; ?><?php endif; ?>  
                                    <?php if (($level == 8 || $level == 9) && ($row->trans_status == 3 || $row->trans_status == 5)) : ?><?php echo $row->trans_approvedate ? '<b style="color: #F00;">Approver\'s Approved Date:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y<br />%g:%i%a", $row->trans_approvedate).'</span><br>' : ""; ?><?php echo trim($row->trans_remarks) ? '<b style="color: #F00;">Supplier Remarks:</b> <span style="font-weight: normal !important;">'.$row->trans_remarks.'</span><br>' : ""; ?><?php echo $row->trans_admindate ? '<b style="color: #F00;">Supplier Approved Date:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y<br />%g:%i%a", $row->trans_admindate).'</span><br>' : ""; ?><?php echo $row->trans_releasedate ? '<b style="color: #F00;">Released Date:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y<br />%g:%i%a", $row->trans_releasedate).'</span><br>' : ""; ?><?php endif; ?>  
                                    <?php if (($level == 8 || $level == 9) && $row->trans_status == 9) : ?><?php echo $row->trans_update ? '<b style="color: #F00;">Date Closed:</b> <span style="font-weight: normal !important;">'.mdate("%M %j, %Y<br />%g:%i%a", $row->trans_update).'</span><br>' : ""; ?><?php endif; ?>  
                                </td>
                                <?php if ($level == 7) : ?>      
                                <td><?php echo $row->trans_remarks; ?></td>
                                <?php endif; ?>
                                <td><?php echo $this->Core->display_status($row->trans_status, $level); ?></td>
                                <?php if (($level == 8 && ($trans_sec == 'endorse' || $trans_sec == 'pending')) || $level == 9) : ?>
                                <td align="center" class="managediv<?php echo $row->trans_id; ?>">
                                    <?php if ($level == 8) : ?>                    
                                        <?php if ($exceed_count > 0) : ?>                    
                                        <?php if ($trans_sec == 'endorse' || $trans_sec == 'pending') : ?>                                                     
                                            <?php if ($partial_stock_count == 1) : ?>
                                                <a title="Adjust Request Items for Transaction No.: <?php echo $row->trans_dateid; ?>" class="manageTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-sliders fa-lg dbluetext"></i></a>
                                            <?php else : ?>
                                                <?php if ($trans_sec != 'pending') : ?>
                                                    <a title="Pending Request Items for Transaction No.: <?php echo $row->trans_dateid; ?>" class="cancelTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-inbox fa-lg orangetext"></i></a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php else : ?>                                                            
                                            <a title="Approve for Release Transaction No.: <?php echo $row->trans_dateid; ?>" class="adminAppTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-thumbs-up fa-lg greentext"></i></a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?php if ($row->trans_status == 1 || $row->trans_status == 4 || $row->trans_status == 8) : ?>
                                        <a title="Cancel Request for Transaction No.: <?php echo $row->trans_dateid; ?>" class="delTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-times fa-lg redtext"></i></a>
                                        <?php elseif ($row->trans_status == 5) : ?>
                                        <a class="closeTrans cursorpoint underlined" title="Close Request for Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-times-circle fa-lg dbluetext"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <?php if ($level == 8) : ?>                                                                        
                                    <td align="center" class="manage2div<?php echo $row->trans_id; ?>">
                                        <a class="adminRejTrans cursorpoint underlined" title="Reject Request for Transaction No.: <?php echo $row->trans_dateid; ?>" attribute="<?php echo $row->trans_id; ?>" attribute2="<?php echo $row->trans_dateid; ?>"><i class="fa fa-thumbs-down fa-lg redtext"></i></a>    
                                    </td>
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
                                    elseif ($level == 9) :
                                        echo "5";
                                    else :
                                        echo "7";
                                    endif;
                                ?>
                                " align="center">No <?php echo $trans_sec == "admin approve" ? "for release" : $trans_sec; ?> request found</td>
                            </tr>   
                            <?php endif; ?>
                        </table>