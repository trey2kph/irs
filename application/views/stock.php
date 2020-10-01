    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12"><?php echo $page_title; ?></div>                    
                    <?php if ($stock_mode == 2) : ?>
                    <div class="stock_list">
                        <form name="frmadditem" method="POST" enctype="multipart/form-data"> 
                        <table class="tdataform" width="100%">
                            <tr>                                
                                <th colspan="2" width="30%">Quantity</th>
                                <th width="15%">Critical</th>
                                <th width="15%">Order Quantity</th>
                                <th width="20%">Max Request</th>
                                <th width="20%">Unit</th>
                            </tr>
                            <tr>                                
                                <td colspan="2" align="center">                                    
                                    <input type="text" name="item_quantity" id="stockcount" value="<?php echo $post['item_quantity'] ? $post['item_quantity'] : 0; ?>" class="txtbox width50 righttalign numberonly" />&nbsp;<?php echo $this->Form->onetoten_dropdown('stockopt'.$row->item_id, 'stockopt'.$row->item_id); ?>&nbsp;<input type="button" name="btnplusstock" class="plusnstock smallbtn" value="+" attribute="<?php echo $row->item_id; ?>" /><!--&nbsp;<input type="button" name="btnminusstock" class="minusnstock smallbtn" value="-" attribute="<?php //echo $row->item_id; ?>" /-->  
                                </td>
                                <td align="center">
                                    <input type="text" name="item_critical" value="<?php echo $post['item_critical'] ? $post['item_critical'] : 0; ?>" class="txtbox width50 righttalign numberonly" />
                                </td>
                                <td align="center">
                                    <input type="text" name="item_order" value="<?php echo $post['item_order'] ? $post['item_order'] : 0; ?>" class="txtbox width50 righttalign numberonly" />
                                </td>
                                <td align="center">
                                    <input type="text" name="item_max" value="<?php echo $post['item_max'] ? $post['item_max'] : 0; ?>" class="txtbox width50 righttalign numberonly" />
                                </td>
                                <td align="center">
                                    <?php echo $this->Form->iunit_dropdown('item_unitid', 'item_unitid', $post['item_unitid']); ?>
                                </td>    
                            </tr>                            
                            <tr>
                                <td colspan="6" class="righttalign"><i class="redtext">* for unlimited number of request, please set 'Max Request' to 0 (zero)</i></td>
                            </tr>
                            <tr>
                                <th colspan="2">Supplier</th>
                                <th colspan="2">Cost per Unit</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                            <tr>                                
                                <td colspan="2" align="center"><input type="text" name="item_supplier" value="<?php echo $post['item_supplier']; ?>" class="txtbox width200" /><?php echo '<span class="redtext">'.form_error('item_supplier').'</span>'; ?></td>
                                <td colspan="2" align="center">PHP <input type="text" name="item_price" value="<?php echo $post['item_price'] ? number_format($post['item_price'], 2) : number_format(0, 2); ?>" class="txtbox width100 righttalign decinumberonly" /><?php echo '<span class="redtext">'.form_error('item_price').'</span>'; ?></td>
                                <td align="center">
                                    <?php echo $this->Form->icat_dropdown('item_cat', 'item_cat', $post['item_cat']); ?>
                                    <div class="catname"<?php if($post == NULL || $post['item_cat'] != 1000) : ?> style="display: none;"<?php endif; ?>>
                                    <input type="text" name="item_catname" value="<?php echo $post['item_catname']; ?>" />
                                    </div>
                                    <?php echo '<span class="redtext">'.form_error('item_catname').'</span>'; ?>
                                </td>                                
                                <td align="center"><?php echo $this->Form->istatus_dropdown('item_status', $post['item_status']); ?></td>
                            </tr>
                            <tr>
                                <th colspan="6">Item Name</th>
                            </tr>                            
                            <tr>
                                <td colspan="6"><input type="text" name="item_name" value="<?php echo $post['item_name']; ?>" class="txtbox width200" /><?php echo '<span class="redtext">'.form_error('item_name').'</span>'; ?></td>
                            </tr>
                            <tr>
                                <th colspan="6">Item Description</th>
                            </tr>                            
                            <tr>
                                <td colspan="6"><textarea name="item_desc" rows="5" cols="80" class="txtarea"><?php echo $post['item_desc']; ?></textarea><?php echo '<span class="redtext">'.form_error('item_desc').'</span>'; ?></td>
                            </tr>
                            <tr>
                                <td align="right" colspan="6">
                                    <input type="submit" name="btnadditem" value="Add" class="btn" />&nbsp;<input type="button" name="btncancel" value="Cancel" class="redbtn" onClick="parent.location='<?php echo WEB; ?>/stock'" />
                                </td>
                            </tr>
                            
                        </table>
                        </form>
                    </div>
                    <?php elseif ($stock_mode == 1) : ?>
                    <div class="stock_list">                        
                        <form name="frmedititem" method="POST" enctype="multipart/form-data"> 
                        <table class="tdataform" width="100%">
                            <tr>
                                <th width="10%">ID</th>
                                <th colspan="2" width="20%">Count</th>
                                <th colspan="2" width="15%">Quantity</th>
                                <th width="15%">Critical</th>
                                <th width="15%">Order Quantity</th>
                                <th width="15%">Max Request</th>
                                <th width="10%">Unit</th>
                            </tr>
                            <tr>                                                             
                                <td align="center"><?php echo $stock_data['item_id']; ?></td>
                                <td colspan="2" align="center">
                                    <input type="text" id="stockopt<?php echo $stock_data['item_id']; ?>" name="stockopt<?php echo $stock_data['item_id']; ?>" value="1" class="txtbox width40 righttalign numberonly" >&nbsp;<input type="button" name="btnplusstock" class="plusstock smallbtn" value="+" attribute="<?php echo $stock_data['item_id']; ?>" /><!--input type="button" name="btnplusstock" class="plusstock2 smallbtn" value="+" attribute="<?php //echo $stock_data['item_id']; ?>" /--><!--&nbsp;<input type="button" name="btnminusstock" class="minusstock smallbtn" value="-" attribute="<?php //echo $stock_data['item_id']; ?>" /-->  
                                    </td>
                                <td colspan="2" align="center" id="stock<?php echo $stock_data['item_id']; ?>"><?php echo $stock_data['item_quantity']; ?></td>
                                <td align="center"><input type="text" name="item_critical" value="<?php echo $post['item_critical'] ? $post['item_critical'] : $stock_data['item_critical']; ?>" class="txtbox width50 righttalign numberonly" /><?php echo '<span class="redtext">'.form_error('item_critical').'</span>'; ?></td>
                                <td align="center"><input type="text" name="item_order" value="<?php echo $post['item_order'] ? $post['item_order'] : $stock_data['item_order']; ?>" class="txtbox width50 righttalign numberonly" /><?php echo '<span class="redtext">'.form_error('item_order').'</span>'; ?></td>
                                <td align="center"><input type="text" name="item_max" value="<?php echo $post['item_max'] ? $post['item_max'] : $stock_data['item_max']; ?>" class="txtbox width50 righttalign numberonly" /><?php echo '<span class="redtext">'.form_error('item_max').'</span>'; ?></td>
                                <td align="center"><?php echo $stock_data['unit_name']; ?></td>    
                            </tr>
                            <tr>
                                <td colspan="9" class="righttalign"><i class="redtext">* for unlimited number of request, please set 'Max Request' to 0 (zero)</i></th>
                            </tr>
                            <tr>
                                <th colspan="2">Supplier</th>
                                <th colspan="3">Cost per Unit</th>
                                <th colspan="2">Category</th>
                                <th colspan="2">Status</th>
                            </tr>
                            <tr>                                
                                <td colspan="2" align="center"><input id="item_supplier" type="text" name="item_supplier" value="<?php echo $post['item_supplier'] ? $post['item_supplier'] : $stock_data['item_supplier']; ?>" class="txtbox width200" /><?php echo '<span class="redtext">'.form_error('item_supplier').'</span>'; ?></td>                                
                                <td colspan="3" align="center">PHP <input id="item_price" type="text" name="item_price" value="<?php echo $post['item_price'] ? number_format($post['item_price'], 2) : number_format($stock_data['item_price'], 2); ?>" class="txtbox width100 righttalign decinumberonly" /><?php echo '<span class="redtext">'.form_error('item_price').'</span>'; ?></td>                                
                                <td colspan="2" align="center">
                                    <?php echo $this->Form->icat_dropdown('item_cat', 'item_cat', $post['item_cat'] ? $post['item_cat'] : $stock_data['item_cat']); ?>
                                    <div class="catname"<?php if($post == NULL || $post['item_cat'] != 1000) : ?> style="display: none;"<?php endif; ?>>
                                    <input type="text" name="item_catname" value="<?php echo $post['item_catname']; ?>" />
                                    </div>
                                    <?php echo '<span class="redtext">'.form_error('item_catname').'</span>'; ?>
                                </td>                                
                                <td colspan="2" align="center"><?php echo $this->Form->istatus_dropdown('item_status', $post['item_status'] ? $post['item_status'] : $stock_data['item_status']); ?></td>
                            </tr>
                            <tr>
                                <th colspan="9">Item Name</th>
                            </tr>                            
                            <tr>
                                <td colspan="9"><input type="text" name="item_name" value="<?php echo $post['item_name'] ? $post['item_name'] : $stock_data['item_name']; ?>" class="txtbox width200" /><?php echo '<span class="redtext">'.form_error('item_name').'</span>'; ?></td>
                            </tr>
                            <tr>
                                <th colspan="9">Item Description</th>
                            </tr>                            
                            <tr>
                                <td colspan="9"><textarea name="item_desc" rows="5" cols="80" class="txtarea"><?php echo $post['item_desc'] ? $post['item_desc'] : $stock_data['item_desc']; ?></textarea><?php echo '<span class="redtext">'.form_error('item_desc').'</span>'; ?></td>
                            </tr>
                            <?php if ($procure_data) : ?>
                            <tr>
                                <th colspan="9">Procurement History <i class="fa fa-shopping-bag"></i></th>
                            </tr>                            
                            <tr>
                                <td colspan="9">
                                    <div id="pcuredata">
                                    <table class="tdata" width="100%">
                                        <tr>
                                            <th width="15%">Date/Time</th>
                                            <th width="10%">Cost</th>
                                            <th width="10%">Quantity</th>  
                                            <th width="10%">Total Cost</th>                                          
                                            <th width="12%">PO #</th>                                            
                                            <th width="13%">Invoice #</th>                                            
                                            <th width="15%">Supplier</th>                                            
                                            <th width="10%">Procure by</th>                                            
                                            <th width="5%">Remove</th>                                                  
                                        </tr>
                                        <?php foreach ($procure_data as $key => $value) : ?>
                                        <tr>
                                            <td><?php echo mdate("%M %j, %Y %g:%i%a", $value->pcure_date); ?></td>
                                            <td><?php echo $value->pcure_price ? 'PHP '.number_format($value->pcure_price, 2) : 'n/a'; ?></td>
                                            <td><?php echo $value->pcure_quantity; ?></td>                        
                                            <td><?php echo $value->pcure_price ? 'PHP '.number_format(($value->pcure_price * $value->pcure_quantity), 2) : 'n/a'; ?></td>
                                            <td><?php echo $value->pcure_ponumber; ?></td>                                            
                                            <td><?php echo $value->pcure_invoice; ?></td>                                            
                                            <td><?php echo $value->pcure_supplier; ?></td>                                            
                                            <td><?php echo $value->user_fullname; ?></td>                                            
                                            <td class="centertalign">
                                                <i attribute="<?php echo $stock_data['item_id']; ?>" attribute2="<?php echo $value->pcure_id; ?>" attribute3="<?php echo $value->pcure_quantity; ?>" class="minusstock2 fa fa-times cursorpoint redtext"></i>
                                            </td>                                            
                                        </tr>
                                        <?php endforeach; ?>
                                    </table>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td align="right" colspan="9">
                                    <input type="hidden" name="item_id" value="<?php echo $stock_data['item_id']; ?>" />
                                    <input type="hidden" name="referrer" value="<?php echo $referrer; ?>" />
                                    <input type="submit" name="btnadditem" value="Update" class="btn" />&nbsp;<input type="button" name="btncancel" value="Cancel" class="redbtn" onClick="parent.location='<?php echo $referral ? $referrer : WEB.'/stock'; ?>'" />
                                </td>
                            </tr>
                        </table>
                        </form>
                    </div>
                    <?php else : ?> 
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/stock/" method="POST" enctype="multipart/form-data">
                                Search Item&nbsp;<input type="text" name="searchitem" id="searchitem" value="<?php echo $post['searchitem']; ?>" placeholder="by name..." class="txtbox" />&nbsp;<button type="submit" name="btnitemsearch" class="btn"><i class="fa fa-search"></i> Search</button><?php if ($post['searchitem']) : ?>&nbsp;<button type="button" name="btnitemall" id="itemall_stock" class="btn"><i class="fa fa-refresh"></i> View All</button><?php endif; ?>&nbsp;<button type="button" name="btnitemadd" onClick="parent.location='<?php echo WEB; ?>/stock/add'" class="btn"><i class="fa fa-plus"></i> Add New Stock</button>&nbsp;<button type="button" name="btncat" onClick="parent.location='<?php echo WEB; ?>/stock/cat'" class="btn"><i class="fa fa-cubes"></i> Manage Stock Category</button>
                            </form>
                        </div>
                    </div>
                    <div id="stock_list" class="stock_list">
                        <table class="tdata" width="100%">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Critical</th>
                                <th width="10%">Reserved for Release</th>
                                <th width="10%">Maximum Request</th>
                                <th width="10%">Unit</th>
                                <th width="25%">Item Name</th>
                                <th width="5%">Active</th>
                                <th width="20%" colspan="2">Manage</th>                            
                            </tr>
                            <?php if ($stock_data) : ?>                                
                                <?php foreach ($stock_data as $row) : ?>
                                <tr>
                                    <td id="div<?php echo $row->item_id; ?>" class="<?php if ($row->item_quantity == 0) : ?>redbg<?php elseif ($row->item_quantity <= $row->item_critical) : ?>lredbg<?php endif; ?>"><?php echo $row->item_id; ?></td>
                                    <td id="stock<?php echo $row->item_id; ?>"><?php echo $row->item_quantity; ?></td>
                                    <td><?php echo $row->item_critical; ?></td>
                                    <td><?php echo $row->item_quantity - $row->item_qrelease; ?></td>
                                    <td><?php echo $row->item_max ? $row->item_max : '<i>unlimited</i>'; ?></td>
                                    <td><?php echo $row->unit_name; ?></td>
                                    <td id="tr<?php echo $row->item_id; ?>" class="<?php if ($row->item_quantity == 0) : ?>blinked redtext<?php endif; ?>"><?php echo $row->item_name; ?><br /><i><?php echo $row->item_desc; ?></i></td>
                                    <td align="center" class="istatusDiv<?php echo $row->item_id; ?>"><?php echo $row->item_status == 2 ? '<a title="Click to deactivate Item ID #'.$row->item_id.'" class="statusItem cursorpoint underlined" attribute="'.$row->item_id.'" attribute2="'.$row->item_status.'"><i class="fa fa-check fa-lg greentext"></i></a>' : '<a title="Click to activate Item ID #'.$row->item_id.'" class="statusItem cursorpoint underlined" attribute="'.$row->item_id.'" attribute2="'.$row->item_status.'"><i class="fa fa-times fa-lg redtext"></i></a>'; ?></td>
                                    <td width="15%" align="center">
                                        <input type="text" id="stockopt<?php echo $row->item_id; ?>" name="stockopt<?php echo $row->item_id; ?>" value="1" class="txtbox width40 numberonly righttalign" >
                                        <?php //echo $this->Form->onetoten_dropdown('stockopt'.$row->item_id, 'stockopt'.$row->item_id); ?>&nbsp;<input type="button" name="btnplusstock" class="plusstock smallbtn" value="+" attribute="<?php echo $row->item_id; ?>" /><!--input type="button" name="btnplusstock" class="plusstock2 smallbtn" value="+" attribute="<?php //echo $row->item_id; ?>" /--><!--&nbsp;<input type="button" name="btnminusstock" class="minusstock smallbtn" value="-" attribute="<?php //echo $row->item_id; ?>" /-->  
                                    </td>       
                                    <td width="5%" align="center"><a title="Click to edit Item ID #<?php echo $row->item_id; ?>" href='<?php echo WEB; ?>/stock/edit/id/<?php echo $row->item_id; ?>' class='underlined'><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="10" class="centertalign">No stock record found</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <i class="redtext">* out-of-stock in red and critical units on light red flag</i>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>