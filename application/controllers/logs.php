<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends CI_Controller {        
    
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

	public function index($page = NULL, $page_num = NULL)
	{	
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
                $post = $this->input->post();
                $sess_user = $this->session->userdata('session_searchuser_logs');
                $sess_task = $this->session->userdata('session_searchtask_logs');
                $sess_from = $this->session->userdata('session_searchfrom_logs');
                $sess_to = $this->session->userdata('session_searchto_logs');
                $sess_str = $this->session->userdata('session_searchstr_logs');
                if ($post) :
                    $searchuser = $post['userlogs'] ? $post['userlogs'] : 0;
                    $searchtask = $post['tasklogs'] ? $post['tasklogs'] : 0;
                    $searchfrom = $post['fromlogs'] ? $post['fromlogs'] : 0;
                    $searchto = $post['tologs'] ? $post['tologs'] : 0;
                    $searchstr = $post['searchlogs'] ? $post['searchlogs'] : 0;
                    $data['post'] = $post;
                    $session_search = array(
                       'session_searchuser_logs'	    => $searchuser,
                       'session_searchtask_logs'	    => $searchtask,
                       'session_searchfrom_logs'	    => $searchfrom,
                       'session_searchto_logs'  	    => $searchto,
                       'session_searchstr_logs'	        => $searchstr
                    );    
                    $this->session->set_userdata($session_search);     
                elseif ($sess_user || $sess_task || $sess_from || $sess_to || $sess_str) :
                    $searchuser = $sess_user;
                    $searchtask = $sess_task;
                    $searchfrom = $sess_from;
                    $searchto = $sess_to;
                    $searchstr = $sess_str;
                    $data['post']['userlogs'] = $sess_user;
                    $data['post']['fromlogs'] = $sess_from;
                    $data['post']['tologs'] = $sess_to;
                    if ($sess_task == '0') $data['post']['tasklogs'] = NULL;
                    else $data['post']['tasklogs'] = $sess_task;
                    if ($sess_str == '0') $data['post']['searchlogs'] = NULL;
                    else $data['post']['searchlogs'] = $sess_str;
                else : 
                    $searchuser = 0;
                    $searchtask = 0;
                    $searchfrom = 0;
                    $searchto = 0;
                    $searchstr = 0;
                    $data['post'] = NULL;
                endif;        
                
                $pages = $page_num ? (int)$page_num : 1 ;
                $start = NUM_ROWS * ($pages - 1);   
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Logbook";
                $level = $data['session_data']['session_level'];
                $logs_count = $this->Core->get_logs(0, 1, 0, 0, 0, $searchuser, $searchtask, $searchfrom, $searchto, $searchstr);
                $data['logs_data'] = $this->Core->get_logs(1, 0, $start, NUM_ROWS, 0, $searchuser, $searchtask, $searchfrom, $searchto, $searchstr);
            
                // PAGINATION		                
                $page_data['base_url'] = WEB.'/logs/index/page/';
                $page_data['total_rows'] = $logs_count;
                $page_data['per_page'] = NUM_ROWS;
                $page_data['uri_segment'] = 4;
                $page_data['num_links'] = NUM_LINKS;
                $page_data['use_page_numbers'] = TRUE;
                $page_data['full_tag_open'] = 'Page: ';
                $this->pagination->initialize($page_data); 	
    
                // TEMPLATE
                $this->load->view('header', $data);
                $this->load->view('logs', $data);
                $this->load->view('footer');
            endif;
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

/* End of file logs.php */
/* Location: ./application/controllers/logs.php */