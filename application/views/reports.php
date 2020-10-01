    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Reports</div>  
                    
                        <!--div id="chart_div" style="width: 500px; height: 300px; margin: 0px auto; border-radius: 10px;"></div-->
                        <div class="clearboth">&nbsp;</div>
                        Date Coverage&nbsp;<input type="text" name="rep_date_from" id="rep_date_from" class="rep_date_from width100 txtbox" value="<?php echo $post['rep_date_from'] ? $post['rep_date_from'] : mdate("%Y-%m-%d", strtotime('last month')); ?>" />&nbsp;to&nbsp;<input type="text" name="rep_date_to" id="rep_date_to" class="rep_date_to width100 txtbox" value="<?php echo $post['rep_date_to'] ? $post['rep_date_to'] : mdate("%Y-%m-%d"); ?>" />&nbsp;<?php echo $this->Form->icat_dropdown('rep_cat', 'rep_cat', $post['rep_cat']); ?>
                    <div class="report_data">
                        <!--Date covered <?php //echo $this->Form->year_dropdown('report_year', 'report_year', mdate('%Y')); ?>
                        <?php //echo $this->Form->month_dropdown('report_month', 'report_month', mdate('%m')); ?>-->
                        <ul class="report_list">
                            <li><a href="<?php echo WEB; ?>/reports/summary" id="replink1" target="_blank">Summary Report (PDF)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/inventory" id="replink2" target="_blank">Inventory Report (PDF)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/request" id="replink3" target="_blank">Weekly Transaction Report (PDF)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/csv_dailyapprove" id="replink9" target="_blank">Daily Approved Request Report (XLS)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/overstock" id="replink12" target="_blank">Overstock Report (PDF)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/in_out" id="replink4" target="_blank">Stock In and Out Report (PDF)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/csv_reordering_point" id="replink5" target="_blank">Reordering Point Report (XLS)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/csv_consumption" id="replink6" target="_blank">Consumption Report (XLS)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/csv_consumption_price" id="replink11" target="_blank">Consumption Report with Cost (XLS)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/csv_pending" id="replink7" target="_blank">Pending Item Report (XLS)</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/pending_request" id="replink8" target="_blank">Pending Transaction Report (PDF)</a></li>
                            <!--li><a href="#" class="underlined">Requisition Report</a></li>
                            <li><a href="<?php echo WEB; ?>/reports/logs" class="underlined">Audit Trail Report</a></li-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>