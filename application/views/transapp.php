            <?php if ($transdata) : ?>
                <div class="bluetext robotobold cattext">Transaction ID No. <?php echo mdate("%Y", $transdata['trans_date']).'-'.$transdata['trans_date']; ?></div>
                <div class="margintopbottom20">
                    
                    <div class="appinilist">
                    
                    <?php 
                        //var_dump($transdata);

                        $order_detail = "";
                        $trans_items = html_entity_decode($transdata['trans_order'], ENT_QUOTES);
                        $trans_items = unserialize($trans_items);
                        $qtynum = 0;
                        //var_dump($trans_items);
                        foreach ($trans_items as $orderrow) :
                            
                            $order_detail .= '<script type="text/javascript">// slider ';

                            $order_detail .= '
                                
                                        $("#qty'.$qtynum.'").spinner({
                                          spin: function( event, ui ) {
                                            if ( ui.value > '.$orderrow['qty'].' ) {
                                              $(this).spinner( "value", '.$orderrow['qty'].' );
                                              return false;
                                            } else if ( ui.value < 0 ) {
                                              $(this).spinner( "value", 0 );
                                              return false;
                                            }
                                          }
                                        });';

                            $order_detail .= '
                            </script>';

                            $order_detail .= '<input type="text" name="qty'.$qtynum.'" id="qty'.$qtynum.'" attribute="'.$orderrow['rowid'].'" value="'.$orderrow['qty'].'" class="txtbox width50 righttalign marginbottom5" readonly /> '.$orderrow['options']['unit'].' - '.$orderrow['name'].' <br />';
                            $qtynum++;
                        endforeach;     

                        echo $order_detail;
                        echo '<input type="hidden" name="qtycount" id="qtycount" value="'.$qtynum.'" />';
                    ?>
                        
                    </div>
                    
                    <br>Remarks for Requestor<br>
                    <textarea name="transremarks" id="transremarks" rows="5" cols="55" class="txtarea">Please claim your approved request on {day} between {time} only. Kindly present this form as proof that it was approved by CAD. Thank you.</textarea>    
                    <input type="hidden" name="transid" id="transid" value="<?php echo $transdata['trans_id']; ?>" /><br> 
                    <input type="hidden" name="transdate" id="transdate" value="<?php echo mdate("%Y", $transdata['trans_date']).'-'.$transdata['trans_date']; ?>" /><br>
                    <div class="divremark"><button type="button" name="updateRemarks" id="updateRemarks" class="btn margintop15"><i class="fa fa-check"></i> Approved for Release</button></div>
                </div>

            <?php endif; ?>