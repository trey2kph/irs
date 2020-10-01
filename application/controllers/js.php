<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Js extends CI_Controller {
    
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

	public function index()
	{				
		$this->output->set_header('Content-type: text/javascript');
		$this->load->view('plugins');
	}

	public function plugins($mode = 0)
	{	
        $data['session_data'] = $this->session->all_userdata();
        $level = $data['session_data']['session_level'];
        $data['level'] = $level;	
        $data['mode'] = $mode;	
		$this->output->set_header('Content-type: text/javascript');        
		$this->load->view('plugins', $data);
	}
    
    public function dashboard_js()
	{		
		$this->output->set_header('Content-type: text/javascript');
        if($this->session->userdata(SESSION_NAME)) :
		$this->load->view('dashboard_js');
        endif;
	}
    
    public function inventory_js()
	{		
		$this->output->set_header('Content-type: text/javascript');
        if($this->session->userdata(SESSION_NAME)) :
		$this->load->view('inventory_js');
        endif;
	}
    
    public function inout_js()
	{		
		$this->output->set_header('Content-type: text/javascript');
        if($this->session->userdata(SESSION_NAME)) :
		$this->load->view('inout_js');
        endif;
	}
    
    public function stock_js()
	{		
		$this->output->set_header('Content-type: text/javascript');
        if($this->session->userdata(SESSION_NAME)) :
		$this->load->view('stock_js');
        endif;
	}
    
    public function pending_js()
	{		
		$this->output->set_header('Content-type: text/javascript');
		$this->load->view('pending_js');
	}
}

/* End of file js.php */
/* Location: ./application/controllers/js.php */