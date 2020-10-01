            <?php if ($pcuredata) : ?>
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
                    <?php foreach ($pcuredata as $key => $value) : ?>
                    <tr>
                        <td><?php echo mdate("%M %j, %Y %g:%i%a", $value->pcure_date); ?></td>
                        <td><?php echo $value->pcure_price ? 'PHP '.number_format($value->pcure_price, 2) : 'n/a'; ?></td>
                        <td><?php echo $value->pcure_quantity; ?></td>                        
                        <td><?php echo $value->pcure_price ? 'PHP '.number_format(($value->pcure_price * $value->pcure_quantity), 2) : 'n/a'; ?></td>
                        <td><?php echo $value->pcure_ponumber; ?></td>                                            
                        <td><?php echo $value->pcure_invoice; ?></td>                                            
                        <td><?php echo $value->pcure_supplier; ?></td>                                            
                        <td><?php echo $value->user_fullname; ?></td>                                            
                        <td><i attribute="<?php echo $itemid; ?>" attribute2="<?php echo $value->pcure_id; ?>" attribute3="<?php echo $value->pcure_quantity; ?>" class="minusstock2 fa fa-times cursorpoint redtext"></i></td>                                            
                    </tr>
                    <?php endforeach; ?>
                </table>

            <?php endif; ?>