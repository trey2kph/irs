                        <table class="tdatamid" width="700px"> 
                            <?php if ($inout_data) : ?>
                            <?php $total_price = 0; ?>
                            <?php foreach ($inout_data as $row) : ?>
                                <tr>
                                    <?php
                                        $stock_in = $this->Core->get_stock('IN', $row->item_id, $searchdate ? $searchdate : mdate('%Y-%m-%d', strtotime('last month')), $searchdateto ? $searchdateto : mdate('%Y-%m-%d'), 1);
                                        $stock_out = $this->Core->get_stock('OUT', $row->item_id, $searchdate ? $searchdate : mdate('%Y-%m-%d', strtotime('last month')), $searchdateto ? $searchdateto : mdate('%Y-%m-%d'), 1);
                                        $s_in = $stock_in[0]['iqty'] ? $stock_in[0]['iqty'] : 0;
                                        $s_out = $stock_out[0]['iqty'] ? $stock_out[0]['iqty'] : 0;
                                        $s_diff = $s_in - $s_out;
                                        $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', $searchdate ? mdate('%Y-%m-%d', strtotime($searchdate) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                                        $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', $searchdate ? mdate('%Y-%m-%d', strtotime($searchdate) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                                        $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
                                        $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
                                        $b_diff = $b_in - $b_out;
                                        $e_diff = $b_diff + $s_diff;
                                    ?>
                                    <td width="25px"><?php echo $row->item_id; ?></td>
                                    <td width="95px"><?php echo $row->item_name; ?></td>
                                    <td width="155px"><?php echo $row->item_desc; ?></td>
                                    <td width="60px" class="centertalign"><?php echo $b_diff; ?></td>
                                    <td width="40px" class="centertalign"><?php echo $s_in; ?></td>
                                    <td width="40px" class="centertalign"><?php echo $s_out; ?></td>
                                    <td width="60px" class="centertalign">
                                    <?php 
                                        if ($s_diff > 0) :
                                            echo '<span class="greentext"><i class="fa fa-caret-up"></i> '.$s_diff.'</span>';
                                        elseif ($s_diff < 0) : 
                                            echo '<span class="redtext"><i class="fa fa-caret-down"></i> '.abs($s_diff).'</span>'; 
                                        else :
                                            echo 0;
                                        endif;
                                    ?>
                                    </td>
                                    <td width="60px" class="centertalign"><?php echo $e_diff; ?></td>
                                    <td width="55px" class="centertalign"><?php echo $row->item_quantity; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td width="680px" class="centertalign">No in and out record found for this day</td>
                                </tr>
                            <?php endif; ?>
                        </table>