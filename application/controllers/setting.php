<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends CI_Controller {
    
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
            if ($this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :  
                $post = $this->input->post();
                if ($post) :
                    $data['post'] = $post;
        
                    // FORM VALIDATION        
                    $this->form_validation->set_rules('set_mailfoot', 'Mail Footer', 'required');
                    
                    if ($this->form_validation->run() == TRUE) :
                        $edit_set = $this->Core->set_update($post); 
                        if ($edit_set) :                    
                            //AUDIT TRAIL
                            //$log = $this->Core->log_action("UPDATE_SET", 0, $this->profile_id());
                            echo '<script type="text/javascript">alert("Setting has been successfully updated");</script>';
                        endif; 
                    endif;
                else : 
                    $data['post'] = NULL;    
                endif;      
        
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Setting";
                $data['setting'] = $this->Core->get_set(0);
        
                // TEMPLATE
                $this->load->view('header', $data);
                $this->load->view('setting', $data);
                $this->load->view('footer');
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
}

/* End of file setting.php */
/* Location: ./application/controllers/setting.php */