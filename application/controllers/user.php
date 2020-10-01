<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {  
    
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
            if ($this->profile_level() != 2 && $this->profile_level() != 9 && $this->profile_level() != 8) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :        
                $post = $this->input->post();
                $sess_str = $this->session->userdata('session_searchstr_user');
            
                if ($post) : 
                    $searchstr = $post['searchuser'] ? $post['searchuser'] : 0;
                    $data['post'] = $post;
                    $session_search = array(
                       'session_searchstr_user' => $searchstr,
                    );    
                    $this->session->set_userdata($session_search);     
                elseif ($sess_str) :
                    $searchstr = $sess_str;
                    if ($sess_str == '0') $data['post']['searchuser'] = NULL;
                    else $data['post']['searchuser'] = $sess_str;
                else :
                    $searchstr = 0;
                    $data['post'] = NULL;
                endif;
            
                $pages = $page_num ? (int)$page_num : 1 ;
                $start = NUM_ROWS * ($pages - 1);   
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS User Management";
                if ($this->profile_level() == 2) :
                    $data['user_count'] = $this->Core->get_user_thru_approver(0, 1, 0, 0, $this->profile_id(), $searchstr);		
                    $data['user_data'] = $this->Core->get_user_thru_approver(1, 0, $start, NUM_ROWS, $this->profile_id(), $searchstr);
                    $data['profile_level'] = $this->profile_level();
                else :
                    $data['user_count'] = $this->Core->get_user(0, 1, 0, 0, 0, 0, 0, $searchstr);		
                    $data['user_data'] = $this->Core->get_user(1, 0, $start, NUM_ROWS, 0, 0, 0, $searchstr);
                endif;
                $data['user_mode'] = 0;	
                    
                $level = $data['session_data']['session_level'];
                $data['level'] = $level;	
    
                // PAGINATION		                
                $page_data['base_url'] = WEB.'/user/index/page/';
                $page_data['total_rows'] = $data['user_count'];
                $page_data['per_page'] = NUM_ROWS;
                $page_data['uri_segment'] = 4;
                $page_data['num_links'] = NUM_LINKS;
                $page_data['use_page_numbers'] = TRUE;
                $page_data['full_tag_open'] = 'Page: ';
                $this->pagination->initialize($page_data); 
            
                // TEMPLATE
                $this->load->view('header', $data);	
                $this->load->view('user', $data);
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
                $data['user_mode'] = 2;
                    
                $level = $data['session_data']['session_level'];
                $data['level'] = $level;	
        
                if(is_array($post['user_dept'])) :
                    $post['user_dept'] = implode(",", $post['user_dept']);
                else :
                    $post['user_dept'] = $post['user_dept'];   
                endif;
    
                // FORM VALIDATION
                $this->form_validation->set_rules('user_empnum', 'Employee No.', 'required|is_unique[tbl_user.user_empnum]');
                $this->form_validation->set_rules('user_fullname', 'Name', 'required');
                $this->form_validation->set_rules('user_level', 'Level', 'required|callback_check_default');
                if(is_array($post['user_dept'])) :
                    $this->form_validation->set_rules('user_dept[]', 'Department', 'required');
                else :
                    $this->form_validation->set_rules('user_dept', 'Department', 'required');
                endif;      
                $this->form_validation->set_rules('user_telno', 'Contact Number', 'required');
                if ($post['user_level'] == 1) $this->form_validation->set_rules('user_approvers', 'Approver', 'callback_check_mselect');                   
                //if ($post['user_level'] != 3) $this->form_validation->set_rules('user_email', 'Email Address', 'required|valid_email|is_unique[tbl_user.user_email]');
                $this->form_validation->set_rules('user_email', 'Email Address', 'required|valid_email');
                $this->form_validation->set_rules('user_password1', 'Password', 'required|min_length[8]|max_length[12]|matches[user_password2]');
                $this->form_validation->set_rules('user_password2', 'Confirm Password', 'required');
                $this->form_validation->set_message('is_unique', 'Someone already use that %s');
                $this->form_validation->set_message('check_default', 'You need to select something on %s');        
                $this->form_validation->set_message('check_mselect', 'You need to select something on %s');
                $this->form_validation->set_error_delimiters('<span class="redtext">', '</span>');
    
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "iRS User Management : Add User";
    
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('user', $data);
                    $this->load->view('footer');
                else :
                    $register_member = $this->Core->user_action($post, 'add_approve');
                    if ($register_member) :
                        echo '<script type="text/javascript">alert("New user has been added.");</script>';
                        echo '<script>window.location.href = "'.WEB.'/user";</script>';
                    else :
                        echo '<script type="text/javascript">alert("Error");</script>';
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
                $data['user_data'] = $this->Core->get_user(0, 0, 0, 0, $id, 0, 0, 0);
                $data['user_mode'] = 1;
                    
                $level = $data['session_data']['session_level'];
                $data['level'] = $level;
        
                if(is_array($post['user_dept'])) :
                    $post['user_dept'] = implode(",", $post['user_dept']);
                endif;
        
                // FORM VALIDATION
                $this->form_validation->set_rules('user_empnum', 'Employee No.', 'required|callback_edit_unique[tbl_user.user_empnum.'.$post['user_id'].']');
                $this->form_validation->set_rules('user_fullname', 'Name', 'required');
                //$this->form_validation->set_rules('user_dept[]', 'user_dept', 'trim');
                if(is_array($post['user_dept'])) :
                    $this->form_validation->set_rules('user_dept[]', 'Department', 'required');
                else :
                    $this->form_validation->set_rules('user_dept', 'Department', 'required');
                endif;
                $this->form_validation->set_rules('user_telno', 'Contact Number', 'required');
                if ($post['user_level'] == 1) $this->form_validation->set_rules('user_approvers', 'Approver', 'callback_check_mselect');                   
                $this->form_validation->set_rules('user_email', 'Email Address', 'required|valid_email');
                $this->form_validation->set_message('check_default', 'You need to select something on %s');        
                $this->form_validation->set_message('check_mselect', 'You need to select something on %s');
                $this->form_validation->set_error_delimiters('<span class="redtext">', '</span>');
        
                //var_dump($data['post']['user_dept']);
    
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "iRS User Management : Edit User";
    
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('user', $data);
                    $this->load->view('footer');
                else :
                    $update_member = $this->Core->user_action($post, 'update');	
                    if ($update_member) :
                        echo '<script type="text/javascript">alert("User has been updated.");</script>';
                        echo '<script>window.location.href = "'.WEB.'/user";</script>';
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
    
    function check_mselect($array)
    {
        return $array == NULL ? FALSE : TRUE;
    }
    
    public function edit_unique($value, $params)
    {
        $this->form_validation->set_message('edit_unique',
            'The %s is already being used by another account.');

        list($table, $field, $id) = explode(".", $params, 3);

        $query = $this->db->select($field)->from($table)
            ->where($field, $value)->where('user_id !=', $id)->limit(1)->get();

        if ($query->row()) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */