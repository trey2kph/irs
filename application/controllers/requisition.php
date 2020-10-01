<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Requisition extends CI_Controller {
    
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
    
    function profile_level() {
        $pro_level = $this->session->userdata('session_level');
        return $pro_level;
    }

	public function index($page = NULL, $page_num = NULL)
	{		
		if($this->session->userdata(SESSION_NAME)) :
        
            // CHECK IF USER HAVE 2 UNCLOSE TRANSACTION
            $session_uid = $this->session->all_userdata();
            $checkunclose = $this->Core->get_trans(0, 1, 0, 0, 0, $session_uid['session_uid'], 0, 5, 0);
        
            if ($this->profile_level() == 3) :
                $user_appr = 1;             
            else :
                $user_appr = $this->Core->get_users_approver($session_uid['session_uid']);             
            endif;
        
            if ($user_appr) :
            
                $uappr = array();
                foreach ($user_appr as $ua) :
                    array_push($uappr, $ua['appr_approverid']);
                endforeach;
                $user_appr = $uappr;

                if ($this->profile_level() != 1 && $this->profile_level() != 3 && $this->profile_level() != 9) :
                    echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
                    echo '<script>window.location.href = "'.WEB.'";</script>';
                elseif ($checkunclose >= 2) :
                    echo '<script type="text/javascript">alert("Requisition is unavailable as you\'ve 2 unclosed transactions prior from release. Please close those transaction on your dashboard.");</script>';
                    echo '<script>window.location.href = "'.WEB.'";</script>';
                elseif (empty($user_appr) && $this->profile_level() != 3) :
                    echo '<script type="text/javascript">alert("You\'ve no approver set on your account. Please call admin.");</script>';
                    echo '<script>window.location.href = "'.WEB.'";</script>';
                else :        
                    $post = $this->input->post();
                    $sess_str = $this->session->userdata('session_searchstr_req');
                    $sess_cat = $this->session->userdata('session_searchcat_req');

                    if ($post) : 
                        $searchstr = $post['searchitem'] ? $post['searchitem'] : 0;
                        $searchcat = $post['searchcat'] ? $post['searchcat'] : 0;
                        $data['post'] = $post;
                        $session_search = array(
                           'session_searchstr_req' => $searchstr,
                           'session_searchcat_req' => $searchcat,
                        );    
                        $this->session->set_userdata($session_search);     
                    elseif ($sess_str || $sess_cat) :
                        $searchstr = $sess_str;
                        $searchcat = $sess_cat;
                        if ($sess_str == '0') $data['post']['searchitem'] = NULL;
                        else $data['post']['searchitem'] = $sess_str;
                        $data['post']['searchcat'] = $sess_cat;
                    else : 
                        $searchstr = 0;
                        $searchcat = 0;
                        $data['post'] = NULL;
                        $data['post']['searchitem'] = NULL;
                        $data['post']['searchcat'] = 0;
                    endif;

                    $pages = $page_num ? (int)$page_num : 1 ;
                    $start = NUM_ROWS * ($pages - 1);   

                    // DATA
                    $data['session_data'] = $this->session->all_userdata();
                    $data['page_title'] = "iRS Requisition";
                    $item_count = $this->Core->get_item(0, 1, 0, 0, $searchcat, 0, 2, $searchstr);		
                    $data['item_data'] = $this->Core->get_item(1, 0, $start, NUM_ROWS, $searchcat, 0, 2, $searchstr);
                    $data['cat'] = $this->Core->get_cat();        
                    
                    $level = $data['session_data']['session_level'];
                    $data['level'] = $level;

                    // PAGINATION		                
                    $page_data['base_url'] = WEB.'/requisition/index/page/';
                    $page_data['total_rows'] = $item_count;
                    $page_data['per_page'] = NUM_ROWS;
                    $page_data['uri_segment'] = 4;
                    $page_data['num_links'] = NUM_LINKS;
                    $page_data['use_page_numbers'] = TRUE;
                    $page_data['full_tag_open'] = 'Page: ';
                    $this->pagination->initialize($page_data); 	

                    // CART DATA
                    $cartdata = $this->cart->contents();
                    $data['cart_data'] = $this->Core->do_cart();	

                    // TEMPLATE
                    $this->load->view('header', $data);	
                    $this->load->view('requisition', $data);
                    $this->load->view('footer');	
                endif;
        
            else :
                echo '<script>alert("No approver has been set to your account please call Admin. Thanks.")</script>';
                echo '<script>window.location.href = "'.WEB.'";</script>';
            endif;
        
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;
	}
}

/* End of file requisition.php */
/* Location: ./application/controllers/requisition.php */