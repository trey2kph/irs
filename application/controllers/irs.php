<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Irs extends CI_Controller {  
    
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

	public function index($page = NULL, $page_num = NULL)
	{	
		if($this->session->userdata(SESSION_NAME)) :
			$post = $this->input->post();
            $sess_str = $this->session->userdata('session_searchstr_trans');
            $sess_status = $this->session->userdata('session_searchstatus_trans');
            if ($post) :
                $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
                $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
                $data['post'] = $post;
                $session_search = array(
                   'session_searchstr_trans'	    => $searchstr,
                   'session_searchstatus_trans'	    => $searchstatus
                );    
                $this->session->set_userdata($session_search);     
			elseif ($sess_str || $sess_status) :
                $searchstr = $sess_str;
                $searchstatus = $sess_status;
                if ($sess_str == '0') $data['post']['searchtrans'] = NULL;
                else $data['post']['searchtrans'] = $sess_str;
                $data['post']['statustrans'] = $sess_status;
			else : 
                $searchstr = 0;
                $searchstatus = 0;
                $data['post'] = NULL;
            endif;    
        
            $data['ann'] = $this->Core->get_announcement();
            
			$pages = $page_num ? (int)$page_num : 1 ;
			$start = NUM_ROWS * ($pages - 1);   

			// DATA
			$data['session_data'] = $this->session->all_userdata();
            //var_dump($data['session_data']);
			$data['page_title'] = "iRS Dashboard";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 1 || $level == 3 || $level == 5 || $level == 6 || $level == 9) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, $data['session_data']['session_uid'], 0, $searchstatus, $searchstr);
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, $data['session_data']['session_uid'], 0, $searchstatus, $searchstr);
                $data['trans_sec'] = '';
                if ($level == 5 || $level == 6 || $level == 9) :
                    $data['trans_count_chart'] = $this->Core->get_trans_count();                        
                    $data['trans_count_chart1'] = $this->Core->get_trans_count_status(date('Y-m-d', strtotime('-200 days')), date('Y-m-d'), 9);
                    $data['trans_count_chart2'] = $this->Core->get_trans_count_status(date('Y-m-d', strtotime('-200 days')), date('Y-m-d'), 5);
                    $data['trans_count_chart3'] = $this->Core->get_trans_count_status(date('Y-m-d', strtotime('-200 days')), date('Y-m-d'), 4);
                    $data['trans_count_chart4'] = $this->Core->get_trans_count_dept();
                    $data['trans_count_chartmain1'] = $this->Core->get_trans_count_status2(date('Y-m-d', strtotime('-18 days')), date('Y-m-d'));  
                    $data['trans_count_chartmain2'] = $this->Core->get_trans_count_status2(date('Y-m-d', strtotime('-1200 days')), date('Y-m-d'), 4);      
                endif;
            elseif ($level == 2) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 1, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $data['session_data']['session_uid'], 1, $searchstr);                
                $data['trans_sec'] = 'for approval';
                $data['count_approval'] = $trans_count;
                $data['count_approved'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 20, $searchstr);
                $data['count_reject'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 8, $searchstr);		
            elseif ($level == 7) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 3, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 3, $searchstr);                
                $data['trans_sec'] = 'admin approve';
                $data['count_admin_approve'] = $trans_count;
                $data['count_release'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 5, $searchstr);		
                $data['count_close'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 9, $searchstr);		
            elseif ($level == 8) :
                $data['zero_count'] = $this->Core->get_zero_stock(0, 1, 0, 0, 1);
        
                $data['trans_count_chart'] = $this->Core->get_trans_count();                        
                $data['trans_count_chart1'] = $this->Core->get_trans_count_status(date('Y-m-d', strtotime('-200 days')), date('Y-m-d'), 9);
                $data['trans_count_chart2'] = $this->Core->get_trans_count_status(date('Y-m-d', strtotime('-200 days')), date('Y-m-d'), 5);
                $data['trans_count_chart3'] = $this->Core->get_trans_count_status(date('Y-m-d', strtotime('-200 days')), date('Y-m-d'), 4);
                $data['trans_count_chart4'] = $this->Core->get_trans_count_dept();
                $data['trans_count_chartmain1'] = $this->Core->get_trans_count_status2(date('Y-m-d', strtotime('-18 days')), date('Y-m-d'));  
                $data['trans_count_chartmain2'] = $this->Core->get_trans_count_status2(date('Y-m-d', strtotime('-1200 days')), date('Y-m-d'), 4);      
            elseif ($level == 5) :
                header('Location: '.WEB.'/reports');
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/irs/index/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('index', $data);
			$this->load->view('footer', $data);
		else :
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";
        
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', FALSE);
            header('Pragma: no-cache');

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('login');
			$this->load->view('footer');
		endif;
	}
    
    public function approved($page = NULL, $page_num = NULL)
	{		
		if($this->session->userdata(SESSION_NAME)) :
			$post = $this->input->post();
            $sess_str = $this->session->userdata('session_searchstr_trans');
            $sess_status = $this->session->userdata('session_searchstatus_trans');
            if ($post) :
                $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
                $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
                $data['post'] = $post;
                $session_search = array(
                   'session_searchstr_trans'	    => $searchstr,
                   'session_searchstatus_trans'	    => $searchstatus
                );    
                $this->session->set_userdata($session_search);     
			elseif ($sess_str || $sess_status) :
                $searchstr = $sess_str;
                $searchstatus = $sess_status;
                if ($sess_str == '0') $data['post']['searchtrans'] = NULL;
                else $data['post']['searchtrans'] = $sess_str;
                $data['post']['statustrans'] = $sess_status;
			else : 
                $searchstr = 0;
                $searchstatus = 0;
                $data['post'] = NULL;
            endif;        
            
			$pages = $page_num ? (int)$page_num : 1 ;
			$start = NUM_ROWS * ($pages - 1);   

			// DATA
			$data['session_data'] = $this->session->all_userdata();
			$data['page_title'] = "iRS Dashboard";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 2) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 20, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $data['session_data']['session_uid'], 20, $searchstr);                
                $data['trans_sec'] = 'approved';
                $data['count_approval'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 1, $searchstr);
                $data['count_approved'] = $trans_count;
                $data['count_reject'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 8, $searchstr);	
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/irs/approved/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('index', $data);
			$this->load->view('footer');
		else :
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('login');
			$this->load->view('footer');
		endif;
	}
    
    public function rejected($page = NULL, $page_num = NULL)
	{		
		if($this->session->userdata(SESSION_NAME)) :
			$post = $this->input->post();
            $sess_str = $this->session->userdata('session_searchstr_trans');
            $sess_status = $this->session->userdata('session_searchstatus_trans');
            if ($post) :
                $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
                $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
                $data['post'] = $post;
                $session_search = array(
                   'session_searchstr_trans'	    => $searchstr,
                   'session_searchstatus_trans'	    => $searchstatus
                );    
                $this->session->set_userdata($session_search);     
			elseif ($sess_str || $sess_status) :
                $searchstr = $sess_str;
                $searchstatus = $sess_status;
                if ($sess_str == '0') $data['post']['searchtrans'] = NULL;
                else $data['post']['searchtrans'] = $sess_str;
                $data['post']['statustrans'] = $sess_status;
			else : 
                $searchstr = 0;
                $searchstatus = 0;
                $data['post'] = NULL;
            endif;        
            
			$pages = $page_num ? (int)$page_num : 1 ;
			$start = NUM_ROWS * ($pages - 1);   

			// DATA
			$data['session_data'] = $this->session->all_userdata();
			$data['page_title'] = "iRS Dashboard";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 2) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 8, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, $data['session_data']['session_uid'], 8, $searchstr);                
                $data['trans_sec'] = 'rejected';
                $data['count_approval'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 1, $searchstr);
                $data['count_approved'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, $data['session_data']['session_uid'], 20, $searchstr);	
                $data['count_reject'] = $trans_count;            
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/irs/disapproved/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('index', $data);
			$this->load->view('footer');
		else :
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('login');
			$this->load->view('footer');
		endif;
	}
    
    public function release($page = NULL, $page_num = NULL)
	{		
		if($this->session->userdata(SESSION_NAME)) :
			$post = $this->input->post();
            $sess_str = $this->session->userdata('session_searchstr_trans');
            $sess_status = $this->session->userdata('session_searchstatus_trans');
            if ($post) :
                $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
                $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
                $data['post'] = $post;
                $session_search = array(
                   'session_searchstr_trans'	    => $searchstr,
                   'session_searchstatus_trans'	    => $searchstatus
                );    
                $this->session->set_userdata($session_search);     
			elseif ($sess_str || $sess_status) :
                $searchstr = $sess_str;
                $searchstatus = $sess_status;
                if ($sess_str == '0') $data['post']['searchtrans'] = NULL;
                else $data['post']['searchtrans'] = $sess_str;
                $data['post']['statustrans'] = $sess_status;
			else : 
                $searchstr = 0;
                $searchstatus = 0;
                $data['post'] = NULL;
            endif;        
            
			$pages = $page_num ? (int)$page_num : 1 ;
			$start = NUM_ROWS * ($pages - 1);   

			// DATA
			$data['session_data'] = $this->session->all_userdata();
			$data['page_title'] = "iRS Dashboard";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 7) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 5, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 5, $searchstr);                
                $data['trans_sec'] = 'release';
                $data['count_admin_approve'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 3, $searchstr);
                $data['count_pending'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
                $data['count_release'] = $trans_count;		
                $data['count_close'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 9, $searchstr);		            
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/irs/release/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('index', $data);
			$this->load->view('footer');
		else :
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('login');
			$this->load->view('footer');
		endif;
	}
    
    public function close($page = NULL, $page_num = NULL)
	{		
		if($this->session->userdata(SESSION_NAME)) :
			$post = $this->input->post();
            $sess_str = $this->session->userdata('session_searchstr_trans');
            $sess_status = $this->session->userdata('session_searchstatus_trans');
            if ($post) :
                $searchstr = $post['searchtrans'] ? $post['searchtrans'] : 0;
                $searchstatus = $post['statustrans'] ? $post['statustrans'] : 0;
                $data['post'] = $post;
                $session_search = array(
                   'session_searchstr_trans'	    => $searchstr,
                   'session_searchstatus_trans'	    => $searchstatus
                );    
                $this->session->set_userdata($session_search);     
			elseif ($sess_str || $sess_status) :
                $searchstr = $sess_str;
                $searchstatus = $sess_status;
                if ($sess_str == '0') $data['post']['searchtrans'] = NULL;
                else $data['post']['searchtrans'] = $sess_str;
                $data['post']['statustrans'] = $sess_status;
			else : 
                $searchstr = 0;
                $searchstatus = 0;
                $data['post'] = NULL;
            endif;        
            
			$pages = $page_num ? (int)$page_num : 1 ;
			$start = NUM_ROWS * ($pages - 1);   

			// DATA
			$data['session_data'] = $this->session->all_userdata();
			$data['page_title'] = "iRS Dashboard";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 7) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 9, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 9, $searchstr);                
                $data['trans_sec'] = 'close';
                $data['count_admin_approve'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 3, $searchstr);
                $data['count_pending'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
                $data['count_release'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 5, $searchstr);		
                $data['count_close'] = $trans_count;		            
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/irs/close/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('index', $data);
			$this->load->view('footer');
		else :
            $data['referer'] = 'irs/close';
        
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('login', $data);
			$this->load->view('footer');
		endif;
	}

	public function logout()
	{	
        //AUDIT TRAIL
		$log = $this->Core->log_action("LOGOUT", 0, $this->profile_id());	
        
		$this->session->sess_destroy();
		
		// DATA
		$data['session_data'] = "";
		$data['page_title'] = "Login";
		
		// TEMPLATE
		$this->load->view('header', $data);
		$this->load->view('login');
		$this->load->view('footer');
	}

	public function register()
	{	
		$post = $this->input->post();
		if ($post) $data['post'] = $post;
		else $data['post'] = NULL;

		// FORM VALIDATION
        $this->form_validation->set_rules('user_empnum', 'Employee No.', 'required|is_unique[tbl_user.user_empnum]');
		$this->form_validation->set_rules('user_fullname', 'Name', 'required');        
        $this->form_validation->set_rules('user_level', 'Level', 'required|callback_check_default');
		$this->form_validation->set_rules('user_dept', 'Department', 'required');
		$this->form_validation->set_rules('user_telno', 'Contact Number', 'required');
        if ($post['user_level'] == 1) $this->form_validation->set_rules('user_approvers', 'Approver', 'callback_check_mselect'); 
		$this->form_validation->set_rules('user_email', 'Email Address', 'required|valid_email|is_unique[tbl_user.user_email]');
		$this->form_validation->set_rules('user_password1', 'Password', 'required|matches[user_password2]');
		$this->form_validation->set_rules('user_password2', 'Confirm Password', 'required');
		$this->form_validation->set_message('is_unique', 'Someone already use that %s');  
        $this->form_validation->set_message('check_default', 'You need to select something on %s');        
        $this->form_validation->set_message('check_mselect', 'You need to select something on %s');
		$this->form_validation->set_error_delimiters('<span class="redtext">', '</span>');

		if ($this->form_validation->run() == FALSE) :
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Register";

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('register', $data);
			$this->load->view('footer');
		else :
			$register_member = $this->Register->add_member($post);	
			if ($register_member) :
                $last_user_id = $this->db->insert_id();      
                //AUDIT TRAIL
                $log = $this->Core->log_action("USER_REGISTER", 0, $last_user_id);
				echo '<script type="text/javascript">alert("User '.$post['user_uname'].' has been registered and subject for approval.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
			endif;
		endif;
		
	}

	public function forgot()
	{	
		$post = $this->input->post();
		if ($post) $data['post'] = $post;
		else $data['post'] = NULL;
		
		// FORM VALIDATION
		$this->form_validation->set_rules('user_email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE) :
			// DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Send New Password";

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('forgot', $data);
			$this->load->view('footer');
		else :
			$new_password = $this->Register->random_password($post);	
			$user_data = $this->Register->get_member(0, $post['user_email']);
			if ($user_data) :				
                $update_password = $this->Register->update_password($post['user_email'], $new_password);	
        
                //AUDIT TRAIL
                $log = $this->Core->log_action("USER_FORGET_PASSWORD", 0, $user_data['user_id']);
        
                /*ini_set("SMTP","mail.megaworldcorp.com");                     
                ini_set("smtp_port","25");                     
                ini_set("sendmail_from","pmis@megaworldcorp.com");*/

                $message = "<div style='display: block; border: 5px solid #024485; padding: 10px; font-size: 12px; font-family: Verdana; width: 100%;'><span style='font-size: 18px; color: #024485; font-weight: bold;'>iRS Account Update</span><br><br>Hi ".$user_data['user_fullname'].", <br><br>Your new password for <b>".$user_data['user_empnum']."</b> is <b>".$new_password."</b>.<br><br>Please log on <a href = '".WEB."'>iRS System</a>.<br><br>Thank you,<br><br>iRS Admin";
                $message .= "<hr />".MAILFOOT."</div>";

                $headers = "From: noreply@megaworldcorp.com\r\n";
                $headers .= "Reply-To: noreply@megaworldcorp.com\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                $sendmail = mail(($user_data['user_email'] == 'it@megaworldcorp.com' ? 'asstcontroller@megaworldcorp.com' : $user_data['user_email']), "Your iRS New Password", $message, $headers);
        
                if($sendmail) :
        
                    echo '<script type="text/javascript">alert("Your new password for '.$user_data['user_empnum'].' ('.$new_password.') has been sent to your email.");</script>';
                    echo '<script>window.location.href = "'.WEB.'";</script>';
                else :
                    echo '<script type="text/javascript">alert("'.$sendmail.'");</script>';
                endif;
			else :	
				echo '<script type="text/javascript">alert("Your email address '.$post['user_email'].' doesn\'t exist on our system.");</script>';
				echo '<script>window.location.href = "'.WEB.'/irs/forgot";</script>';
			endif;
		endif;
		
	}
    
    //CALLBACKS
    
    function check_default($post_string)
    {
        return $post_string == '0' ? FALSE : TRUE;
    }
    
    function check_mselect($array)
    {
        return $array == NULL ? FALSE : TRUE;
    }
}

/* End of file irs.php */
/* Location: ./application/controllers/irs.php */