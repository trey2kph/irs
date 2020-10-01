<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller { 
    
    function __construct()
    {
        parent::__construct();
        #DEFINE BY SETTING
        $setting = $this->Core->get_set(0);
        $expirydate = strtotime(date("Y-m-d 00:00:00"));
        
        define("ANNOUNCEMENT", $setting['set_annexpire'] > $expirydate ? trim($setting['set_announce']) : "");
        define("NUM_ROWS", $setting['set_numrows']); // the number of records on each page
        define("MAILFOOT", $setting['set_mailfoot']);    
    }      

    function profile_id() {
        $pro_id = $this->session->userdata('session_uid');
        return $pro_id;
    }
    
    function profile_level() {
        $pro_level = $this->session->userdata('session_level');
        return $pro_level;
    }
    
    function report_from() {
        $get = $this->input->get();
        return $get['from'] ? $get['from'] : 0;
    }
    
    function report_to() {
        $get = $this->input->get();
        return $get['to'] ? $get['to'] : 0;
    }
    
    function item_category() {
        $get = $this->input->get();
        return $get['cat'] ? $get['cat'] : 0;
    }
    
    function item_category_name() {
        $get = $this->input->get();
        if ($get['cat'] != 0) :
            $cat_name = $this->Core->get_category(0, 0, 0, 0, $get['cat'], 0, 0);
            return $cat_name['cat_name'] ? $cat_name['cat_name'] : NULL;
        endif;
    }

	public function index()
	{	
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        	
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
        
                $data['status_data'] = $this->Core->get_transcount_status('2014-01-01', mdate('%Y-%m-%d'));			        
        
                $this->load->view('header', $data);	
                $this->load->view('reports');
                $this->load->view('footer');	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function summary()
	{	
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :           
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('summary_'.$this->report_from().'_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('summary_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'portrait');
                
                $status_data = $this->Core->get_transcount_status(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')));			        
                $status_total = $this->Core->get_transtotal(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')));
                $summary_data = $this->Core->get_transcount_dept(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')));			        
                $summary_data2 = $this->Core->get_item(1, 0, 0, 0, 0, 0, 2, 0);			        
                
                $sumdata = '';
                if ($status_data) :
                $sumdata .= '<b class="report_title">Status Updates</b>';
                $sumdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="400px">Status</th>
                                    <th width="90px">Total Request</th>
                                    <th width="90px">Percentage</th>
                                </tr>';
                foreach ($status_data as $row) :
                    $sumdata .= '<tr>
                                    <td width="400px">'.$this->Core->display_status($row->trans_status, 9).'</td>
                                    <td width="90px" align="right">'.$row->transcount.'</td>
                                    <td width="90px" align="right">'. round(($row->transcount / $status_total) * 100, 2) .'%</td>
                                </tr>';
                endforeach;
                $sumdata .= '</table>';
                endif;
                
                if ($summary_data) :
                $sumdata .= '<br /><br /><b class="report_title">Department with Most Closed Request</b><br /><br />';
                $sumdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="505px">Department</th>
                                    <th width="90px">Total Request</th>
                                    <!--th width="90px">Price</th-->
                                </tr>';
                foreach ($summary_data as $row) :
                    $sumdata .= '<tr>
                                    <td width="505px">'.$row->dept_name.'</td>
                                    <td width="90px" align="right">'.$row->transcount.'</td>
                                    <!--td width="90px" class="righttalign">'.$row->transprice.'</td-->
                                </tr>';
                endforeach;
                $sumdata .= '</table>';
                endif;
                
                $torder = $this->Core->get_transcount_by_item(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')));                              
        
                if ($torder) :
                $sumdata .= '<br /><br /><b class="report_title">Most Requested Item</b><br /><br />';
                $sumdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="505px">Item</th>
                                    <th width="90px">Total Request</th>
                                </tr>';
                $item_array = array();
                foreach ($summary_data2 as $row) :                    
                    $trans_order = $this->Core->get_transcount_by_item(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')));                              
                
                    $total_request = 0;
                    
                    foreach ($trans_order as $trow) :
                        $order_array = html_entity_decode($trow->trans_order, ENT_QUOTES);
                        $order_array = unserialize($order_array);
                        
                        foreach ($order_array as $orderrow) :
                            if ($orderrow['id'] == $row->item_id) $total_request++;
                        endforeach;
                    endforeach;
                
                    $item_array[$row->item_id] = $total_request;        
                    
                endforeach;
                
                arsort($item_array);
                        
                foreach ($item_array as $key => $item) : 
                    if ($item != 0) :
                        $iname = $this->Core->get_item(0, 0, 0, 0, 0, $key, 0, 0);
                        $sumdata .= '<tr>
                                        <td width="505px">'.$iname['item_name'].'</td>
                                        <td width="90px" align="right">'.$item.'</td>
                                    </tr>';
                    endif;
                endforeach;
                
                $sumdata .= '</table>';
                endif;
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime($this->report_from())).' to '.mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime("last month")).' to '.mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'Summary Report as'.$datelabel,
                    'message' => $sumdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) :            
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("SUMMARY_REPORT_GENERATE", 0, $this->profile_id());                    
                    if ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/summary_'.$this->report_from().'_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/summary_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                endif;	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function inventory()
	{	
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     	      
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                $this->html2pdf->filename('inventory_'.mdate('%Y%m').'.pdf');
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'landscape');
                
                $inventory_data = $this->Core->get_item(1, 0, 0, 0, 0, 0, 0, $searchstr);			        
                
                $invdata = '';
                $invdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="30px">ID</th>
                                    <th width="120px">Category</th>
                                    <th width="210px">Item Name</th>
                                    <th width="310px">Description</th>              
                                    <th width="90px">Quantity</th>
                                    <th width="80px">Unit</th>
                                    <!--th width="90px">Price per Unit</th>
                                    <th width="90px">Total Price</th-->
                                </tr>';
                $total_price = 0;
                foreach ($inventory_data as $row) :
                    $invdata .= '<tr>
                                    <td width="30px">'.$row->item_id.'</td>
                                    <td width="120px">'.$row->cat_name.'</td>
                                    <td width="210px">'.$row->item_name.'</td>
                                    <td width="310px">'.$row->item_desc.'</td>
                                    <td width="90px">'.$row->item_quantity.'</td>
                                    <td width="80px">'.$row->unit_name.'</td>
                                    <!--td width="90px" class="righttalign">'.number_format($row->item_price, 2).'</td>
                                    <td width="90px" class="righttalign">'.number_format($row->item_price * $row->item_quantity, 2).'</td-->
                                </tr>';
                    $total_price += floatval($row->item_price * $row->item_quantity);
                endforeach;
                $invdata .= '<!--tr>
                                <td colspan="4" class="righttalign">Total</td>
                                <td colspan="2" class="righttalign bold">'.number_format($total_price, 2).'</td>
                            </tr-->
                        </table>';
                
                $data = array(
                    'title' => 'Inventory Report as of '.mdate("%M %j, %Y - %g:%i%a", time()),
                    'message' => $invdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) :            
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("INVENTORY_REPORT_GENERATE", 0, $this->profile_id());
                    echo '<script>window.location.href="'.WEB.'/assets/pdfs/inventory_'.mdate('%Y%m').'.pdf";</script>';
                endif;	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function in_out()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    $this->html2pdf->filename('inout_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.pdf');
                elseif ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('inout_'.$this->report_from().'_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('inout_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'landscape');
                
                $inout_data = $this->Core->get_item_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')), 0, 1, $this->item_category());
                
                $iodata = '';
                $iodata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="30px">ID</th>
                                    <th width="120px">'.($this->item_category_name() ? $this->item_category_name() : 'Item Name').'</th>
                                    <th width="220px">Description</th>              
                                    <th width="90px">Beginning Balance</th>
                                    <th width="90px">In</th>
                                    <th width="90px">Out</th>
                                    <th width="90px">Difference</th>
                                    <th width="90px">Balance</th>
                                </tr>';
                if ($inout_data) :
                foreach ($inout_data as $row) :
                    $iodata .= '<tr>';
                    $stock_in = $this->Core->get_stock('IN', $row->item_id, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')), 1);
                    $stock_out = $this->Core->get_stock('OUT', $row->item_id, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), ($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')), 1);
                    $s_in = $stock_in[0]['iqty'] ? $stock_in[0]['iqty'] : 0;
                    $s_out = $stock_out[0]['iqty'] ? $stock_out[0]['iqty'] : 0;
                    $s_diff = $s_in - $s_out;
                    if ($s_diff > 0) :
                        $diff_val = '+'.$s_diff;
                    elseif ($s_diff < 0) : 
                        $diff_val = $s_diff; 
                    else :
                        $diff_val = 0;
                    endif;
                    $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', $this->report_from() ? mdate('%Y-%m-%d', strtotime($this->report_from()) - 86400) : mdate('%Y-%m-%d', time() - (86400 * 31)), 1);
                    $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', $this->report_from() ? mdate('%Y-%m-%d', strtotime($this->report_from()) - 86400) : mdate('%Y-%m-%d', time() - (86400 * 31)), 1);
                    $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
                    $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
                    $b_diff = $b_in - $b_out;
        
                    $iodata .= '<td width="30px">'.$row->item_id.'</td>
                                <td width="120px">'.$row->item_name.'</td>
                                <td width="220px">'.$row->item_desc.'</td>
                                <td width="90px" class="centertalign">'.$b_diff.'</td>
                                <td width="90px" class="centertalign">'.$s_in.'</td>
                                <td width="90px" class="centertalign">'.$s_out.'</td>
                                <td width="90px" class="centertalign">'.$diff_val.'</td>
                                <td width="90px" class="centertalign">'.$row->item_quantity.'</td>
                            </tr>';
                endforeach;
                else :
                    $iodata .= '<tr>
                                     <td colspan="8" class="centertalign">No in and out record found for this day</td>
                                </tr>';
                endif;
                                
                $iodata .= '
                        </table>';
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime($this->report_from())).' to '.mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime("last month")).' to '.mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'In and Out Report'.$datelabel,
                    'message' => $iodata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) {
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("IN_OUT_REPORT_GENERATE", 0, $this->profile_id());
                    if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/inout_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.pdf";</script>';
                    elseif ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/inout_'.$this->report_from().'_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/inout_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                }	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function reordering_point()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    $this->html2pdf->filename('rop_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.pdf');
                elseif ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('rop_'.$this->report_from().'_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('rop_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'portrait');
                
                $rop_data = $this->Core->get_item(1, 0, 0, 0, $this->item_category(), 0, 0, 0);
                
                $ropdata = '';
                $ropdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="120px">'.($this->item_category_name() ? $this->item_category_name() : 'Item Name').'</th>
                                    <th width="50px">Quantity Last '.mdate('%M %j, %Y', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))).'</th>
                                    <th width="50px">Stock on Hand</th>
                                    <th width="50px">Remarks</th>
                                    <th width="50px">Reorder Quantity</th>
                                    <th width="50px">Standard Order Quantity</th>
                                </tr>';
                if ($rop_data) :
                foreach ($rop_data as $row) :
                    $ropdata .= '<tr>';
                    $stock_in = $this->Core->get_stock('IN', $row->item_id, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 1);
                    $stock_out = $this->Core->get_stock('OUT', $row->item_id, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 1);
                    $s_in = $stock_in[0]['iqty'] ? $stock_in[0]['iqty'] : 0;
                    $s_out = $stock_out[0]['iqty'] ? $stock_out[0]['iqty'] : 0;
                    $s_diff = $s_in - $s_out;
                    if ($s_diff > 0) :
                        $diff_val = '+'.$s_diff;
                    elseif ($s_diff < 0) : 
                        $diff_val = $s_diff; 
                    else :
                        $diff_val = 0;
                    endif;
                    $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', $this->report_from() ? mdate('%Y-%m-%d', strtotime($this->report_from()) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                    $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', $this->report_from() ? mdate('%Y-%m-%d', strtotime($this->report_from()) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                    $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
                    $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
                    $b_diff = $b_in - $b_out;
        
                    $ropdata .= '<td width="120px">'.$row->item_name.'<br><i>'.$row->item_desc.'</i></td>
                                <td width="50px" class="centertalign">'.$b_diff.'</td>
                                <td width="50px" class="centertalign">'.$row->item_quantity.'</td>
                                <td width="50px" class="centertalign">'.($row->item_quantity <= $row->item_critical ? 'Need to Request' : '' ).'</td>
                                <td width="50px" class="centertalign">'.$row->item_critical.'</td>
                                <td width="50px" class="centertalign">'.$row->item_order.'</td>
                            </tr>';
                endforeach;
                else :
                    $ropdata .= '<tr>
                                     <td colspan="6" class="centertalign">No in and out record found for this day</td>
                                </tr>';
                endif;
                                
                $ropdata .= '
                        </table>';
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime($this->report_from())).' to '.mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime("last month")).' to '.mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'Reordering Point Report'.$datelabel,
                    'message' => $ropdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) {
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("REORDERING_REPORT_GENERATE", 0, $this->profile_id());
                    if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/rop_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.pdf";</script>';
                    elseif ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/rop_'.$this->report_from().'_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/rop_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                }	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function csv_reordering_point()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
        
                //Set the filename to save/download as
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    $file = fopen('assets/xls/rop_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.csv', 'w');
                elseif ($this->report_from() || $this->report_to()) :
                    $file = fopen('assets/xls/rop_'.$this->report_from().'_'.$this->report_to().'.csv', 'w');
                else :
                    $file = fopen('assets/xls/rop_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.csv', 'w');
                endif;
        
                $fromdate = mdate('%M %j %Y', strtotime($this->report_from()));
                $todate = mdate('%M %j %Y', strtotime($this->report_to()));
                $fromto = $this->report_from() ? '('.$fromdate.' to '.$todate.')' : '';
                
                $csvhead = 'Reordering Point Report '.$fromto;
                fputcsv($file, explode(',' ,$csvhead));        
                
                $csvheader = ($this->item_category_name() ? $this->item_category_name() : 'Item Name').', Quantity Last '.mdate('%M %j %Y', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))).', In, Out, Difference, Stock on Hand, Remarks, Reorder Quantity, Standard Order Quantity';
                fputcsv($file, explode(',' ,$csvheader));
        
                $rop_data = $this->Core->get_item(1, 0, 0, 0, $this->item_category(), 0, 0, 0);
            
                foreach ($rop_data as $row) :
                    $stock_in = $this->Core->get_stock('IN', $row->item_id, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 1);
                    $stock_out = $this->Core->get_stock('OUT', $row->item_id, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 1);
                    $s_in = $stock_in[0]['iqty'] ? $stock_in[0]['iqty'] : 0;
                    $s_out = $stock_out[0]['iqty'] ? $stock_out[0]['iqty'] : 0;
                    $s_diff = $s_in - $s_out;
                    if ($s_diff > 0) :
                        $diff_val = '+'.$s_diff;
                    elseif ($s_diff < 0) : 
                        $diff_val = $s_diff; 
                    else :
                        $diff_val = 0;
                    endif;
                    $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', $this->report_from() ? mdate('%Y-%m-%d', strtotime($this->report_from()) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                    $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', $this->report_from() ? mdate('%Y-%m-%d', strtotime($this->report_from()) - 86400) : mdate('%Y-%m-%d', strtotime('last month') - 86400), 1);
                    $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
                    $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
                    $b_diff = $b_in - $b_out;
        
                    $csvdata = $row->item_name.', '.$b_diff.', '.$s_in.', '.$s_out.', '.$diff_val.', '.$row->item_quantity.', '.($row->item_quantity <= $row->item_critical ? 'Need to Request' : '' ).', '.$row->item_critical.', '.$row->item_order;
                    fputcsv($file, explode(',' ,$csvdata));
                endforeach;
                
                
                //AUDIT TRAIL
                $log = $this->Core->log_action("REORDERING_CSV_GENERATE", 0, $this->profile_id());
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/rop_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.csv";</script>';
                elseif ($this->report_from() || $this->report_to()) :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/rop_'.$this->report_from().'_'.$this->report_to().'.csv";</script>';
                else :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/rop_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.csv";</script>';
                endif;
        
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function csv_pending()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
        
                //Set the filename to save/download as
                if ($this->report_from() || $this->report_to()) :
                    $file = fopen('assets/xls/pend_'.$this->report_from().'_'.$this->report_to().'.csv', 'w');
                else :
                    $file = fopen('assets/xls/pend_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.csv', 'w');
                endif;
        
                $fromdate = mdate('%M %j %Y', strtotime($this->report_from()));
                $todate = mdate('%M %j %Y', strtotime($this->report_to()));
                $fromto = $this->report_from() ? '('.$fromdate.' to '.$todate.')' : '';
                
                $csvhead = 'List of Pending Item '.$fromto;
                fputcsv($file, explode(',' ,$csvhead));     
                
                $csvheader = 'Quantity, Unit, Item';
                fputcsv($file, explode(',' ,$csvheader));
        
                $pend_data = $this->Core->get_pend(1, 0, 0, 0, 0, mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 2, 1);  
            
                foreach ($pend_data as $row) :
        
                    $csvdata = $row->quantity.', '.$row->pi_unit.', '.$row->item_name;
                    fputcsv($file, explode(',' ,$csvdata));
                endforeach;
                
                
                //AUDIT TRAIL
                $log = $this->Core->log_action("PENDING_CSV_GENERATE", 0, $this->profile_id());
                if ($this->report_from() || $this->report_to()) :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/pend_'.$this->report_from().'_'.$this->report_to().'.csv";</script>';
                else :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/pend_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.csv";</script>';
                endif;
        
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function consumption()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    $this->html2pdf->filename('consumpt_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.pdf');
                elseif ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('consumpt_'.$this->report_from().'_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('consumpt_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'landscape');
        
                
        
                $consumptdata = '';
                $consumptdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="120px">Item</th>';
                $consumpt_hdept = $this->Core->get_dept();        
                foreach ($consumpt_hdept as $rowhdept) :                    
                    $consumptdata .= '<th width="50px">'.$rowhdept->dept_abbr.'</th>';
                endforeach;
                
                $consumptdata .= '</tr>';
        
                $consumpt_cat = $this->Core->get_cat();
                
                foreach ($consumpt_cat as $ccat) :
                    $consumpt_data = $this->Core->get_item(1, 0, 0, 0, $ccat->cat_id, 0, 0, 0);                                       
                    if ($consumpt_data) :
                        foreach ($consumpt_data as $row) :
                            
                            
                            
                            $consumptdata .= '<tr>';
                            $consumptdata .= '<td width="120px">'.$row->item_name.'<br><i>'.$row->item_desc.'</i></td>';
        
                            $consumpt_dept = $this->Core->get_dept();
                            foreach ($consumpt_dept as $rowdept) :
                                $consumptdata .= '<td align="center" width="30px">';
                                $consumpt_item = $this->Core->get_dept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), $row->item_id, $rowdept->dept_id);                         
                                if ($consumpt_item) :
                                foreach ($consumpt_item as $rowitem) :
                                    $consumptdata .= $rowitem->qty_total;
                                endforeach;
                                else :
                                    $consumptdata .= '0';
                                endif;
                                $consumptdata .= '</td>';
                            endforeach;
                            $consumptdata .= '</tr>';
                        endforeach;
                    endif;
        
                endforeach;       
                                
                $consumptdata .= '
                        </table>';
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime($this->report_from())).' to '.mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime("last month")).' to '.mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'Consumption Report'.$datelabel,
                    'message' => $consumptdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) {
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("CONSUMPTION_REPORT_GENERATE", 0, $this->profile_id());
                    if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/consumpt_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.pdf";</script>';
                    elseif ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/consumpt_'.$this->report_from().'_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/consumpt_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                }	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function csv_consumption()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
        
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    $file = fopen('assets/xls/consumpt_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.csv', 'w');
                elseif ($this->report_from() || $this->report_to()) :
                    $file = fopen('assets/xls/consumpt_'.$this->report_from().'_'.$this->report_to().'.csv', 'w');
                else :
                    $file = fopen('assets/xls/consumpt_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.csv', 'w');
                endif;
        
                $fromdate = mdate('%M %j %Y', strtotime($this->report_from()));
                $todate = mdate('%M %j %Y', strtotime($this->report_to()));
                $fromto = $this->report_from() ? '('.$fromdate.' to '.$todate.')' : '';
                
                $csvhead = 'Consumption Report '.$fromto;
                fputcsv($file, explode(',' ,$csvhead));        
                
                $consumpthead = "";
                $consumpt_hdept = $this->Core->get_dept();        
                foreach ($consumpt_hdept as $rowhdept) :                    
                    $consumpthead .= ', '.$rowhdept->dept_abbr;
                endforeach;        
                $csvheader = "Item".$consumpthead.", Total";
                fputcsv($file, explode(',' ,$csvheader));
                        
                if ($this->item_category()) :
                
                    $consumpt_cat = $this->Core->get_cat($this->item_category());
                        
                    $consumpt_data = $this->Core->get_item(1, 0, 0, 0, $consumpt_cat['cat_id'], 0, 0, 0);                 
                    foreach ($consumpt_data as $row) :
                        $consumptdata = '';
        
                        if ($consumpt_data) :
                                                                                
                            $consumptdata .= $row->item_name;
        
                            $consumpt_dept = $this->Core->get_dept();
                            $consumpt_total_item = $this->Core->get_idept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), $row->item_id, 0);                         
                            foreach ($consumpt_dept as $rowdept) :
                                $consumpt_item = $this->Core->get_idept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), $row->item_id, $rowdept->dept_id);                         
                                if ($consumpt_item) :
                                    foreach ($consumpt_item as $rowitem) :
                                        $consumptdata .= ', '.$rowitem->qty_total;
                                    endforeach;
                                else :
                                    $consumptdata .= ', 0';
                                endif;                                
                            endforeach;
                            if ($consumpt_total_item) :
                                foreach ($consumpt_total_item as $total_item) :                                
                                    $consumptdata .= ','.$total_item->qty_total;
                                endforeach;
                            else :
                                $consumptdata .= ', 0';
                            endif;
                        fputcsv($file, explode(',', $consumptdata));    
                    
                        endif;                  
                    endforeach;                   
        
                else :
        
                    $consumpt_cat = $this->Core->get_cat();
                
                    foreach ($consumpt_cat as $ccat) :
                        
                        
                        $consumpt_data = $this->Core->get_item(1, 0, 0, 0, $ccat->cat_id, 0, 0, 0);                                       
                        
                        foreach ($consumpt_data as $row) :
                            $consumptdata = '';
            
                            if ($consumpt_data) :
                                                                                    
                                $consumptdata .= $row->item_name;
            
                                $consumpt_dept = $this->Core->get_dept();
                                $consumpt_total_item = $this->Core->get_idept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), $row->item_id, 0);                         
                                foreach ($consumpt_dept as $rowdept) :
                                    $consumpt_item = $this->Core->get_idept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), $row->item_id, $rowdept->dept_id);                         
                                    if ($consumpt_item) :
                                        foreach ($consumpt_item as $rowitem) :
                                            $consumptdata .= ', '.$rowitem->qty_total;
                                        endforeach;
                                    else :
                                        $consumptdata .= ', 0';
                                    endif;                                
                                endforeach;
                                if ($consumpt_total_item) :
                                    foreach ($consumpt_total_item as $total_item) :                                
                                        $consumptdata .= ','.$total_item->qty_total;
                                    endforeach;
                                else :
                                    $consumptdata .= ', 0';
                                endif;
                            fputcsv($file, explode(',', $consumptdata));    
                        
                            endif;                  
                        endforeach;                   
            
                    endforeach;
        
                    $consumpt_total_total = $this->Core->get_iddept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 0, 0);  
        
                    $consumptfoot = "";
                    $consumptgrandtotal = 0;
                    $consumpt_fdept = $this->Core->get_dept();        
                    foreach ($consumpt_fdept as $rowfdept) :                    
                        $consumpt_total_dept = $this->Core->get_ddept_from_log(mdate('%Y-%m-%d', strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month'))))), mdate('%Y-%m-%d', strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')))), 0, $rowfdept->dept_id);                         
                        if ($consumpt_total_dept) :
                            foreach ($consumpt_total_dept as $total_dept) :                                
                                $consumptgrandtotal = $consumptgrandtotal + $total_dept->qty_total;
                                $consumptfoot .= ','.$total_dept->qty_total;
                            endforeach;
                        else :
                            $consumptfoot .= ', 0';
                        endif;
                    endforeach; 
                    
                    $csvfooter = "Total".$consumptfoot.','.$consumptgrandtotal;
                    fputcsv($file, explode(',' ,$csvfooter));
        
                endif;
        
                //AUDIT TRAIL
                $log = $this->Core->log_action("CONSUMPTION_CSV_GENERATE", 0, $this->profile_id());
                if (($this->report_from() || $this->report_to()) && $this->item_category()) :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/consumpt_'.$this->report_from().'_'.$this->report_to().'_'.$this->item_category().'.csv";</script>';
                elseif ($this->report_from() || $this->report_to()) :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/consumpt_'.$this->report_from().'_'.$this->report_to().'.csv";</script>';
                else :
                    echo '<script>window.location.href="'.WEB.'/assets/xls/consumpt_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.csv";</script>';
                endif;
        
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function csv_dailyapprove()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     			
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
        
                if ($this->report_to()) :
                    $file = fopen('assets/xls/dailyapprove_'.$this->report_to().'.csv', 'w');
                else :
                    $file = fopen('assets/xls/dailyapprove_'.mdate('%Y-%m-%d').'.csv', 'w');
                endif;
        
                $fromdate = mdate('%M %j %Y', strtotime($this->report_to()));
                $fromto = $this->report_to() ? '('.$fromdate.')' : '';
                
                $csvhead = 'Daily Approved Request Report from 8:30am to 3:00pm '.$fromto;
                fputcsv($file, explode(',' ,$csvhead));        
                
                $consumpthead = '';
                $consumpt_htrans = $this->Core->get_adminapp_trans($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d')); 

		//var_dump($consumpt_htrans);
        
                if ($consumpt_htrans) :
        
                    foreach ($consumpt_htrans as $rowhtrans) :                    
                        $consumpthead .= ', '.mdate('%Y', strtotime($rowhtrans->trans_date)).'-'.$rowhtrans->trans_date;
                    endforeach;        
                    $csvheader = "Item".$consumpthead.", Total";
                    fputcsv($file, explode(',' ,$csvheader));        

                    $approve_data = $this->Core->get_item(1, 0, 0, 0, 0, 0, 0, 0);   

                    foreach ($approve_data as $row) :
                        $approvedata = '';

                        if ($approve_data) :

                            $approvedata .= $row->item_name;

                            $approve_trans = $this->Core->get_adminapp_trans($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d'));        

                            $cnt_total = 0;

                            foreach ($approve_trans as $rowatrans) :                    

                                $order_array = html_entity_decode($rowatrans->trans_order, ENT_QUOTES);
                                $order_array = unserialize($order_array);

                                $cnt_quantity = 0;

                                foreach ($order_array as $orderrow) :
                                    if ($orderrow['id'] == $row->item_id) :
                                        $cnt_quantity = $cnt_quantity + $orderrow['qty'];                
                                        $cnt_total = $cnt_total + $orderrow['qty'];                
                                    endif;
                                endforeach;

                                $approvedata .= ','.$cnt_quantity;                

                            endforeach;

                            $approvedata .= ','.$cnt_total;                                

                            fputcsv($file, explode(',', $approvedata));    

                        endif;                  
                    endforeach;    

                    $approvefoot = '';
                    $approve_ftrans = $this->Core->get_adminapp_trans($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d'));        

                    $cnt_ftotal = 0;

                    foreach ($approve_ftrans as $rowftrans) :                    

                        $order_farray = html_entity_decode($rowftrans->trans_order, ENT_QUOTES);
                        $order_farray = unserialize($order_farray);

                        $cnt_fquantity = 0;

                        foreach ($order_farray as $orderfrow) :                            
                            $cnt_fquantity = $cnt_fquantity + $orderfrow['qty'];                
                            $cnt_ftotal = $cnt_ftotal + $orderfrow['qty'];                
                        endforeach;

                        $approvefoot .= ','.$cnt_fquantity;                

                    endforeach;

                    $csvfooter = "Total".$approvefoot.','.$cnt_ftotal;
                    fputcsv($file, explode(',' ,$csvfooter));

                    //AUDIT TRAIL
                    //$log = $this->Core->log_action("CONSUMPTION_CSV_GENERATE", 0, $this->profile_id());
                    if ($this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/xls/dailyapprove_'.$this->report_to().'.csv";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/xls/dailyapprove_'.mdate('%Y-%m-%d').'.csv";</script>';
                    endif;
        
                else :
        
                    echo '<script type="text/javascript">alert("No report will generate on this date because no approved transaction has been made.");</script>';
                    echo '<script>window.close();</script>';
        
                endif;
        
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function request()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :   		
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('request_'.mdate('%Y-%m-%d', strtotime($this->report_to()) - 604800).'_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('request_'.mdate('%Y-%m-%d', strtotime('-1 week')).'_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'landscape');
                
                $request_data = $this->Core->get_trans_by_date(strtotime(($this->report_to() ? mdate('%Y-%m-%d', strtotime($this->report_to()) - 604800) : mdate('%Y-%m-%d', strtotime('-1 week')))." 00:00:00"), strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d'))." 23:59:59"));	
                
                $reqdata = '';
                $reqdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="100px">Transaction ID</th>
                                    <th width="140px">Request by</th>
                                    <th width="340px">Order Details</th>              
                                    <th width="90px">Request Date</th>
                                    <th width="90px">Status</th>
                                    <th width="90px">Last Updated</th>
                                </tr>';
                if ($request_data) :
                foreach ($request_data as $row) :
                    $order_array = html_entity_decode($row->trans_order, ENT_QUOTES);
                    $order_array = unserialize($order_array);
                
                    $order_detail = "";
                    foreach ($order_array as $orderrow) :
                        $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";                
                    endforeach;
                
                    $reqdata .= '<tr>';
                    $reqdata .= '<td width="100px">'.mdate('%Y', $row->trans_date).'-'.$row->trans_date.'</td>
                                <td width="140px">'.$row->user_fullname.'</td>
                                <td width="340px">'.$order_detail.'</td>
                                <td width="90px" class="centertalign">'.mdate('%M %j, %Y<br />%g:%i%a', $row->trans_date).'</td>
                                <td width="90px" class="centertalign">'.$this->Core->display_status($row->trans_status, 9).'</td>
                                <td width="90px" class="centertalign">'.mdate('%M %j, %Y<br />%g:%i%a', $row->trans_update).'</td>
                            </tr>';
                endforeach;
                else :
                    $reqdata .= '<tr>
                                     <td colspan="6" class="centertalign">No transaction record found for this day</td>
                                </tr>';
                endif;
                                
                $reqdata .= '
                        </table>';
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime($this->report_to()) - 604800).' to '.mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime("-1 week")).' to '.mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'Transaction Report '.$datelabel,
                    'message' => $reqdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) {
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("REQUEST_REPORT_GENERATE", 0, $this->profile_id());
                    if ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/request_'.$this->report_from().'_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/request_'.mdate('%Y-%m-%d', strtotime('-1 week')).'_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                    
                }	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function admin_approved()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :   		
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('adminapp_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('adminapp_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'landscape');
                
                $request_data = $this->Core->get_adminapp_trans($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d'));	
                
                $reqdata = '';
                $reqdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="100px">Transaction ID</th>
                                    <th width="120px">Request by</th>
                                    <th width="300px">Order Details</th>              
                                    <th width="90px">Approved Date</th>
                                    <th width="90px">Date Endorse by Admin</th>
                                    <th width="150px">Requestor\'s Remarks</th>
                                </tr>';
                if ($request_data) :
                foreach ($request_data as $row) :
                    $order_array = html_entity_decode($row->trans_order, ENT_QUOTES);
                    $order_array = unserialize($order_array);
                
                    $order_detail = "";
                    foreach ($order_array as $orderrow) :
                        $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";                
                    endforeach;
                
                    $reqdata .= '<tr>';
                    $reqdata .= '<td width="100px">'.mdate('%Y', $row->trans_date).'-'.$row->trans_date.'</td>
                                <td width="120px">'.$row->user_fullname.'</td>
                                <td width="300px">'.$order_detail.'</td>
                                <td width="90px" class="centertalign">'.mdate('%M %j, %Y<br />%g:%i%a', $row->trans_approvedate).'</td>
                                <td width="90px" class="centertalign">'.($row->trans_admindate ? mdate('%M %j, %Y<br />%g:%i%a', $row->trans_admindate) : '').'</td>
                                <td width="150px">'.$row->trans_reqremarks.'</td>
                            </tr>';
                endforeach;
                else :
                    $reqdata .= '<tr>
                                     <td colspan="6" class="centertalign">No transaction record found for this day</td>
                                </tr>';
                endif;
                                
                $reqdata .= '
                        </table>';
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'Request Approved by Supply Admin on '.$datelabel.' (8:30 to 11am)',
                    'message' => $reqdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) {
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("ADMINAPP_REPORT_GENERATE", 0, $this->profile_id());
                    if ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/adminapp_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/adminapp_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                    
                }	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function pending_request()
	{
        if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 5 && $this->profile_level() != 6 && $this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :   		
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Reports";
                //Load the library
                $this->load->library('html2pdf');
                
                //Set folder to save PDF to
                $this->html2pdf->folder('./assets/pdfs/');
                
                //Set the filename to save/download as
                if ($this->report_from() || $this->report_to()) :
                    $this->html2pdf->filename('request_'.$this->report_from().'_'.$this->report_to().'.pdf');
                else :
                    $this->html2pdf->filename('request_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf');
                endif;
                
                //Set the paper defaults
                $this->html2pdf->paper('a4', 'landscape');
                
                $request_data = $this->Core->get_pendtrans_by_date(strtotime(($this->report_from() ? $this->report_from() : mdate('%Y-%m-%d', strtotime('last month')))." 00:00:00"), strtotime(($this->report_to() ? $this->report_to() : mdate('%Y-%m-%d'))." 23:59:59"));	
                
                $reqdata = '';
                $reqdata .= '<table class="tdatareport">                            
                                <tr>
                                    <th width="100px">Transaction ID</th>
                                    <th width="140px">Request by</th>
                                    <th width="340px">Order Details</th>              
                                    <th width="90px">Request Date</th>
                                    <th width="90px">Status</th>
                                    <th width="90px">Last Updated</th>
                                </tr>';
                if ($request_data) :
                foreach ($request_data as $row) :
                    $order_array = html_entity_decode($row->trans_order, ENT_QUOTES);
                    $order_array = unserialize($order_array);
                
                    $order_detail = "";
                    foreach ($order_array as $orderrow) :
                        $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";                
                    endforeach;
                
                    $reqdata .= '<tr>';
                    $reqdata .= '<td width="100px">'.$row->trans_id.'</td>
                                <td width="140px">'.$row->user_fullname.'</td>
                                <td width="340px">'.$order_detail.'</td>
                                <td width="90px" class="centertalign">'.mdate('%M %j, %Y<br />%g:%i%a', $row->trans_date).'</td>
                                <td width="90px" class="centertalign">'.$this->Core->display_status($row->trans_status, 9).'</td> 
                                <td width="90px" class="centertalign">'.mdate('%M %j, %Y<br />%g:%i%a', $row->trans_update).'</td>
                            </tr>';
                endforeach;
                else :
                    $reqdata .= '<tr>
                                     <td colspan="7" class="centertalign">No transaction record found for this day</td>
                                </tr>';
                endif;
                                
                $reqdata .= '
                        </table>';
        
                if ($this->report_from() || $this->report_to()) :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime($this->report_from())).' to '.mdate("%M %j, %Y", strtotime($this->report_to()));
                else :
                    $datelabel = ' from '.mdate("%M %j, %Y", strtotime("last month")).' to '.mdate("%M %j, %Y");
                endif;
                
                $data = array(
                    'title' => 'Pending Transaction Report '.$datelabel,
                    'message' => $reqdata
                );
                
                //Load html view
                $this->html2pdf->html($this->load->view('reports_data', $data, true));
                
                if($this->html2pdf->create('save')) {
                    //AUDIT TRAIL
                    $log = $this->Core->log_action("REQUEST_REPORT_GENERATE", 0, $this->profile_id());
                    if ($this->report_from() || $this->report_to()) :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/request_'.$this->report_from().'_'.$this->report_to().'.pdf";</script>';
                    else :
                        echo '<script>window.location.href="'.WEB.'/assets/pdfs/request_'.mdate('%Y-%m-%d', strtotime('last month')).'_'.mdate('%Y-%m-%d').'.pdf";</script>';
                    endif;
                    
                }	
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function email_inventory()
	{	
        $data['page_title'] = "iRS Reports";
        //Load the library
        $this->load->library('html2pdf');
        
        //Set folder to save PDF to
        $this->html2pdf->folder('./assets/pdfs/');
        
        //Set the filename to save/download as
        $this->html2pdf->filename('einventory_'.mdate('%Y%m%d').'.pdf');
        
        //Set the paper defaults
        $this->html2pdf->paper('a4', 'landscape');
        
        $einventory_count = $this->Core->get_item(0, 1, 0, 0, 0, 0, 2, $searchstr);			        
        $einventory_data = $this->Core->get_item(1, 0, 0, 0, 0, 0, 2, $searchstr);			        
        
        $einvdata = '';
        $einvdata .= '<table class="tdatareport">                            
                        <tr>
                            <th width="30px">ID</th>
                            <th width="120px">Category</th>
                            <th width="210px">Item Name</th>
                            <th width="310px">Description</th>              
                            <th width="90px">Quantity</th>
                            <th width="80px">Unit</th>
                        </tr>';
        foreach ($einventory_data as $row) :
            $einvdata .= '<tr>
                            <td width="30px">'.$row->item_id.'</td>
                            <td width="120px">'.$row->cat_name.'</td>
                            <td width="210px">'.$row->item_name.'</td>
                            <td width="310px">'.$row->item_desc.'</td>
                            <td width="90px">'.$row->item_quantity.'</td>
                            <td width="80px">'.$row->unit_name.'</td>
                        </tr>';
        endforeach;
        $einvdata .= '</table>';
        
        $data = array(
            'title' => 'Inventory Report as of '.mdate("%M %j, %Y - %g:%i%a", time()),
            'message' => $einvdata
        );        
                
        //Load html view
        $this->html2pdf->html($this->load->view('reports_data', $data, true));        
        
        if($einventory_count != 0) :
        
            if($this->html2pdf->create('save')) :   
                $message = "Hi Admin,\n\n";
                $message .= "Attached here as of today's inventory.\n\n";
                $message .= "Click <a href='".WEB."/assets/pdfs/einventory_".mdate('%Y%m%d').".pdf'>here</a> if attached file is broken\n\n";    
                $message .= "Click <a href='".WEB."/mega_irs/inventory'>here</a> for details\n\n";    
                $message .= "Thanks,\n";
                $message .= "iRS Admin";
                $message .= "<hr />For any concerns regarding iRS, please contact the Megaworld Purchasing Department @ Loc. 338. This email is system generated. Please do not reply.";
                
                $this->email->from('jisleta@megaworldcorp.com', 'iRS System (no-reply)');
                $this->email->to('jisleta@megaworldcorp.com');         
                $this->email->subject('iRS as of Today\'s Inventory');
                $this->email->attach('/assets/pdfs/einventory_'.mdate('%Y%m%d').'.pdf');
                $this->email->message($message);
                
                $this->email->send();
            
                //AUDIT TRAIL
                $log = $this->Core->log_action("INVENTORY_REPORT_EMAILED", 0, 0);
            endif;	
        endif;	
        
        echo '<script>window.location.href = "'.WEB.'";</script>';
	}
    
    public function email_pending()
	{	
        $searchfrom = date("Y-m-d", time() - strtotime("-1 day")); 
        $searchto = date("Y-m-d");
        
        $data['page_title'] = "iRS Reports";
        //Load the library
        $this->load->library('html2pdf');
        
        //Set folder to save PDF to
        $this->html2pdf->folder('./assets/pdfs/');
        
        //Set the filename to save/download as
        $this->html2pdf->filename('pending_'.mdate('%Y%m%d').'.pdf');
        
        //Set the paper defaults
        $this->html2pdf->paper('a4', 'portrait');
        
        $pend_count = $this->Core->get_pend(0, 1, 0, 0, 0, $searchfrom, $searchto, 2, 1);   
        $pend_data = $this->Core->get_pend(1, 0, 0, 0, 0, $searchfrom, $searchto, 2, 1);   
        
        $pdata = '';
        $pdata .= '<table class="tdatareport">                            
                        <tr>
                            <th width="15%">Quantity</th>
                            <th width="15%">Unit</th>                             
                            <th width="70%">Item</th>                   
                        </tr>';
        foreach ($pend_data as $row) :
            $pdata .= '<tr>
                            <td width="20%" class="righttalign">'.$row->quantity.'</td>                                              
                            <td width="20%">'.$row->pi_unit.'</td>
                            <td width="60%">'.$row->item_name.'</td>
                        </tr>';
        endforeach;
        $pdata .= '</table>';
        
        $data = array(
            'title' => 'List of Today\'s Pending Item',
            'message' => $pdata
        );
        
        //Load html view
        $this->html2pdf->html($this->load->view('reports_data', $data, true));
        
        if($pend_count != 0) :
        
            if($this->html2pdf->create('save')) :            
            
                $message = "Hi Admin,\n\n";
                $message .= "Attached here the list of today's pending item.\n\n";
                $message .= "Click <a href='".WEB."/assets/pdfs/pending_".mdate('%Y%m%d').".pdf'>here</a> if attached file is broken\n\n";    
                $message .= "Click <a href='".WEB."/mega_irs/pending'>here</a> for details\n\n";    
                $message .= "Thanks,\n";
                $message .= "iRS Admin";
                $message .= "<hr />For any concerns regarding iRS, please contact the Megaworld Purchasing Department @ Loc. 338. This email is system generated. Please do not reply.";
                
                $this->email->from('jisleta@megaworldcorp.com', 'iRS System (no-reply)');
                $this->email->to('jisleta@megaworldcorp.com');         
                $this->email->subject('iRS List of Today\'s Pending Items');
                $this->email->attach('/assets/pdfs/pending_'.mdate('%Y%m%d').'.pdf');
                $this->email->message($message);
                
                $this->email->send();
            
                //AUDIT TRAIL
                $log = $this->Core->log_action("PENDING_REPORT_EMAILED", 0, 0);
            endif;	
        endif;	
        
        echo '<script>window.location.href = "'.WEB.'";</script>';
	}
}

/* End of file reports.php */
/* Location: ./application/controllers/reports.php */