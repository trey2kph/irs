            <?php if ($itemdata) : ?>
                <script>
                    $(".decinumberonly").keydown(function(event) {

                        if (event.shiftKey == true) {
                            event.preventDefault();
                        }

                        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 110 || event.keyCode == 190) {

                        } else {
                            event.preventDefault();
                        }

                        if($(this).val().indexOf('.') == 1 && event.keyCode == 110 && event.keyCode == 190)
                            event.preventDefault(); 

                    });
                </script>

                <div class="bluetext robotobold cattext">Item to Procure:</b> <?php echo (int)$post['quantity']; ?> <?php echo $itemdata['item_name']; ?></div>
                <div class="margintopbottom20">
                
                <div class="iteminilist">
                    
                    <b>Invoice #:</b> <input type="text" name="deliinvoice" id="deliinvoice" value="" class="txtbox" /><br>
                    <b>PO Number:</b> <input type="text" name="deliponum" id="deliponum" value="" class="txtbox" /><br>
                    <b>Cost per Unit (in PHP):</b> <input type="text" name="deliprice" id="deliprice" value="<?php echo $itemdata['item_price'] ? number_format($itemdata['item_price'], 2) : number_format(0, 2); ?>" class="txtbox righttalign decinumberonly" /><br>
                    <b>Supplier:</b> <input type="text" name="delisupplier" id="delisupplier" value="<?php echo $itemdata['item_supplier']; ?>" class="txtbox" /><br>
                    <input type="hidden" name="deliqtycount" id="deliqtycount" value="<?php echo (int)$post['quantity']; ?>" />
                    <input type="hidden" name="deliitemid" id="deliitemid" value="<?php echo $itemdata['item_id']; ?>" />
                    <input type="hidden" name="deliuser" id="deliuser" value="<?php echo (int)$post['quantity']; ?>" />
                    
                </div>
                    
                <div class="divadditem"><input type="button" name="procureitem" id="procureitem" value="Submit" class="btn margintop15" /></div>
                </div>

            <?php endif; ?>