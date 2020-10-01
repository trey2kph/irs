<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        error_reporting(0);
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
    
    function profile_fullname() {
        $pro_level = $this->session->userdata('session_fullname');
        return $pro_level;
    }
    
    function method_name() {
        $methodname = $_SERVER['HTTP_REFERER'];
        $methodname = explode("/", preg_replace('#^https?://#', '', $methodname));
        return $methodname[3];
    }
    
    public function viewall_trans() {
        $this->session->unset_userdata('session_searchstr_trans');
    }
    
    public function viewall_resitem() {
        $this->session->unset_userdata('session_searchstr_req');
        $this->session->unset_userdata('session_searchcat_req');
    }
    
    public function viewall_stock() {
        $this->session->unset_userdata('session_searchstr_stock');
    }
    
    public function viewall_user() {
        $this->session->unset_userdata('session_searchstr_user');
    }
    
    public function viewall_dept() {
        $this->session->unset_userdata('session_searchstr_dept');
    }
    
    public function updatedash($page = NULL, $page_num = NULL)
    {
        $post = $this->input->post();
        $sess_str = $this->session->userdata('session_searchstr');
        $sess_status = $this->session->userdata('session_searchstatus');
        if ($post) :
            $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
            $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
            $data['post'] = $post;
            $session_search = array(
               'session_searchstr'	    => $searchstr,
               'session_searchstatus'	=> $searchstatus
            );    
            $this->session->set_userdata($session_search);     
        elseif ($sess_str || $sess_status) :
            $searchstr = $sess_str;
            $searchstatus = $sess_status;
            $data['post']['searchtrans'] = $searchstr == 0 ? NULL : $searchstr;
            $data['post']['statustrans'] = $searchstatus;
        else : 
            $searchstr = 0;
            $searchstatus = 0;
            $data['post'] = NULL;
        endif;        
        
        $pages = $page_num ? (int)$page_num : 1 ;
        $start = NUM_ROWS * ($pages - 1); 
        
        $proid = $this->profile_id();
        $level = $this->profile_level();
        $methodname = $this->method_name();
        $data['level'] = $level;
        
        if ($level == 1 || $level == 3 || $level == 9) :
            $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, $proid, 0, $searchstatus, $searchstr);
            $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, $proid, 0, $searchstatus, $searchstr);
            $data['trans_sec'] = '';
        elseif ($level == 2) :
            if ($methodname == NULL || $methodname == 'index') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $proid, 1, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $proid, 1, $searchstr);    
                $data['trans_sec'] = 'for approval';
            elseif ($methodname == 'approved') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $proid, 20, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $proid, 20, $searchstr);
                $data['trans_sec'] = 'approved';
            elseif ($methodname == 'disapproved') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $proid, 8, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $proid, 8, $searchstr);                
                $data['trans_sec'] = 'disapproved';
            endif;
        elseif ($level == 6 || $level == 7) :
            if ($methodname == NULL || $methodname == 'index') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 3, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 3, $searchstr);                
                $data['trans_sec'] = 'admin approve';
            elseif ($methodname == 'release') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 5, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 5, $searchstr);                
                $data['trans_sec'] = 'release';
            elseif ($methodname == 'close') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 9, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 9, $searchstr);                
                $data['trans_sec'] = 'close';
            endif;
        elseif ($level == 8) :
            if ($methodname == NULL || $methodname == 'index') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 2, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 2, $searchstr);                
                $data['trans_sec'] = 'endorse';
            elseif ($methodname == 'admin_approve') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 30, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 30, $searchstr);                
                $data['trans_sec'] = 'admin approve';
            elseif ($methodname == 'pending') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 4, $searchstr);                
                $data['trans_sec'] = 'pending';
            elseif ($methodname == 'admin_reject') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 7, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 7, $searchstr);                
                $data['trans_sec'] = 'admin reject';
            endif;
        endif;
        
        // PAGINATION		                
        $page_data['base_url'] = WEB.'/irs/index/page/';
        $page_data['total_rows'] = $trans_count;
        $page_data['per_page'] = NUM_ROWS;
        $page_data['uri_segment'] = 4;
        $page_data['num_links'] = NUM_LINKS;
        $page_data['use_page_numbers'] = TRUE;
        $this->pagination->initialize($page_data); 	
        
        // TEMPLATE
        $this->load->view('updatedash', $data);
    }
    
    public function updatetrans($page = NULL, $page_num = NULL)
    {
        $post = $this->input->post();
        $sess_str = $this->session->userdata('session_searchstr');
        $sess_status = $this->session->userdata('session_searchstatus');
        if ($post) :
            $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
            $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
            $data['post'] = $post;
            $session_search = array(
               'session_searchstr'	    => $searchstr,
               'session_searchstatus'	=> $searchstatus
            );    
            $this->session->set_userdata($session_search);     
        elseif ($sess_str || $sess_status) :
            $searchstr = $sess_str;
            $searchstatus = $sess_status;
            $data['post']['searchtrans'] = $searchstr == 0 ? NULL : $searchstr;
            $data['post']['statustrans'] = $searchstatus;
        else : 
            $searchstr = 0;
            $searchstatus = 0;
            $data['post'] = NULL;
        endif;        
        
        $pages = $page_num ? (int)$page_num : 1 ;
        $start = NUM_ROWS * ($pages - 1); 
        
        $proid = $this->profile_id();
        $level = $this->profile_level();
        $methodname = $this->method_name();
        $data['level'] = $level;
        
        if ($level == 1 || $level == 3 || $level == 9) :
            $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, $proid, 0, $searchstatus, $searchstr);
            $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, $proid, 0, $searchstatus, $searchstr);
            $data['trans_sec'] = '';
        elseif ($level == 2) :
            if ($methodname == NULL || $methodname == 'index') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $proid, 1, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $proid, 1, $searchstr);    
                $data['trans_sec'] = 'for approval';
            elseif ($methodname == 'approved') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $proid, 20, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $proid, 20, $searchstr);
                $data['trans_sec'] = 'approved';
            elseif ($methodname == 'disapproved') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $proid, 8, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $proid, 8, $searchstr);                
                $data['trans_sec'] = 'disapproved';
            endif;
        elseif ($level == 6 || $level == 7) :
            if ($methodname == NULL || $methodname == 'index') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 3, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 3, $searchstr);                
                $data['trans_sec'] = 'admin approve';
            elseif ($methodname == 'release') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 5, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 5, $searchstr);                
                $data['trans_sec'] = 'release';
            elseif ($methodname == 'close') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 9, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 9, $searchstr);                
                $data['trans_sec'] = 'close';
            endif;
        elseif ($level == 8) :
            if ($methodname == NULL || $methodname == 'index') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 2, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 2, $searchstr);                
                $data['trans_sec'] = 'endorse';
            elseif ($methodname == 'admin_approve') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 30, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 30, $searchstr);                
                $data['trans_sec'] = 'admin approve';
            elseif ($methodname == 'pending') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 4, $searchstr);                
                $data['trans_sec'] = 'pending';
            elseif ($methodname == 'admin_reject') :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 7, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 7, $searchstr);                
                $data['trans_sec'] = 'admin reject';
            endif;
        endif;
        
        // PAGINATION		                
        $page_data['base_url'] = WEB.'/trans/index/page/';
        $page_data['total_rows'] = $trans_count;
        $page_data['per_page'] = NUM_ROWS;
        $page_data['uri_segment'] = 4;
        $page_data['num_links'] = NUM_LINKS;
        $page_data['use_page_numbers'] = TRUE;
        $this->pagination->initialize($page_data); 	
        
        // TEMPLATE
        $this->load->view('updatetrans', $data);
    }
    
    public function updateinv()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        if ($post['searchinv']) :
            $searchstr = $post['searchinv'] ? $post['searchinv'] : 0;
            $cat = $post['searchcat'] ? $post['searchcat'] : 0;
        else : 
            $searchstr = NULL;
            $cat = 0;
        endif;
        
        $proid = $this->profile_id();
        $level = $this->profile_level();
        $methodname = $this->method_name();
        $data['level'] = $level;
        
        if ($level == 8 || $level == 9) :
            $data['inventory_data'] = $this->Core->get_item(1, 0, 0, 0, $cat, 0, 0, $searchstr);			        
        endif;
        
        // TEMPLATE
        $this->load->view('updateinv', $data);
    }
    
    public function updateinout()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        if ($post['inout_date_from'] && $post['inout_date_to']) :
            $searchdate = $post['inout_date_from'] ? $post['inout_date_from'] : NULL;
            $searchdateto = $post['inout_date_to'] ? $post['inout_date_to'] : NULL;
            $data['searchdate'] = $searchdate;
            $data['searchdateto'] = $searchdateto;
        else : 
            $searchdate = NULL;
            $searchdateto = NULL;
            $data['searchdate'] = NULL;
            $data['searchdateto'] = NULL;
        endif;
        
        $proid = $this->profile_id();
        $level = $this->profile_level();
        $methodname = $this->method_name();
        $data['level'] = $level;
        
        if ($level == 8 || $level == 9) :
            $data['inout_data'] = $this->Core->get_item_from_log(($searchdate ? $searchdate : mdate('%Y-%m-%d')), ($searchdateto ? $searchdateto : mdate('%Y-%m-%d')), 0, 1);	
        endif;
        
        // TEMPLATE
        $this->load->view('updateinout', $data);
    }
    
    public function updatestock($page = NULL, $page_num = NULL)
    {
        $post = $this->input->post();
        if ($post) : 
            $searchstr = $post['searchitem'] ? $post['searchitem'] : 0;
            $data['post'] = $post;
        else :
            $searchstr = 0;
            $data['post'] = NULL;
        endif;

        $pages = $page_num ? (int)$page_num : 1 ;
        $start = NUM_ROWS * ($pages - 1);
        
        $proid = $this->profile_id();
        $level = $this->profile_level();
        $methodname = $this->method_name();
        $data['level'] = $level;
        
        if ($level == 8 || $level == 9) :
            $data['stock_count'] = $this->Core->get_item(0, 1, 0, 0, 0, 0, 0, $searchstr);		
			$data['stock_data'] = $this->Core->get_item(1, 0, $start, NUM_ROWS, 0, 0, 0, $searchstr);		
        endif;

        // PAGINATION		                
        $page_data['base_url'] = WEB.'/stock/index/page/';
        $page_data['total_rows'] = $data['stock_count'];
        $page_data['per_page'] = NUM_ROWS;
        $page_data['uri_segment'] = 4;
        $page_data['num_links'] = NUM_LINKS;
        $page_data['use_page_numbers'] = TRUE;
        $this->pagination->initialize($page_data); 
        
        // TEMPLATE
        $this->load->view('updatestock', $data);
    }
    
    public function updatepend($page = NULL, $page_num = NULL)
    {
        $post = $this->input->post();
        if ($post) : 
            $searchfrom = $post['searchfrom'] ? $post['searchfrom'] : 0;
            $searchto = $post['searchto'] ? $post['searchto'] : 0;
            $data['post'] = $post;
        else :
            $searchfrom = 0;
            $searchto = 0;
            $data['post'] = NULL;
        endif;

        $pages = $page_num ? (int)$page_num : 1 ;
        $start = NUM_ROWS * ($pages - 1);
        
        $proid = $this->profile_id();
        $level = $this->profile_level();
        $data['level'] = $level;
        
        if ($level == 7 || $level == 8 || $level == 9) :// DATA
            $data['pend_count'] = $this->Core->get_pend(0, 1, 0, 0, 0, $searchfrom, $searchto, 2, 1);		
            $data['pend_data'] = $this->Core->get_pend(1, 0, $start, NUM_ROWS, 0, $searchfrom, $searchto, 2, 1);   
        endif;

        // PAGINATION		                
        $page_data['base_url'] = WEB.'/pending/index/page/';
        $page_data['total_rows'] = $data['pend_count'];
        $page_data['per_page'] = NUM_ROWS;
        $page_data['uri_segment'] = 4;
        $page_data['num_links'] = NUM_LINKS;
        $page_data['use_page_numbers'] = TRUE;
        $this->pagination->initialize($page_data); 
        
        // TEMPLATE
        $this->load->view('updatepend', $data);
    }

	public function updateannounce()
	{
		extract($_POST);
        
        $updateannouncement = $this->Core->update_announcement($anntext);
                
        //AUDIT TRAIL
        $log = $this->Core->log_action("UPDATE_ANNOUNCEMENT", 0, $this->profile_id());
        
        echo 'Last Update: '.mdate('%M %j, %Y %g:%i%a', $updateannouncement);
	}
    
	public function addcart()
	{
		extract($_POST);

		$found = FALSE;
		$cart = $this->cart->contents();
		foreach($cart as $items){
			if($id == $items['id']  ){
                                
                $squantity = $items['qty'] + $quantity;
                if ($items['limit']) :
                    if ($squantity >= $items['limit']) :
                        $fquantity = $items['limit'];
                    else :
                        $fquantity = $squantity;
                    endif;
                else :
                    $fquantity = $squantity;
                endif;
                
				$data = array(
	                'rowid' => $items['rowid'],
	                'qty' => $fquantity,
                    'price' => $items['price'],
                    'limit' => $items['limit']
                );
                $this->cart->update($data);     
                $found = TRUE;
             }           
		}   

		if($found == FALSE){
            
            $squantity = $quantity;
            if ($limit) :
                if ($squantity >= $limit) :
                    $fquantity = $limit;
                else :
                    $fquantity = $squantity;
                endif;
            else :
                $fquantity = $squantity;
            endif;
            
		    $data = array(
	           'id'     	=> $id,
	           'qty'    	=> $fquantity,
	           'price'   	=> $price,
	           'limit'   	=> $limit,
	           'name'   	=> $name,
	           'options' 	=> array('unit' => $unit)
                
	        );
		    $this->cart->insert($data); 
		}

        //AUDIT TRAIL
        $log = $this->Core->log_action("ADD_CART", $id, $this->profile_id());
        
		$this->Core->db_cart();
		echo $this->Core->do_cart();
	}

	public function minuscart()
	{
		extract($_POST);

		$found = FALSE;
		$cart = $this->cart->contents();
		foreach($cart as $items){
			if($id == $items['id']  ){
				$data = array(
	                'rowid'    => $items['rowid'],
	                'qty'      => $items['qty'] - $quantity
                );
                $this->cart->update($data);     
                $found = TRUE;
             }           
		}   

        //AUDIT TRAIL
        $log = $this->Core->log_action("MINUS_CART", $id, $this->profile_id());

		$this->Core->db_cart();
		echo $this->Core->do_cart();
	}

	public function removecart()
	{
		extract($_POST);

		$found = FALSE;
		$cart = $this->cart->contents();
		foreach($cart as $items){
			if($id == $items['id']  ){
				$data = array(
	                'rowid' => $items['rowid'],
	                'qty' => 0
                );
                $this->cart->update($data);     
                $found = TRUE;
             }           
		}   

        //AUDIT TRAIL
        $log = $this->Core->log_action("REMOVE_CART", $id, $this->profile_id());

		$this->Core->db_cart();
		echo $this->Core->do_cart();
	}

	public function clearcart()
	{
		$clear_cart = $this->cart->destroy();
		
        //AUDIT TRAIL
        $log = $this->Core->log_action("CLEAR_CART", 0, $this->profile_id());
        
		$cart_item = "<center><br><b>Requisition Slip is empty</b><br><br><br>Click <span class=\"smallbtn\">Add to Order <i class=\"fa fa-caret-right\"></i></span> on the left to place an order</center>";		
		$this->Core->db_cart();

		echo $cart_item;
	}

	public function docart()
	{	
		$this->Core->db_cart();
		echo $this->Core->do_cart();
	}

	public function reviewcart()
	{
		echo $this->Core->review_cart();
	}

	public function processcart()
	{
        extract($_POST);
        
        $order_array = $this->cart->contents();
        $order_detail = "";
        foreach ($order_array as $orderrow) :
            $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";
        endforeach;
        
        $srf_level = $this->profile_level() == 3 ? 1 : 0;
        
        $newtransid = $this->Core->process_cart($price, $reqremark, $srf_level);
        if ($newtransid) :
            $trans_data = $this->Core->get_trans(0, 0, 0, 0, $newtransid, 0, 0, 0, 0);
            
            if ($this->profile_id()) :
                $approver_data = $this->Core->get_users_approver($this->profile_id());
            else :
                $approver_data = NULL;
            endif;
        
            //var_dump($approver_data);
        
            $mailink = WEB;
        
            if ($approver_data) :
                foreach ($approver_data as $apprdata) :
                    $approver_info = $this->Core->get_user(0, 0, 0, 0, $apprdata['appr_approverid'], 0, 2, 0);

                    /*ini_set("SMTP","mail.megaworldcorp.com");                     
                    ini_set("smtp_port","25");                     
                    ini_set("sendmail_from","pmis@megaworldcorp.com");*/

                    $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction for Approval</span><br><br>Hi ".$approver_info['user_fullname'].",<br><br>";
                    $message2 .= "New request has been subject to you for approval from ".$this->profile_fullname().".<br><br>";
                    $message2 .= $order_detail."<br><br>";    
                    $message2 .= "<b>Transaction ID:</b> ".$trans_data['trans_dateid']."<br><br>";    
                    if ($reqremark) : $message2 .= "<b>Requestor's Remark:</b> ".$reqremark."<br><br>"; endif;
                    $message2 .= "Click <a href='".$mailink."'>here</a> to check<br><br>";    
                    $message2 .= "Thanks,<br>";
                    $message2 .= "iRS Admin";
                    $message2 .= "<hr />".MAILFOOT."</div>";

                    $headers = "From: noreply@megaworldcorp.com\r\n";
                    $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    if ($this->profile_id() != 3) :
                    $sendmail = mail($approver_info['user_email'], "iRS Transaction# ".$trans_data['trans_dateid']." for Approval", $message2, $headers);
                    endif;
                endforeach;
            endif;
        endif;
		echo $newtransid;
	}

	public function deletetrans()
	{	
		$id = $_POST['transid'];
        $dateid = $_POST['transdate'];        
        
        //AUDIT TRAIL
        $log = $this->Core->log_action("TRANSACTION_CANCEL", $dateid, $this->profile_id());

		$delete_trans = $this->Core->trans_action(NULL, 'delete', $id);
	}

	public function pendtrans()
	{	
		$iddate = $_POST['transdate'];
        $id = $_POST['transid'];
        
        $trans_data = $this->Core->get_trans(0, 0, 0, 0, $id, 0, 0, 0, 0);
        $trans_order = html_entity_decode($trans_data['trans_order'], ENT_QUOTES);
        $trans_order = unserialize($trans_order);
        
        $pend_count = 0;
        
        foreach($trans_order as $t_orders) :
            //ADD PENDING DATA        
            $pend['item_id'] = $trans_order[$t_orders['rowid']]['id'];
            $pend['qty'] = $trans_order[$t_orders['rowid']]['qty'];
            $pend['item'] = $trans_order[$t_orders['rowid']]['name']; 
            $pend['unit'] = $trans_order[$t_orders['rowid']]['options']['unit']; 
        
            $add_pending = $this->Core->add_pending($pend, $id, $trans_data['trans_uid']);
        
            $log = $this->Core->log_action("ITEM_PEND", $pend['item_id'], $this->profile_id());
            if (!$add_pending) : $pend_count = 1; endif;
        endforeach;
        
        if ($pend_count == 0) :
            $pend_trans = $this->Core->trans_action(NULL, 'pending', $id);//AUDIT TRAIL
            $log = $this->Core->log_action("TRANSACTION_PEND", $iddate, $this->profile_id());
            
            $order = $this->Core->get_trans(0, 0, 0, 0, $id, 0, 0, 0, 0);
            $order_array = html_entity_decode($order['trans_order'], ENT_QUOTES);
            $order_array = unserialize($order_array);
            
            $order_detail = "";
            foreach ($order_array as $orderrow) :
                $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";
            endforeach;
            
            $user_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_uid'], 0, 0, 0);                  
                    
            /*ini_set("SMTP","mail.megaworldcorp.com");                     
            ini_set("smtp_port","25");                     
            ini_set("sendmail_from","pmis@megaworldcorp.com");*/
        
            $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Cancelled Transaction</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
            $message .= "Your transaction request no. ".$iddate." has been cancelled due to stock shortage.<br><br>";
            $message .= "Those item will tag on our pending list and subject for ordering<br><br>";
            $message .= "Thanks,<br>";
            $message .= "iRS Admin";
            $message .= "<hr />".MAILFOOT."</div>";
    
            $headers = "From: noreply@megaworldcorp.com\r\n";
            $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    
            if ($user_info) :
                $sendmail = mail($user_info['user_email'], "iRS Transaction# ".$iddate." Cancelled", $message, $headers);
            endif;
        
        endif;
	}
    
    public function gettrans()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        
        $data['transdata'] = $this->Core->get_trans(0, 0, 0, 0, $post['transid'], 0, 0, 0, 0);
        
        // TEMPLATE
        $this->load->view('transdata', $data);
    }
    
    public function getapptrans()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        
        $data['transdata'] = $this->Core->get_trans(0, 0, 0, 0, $post['transid'], 0, 0, 0, 0);
        
        // TEMPLATE
        $this->load->view('transapp', $data);
    }
    
    public function getappini()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        
        $data['transdata'] = $this->Core->get_trans(0, 0, 0, 0, $post['transid'], 0, 0, 0, 0);
        
        // TEMPLATE
        $this->load->view('transappini', $data);
    }
    
    public function updateovertrans()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        
        $postvalue = array();
        
        $transid = $post['transid'];
        $transiddate = $post['transdate'];
        $rowid = explode(',', $post['rowid']);
        $val = explode(',', $post['val']);
        
        $key = 0;
        foreach($rowid as $value) :
            if ($value) :
                $postvalue[$key]['rowid'] = $value;
                $key++;
            endif;
        endforeach;
        $key = 0;
        foreach($val as $value) :
            if ($value != 123456789) :
                $postvalue[$key]['value'] = $value;
                $key++;
            endif;
        endforeach;
        
        //var_dump($postvalue);        
        //var_dump($transid);
        
        $order = $this->Core->get_trans(0, 0, 0, 0, $transid, 0, 0, 0, 0);
        $order_array = html_entity_decode($order['trans_order'], ENT_QUOTES);
        $order_array = unserialize($order_array);
        
        $order_detail = "";
        foreach ($order_array as $orderrow) :
            $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";
        endforeach;
        
        // UPDATE THE QUANTITY TO BE CLOSE AND ADD PENDING TRANSACTION
        $pendaddid = $this->Core->trans_update($postvalue, $transid);        
        
        if ($pendaddid) :
        
            $porder = $this->Core->get_trans(0, 0, 0, 0, $pendaddid, 0, 0, 0, 0);
        
            $pendaddiddate = mdate("%Y", $porder['trans_date']).'-'.$porder['trans_date'];
        
            $porder_array1 = html_entity_decode($porder['trans_originorder'], ENT_QUOTES);
            $porder_array1 = unserialize($porder_array1);

            $porder_detail1 = "";
            foreach ($porder_array1 as $porderrow1) :
                $porder_detail1 .= $porderrow1['qty']." ".$porderrow1['options']['unit']." - ".$porderrow1['name']."<br />";
            endforeach;
        
            $porder_array2 = html_entity_decode($porder['trans_order'], ENT_QUOTES);
            $porder_array2 = unserialize($porder_array2);

            $porder_detail2 = "";
            foreach ($porder_array2 as $porderrow2) :
                $porder_detail2 .= $porderrow2['qty']." ".$porderrow2['options']['unit']." - ".$porderrow2['name']."<br />";
            endforeach;
        
            $user_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_uid'], 0, 0, 0);                  

            /*ini_set("SMTP","mail.megaworldcorp.com");                     
            ini_set("smtp_port","25");                     
            ini_set("sendmail_from","pmis@megaworldcorp.com");*/

            $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Pending Transaction</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
            $message .= "One or more item on your transaction request no. ".$transiddate." has been set to pending due to stock shortage from...<br><br>";
            $message .= $porder_detail1."<br>";  
            $message .= "Item/s to be pend...<br><br>";
            $message .= $porder_detail2."<br>";  
            $message .= "Set as <b>transaction ID number ".$pendaddiddate."</b>. Those item will subject for ordering<br><br>";
            $message .= "Thanks,<br>";
            $message .= "iRS Admin";
            $message .= "<hr />".MAILFOOT."</div>";

            $headers = "From: noreply@megaworldcorp.com\r\n";
            $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if ($user_info) :
                $sendmail = mail($user_info['user_email'], "iRS Pending# ".$pendaddiddate." was created from Transaction# ".$transiddate, $message, $headers);
            endif;
        
        endif;
        
    }
    
    public function approvetrans()
	{	
        $post = $this->input->post();        		
        $userid = $this->session->userdata('session_uid');
        
        $id = $post['transid'];
        $iddate = $post['transdate'];
        $approve = $post['approve'];
        
        $postvalue = array();
        
        $transid = $post['transid'];
        $rowid = explode(',', $post['rowid']);
        $val = explode(',', $post['val']);
        
        $key = 0;
        foreach($rowid as $value) :
            if ($value) :
                $postvalue[$key]['rowid'] = $value;
                $key++;
            endif;
        endforeach;
        $key = 0;
        foreach($val as $value) :
            if ($value != 123456789) :
                $postvalue[$key]['value'] = $value;
                $key++;
            endif;
        endforeach;
        
        // UPDATE THE QUANTITY TO BE CLOSE AND ADD PENDING TRANSACTION
        $edit_trans = $this->Core->trans_edit($postvalue, $transid);     
        
        //var_dump($approve);
        
        $trans_approve = $this->Core->trans_action($post, 'approve', $id, $this->profile_id());
        
        $order = $this->Core->get_trans(0, 0, 0, 0, $id, 0, 0, 0, 0);
        $order_array = html_entity_decode($order['trans_order'], ENT_QUOTES);
        $order_array = unserialize($order_array);
        $order_array2 = html_entity_decode($order['trans_originorder'], ENT_QUOTES);
        $order_array2 = unserialize($order_array2);
        
        $exceed_count = 0;
        $order_detail = "";
        foreach ($order_array as $orderrow) :
            $order_detail .= $orderrow['qty']." ".$orderrow['options']['unit']." - ".$orderrow['name']."<br />";
            $check_exceed = $this->Core->check_if_exceed($orderrow['id'], $orderrow['qty']);
            if ($check_exceed) $exceed_count++;
        endforeach;
        
        $order_detail2 = "";
        foreach ($order_array2 as $orderrow2) :
            $order_detail2 .= $orderrow2['qty']." ".$orderrow2['options']['unit']." - ".$orderrow2['name']."<br />";
        endforeach; 
        
        if ($approve == 2) :
            if ($return == 1) :
                //AUDIT TRAIL
                $log = $this->Core->log_action("TRANSACTION_RETURN", $iddate, $this->profile_id());
            else :        
                //AUDIT TRAIL
                $log = $this->Core->log_action("TRANSACTION_ENDORSE", $iddate, $this->profile_id());
            
                $mailink = WEB;
            
                $user_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_uid'], 0, 0, 0);                  
                
                /*ini_set("SMTP","mail.megaworldcorp.com");                     
                ini_set("smtp_port","25");                     
                ini_set("sendmail_from","pmis@megaworldcorp.com");*/
            
                $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction Approved</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
                $message .= "Your request has been approved and endorsed to supplier.<br><br>";
                $message .= $order_detail."<br><br>";  
                if ($order['trans_appremarks']) $message .= "<b>Approver's Remarks:</b> ".$order['trans_appremarks']."<br><br>";
                $message .= "Click <a href='".$mailink."'>here</a> to check<br><br>";      
                $message .= "Thanks,<br>";
                $message .= "iRS Admin";
                $message .= "<hr />".MAILFOOT."</div>";
        
                $headers = "From: noreply@megaworldcorp.com\r\n";
                $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
                if ($user_info) :
                    $sendmail = mail($user_info['user_email'], "iRS Transaction# ".$iddate." Approved", $message, $headers);
                endif;
                
                $admin_info = $this->Core->get_user(1, 0, 0, 0, 0, 0, 8, 0);
            
                $mailink2 = WEB;
            
                if ($admin_info) :
                    foreach ($admin_info as $adminfo) : 

                        $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction Endorsed</span><br><br>Hi ".$adminfo->user_fullname.",<br><br>";
                        $message2 .= "New request has been approved and endorse to you.<br><br>";
                        $message2 .= $order_detail."<br><br>"; 
                        if ($order['trans_appremarks']) $message .= "<b>Approver's Remarks:</b> ".$order['trans_appremarks']."<br><br>";   
                        $message2 .= "Click <a href='".$mailink."'>here</a> to check<br><br>";    
                        $message2 .= "Thanks,<br>";
                        $message2 .= "iRS Admin";
                        $message2 .= "<hr />".MAILFOOT."</div>";

                        $headers = "From: noreply@megaworldcorp.com\r\n";
                        $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                        $sendmail = mail($adminfo->user_email, "iRS Transaction# ".$iddate." Endorsed", $message2, $headers);

                    endforeach;
                endif;
            endif;    
        
        elseif ($approve == 3) :
            //AUDIT TRAIL
            $log = $this->Core->log_action("TRANSACTION_ADMIN_APPROVE", $iddate, $this->profile_id());            
        
            if ($exceed_count == 0) :
        
                foreach ($order_array as $orderrow) :
                    $adapp_item = $this->Core->item_adapp($orderrow['id'], $orderrow['qty']);                     
                endforeach;
            
                $mailink = WEB;
        
                $user_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_uid'], 0, 0, 0);                  
                
                /*ini_set("SMTP","mail.megaworldcorp.com");                     
                ini_set("smtp_port","25");                     
                ini_set("sendmail_from","pmis@megaworldcorp.com");*/
        
                /*if ($order['trans_adjust'] == 2) :
                    $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS - Your Request is Adjusted and Ready for Pickup</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
                    $message2 .= "Your transaction request no. ".$iddate." has been adjusted by admin and ready for pickup<br><br>";
                    $message2 .= "The transaction has been adjusted from...<br><br>";
                    $message2 .= $order_detail2."<br>";  
                    $message2 .= "To...<br><br>";
                    $message2 .= $order_detail."<br>";  
                    $message2 .= "Click <a href='".$mailink."'>here</a> to check<br><br>";    
                    if ($order['trans_remarks']) $message2 .= "<b>Remarks:</b> ".$order['trans_remarks']."<br><br>";
                    $message2 .= "Thanks,<br>";
                    $message2 .= "iRS Admin";
                    $message2 .= "<hr />".MAILFOOT."</div>";*/
                if ($order['trans_adjust'] == 1) :
                    $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS - Your Request is Adjusted and Ready for Pickup</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
                    $message2 .= "Your transaction request no. ".$iddate." has been adjusted by admin and ready for pickup<br><br>";
                    $message2 .= "The transaction has been adjusted due to stock shortage from...<br><br>";
                    $message2 .= $order_detail2."<br>";  
                    $message2 .= "To...<br><br>";
                    $message2 .= $order_detail."<br>";  
                    $message2 .= "Those adjusted item will tag on our pending list and subject for ordering<br><br>";
                    $message2 .= "Click <a href='".$mailink."'>here</a> to check<br><br>";    
                    if ($order['trans_remarks']) $message2 .= "<b>Remarks:</b> ".$order['trans_remarks']."<br><br>";
                    $message2 .= "Thanks,<br>";
                    $message2 .= "iRS Admin";
                    $message2 .= "<hr />".MAILFOOT."</div>";
                else :
                    $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS - Your Request is Ready for Pickup</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
                    $message2 .= "Your transaction request no. ".$iddate." has been approved by admin and ready for pickup<br><br>";
                    $message2 .= $order_detail."<br>";    
                    $message2 .= "Click <a href='".$mailink."'>here</a> to check<br><br>";    
                    if ($order['trans_remarks']) $message2 .= "<b>Remarks:</b> ".$order['trans_remarks']."<br><br>";
                    $message2 .= "Thanks,<br>";
                    $message2 .= "iRS Admin";
                    $message2 .= "<hr />".MAILFOOT."</div>";
                endif;
        
                $headers2 = "From: noreply@megaworldcorp.com\r\n";
                $headers2 .= "Reply-To: noreply@megaworldcorp.com\r\n";
                $headers2 .= "MIME-Version: 1.0\r\n";
                $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
                if ($user_info) :
                    $sendmail = mail($user_info['user_email'], "iRS - Your Request# ".$iddate." is Ready for Pickup", $message2, $headers2);
                endif;
                //$sendmail3 = mail("jisleta@megaworldcorp.com", "iRS - Your Request# ".$iddate." is Ready for Pickup", $message2, $headers2);
            
                $asst_info = $this->Core->get_user(1, 0, 0, 0, 0, 0, 7, 0);
            
                if ($asst_info) :
                    foreach ($asst_info as $assinfo) :   

                        $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction Endorse for Release</span><br><br>Hi ".$assinfo->user_fullname.",<br><br>";
                        $message .= "New request has been approved and endorsed to you for release.<br><br>";
                        $message .= $order_detail."<br>"; 
                        $message .= "<b>Remarks for requestor:</b> ".$post['remarks']."<br><br>"; 
                        $message .= "Click <a href='".$mailink."'>here</a> to check<br><br>";      
                        $message .= "Thanks,<br>";
                        $message .= "iRS Admin";
                        $message .= "<hr />".MAILFOOT."</div>";

                        $headers = "From: noreply@megaworldcorp.com\r\n";
                        $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                        $sendmail = mail($assinfo->user_email, "iRS Transaction# ".$iddate." Endorse for Release", $message, $headers);
                    endforeach;
                    //$sendmail3 = mail("jisleta@megaworldcorp.com", "iRS Transaction# ".$iddate." Endorse for Release", $message, $headers);
                endif;
            else :
                echo "FULL";
            endif;
            
        elseif ($approve == 4) :
            //AUDIT TRAIL
            $log = $this->Core->log_action("TRANSACTION_PENDING", $iddate, $this->profile_id());
        
        elseif ($approve == 5) :
            //AUDIT TRAIL
            $log = $this->Core->log_action("TRANSACTION_RELEASE", $iddate, $this->profile_id());        
                        
            if ($exceed_count == 0) :
                foreach ($order_array as $orderrow) :
                    $release_item = $this->Core->item_release($orderrow['id'], $orderrow['qty']);                
                    // item log
                    $this->Core->ilog_action($orderrow['id'], 'REQUISITION RELEASE', $orderrow['qty'], $userid);
                    $this->Core->ilog_action($orderrow['id'], 'REQUESTOR ITEM RELEASE', $orderrow['qty'], $order['trans_uid']);
                endforeach;
        
                $mailink = WEB;
        
                $user_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_uid'], 0, 0, 0); 
        
                /*ini_set("SMTP","mail.megaworldcorp.com");                     
                ini_set("smtp_port","25");                     
                ini_set("sendmail_from","pmis@megaworldcorp.com");*/
        
                $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction Release</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
                $message .= "Your request has been released<br><br>";
                $message .= $order_detail."<br>";    
                $message .= "Please confirm if you received the items by <a href='".$mailink."'>closing</a> the request.<br><br>";
                $message .= "Thanks,<br>";
                $message .= "iRS Admin";
                $message .= "<hr />".MAILFOOT."</div>";
        
                $headers = "From: noreply@megaworldcorp.com\r\n";
                $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
                if ($user_info) :
                    $sendmail = mail($user_info['user_email'], "iRS Transaction# ".$iddate." Released", $message, $headers);
                endif;
        
                if ($this->profile_id()) :
                    $approver_data = $this->Core->get_users_approver($this->profile_id());
                else :
                    $approver_data = NULL;
                endif;
        
                if ($approver_data) :
                    foreach ($approver_data as $apprdata) :
                        $approver_info = $this->Core->get_user(0, 0, 0, 0, $apprdata['appr_approverid'], 0, 2, 0);         

                        $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS - Request has been Released</span><br><br>Hi ".$approver_info['user_fullname'].",<br><br>";
                        $message2 .= "Request of ".$user_info['user_fullname']." has been released.<br><br>";
                        $message2 .= $order_detail."<br><br>";    
                        $message2 .= "Click <a href='".$mailink."/irs/approved'>here</a> to check<br><br>";    
                        $message2 .= "Thanks,<br>";
                        $message2 .= "iRS Admin";
                        $message2 .= "<hr />".MAILFOOT."</div>";

                        $headers = "From: noreply@megaworldcorp.com\r\n";
                        $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                        $headers .= "MIME-Version: 1.0\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                        $sendmail = mail($approver_info['user_email'], "iRS - Request# ".$iddate." has been Released", $message2, $headers);
                    endforeach;
                endif;
            else :
                echo "FULL";
            endif;
        elseif ($approve == 8) :
            //AUDIT TRAIL
            $log = $this->Core->log_action("TRANSACTION_DISAPPROVE", $iddate, $this->profile_id());
        
            $mailink = WEB;
            
            $user_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_uid'], 0, 0, 0);                  
        
            $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction Reject</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
            $message .= "Your request has been disapproved by your approver.<br><br>";
            $message .= $order_detail."<br>";    
            $message .= "Go to: ".$mailink."<br><br>";    
            $message .= "Thanks,<br>";
            $message .= "iRS Admin";
            $message .= "<hr />".MAILFOOT."</div>";
        
            $headers = "From: noreply@megaworldcorp.com\r\n";
            $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            
            $sendmail = mail($user_info['user_email'], "iRoom Transaction# ".$iddate." Disapproved", $message, $headers);
        elseif ($approve == 9) :
            //AUDIT TRAIL
            $log = $this->Core->log_action("TRANSACTION_CLOSE", $iddate, $this->profile_id());
        
            $mailink = WEB.'/irs/close';
            
            if ($order['trans_releaseuser']) :
                $admin_info = $this->Core->get_user(0, 0, 0, 0, $order['trans_releaseuser'], 0, 0, 0);
                
                /*ini_set("SMTP","mail.megaworldcorp.com");                     
                ini_set("smtp_port","25");                     
                ini_set("sendmail_from","pmis@megaworldcorp.com");*/
            
                $message2 = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Transaction Closed</span><br><br>Hi ".$admin_info['user_fullname'].",<br><br>";
                $message2 .= "The request has been closed.<br><br>";
                $message2 .= $order_detail."<br>";    
                $message2 .= "Go to: ".$mailink."<br><br>";    
                $message2 .= "Thanks,<br>";
                $message2 .= "iRS Admin";
                $message2 .= "<hr />".MAILFOOT."</div>";
        
                $headers = "From: noreply@megaworldcorp.com\r\n";
                $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                
                if ($user_info) :
                    $sendmail = mail($user_info['user_email'], "iRS Transaction# ".$iddate." Close", $message, $headers);
                endif;
            endif;
        endif;
	}
    
    public function getitem()
    {
        $post = $this->input->post();
        $data['post'] = $post;
        
        $data['itemdata'] = $this->Core->get_item(0, 0, 0, 0, 0, $post['itemid'], 0, 0, 0);
        
        // TEMPLATE
        $this->load->view('itemdata', $data);
    }
    
    public function edititem()
    {
        $id = $this->input->post('itemid');
        $post = $this->input->post(); 
        
        $edititem = $this->Core->item_action($post, 'update_proc', $id);           
        $price = number_format($post['price'], 2);
        
        echo $price;
    }
    
    public function addprocure()
    {
        $post = $this->input->post(); 
        $post['userid'] = $this->profile_id();
        
        $pcureadd = $this->Core->procure_action($post, 'add');           
        $data['pcuredata'] = $this->Core->get_procure(1, 0, 0, 0, $post['itemid'], 0, 2, 0);
        $data['itemid'] = $post['itemid'];
        
        // TEMPLATE
        $this->load->view('pcuredata', $data);
    }
    
    public function delprocure()
    {
        $post = $this->input->post(); 
        $post['userid'] = $this->profile_id();
        
        $pcureadd = $this->Core->procure_action($post, 'delete', $post['pcureid']);           
        $data['pcuredata'] = $this->Core->get_procure(1, 0, 0, 0, $post['itemid'], 0, 2, 0);
        $data['itemid'] = $post['itemid'];
        
        // TEMPLATE
        $this->load->view('pcuredata', $data);
    }
    
    
    public function plusstock()
	{
		$id = $this->input->post('itemid');
        $post = $this->input->post();
        $userid = $this->session->userdata('session_uid');

        $actual = $this->Core->item_action($post, 'plus_qty', $id);             
        //AUDIT TRAIL
        $log = $this->Core->log_action("STOCK_PLUS", $id, $this->profile_id());
        // ITEM LOG
        $this->Core->ilog_action($id, 'STOCK PLUS', $post['quantity'], $userid);     
        
        echo $actual;
        
	}
    
    public function minusstock()
	{
		$id = $this->input->post('itemid');
        $post = $this->input->post();
        $userid = $this->session->userdata('session_uid');
        
        $subtract = $post['actual'] - $post['quantity'];        
        $actual = $this->Core->item_action($post, 'minus_qty', $id);     
                
        if ($subtract < 0) :
            if ($post['actual'] > 0) :    
                //AUDIT TRAIL
                $log = $this->Core->log_action("STOCK_MINUS", $id, $this->profile_id());
                // ITEM LOG
                $this->Core->ilog_action($id, 'STOCK MINUS', $post['actual'], $userid);   
            endif;
        else:
            //AUDIT TRAIL
            $log = $this->Core->log_action("STOCK_MINUS", $id, $this->profile_id());
            // ITEM LOG
            $this->Core->ilog_action($id, 'STOCK MINUS', $post['quantity'], $userid);   
        endif;
        
        
        echo $actual;
	}

	public function statuspend()
	{
		$id = $this->input->post('pendid');
		$post = $this->input->post();

		$pend_solve = $this->Core->pend_action($post, 'status', $id);
        
		//AUDIT TRAIL
		$log = $this->Core->log_action("PEND_".($pend_solve == 2 ? 'SOLVE' : 'UNSOLVE'), $id, $this->profile_id());

		echo $pend_solve == 2 ? '<a title="Click to mark unsolve" class="statusPend cursorpoint underlined" attribute="'.$id.'" attribute2="'.$pend_solve.'"><i class="fa fa-check fa-lg greentext"></i></a>' : '<a title="Click to mark solve" class="statusPend cursorpoint underlined" attribute="'.$id.'" attribute2="'.$pend_solve.'"><i class="fa fa-times fa-lg redtext"></i></a>';
	}

	public function statusitem()
	{
		$id = $this->input->post('itemid');
		$post = $this->input->post();

		$item_approve = $this->Core->item_action($post, 'status', $id);
        
		//AUDIT TRAIL
		$log = $this->Core->log_action("ITEM_".($item_approve == 2 ? 'DISPLAY' : 'UNDISPLAY'), $id, $this->profile_id());

		echo $item_approve == 2 ? '<a title="Click to deactivate Item ID #'.$id.'" class="statusItem cursorpoint underlined" attribute="'.$id.'" attribute2="'.$item_approve.'"><i class="fa fa-check fa-lg greentext"></i></a>' : '<a title="Click to activate Item ID #'.$id.'" class="statusItem cursorpoint underlined" attribute="'.$id.'" attribute2="'.$item_approve.'"><i class="fa fa-times fa-lg redtext"></i></a>';
	}

	public function statuscat()
	{
		$id = $this->input->post('catid');
		$post = $this->input->post();

		$cat_approve = $this->Core->cat_action($post, 'status', $id);
        
		//AUDIT TRAIL
		$log = $this->Core->log_action("CAT_".($cat_approve == 2 ? 'DISPLAY' : 'UNDISPLAY'), $id, $this->profile_id());

		echo $cat_approve == 2 ? '<a title="Click to deactivate Category ID #'.$id.'" class="statusCat cursorpoint underlined" attribute="'.$id.'" attribute2="'.$cat_approve.'"><i class="fa fa-check fa-lg greentext"></i></a>' : '<a title="Click to activate Category ID #'.$id.'" class="statusCat cursorpoint underlined" attribute="'.$id.'" attribute2="'.$cat_approve.'"><i class="fa fa-times fa-lg redtext"></i></a>';
	}
    
    public function printinv()
    {
        $post = $this->input->post();
        $searchval = $post['search'] ? $post['search'] : NULL;
        $searchurl = $post['search'] ? '_'.$post['search'] : ''; 
        $caturl = $post['cat'] != 0 ? '_cat'.$post['cat'] : ''; 
	    
	    //Set folder to save PDF to
	    $this->html2pdf->folder('./assets/pdfs/');
	    
	    //Set the filename to save/download as
	    $this->html2pdf->filename('inventory'.$searchurl.''.$caturl.'.pdf');
	    
	    //Set the paper defaults
	    $this->html2pdf->paper('a4', 'landscape');
        
        $inventory_data = $this->Core->get_item(1, 0, 0, 0, $post['cat'], 0, 0, $searchval);
        
        $invdata = '';
        $invdata .= '<table class="tdatareport">                            
                        <tr>
                            <th width="30px">ID</th>
                            <th width="200px">Category</th>
                            <th width="200px">Item Name</th>
                            <th width="240px">Description</th>              
                            <th width="90px">Quantity</th>
                            <th width="80px">Unit</th>
                            <!--th width="90px">Price per Unit</th>
                            <th width="90px">Total Price</th-->
                        </tr>';
        if ($inventory_data) :
            $total_price = 0;
            foreach ($inventory_data as $row) :
                $invdata .= '<tr>
                                <td width="30px">'.$row->item_id.'</td>
                                <td width="200px">'.$row->cat_name.'</td>
                                <td width="200px">'.$row->item_name.'</td>
                                <td width="240px">'.$row->item_desc.'</td>
                                <td width="90px">'.$row->item_quantity.'</td>
                                <td width="80px">'.$row->unit_name.'</td>
                                <!--td width="90px" class="righttalign">'.number_format($row->item_price, 2).'</td>
                                <td width="90px" class="righttalign">'.number_format($row->item_price * $row->item_quantity, 2).'</td-->
                            </tr>';
                $total_price += floatval($row->item_price * $row->item_quantity);
            endforeach;
        
        else :
            $invdata .= '<tr>
                             <td colspan="6" class="centertalign">No in and out record found for this day</td>
                        </tr>';
        endif;
                        
        $invdata .= '<tr>
                        <td colspan="6" class="righttalign">&nbsp;</td>
                        <!--td colspan="2" class="righttalign bold">'.number_format($total_price, 2).'</td-->
                    </tr>
                </table>';
	    
	    $data = array(
	    	'title' => 'Inventory Report',
	    	'message' => $invdata
	    );
	    
	    //Load html view
	    $this->html2pdf->html($this->load->view('reports_data', $data, true));
	    
	    if($this->html2pdf->create('save')) {
            //AUDIT TRAIL
            $log = $this->Core->log_action("INVENTORY_REPORT_GENERATE", 0, $this->profile_id());
            echo 1;
	    }
    }
    
    public function csvinv()
    {
        $get = $this->input->get();
        $searchval = $get['search'] ? $get['search'] : NULL;
        $searchurl = $get['search'] ? '_'.$get['search'] : ''; 
        $caturl = $get['cat'] != 0 ? '_cat'.$get['cat'] : ''; 
        
        $xlscontent = '';
        
        $inventory_data = $this->Core->get_item(1, 0, 0, 0, $get['cat'], 0, 0, $searchval);
        
        if ($inventory_data) :
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;Filename=inv'.$searchurl.$caturl.'.xls');

            $xlscontent .= '<html>';
            $xlscontent .= '<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">';
            $xlscontent .= '<body>';

            $xlscontent .= '<table>';
            $xlscontent .= '<tr><td colspan="6"><b>Inventory Report '.$fromto.'</b></td>';
            $xlscontent .= '<tr style="font-weight: bold"><td>ID</td><td>Category</td><td>Item Name</td><td>Description</td><td>Quantity</td><td>Unit</td></tr>';
        
            foreach ($inventory_data as $row) :
                $xlscontent .= '<tr><td>'.$row->item_id.'</td><td>'.$row->cat_name.'</td><td>'.$row->item_name.'</td><td>'.$row->item_desc.'</td><td>'.$row->item_quantity.'</td><td>'.$row->unit_name.'</td></tr>';
            endforeach;
                
            $xlscontent .= '</table>';
            $xlscontent .= '</body>';
            $xlscontent .= '</html>';

            echo $xlscontent;
            
            //AUDIT TRAIL
            $log = $this->Core->log_action("INVENTORY_CSV_GENERATE", 0, $this->profile_id());
        
        endif;
    }
    
    public function printinout()
    {
        $post = $this->input->post();
	    
	    //Set folder to save PDF to
	    $this->html2pdf->folder('./assets/pdfs/');
	    
	    //Set the filename to save/download as
	    $this->html2pdf->filename('inout_'.$post['from'].'_'.$post['to'].'.pdf');
	    
	    //Set the paper defaults
	    $this->html2pdf->paper('a4', 'landscape');
        
        $inout_data = $this->Core->get_item_from_log($post['from'], $post['to'], 0, 1);	
        
        $iodata = '';
        $iodata .= '<table class="tdatareport">                            
                        <tr>
                            <th width="30px">ID</th>
                            <th width="120px">Item Name</th>
                            <th width="220px">Description</th>              
                            <th width="75px">Beginning Balance</th>              
                            <th width="75px">In</th>
                            <th width="75px">Out</th>
                            <th width="75px">Difference</th>           
                            <th width="75px">Ending Balance</th>   
                            <th width="75px">Current Quantity</th>
                        </tr>';
        if ($inout_data) :
        foreach ($inout_data as $row) :
            $iodata .= '<tr>';
            $stock_in = $this->Core->get_stock('IN', $row->item_id, $post['from'], $post['to'], 1);
            $stock_out = $this->Core->get_stock('OUT', $row->item_id, $post['from'], $post['to'], 1);
            //var_dump($stock_in);
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
            
            $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', mdate('%Y-%m-%d', strtotime($post['from']) - 86400), 1);
            $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', mdate('%Y-%m-%d', strtotime($post['from']) - 86400), 1);
            $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
            $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
            $b_diff = $b_in - $b_out; 
            $e_diff = $b_diff + $s_diff;  
        
            $iodata .= '<td width="30px">'.$row->item_id.'</td>
                        <td width="120px">'.$row->item_name.'</td>
                        <td width="220px">'.$row->item_desc.'</td>
                        <td width="75px" class="centertalign">'.$b_diff.'</td>
                        <td width="75px" class="centertalign">'.$s_in.'</td>
                        <td width="75px" class="centertalign">'.$s_out.'</td>
                        <td width="75px" class="centertalign">'.$diff_val.'</td>
                        <td width="75px" class="centertalign">'.$e_diff.'</td>
                        <td width="75px" class="centertalign">'.$row->item_quantity.'</td>
                    </tr>';
        endforeach;
        else :
            $iodata .= '<tr>
                             <td colspan="9" class="centertalign">No in and out record found for this day</td>
                        </tr>';
        endif;
                        
        $iodata .= '<tr>
                       <td colspan="9" class="righttalign">&nbsp;</td>
                     </tr>
                </table>';
	    
	    $data = array(
	    	'title' => 'In and Out Report for a month of '.mdate("%M %j, %Y", strtotime($post['from'])).' to '.mdate("%M %j, %Y", strtotime($post['to'])),
	    	'message' => $iodata
	    );
	    
	    //Load html view
	    $this->html2pdf->html($this->load->view('reports_data', $data, true));
	    
	    if($this->html2pdf->create('save')) {
            //AUDIT TRAIL
            //$log = $this->Core->log_action("IN_OUT_REPORT_GENERATE", 0, $this->profile_id());
            echo 'PDF saved';
	    }
    }
    
    public function csvinout()
    {
        $get = $this->input->get();
        
        $xlscontent = '';
        
        $inout_data = $this->Core->get_item_from_log($get['from'], $get['to'], 0, 1);	
        
        if ($inout_data) :
        
            $fromdate = mdate('%M %j %Y', strtotime($get['from']));
            $todate = mdate('%M %j %Y', strtotime($get['to']));
            $fromto = $get['from'] ? '('.$fromdate.' to '.$todate.')' : '';
        
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;Filename=inout'.$get['from'].''.$get['to'].'.xls');

            $xlscontent .= '<html>';
            $xlscontent .= '<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">';
            $xlscontent .= '<body>';

            $xlscontent .= '<table>';
            $xlscontent .= '<tr><td colspan="8"><b>In and Out Report '.$fromto.'</b></td></tr>';
            $xlscontent .= '<tr style="font-weight: bold"><td>ID</td><td>Item Name</td><td>Description</td><td>Beginning Balance</td><td>In</td><td>Out</td><td>Difference</td><td>Ending Balance</td><td>Current Quantity</td></tr>';
            
            foreach ($inout_data as $row) :
                
                $stock_in = $this->Core->get_stock('IN', $row->item_id, $get['from'], $get['to'], 1);
                $stock_out = $this->Core->get_stock('OUT', $row->item_id, $get['from'], $get['to'], 1);
                $s_in = $stock_in[0]['iqty'] ? $stock_in[0]['iqty'] : 0;
                $s_out = $stock_out[0]['iqty'] ? $stock_out[0]['iqty'] : 0;
                $s_diff = $s_in - $s_out;
                $before_in = $this->Core->get_stock('IN', $row->item_id, '2014-01-01', $get['from'], 1);
                $before_out = $this->Core->get_stock('OUT', $row->item_id, '2014-01-01', $get['from'], 1);
                $b_in = $before_in[0]['iqty'] ? $before_in[0]['iqty'] : 0;
                $b_out = $before_out[0]['iqty'] ? $before_out[0]['iqty'] : 0;
                $b_diff = $b_in - $b_out;
                if ($s_diff > 0) :
                    $diff_val = '+'.$s_diff;
                elseif ($s_diff < 0) : 
                    $diff_val = $s_diff; 
                else :
                    $diff_val = 0;
                endif;
                $e_diff = $b_diff + $s_diff;
            
                $xlscontent .= '<tr><td>'.$row->item_id.'</td><td>'.$row->item_name.'</td><td>'.$row->item_desc.'</td><td>'.$b_diff.'</td><td>'.$s_in.'</td><td>'.$s_out.'</td><td>'.$diff_val.'</td><td>'.$e_diff.'</td><td>'.$row->item_quantity.'</td></tr>';
        
            endforeach;
                
            $xlscontent .= '</table>';
            $xlscontent .= '</body>';
            $xlscontent .= '</html>';

            echo $xlscontent;
            
            //AUDIT TRAIL
            $log = $this->Core->log_action("IN_OUT_CSV_GENERATE", 0, $this->profile_id());
        
        endif;
        
    }
    
    public function csvconsumpt()
    {
        $get = $this->input->get();
        
        $fromdate = mdate('%M %j %Y', strtotime($get['from']));
        $todate = mdate('%M %j %Y', strtotime($get['to']));
        $fromto = $get['from'] ? '('.$fromdate.' to '.$todate.')' : '';
        
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;Filename=consumpt'.$get['from'].''.$get['to'].'.xls');
        
        $consumpt_hdept = $this->Core->get_dept();        
        $consumpt_hdept_cnt = count($consumpt_hdept);   
        
        $xlscontent = '';

        $xlscontent .= '<html>';
        $xlscontent .= '<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">';
        $xlscontent .= '<body>';

        $xlscontent .= '<table>';
        $xlscontent .= '<tr><td colspan="'.($consumpt_hdept_cnt + 2).'"><b>Consumption Report '.$fromto.'</b></td></tr>';
        
        $consumpthead = '';
        foreach ($consumpt_hdept as $rowhdept) :                    
            $consumpthead .= '</td><td><b>'.$rowhdept->dept_abbr.'</b>';
        endforeach;        
        $csvheader = '<b>Item</b>'.$consumpthead.'</td><td><b>Total</b>';
        
        $xlscontent .= '<tr><td>'.$csvheader.'</td></tr>';  

        $consumpt_cat = $this->Core->get_cat();
        
        foreach ($consumpt_cat as $ccat) :
            
            $consumpt_data = $this->Core->get_item(1, 0, 0, 0, $ccat->cat_id, 0, 0, 0);                             
            
            foreach ($consumpt_data as $row) :
                $consumptdata = '';

                if ($consumpt_data) :
                                                                        
                    $consumptdata .= $row->item_name;

                    $consumpt_dept = $this->Core->get_dept();
                    $consumpt_total_item = $this->Core->get_idept_from_log(mdate('%Y-%m-%d', strtotime($get['from'])), mdate('%Y-%m-%d', strtotime($get['to'])), $row->item_id, 0);                         
                    foreach ($consumpt_dept as $rowdept) :
                        $consumpt_item = $this->Core->get_idept_from_log(mdate('%Y-%m-%d', strtotime($get['from'])), mdate('%Y-%m-%d', strtotime($get['to'])), $row->item_id, $rowdept->dept_id);                         
                        if ($consumpt_item) :
                            foreach ($consumpt_item as $rowitem) :
                                $consumptdata .= '</td><td>'.$rowitem->qty_total;
                            endforeach;
                        else :
                            $consumptdata .= '</td><td>0';
                        endif;                                
                    endforeach;
                    if ($consumpt_total_item) :
                        foreach ($consumpt_total_item as $total_item) :                                
                            $consumptdata .= '</td><td>'.$total_item->qty_total;
                        endforeach;
                    else :
                        $consumptdata .= '</td><td>0';
                    endif;
        
                    $xlscontent .= '<tr><td>'.$consumptdata.'</td></tr>'; 
            
                endif;                  
            endforeach;                   

        endforeach;

        $consumpt_total_total = $this->Core->get_iddept_from_log(mdate('%Y-%m-%d', strtotime($get['from'])), mdate('%Y-%m-%d', strtotime($get['to'])), 0, 0);  

        $consumptfoot = "";
        $consumptgrandtotal = 0;
        $consumpt_fdept = $this->Core->get_dept();        
        foreach ($consumpt_fdept as $rowfdept) :                    
            $consumpt_total_dept = $this->Core->get_ddept_from_log(mdate('%Y-%m-%d', strtotime($get['from'])), mdate('%Y-%m-%d', strtotime($get['to'])), 0, $rowfdept->dept_id);                         
            if ($consumpt_total_dept) :
                foreach ($consumpt_total_dept as $total_dept) :                                
                    $consumptgrandtotal = $consumptgrandtotal + $total_dept->qty_total;
                    $consumptfoot .= '</td><td>'.$total_dept->qty_total;
                endforeach;
            else :
                $consumptfoot .= '</td><td>0';
            endif;
        endforeach; 
        
        $csvfooter = "<tr><td>Total".$consumptfoot.'</td><td>'.$consumptgrandtotal.'</td></tr>';
        $xlscontent .= '<tr><td>'.$csvfooter.'</td></tr>'; 
                
        $xlscontent .= '</table>';
        $xlscontent .= '</body>';
        $xlscontent .= '</html>';

        echo $xlscontent;
    }
    
    public function csvpending()
    {
        $get = $this->input->get();
        
        $fromdate = mdate('%M %j %Y', strtotime($get['from']));
        $todate = mdate('%M %j %Y', strtotime($get['to']));
        $fromto = $get['from'] ? '('.$fromdate.' to '.$todate.')' : '';
        
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;Filename=pending'.$get['from'].''.$get['to'].'.xls');
        
        $xlscontent = '';

        $xlscontent .= '<html>';
        $xlscontent .= '<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">';
        $xlscontent .= '<body>';

        $xlscontent .= '<table>';
        $xlscontent .= '<tr><td colspan="3"><b>Pending Items '.$fromto.'</b></td></tr>';
        $xlscontent .= '<tr style="font-weight: bold"><td>Quantity</td><td>Unit</td><td>Item</td></tr>';

        $pend_data = $this->Core->get_pend(1, 0, 0, 0, 0, mdate('%Y-%m-%d', strtotime($get['from'])), mdate('%Y-%m-%d', strtotime($get['to'])), 2, 1);  
            
        foreach ($pend_data as $row) :
            $pend_dataitem = $this->Core->get_pend_by_item(0, 0, 0, 0, $row->pi_itemid); 
            $xlscontent .= '<tr><td>'.$row->quantity.'</td><td>'.$pend_dataitem['pi_unit'].'</td><td>'.$pend_dataitem['item_name'].'</td></tr>';
        endforeach;
                
        $xlscontent .= '</table>';
        $xlscontent .= '</body>';
        $xlscontent .= '</html>';

        echo $xlscontent;
    }

	public function deleteuser()
	{	
		$id = $_POST['userid'];
        
		//AUDIT TRAIL
		$log = $this->Core->log_action("DELETE_USER", $id, $this->profile_id());

		$delete_user = $this->Core->user_action(NULL, 'delete', $id);
	}

	public function passuser()
	{	
		$id = $_POST['userid'];
        
		//AUDIT TRAIL
		$log = $this->Core->log_action("SENDPASSWORD_USER", $id, $this->profile_id());
        
        $user_info = $this->Core->get_user(0, 0, 0, 0, $id, 0, 0, 0);
        
        if ($user_info['user_email']) :        
        
            $mailink = WEB;
            
			$new_password = $this->Register->random_password();	        
            $update_password = $this->Register->update_password($user_info['user_email'], $new_password);
	
            var_dump($user_info['user_email']);	

            /*ini_set("SMTP","mail.megaworldcorp.com");                     
            ini_set("smtp_port","25");                     
            ini_set("sendmail_from","pmis@megaworldcorp.com");*/

            $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>Your iRS New Account Password</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
            $message .= "Your account password has been sent by system.<br><br>";
            $message .= "<b>".$new_password."</b><br><br>";
            $message .= "Please change your password upon login<br>";
            $message .= "Click <a href='".$mailink."'>here</a> to log in<br><br>";
            $message .= "Thanks,<br>";
            $message .= "iRS Admin";
            $message .= "<hr />".MAILFOOT."</div>";

            $headers = "From: noreply@megaworldcorp.com\r\n";
            $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if ($user_info) :
                $sendmail = mail($user_info['user_email'], "Your iRS New Account Password", $message, $headers);        
            endif;
        endif;
	}

	public function approveuser()
	{
		$id = $this->input->post('userid');
		$post = $this->input->post();

		$user_approve = $this->Core->user_action($post, 'approve', $id);
		$user_info = $this->Core->get_user(0, 0, 0, 0, $id, 0, 0, 0);
        
		//AUDIT TRAIL
		$log = $this->Core->log_action(($user_approve == 2 ? 'APPROVED' : 'DISAPPROVED')."_USER", $id, $this->profile_id());

		echo $user_approve == 2 ? '<a title="Click to lock User ID #'.$id.'" class="approveUser cursorpoint underlined" attribute="'.$id.'" attribute2="'.$user_approve.'"><i class="fa fa-unlock-alt fa-lg greentext"></i></a>' : '<a title="Click to unlock User ID #'.$id.'" class="approveUser cursorpoint underlined" attribute="'.$id.'" attribute2="'.$user_approve.'"><i class="fa fa-lock fa-lg redtext"></i></a>'.'';                 
                
        /*ini_set("SMTP","mail.megaworldcorp.com");                     
        ini_set("smtp_port","25");                     
        ini_set("sendmail_from","pmis@megaworldcorp.com");*/

		$message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Account Update</span><br><br>Hi ".$user_info['user_fullname'].",<br><br>";
		$message .= "Your employee ID ".$user_info['user_empnum']." has been ".($user_approve == 2 ? 'APPROVED' : 'DISAPPROVED')." on our system by administrator.<br><br>";
		$message .= "Thanks,<br>";
		$message .= "iRS Admin";
        $message .= "<hr />".MAILFOOT."</div>";
        
        $headers = "From: noreply@megaworldcorp.com\r\n";
        $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        if ($user_info) :
            $sendmail = mail($user_info['user_email'], "iRS Account Update", $message, $headers);
        endif;
        
	}
    
    public function approvesel()
    {
        $user_id = $this->input->post('userid');
        $user_level = $this->input->post('userlevel');
        $user_dept = $this->input->post('userdept');
        if ($user_level == 1)
        {   
            if ($user_id != "undefined") {
                $user_appr = $this->Core->get_users_approver($user_id);                                        
                $uappr = array();
                foreach ($user_appr as $ua) :
                    array_push($uappr, $ua['appr_approverid']);
                endforeach;
                $user_appr = $uappr;

                $approver = $this->Core->get_approver($user_dept, $user_id);
                foreach ($approver as $appr) :
                    if (in_array($appr->user_id, $user_appr)) 
                    {                                                        
                        $ua_select = 'selected="selected"';
                    }
                    else
                    {                                                        
                        $ua_select = '';
                    }

                    $appr_select .= '<option value="'.$appr->user_id.'" '.$ua_select.'>'.$appr->user_fullname.'</option>';
                endforeach;
                echo $appr_select;
            }
            else {
                $approver = $this->Core->get_approver($user_dept);
                foreach ($approver as $appr) :
                    $appr_select .= '<option value="'.$appr->user_id.'">'.$appr->user_fullname.'</option>';
                endforeach;
                echo $appr_select;
            }
        }
        else
        {
            echo "0";
        }
    }
    
    public function deptsel()
    {
        $user_level = $this->input->post('userlevel');
        if ($user_level == 2)
        {   
            echo "1";
        }
        else
        {
            echo "0";
        }
    }

	public function deletedept()
	{	
		$id = $_POST['deptid'];
        
		//AUDIT TRAIL
		$log = $this->Core->log_action("DELETE_DEPT", $id, $this->profile_id());

		$delete_dept = $this->Core->dept_action(NULL, 'delete', $id);
	}

	public function approvedept()
	{
		$id = $this->input->post('deptid');
		$post = $this->input->post();

		$dept_approve = $this->Core->dept_action($post, 'approve', $id);
		$dept_info = $this->Core->get_department(0, 0, 0, 0, $id, 0, 0);
        
		//AUDIT TRAIL
		$log = $this->Core->log_action(($dept_approve == 2 ? 'APPROVED' : 'DISAPPROVED')."_DEPT", $id, $this->profile_id());

		echo $dept_approve == 2 ? '<a title="Click to deactivate Department ID #'.$id.'" class="approveDept cursorpoint underlined" attribute="'.$id.'" attribute2="'.$dept_approve.'"><i class="fa fa-circle fa-lg greentext"></i></a>' : '<a title="Click to activate Department ID #'.$id.'" class="approveDept cursorpoint underlined" attribute="'.$id.'" attribute2="'.$dept_approve.'"><i class="fa fa-circle fa-lg redtext"></i></a>'.'';                 
        
	}

	public function loginprocess()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$checkfmem = $this->Register->check_member($username, $password);
		$user_data = $this->Register->get_member($username, 0);
        
		if ($checkfmem) :
			$session_data = array(
               SESSION_NAME 		=> $username,
               'session_uid'		=> $user_data['user_id'],
               'session_email'		=> $user_data['user_email'],
               'session_fullname'	=> $user_data['user_fullname'],
               'session_level'		=> $user_data['user_level']
           	);

			$this->session->set_userdata($session_data);            
            //var_dump($this->session->userdata);
			//AUDIT TRAIL
			$log = $this->Core->log_action("LOGIN", 0, $user_data['user_id']);
			$success = 1;
		else :
			$success = 0;		
		endif;

		echo $success;
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */