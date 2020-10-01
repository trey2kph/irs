<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pending extends CI_Controller {

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
            if ($this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
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
                $data['page_title'] = "iRS Pending Item List";
                $pend_count = $this->Core->get_pend(0, 1, 0, 0, 0, 0, 2, $searchstr);		
                $data['pend_data'] = $this->Core->get_pend(1, 0, $start, NUM_ROWS, 0, 0, 2, $searchstr);   
        
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
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
}

/* End of file pending.php */
/* Location: ./application/controllers/pending.php */