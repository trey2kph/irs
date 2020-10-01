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