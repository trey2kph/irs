<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dept extends CI_Controller {  
    
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
            if ($this->profile_level() != 9 && $this->profile_level() != 8) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
                $post = $this->input->post();
                $sess_str = $this->session->userdata('session_searchstr_dept');
                //var_dump($sess_str);
            
                if ($post) : 
                    $searchstr = $post['searchdept'] ? $post['searchdept'] : 0;
                    $data['post'] = $post;
                    $session_search = array(
                       'session_searchstr_dept' => $searchstr,
                    );    
                    $this->session->set_userdata($session_search);     
                elseif ($sess_str) :
                    $searchstr = $sess_str;
                    if ($sess_str == '0') $data['post']['searchdept'] = NULL;
                    else $data['post']['searchdept'] = $sess_str;
                else :
                    $searchstr = 0;
                    $data['post'] = NULL;
                endif;
            
                $pages = $page_num ? (int)$page_num : 1 ;
                $start = NUM_ROWS * ($pages - 1);   
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Department Management";                
                $data['dept_count'] = $this->Core->get_department(0, 1, 0, 0, 0, 0, $searchstr);		
                $data['dept_data'] = $this->Core->get_department(1, 0, $start, NUM_ROWS, 0, 0, $searchstr);
                $data['dept_mode'] = 0;
    
                // PAGINATION		                
                $page_data['base_url'] = WEB.'/dept/index/page/';
                $page_data['total_rows'] = $data['dept_count'];
                $page_data['per_page'] = NUM_ROWS;
                $page_data['uri_segment'] = 4;
                $page_data['num_links'] = NUM_LINKS;
                $page_data['use_page_numbers'] = TRUE;
                $page_data['full_tag_open'] = 'Page: ';
                $this->pagination->initialize($page_data); 
            
                // TEMPLATE
                $this->load->view('header', $data);	
                $this->load->view('dept', $data);
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

	public function add()
	{		
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 9 && $this->profile_level() != 8) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
                $post = $this->input->post();
                if ($post) $data['post'] = $post;
                else $data['post'] = NULL;
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['dept_mode'] = 2;
    
                // FORM VALIDATION
                $this->form_validation->set_rules('dept_name', 'Department Name', 'required|callback_edit_unique[tbl_dept.dept_name.'.$post['dept_name'].']');
                $this->form_validation->set_rules('dept_abbr', 'Abbreviation', 'required');
                $this->form_validation->set_error_delimiters('<span class="redtext">', '</span>');
    
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "iRS Department Management : Add User";
    
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('dept', $data);
                    $this->load->view('footer');
                else :
                    $add_dept = $this->Core->dept_action($post, 'add');
                    if ($add_dept) :
                        echo '<script type="text/javascript">alert("New department has been added.");</script>';
                        echo '<script>window.location.href = "'.WEB.'/dept";</script>';
                    endif;
                endif;
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

	public function edit($id = 0)
	{		
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 9 && $this->profile_level() != 8) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
                $post = $this->input->post();
                if ($post) $data['post'] = $post;
                else $data['post'] = NULL;
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['dept_data'] = $this->Core->get_department(0, 0, 0, 0, $id, 0, 0);		
                $data['dept_mode'] = 1;
    
                // FORM VALIDATION
                $this->form_validation->set_rules('dept_name', 'Department Name', 'required|callback_edit_unique[tbl_dept.dept_name.'.$post['dept_name'].']');
                $this->form_validation->set_rules('dept_abbr', 'Abbreviation', 'required');
                $this->form_validation->set_error_delimiters('<span class="redtext">', '</span>');
    
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "iRS Department Management : Edit Department";
    
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('dept', $data);
                    $this->load->view('footer');
                else :
                    $update_dept = $this->Core->dept_action($post, 'update');	
                    if ($update_dept) :
                        echo '<script type="text/javascript">alert("Department has been updated.");</script>';
                        echo '<script>window.location.href = "'.WEB.'/dept";</script>';
                    endif;
                endif;
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
    
    //CALLBACKS
    
    function check_default($post_string)
    {
        return $post_string == '0' ? FALSE : TRUE;
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */