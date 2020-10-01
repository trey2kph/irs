<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pending extends CI_Controller {
    
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
    
	public function index()
	{	
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 7 && $this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
                $post = $this->input->post();
                $sess_from = $this->session->userdata('session_searchfrom_pend');
                $sess_to = $this->session->userdata('session_searchto_pend');
                if ($post) :
                    $searchfrom = $post['pend_date_from'] ? $post['pend_date_from'] : 0;
                    $searchto = $post['pend_date_to'] ? $post['pend_date_to'] : 0;
                    $data['post'] = $post;
                    $session_search = array(
                       'session_searchfrom_pend'    => $searchfrom,
                       'session_searchto_pend'	    => $searchto
                    );    
                    $this->session->set_userdata($session_search);     
                elseif ($sess_from || $sess_to) :
                    $searchfrom = $sess_from;
                    $searchto = $sess_to;
                    $data['post']['pend_date_from'] = $sess_from;
                    $data['post']['pend_date_to'] = $sess_to;
                else : 
                    $searchfrom = 0;
                    $searchto = 0;
                    $data['post'] = NULL;
                endif; 
            
                $pages = $page_num ? (int)$page_num : 1 ;
                $start = NUM_ROWS * ($pages - 1);   
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $level = $data['session_data']['session_level'];
                $data['level'] = $level;
                $data['page_title'] = "iRS Pending Item List";
                $pend_count = $this->Core->get_pend(0, 1, 0, 0, 0, $searchfrom, $searchto, 2, 1);		
                $data['pend_data'] = $this->Core->get_pend(1, 0, $start, NUM_ROWS, 0, $searchfrom, $searchto, 2, 1); 
        
                // PAGINATION		                
                $page_data['base_url'] = WEB.'/pending/index/page/';
                $page_data['total_rows'] = $pend_count;
                $page_data['per_page'] = NUM_ROWS;
                $page_data['uri_segment'] = 4;
                $page_data['num_links'] = NUM_LINKS;
                $page_data['use_page_numbers'] = TRUE;
                $page_data['full_tag_open'] = 'Page: ';
                $this->pagination->initialize($page_data); 	 	
    
                // TEMPLATE
                $this->load->view('header', $data);	
                $this->load->view('pending', $data);
                $this->load->view('footer');
            endif;
		else :
            // DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";
			$data['referer'] = "pending";
        
            // TEMPLATE
			$this->load->view('header', $data);	
            $this->load->view('login', $data);
            $this->load->view('footer');
		endif;	
	}
    
    public function trans($page = NULL, $page_num = NULL)
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
			$data['page_title'] = "iRS Pending Transaction";
            $level = $data['session_data']['session_level'];
            $data['level'] = $level;                                
            $trans_count = $this->Core->get_trans(0, 1, 0, 0, 0, 0, 0, 4, $searchstr);		
            $data['trans_data'] = $this->Core->get_trans(1, 0, $start, NUM_ROWS, 0, 0, 0, 4, $searchstr);                
        
            // PAGINATION		                
			$page_data['base_url'] = WEB.'/pending/trans/page/';
			$page_data['total_rows'] = $trans_count;
			$page_data['per_page'] = NUM_ROWS;
			$page_data['uri_segment'] = 4;
			$page_data['num_links'] = NUM_LINKS;
			$page_data['use_page_numbers'] = TRUE;
            $page_data['full_tag_open'] = 'Page: ';
			$this->pagination->initialize($page_data); 	

			// TEMPLATE
			$this->load->view('header', $data);
			$this->load->view('pending_trans', $data);
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
}

/* End of file pending.php */
/* Location: ./application/controllers/pending.php */