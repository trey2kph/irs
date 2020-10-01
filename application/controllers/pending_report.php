<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pending_report extends CI_Controller {
    
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
        $searchfrom = $post['pend_date_from'] ? $post['pend_date_from'] : 0;
        $searchto = $post['pend_date_to'] ? $post['pend_date_to'] : 0;
    
        $pages = $page_num ? (int)$page_num : 1 ;
        $start = NUM_ROWS * ($pages - 1);   

        // DATA
        $data['page_title'] = "iRS Pending Item List";
        $data['pend_data'] = $this->Core->get_pend(1, 0, $start, NUM_ROWS, 0, $searchfrom, $searchto, 2, 1);   

        // TEMPLATE
        $this->load->view('pending_report', $data);
	}
}

/* End of file pending_report.php */
/* Location: ./application/controllers/pending_report.php */