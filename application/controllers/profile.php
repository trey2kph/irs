<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {
    
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
            //if ($this->profile_level() == 9) :
                //echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				//echo '<script>window.location.href = "'.WEB.'";</script>';
            //else :     
                $post = $this->input->post();
                if ($post) :
                    $data['post'] = $post;
                else :
                    $data['post'] = NULL;
                    $post['user_level'] = NULL;
                    $post['user_password1'] = NULL;
                endif;
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $id = $data['session_data']['session_uid'];
        
                $level = $data['session_data']['session_level'];
                $data['level'] = $level;
        
                if ($this->profile_level() == 9) :
                    $data['user_data'] = $this->Core->get_sadmin_user(0, 0, 0, 0, $id);
                else :
                    $data['user_data'] = $this->Core->get_user(0, 0, 0, 0, $id, 0, 0, 0);
                endif;
                    
                // FORM VALIDATION
                //$this->form_validation->set_rules('user_empnum', 'Employee No.', 'required|callback_edit_unique[tbl_user.user_empnum.'.$post['user_id'].']');
                //$this->form_validation->set_rules('user_fullname', 'Name', 'required');
                //$this->form_validation->set_rules('user_telno', 'Contact Number', 'required');  
                //$this->form_validation->set_rules('user_email', 'Email Address', 'required|valid_email|callback_edit_unique[tbl_user.user_email.'.$post['user_id'].']');          
                if ($post['user_password1']) :
                $this->form_validation->set_rules('user_password1', 'Password', 'required|min_length[8]|max_length[12]|matches[user_password2]');
                $this->form_validation->set_rules('user_password2', 'Confirm Password', 'required');
                endif;        
                $this->form_validation->set_error_delimiters('<span class="redtext">', '</span>');
    
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "My Profile";
    
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('profile', $data);
                    $this->load->view('footer');
                else :
                    //$update_member = $this->Core->user_action($post, 'update_profile');	                
        
                    //if ($update_member) :
                        // UPDATE SESSION FULL NAME
                        $newsession_data = array(
                           'session_fullname'	=> $post['user_fullname']
                        );
            
                        $this->session->set_userdata($newsession_data);            
            
                        if ($post['user_password1']) :
                            $update_password = $this->Core->user_action($post, 'edit_password', $id);	                        
                            //AUDIT TRAIL
                            $log = $this->Core->log_action("UPDATE_PROFILE_PASSWORD", 0, $id);
                            echo '<script type="text/javascript">alert("Your password has been updated.");</script>';
                        //else :
                            //AUDIT TRAIL
                            //$log = $this->Core->log_action("UPDATE_PROFILE", 0, $id);
                            //echo '<script type="text/javascript">alert("Your profile has been updated.");</script>';
                        endif;					
                        echo '<script>window.location.href = "'.WEB.'/profile";</script>';
                    //endif;
                endif;
        
            //endif;

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

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */