                        <table class="tdatamid" width="700px">                                        
                            <?php $total_price = 0; ?>
                            <?php foreach ($inventory_data as $row) : ?>
                                <tr>
                                    <td width="25px"><?php echo $row->item_id; ?></td>
                                    <td width="95px"><?php echo $row->cat_name; ?></td>
                                    <td width="175px"><?php echo $row->item_name; ?></td>
                                    <td width="200px"><?php echo $row->item_desc; ?></td>
                                    <td width="55px"><?php echo $row->item_quantity; ?></td>
                                    <td width="35px"><?php echo $row->unit_name; ?></td>
                                    <!--td width="80px" class="righttalign"><?php //echo number_format($row->item_price, 2); ?></td>
                                    <td width="80px" class="righttalign"><?php //echo number_format($row->item_price * $row->item_quantity, 2); ?></td-->
                                </tr>
                                <?php $total_price += floatval($row->item_price * $row->item_quantity); ?>
                            <?php endforeach; ?>                            
                            <script type="application/javascript">
                                $("#totalquan").html('<?php echo number_format($total_price, 2); ?>');
                            </script>
                        </table>