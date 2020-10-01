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
                                <?php if (($trans_sec == 'for approval' && $level == 2) || $level == 1 || ($level == 8 && ($trans_sec == 'endorse' || $trans_sec == 'pending')) || $level == 9) : ?>
                                <th width="10%"<?php if ($level == 2 || $level == 8) : ?> colspan="2"<? endif; ?>>Manage</th>
                                <?php endif; ?>
                            </tr>
                            <?php if ($trans_data) : ?>
                            <?php foreach ($trans_data as $row) : ?>
                            <tr <?php if (($level == 1 || $level == 9) && $row->trans_status == 4 && !$post['statustrans']) : echo 'class="bold blinked"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 2 && !$post['statustrans']) : echo 'class="bluetext"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 3 && !$post['statustrans']) : echo 'class="redtext"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 8 && !$post['statustrans']) : echo 'class="lgraytext"'; elseif (($level == 1 || $level == 9) && $row->trans_status == 9 && !$post['statustrans']) : echo 'class="greentext"'; endif; ?>>
                                <td><?php echo $row->trans_id; ?></td>                                
                                <?php if ($level == 2 || $level == 8) : ?>                                
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
                                            $cart_item .= '<span class="redtext">'; 
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
                                <td align="center">
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
                                <td align="center">                                    
                                    <a class="dapprTrans cursorpoint underlined" attribute="<?php echo $row->trans_id; ?>">Disapprove</a>
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