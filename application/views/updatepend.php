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