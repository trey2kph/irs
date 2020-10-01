<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {
    
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
                $data['post'] = $post;
                if ($post['btninvsearch']) :
                    $post['inout_date'] = NULL;
                    $searchstr = $post['searchinv'] ? $post['searchinv'] : 0;
                    $cat = $post['searchcat'] ? $post['searchcat'] : 0;
                    $searchdate = NULL;
                    $searchdateto = NULL;
                    $data['searchdate'] = NULL;
                    $data['searchdateto'] = NULL;
                elseif ($post['inout_date_from'] && $post['inout_date_to']) :
                    $post['searchinv'] = 0;
                    $searchstr = 0;
                    $cat = 0;
                    $searchdate = $post['inout_date_from'] ? $post['inout_date_from'] : NULL;
                    $searchdateto = $post['inout_date_to'] ? $post['inout_date_to'] : NULL;
                    $data['searchdate'] = $searchdate;
                    $data['searchdateto'] = $searchdateto;
                elseif (($post['consumpt_date_from'] && $post['consumpt_date_to']) || $post['consumptdept']) :
                    $post['searchinv'] = 0;
                    $searchstr = 0;
                    $cat = 0;
                    $searchcdate = $post['consumpt_date_from'] ? $post['consumpt_date_from'] : NULL;
                    $searchcdateto = $post['consumpt_date_to'] ? $post['consumpt_date_to'] : NULL;
                    $data['searchcdate'] = $searchcdate;
                    $data['searchcdateto'] = $searchcdateto;
                    $data['consumptdept'] = $post['consumptdept'] ? $post['consumptdept'] : 1;
                else : 
                    $searchstr = NULL;
                    $searchdate = NULL;
                    $cat = 0;
                    $data['searchdate'] = NULL;
                    $searchdateto = NULL;
                    $data['searchdateto'] = NULL;
                    $data['consumptdept'] = $post['consumpt_dept'] ? $post['consumpt_dept'] : 1;
                endif;
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Inventory";		
                $data['inventory_data'] = $this->Core->get_item(1, 0, 0, 0, $cat, 0, 0, $searchstr);			        
                $data['inout_data'] = $this->Core->get_item_from_log(($searchdate ? $searchdate : mdate('%Y-%m-%d', strtotime('last month'))), ($searchdateto ? $searchdateto : mdate('%Y-%m-%d')), 0, 1);	
    
                // TEMPLATE
                $this->load->view('header', $data);	
                $this->load->view('inventory', $data);
                $this->load->view('footer');
            endif;
		else :
            // DATA
			$data['session_data'] = NULL;
			$data['page_title'] = "Login";
			$data['referer'] = "inventory";
        
            // TEMPLATE
			$this->load->view('header', $data);	
            $this->load->view('login', $data);
            $this->load->view('footer');
		endif;	
	}
}

/* End of file inventory.php */
/* Location: ./application/controllers/inventory.php */