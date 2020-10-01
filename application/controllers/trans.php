<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trans extends CI_Controller {  
    
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
			$data['page_title'] = "iRS Transaction Management";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 9) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, $data['session_data']['session_uid'], 0, $searchstatus, $searchstr);
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, $data['session_data']['session_uid'], 0, $searchstatus, $searchstr);
                $data['trans_sec'] = '';
                $data['trans_count_chartmain1'] = $this->Core->get_trans_count_status2(date('Y-m-d', strtotime('-200 days')), date('Y-m-d', strtotime('-186 days')));
            elseif ($level == 8) :
                $data['zero_count'] = $this->Core->get_zero_stock(0, 1, 0, 0, 1);
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 2, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 2, $searchstr);                
                $data['trans_sec'] = 'endorse';
                $data['count_endorse'] = $trans_count;
                $data['count_admin_approve'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 30, $searchstr);		
                $data['count_pending'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
                $data['count_admin_reject'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 7, $searchstr);
                $data['trans_count_chartmain1'] = $this->Core->get_trans_count_status2(date('Y-m-d', strtotime('-200 days')), date('Y-m-d', strtotime('-186 days')));    
            elseif ($level == 6) :    
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 3, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 3, $searchstr);                
                $data['trans_sec'] = 'admin approve';
                $data['count_admin_approve'] = $trans_count;
                $data['count_release'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 5, $searchstr);		
                $data['count_close'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 9, $searchstr);		
            elseif ($level == 5) :
                header('Location: '.WEB.'/reports');
            else :
                header('Location: '.WEB);
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/trans/index/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('trans', $data);
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
			$data['page_title'] = "iRS Transaction Management";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 6) :
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
			$this->load->view('trans', $data);
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
			$data['page_title'] = "iRS Transaction Management";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 6) :
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
			$this->load->view('trans', $data);
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
    
    public function admin_approve($page = NULL, $page_num = NULL)
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
			$data['page_title'] = "iRS Transaction Management";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 8) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 30, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 30, $searchstr);                
                $data['trans_sec'] = 'admin approve';
                $data['count_endorse'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 2, $searchstr);
                $data['count_admin_approve'] = $trans_count;
                $data['count_pending'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);
                $data['count_admin_reject'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 7, $searchstr);	
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/trans/admin_approve/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('trans', $data);
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
    
    public function pending($page = NULL, $page_num = NULL)
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
			$data['page_title'] = "iRS Transaction Management";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;                                
            if ($level == 8) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 4, $searchstr);                
                $data['trans_sec'] = 'pending';
                $data['count_endorse'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 2, $searchstr);
                $data['count_admin_approve'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 30, $searchstr);
                $data['count_pending'] = $trans_count;
                $data['count_admin_reject'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 7, $searchstr);
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/trans/pending/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('trans', $data);
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
    
    public function admin_reject($page = NULL, $page_num = NULL)
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
			$data['page_title'] = "iRS Transaction Management";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;
            if ($level == 8) :
                $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 7, $searchstr);		
                $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 7, $searchstr);                
                $data['trans_sec'] = 'admin reject';
                $data['count_endorse'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 2, $searchstr);
                $data['count_admin_approve'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 30, $searchstr);
                $data['count_pending'] = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);
                $data['count_admin_reject'] = $trans_count;            
            else :
                echo '<script>window.location.href="'.WEB.'";</script>';
            endif;
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/trans/admin_reject/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('trans', $data);
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