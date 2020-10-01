            <?php if ($transdata) : ?>
                <div class="bluetext robotobold cattext">Transaction ID No. <?php echo mdate("%Y", $transdata['trans_date']).'-'.$transdata['trans_date']; ?></div>
                <div class="margintopbottom20">
                
                <div class="appinilist">
                
                <?php 
                    $order_detail = "";
                    $trans_items = html_entity_decode($transdata['trans_order'], ENT_QUOTES);
                    $trans_items = unserialize($trans_items);
                    $qtynum = 0;
                    //var_dump($trans_items);
                    foreach ($trans_items as $orderrow) :
                        $check_exceed = $this->Core->check_if_qr_exceed_nonunit($orderrow['id'], $orderrow['qty']);
                        //var_dump($check_exceed);
                        if ($check_exceed != NULL) :

                            $order_detail .= '<script type="text/javascript">// slider ';

                            $order_detail .= '
                                
                                        $("#qty'.$qtynum.'").spinner({
                                          spin: function( event, ui ) {
                                            if ( ui.value > '.$check_exceed.' ) {
                                              $(this).spinner( "value", '.$check_exceed.' );
                                              return false;
                                            } else if ( ui.value < 0 ) {
                                              $(this).spinner( "value", 0 );
                                              return false;
                                            }
                                          }
                                        });';

                            $order_detail .= '
                            </script>';

                            $order_detail .= '<input type="text" name="qty'.$qtynum.'" id="qty'.$qtynum.'" attribute="'.$orderrow['rowid'].'" value="'.$check_exceed.'" class="txtbox width50 righttalign marginbottom5" readonly /> '.$orderrow['options']['unit'].' - '.$orderrow['name'].' <span class="redtext">'.($check_exceed != 0 ? '(must less than or equal '.$check_exceed.')' : '').'</span><br />';
                        endif;
                        $qtynum++;
                    endforeach;                
                    echo $order_detail;
                    echo '<input type="hidden" name="qtycount" id="qtycount" value="'.$qtynum.'" />';
                    echo '<input type="hidden" name="transid" id="transid" value="'.$transdata['trans_id'].'" />'; 
                    echo '<input type="hidden" name="transdate" id="transdate" value="'.mdate("%Y", $transdata['trans_date']).'-'.$transdata['trans_date'].'" /><br>';
                ?>
                    
                </div>
                    
                <div class="divovertrans"><button type="button" name="updateovertrans" id="updateovertrans" class="btn margintop15"><i class="fa fa-sliders"></i> Adjust</button></div>
                </div>

            <?php endif; ?>