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
                            <form id="frmpendate" action="<?php echo WEB; ?>/pending" method="POST" class="inlineblock2">
                                From&nbsp;<input type="text" name="pend_date_from" id="pend_date_from" class="pend_date_from width100 txtbox" value="<?php echo $post['pend_date_from'] ? $post['pend_date_from'] : mdate("%Y-%m-%d"); ?>" />&nbsp;To&nbsp;<input type="text" name="pend_date_to" id="pend_date_to" class="pend_date_to width100 txtbox" value="<?php echo $post['pend_date_to'] ? $post['pend_date_to'] : mdate("%Y-%m-%d"); ?>" /><?php if ($pend_data) : ?>&nbsp;<button type="button" name="btncsv" class="btn pendcsv"><i class="fa fa-file-excel-o"></i> XLS Report</button><?php endif; ?>
                            </form>
                            <button class="btn btnpendtrans cursorpoint"><i class="fa fa-list"></i> Pending Transactions</button>
                        </div>
                    </div>
                    <div id="pend_list" class="pend_list">
                        <table class="tdata"> 
                            <tr>
                                <th width="15%">Quantity</th>
                                <th width="15%">Unit</th>                             
                                <?php if ($level != 7) : ?>
                                <th width="45%">Item</th>                   
                                <th width="25%">Manage Stock</th>  
                                <?php else : ?>
                                <th width="70%">Item</th>                   
                                <?php endif; ?>
                            </tr>
                            <?php //var_dump($pend_data); ?>
                            <?php if ($pend_data) : ?>
                            <?php foreach ($pend_data as $row) : ?>
                            <?php $pend_dataitem = $this->Core->get_pend_by_item(0, 0, 0, 0, $row->pi_itemid);  ?>
                            <tr>
                                <td class="righttalign"><?php echo $row->quantity; ?></td>  
                                <td><?php echo $pend_dataitem['pi_unit']; ?></td>
                                <td><?php echo $pend_dataitem['item_name']; ?></td> 
                                <?php if ($level != 7) : ?>
                                <td align="center"><a href="<?php echo WEB; ?>/stock/edit/id/<?php echo $row->pi_itemid; ?>"><i class="fa fa-edit fa-lg"></i></a></td>                       
                                <?php endif; ?>
                            </tr>    
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>                                
                                <td colspan="4" align="center">No pending items found</td>
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