    <!-- BODY -->
    

    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <?php if ($level > 3) : ?>
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                <?php else : ?> 
                <?php if ($level == 1 || $level == 3) : ?>
                <a href="<?php echo WEB?>" title="View Transactions">
                    <div class="floatmainbutton cursorpoint"><i class="fa fa-bars fa-2x whitetext mediumtext"></i></div>
                </a>
                <?php endif; ?>
                <div class="lowerrightbig">
                <?php endif; ?>
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Requisition</div>
                    <div class="item_list">
                        <div class="data_console">
                            <div class="data_search" style="width: 100% !important;">
                                <form action="<?php echo WEB; ?>/requisition/" method="POST" enctype="multipart/form-data">
                                    Search Item<br /><input id="searchitem" type="text" name="searchitem" value="<?php echo $post['searchitem']; ?>" placeholder="by name..." class="txtbox" />&nbsp;<?php echo $this->Form->icat_dropdown('searchcat', 'searchcat', $post['searchcat']); ?>&nbsp;<input type="submit" name="btnprocsearch" value="Search" class="btn" />
                                </form>
                            </div>
                        </div>
                        <?php if ($item_data) : ?>
                            <?php foreach ($item_data as $id => $row) : ?>
                                <?php $divmod = $id % 2; ?>
                                <div class="item <?php echo $divmod == 1 ? "dwhitebg" : "dwhitebg2" ?>">
                                    <b><?php echo $row->item_name; ?></b><br />
                                    <?php echo $row->item_desc; ?><br /><br />
                                    Quantity: <!--input type="text" id="quantity<?php echo $row->item_id; ?>" name="quantity<?php echo $row->item_id; ?>" value="1" class="txtbox width40 righttalign" /--><?php echo $this->Form->onetoten_dropdown('quantity'.$row->item_id, 'quantity'.$row->item_id); ?>&nbsp;<?php echo $row->unit_name; ?><?php if ($row->item_max) : ?> <i>(<?php echo $row->item_max; ?> max request)</i><?php endif; ?>
                                    <button name="btnaddcart" class="addcart smallbtn floatright" attribute="<?php echo $row->item_id; ?>" attribute2="<?php echo $row->item_name; ?>" attribute3="<?php echo $row->unit_name; ?>" attribute4="<?php echo $row->item_price; ?>" attribute5="<?php echo $row->item_max; ?>">Add to Order <i class="fa fa-caret-right"></i></button>
                                </div>    
                            <?php endforeach; ?>                            
                            <div class="data_console">
                                <div class="pagination" style="width: 100% !important; text-align: center;"><?php echo $this->pagination->create_links(); ?></div>
                            </div>
                        <?php else : ?>
                            <div class="item">
                                No item found
                            </div>    
                        <?php endif; ?>
                        <?php if ($post['searchitem'] != NULL || $post['searchcat'] != 0) : ?>
                            <br /><input type="button" name="btnviewall" id="reqall" value="View All Items" class="smallbtn" />
                        <?php endif; ?>
                    </div>
                    <div id="cart" class="cart">
                        <div class="cartheader"><b><?php echo $session_data['session_fullname']."'".(substr($session_data['session_fullname'], -1) == 's' ? '' : 's')." Requisition Slip"; ?></b></div>
                        <div id="cartitem" class="cartitem"><?php echo $cart_data ? $cart_data : "<center><br><b>Requisition Slip is empty</b><br><br><br>Click <span class=\"smallbtn\">Add to Order <i class=\"fa fa-caret-right\"></i></span> on the left to place an order</center>"; ?></div>
                    </div>

                </div>
            </div>
        </div>
    </div>