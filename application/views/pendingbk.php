    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Pending Item List</div>
                    
                    <div class="data_console">
                        <div class="data_search">
                            <form method="POST" enctype="multipart/form-data">
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
                                <th width="5%">Pending ID</th>
                                <th width="5%">Transaction ID</th>
                                <th width="15%">Request by</th>                             
                                <th width="15%">Date</th>                   
                                <th width="50%">Details</th>  
                                <th width="10%">Solve</th>  
                            </tr>
                            <?php if ($pend_data) : ?>
                            <?php foreach ($pend_data as $row) : ?>
                            <tr>
                                <td><?php echo $row->pend_id; ?></td>                                
                                <td><?php echo $row->pend_transid; ?></td>                                                              
                                <td><?php echo $row->user_fullname; ?></td>
                                <td><?php echo mdate("%M %j, %Y<br />%g:%i%a", $row->pend_date); ?></td>
                                <?php
                                    $order_value = html_entity_decode($row->pend_order, ENT_QUOTES); 
                                    $order_value = unserialize($order_value);
                                ?>
                                <td>
                                    <?php $cart_item = ""; ?>
                                    <?php foreach ($order_value as $orderrow) : ?>                                        
                                    
                                        <?php if ($level == 8) : ?>
                                            <?php 
                                                $exceed = $this->Core->check_if_exceed($orderrow['id'], $orderrow['qty']); 
                                                $exceed_nonunit = $this->Core->check_if_exceed_nonunit($orderrow['id'], $orderrow['qty']); 
                                                if ($exceed_nonunit != 0) : $partial_stock_count = 1; endif;
                                            ?>
                                            <?php if ($exceed) : 
                                                $cart_item .= '<span class="redtext blinked">'; 
                                                $exceed_count++;
                                            endif; ?>
                                        <?php endif; ?>
                                    
                                        <?php $cart_item .= $orderrow['qty']." - ".$orderrow['unit'].' of '.$orderrow['item']; ?>
                                        <?php if ($level == 7 || $level == 8) : ?>
                                        <?php if ($exceed) $cart_item .= ' *</span><br /><i>The stock have '.$exceed.' </i> <a href="'.WEB.'/stock/edit/id/'.$orderrow['item_id'].'" class="underlined">Manage</a>'; ?>
                                        <?php endif; ?>
                                        <?php $cart_item .= '<br />'; ?>
                                    <?php endforeach; ?>  
                                    <?php echo $cart_item; ?>      
                                </td>
                                <td align="center" class="pstatusDiv<?php echo $row->pend_id; ?>"><?php echo $row->pend_status == 2 ? '<a title="Click to mark as unsolve" class="statusPend cursorpoint underlined" attribute="'.$row->pend_id.'" attribute2="'.$row->pend_status.'"><i class="fa fa-check fa-lg greentext"></i></a>' : '<a title="Click to mark as solve" class="statusPend cursorpoint underlined" attribute="'.$row->pend_id.'" attribute2="'.$row->pend_status.'"><i class="fa fa-times fa-lg redtext"></i></a>'; ?></td>
                            </tr>    
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>                                
                                <td colspan="6" align="center">No pending items found</td>
                            </tr>   
                            <?php endif; ?>
                        </table>
                        
                    </div>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>