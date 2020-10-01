    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Pending Transactions</div>
                    
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/pending/trans" method="POST" enctype="multipart/form-data" class="inlineblock2">
                                Search Transaction&nbsp;<input type="text" name="searchtrans" id="searchtrans" value="<?php echo $post['searchtrans']; ?>" placeholder="by ID<?php echo $level == 6 || $level == 7 || $level == 8 ? ", requestor name" : ""  ?> or details..." class="txtbox" />                                
                                &nbsp;<button name="btntranssearch" class="btn"><i class="fa fa-search"></i> Search</button>
                                <?php if ($post['searchtrans']) : ?>&nbsp;<button type="button" name="btntransall" id="transall" class="btn"><i class="fa fa-refresh"></i> View All</button><?php endif; ?>                                
                            </form>
                            <button class="btn cursorpoint btnpenditems"><i class="fa fa-inbox"></i> Pending Items</button>
                        </div>
                    </div>
                    <div id="dboard_list" class="dboard_list">
                        <table class="tdata"> 
                            <tr>
                                <th width="5%">Transaction ID</th>
                                <th width="15%">Request by</th>                             
                                <th width="20%">Date</th>    
                                <th width="55%">Details</th>  
                                <th width="15%">Status</th>                                   
                            </tr>
                            <?php if ($trans_data) : ?>
                            <?php foreach ($trans_data as $row) : ?>
                            <tr>
                                <td><?php echo mdate("%Y", $row->trans_date).'-'.$row->trans_date; ?></td>                                
                                <td><?php echo $row->user_fullname; ?></td>
                                <td><?php echo mdate("%M %j, %Y<br />%g:%i%a", $row->trans_date); ?></td>
                                <?php
                                    $order_value = html_entity_decode($row->trans_order, ENT_QUOTES); 
                                    $order_value = unserialize($order_value);
                                ?>
                                <td>
                                    <?php $cart_item = ""; ?>
                                    <?php $exceed_count = 0; ?>
                                    <?php $partial_stock_count = 0; ?>
                                    <?php foreach ($order_value as $orderrow) : ?>                                        
                                        <?php 
                                            $exceed = $this->Core->check_if_qr_exceed($orderrow['id'], $orderrow['qty']); 
                                            $exceed_nonunit = $this->Core->check_if_qr_exceed_nonunit($orderrow['id'], $orderrow['qty']); 
                                            if ($exceed_nonunit == NULL || $exceed_nonunit != 0) : $partial_stock_count = 1; endif;
                                        ?>
                                        <?php if ($exceed) : 
                                            $cart_item .= '<span class="redtext blinked">'; 
                                            $exceed_count++;
                                        endif; ?>
                                        <?php $cart_item .= $orderrow['qty']." - ";
                                        foreach ($orderrow['options'] as $option_name => $option_value):
                                            $option_value = ($orderrow['qty'] > 1 ? $option_value."s" : $option_value);
                                            $option_value = ($option_value == "boxs" ? "boxes" : $option_value);
                                            $option_value = ($option_value == "inchs" ? "inches" : $option_value);
                                            $cart_item .= $option_name == "unit" ? $option_value : "";
                                        endforeach;
                                        $cart_item .= ' of '.$orderrow['name']; ?>
                                        <?php if ($exceed) :
                                            $exceedtext = $exceed_nonunit == 0 || $exceed_nonunit == "OO" ? '<i>Out of stock</i>' : '<i>The stock have '.$exceed.' </i>';
                                            $cart_item .= ' *</span><br />'.$exceedtext; ?>
                                        <?php endif; ?>
                                        <?php $cart_item .= '<br />'; ?>
                                    <?php endforeach; ?>  
                                    <?php echo $cart_item; ?>  
                                </td>
                                <td><?php echo $this->Core->display_status($row->trans_status, $level); ?></td>
                            </tr>    
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>                                
                                <td colspan="5" align="center">No pending request found</td>
                            </tr>   
                            <?php endif; ?>
                        </table>
                        
                    </div>
                    <?php if (($level == 8 && $trans_sec == 'endorse') || ($level == 8 && $trans_sec == 'pending')) : ?><i class="redtext">* beyond order point or out-of-order</i><?php endif; ?>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>