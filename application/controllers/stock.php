<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller {	
    
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
            if ($this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     
                $post = $this->input->post();
                $sess_str = $this->session->userdata('session_searchstr_stock');
                if ($post) : 
                    $searchstr = $post['searchitem'] ? $post['searchitem'] : 0;
                    $data['post'] = $post;
                    $session_search = array(
                       'session_searchstr_stock' => $searchstr,
                    );    
                    $this->session->set_userdata($session_search);     
                elseif ($sess_str) :
                    $searchstr = $sess_str;
                    if ($sess_str == '0') $data['post']['searchitem'] = NULL;
                    else $data['post']['searchitem'] = $sess_str;
                else :
                    $searchstr = 0;
                    $data['post'] = NULL;
                endif;
    
                $pages = $page_num ? (int)$page_num : 1 ;
                $start = NUM_ROWS * ($pages - 1);   
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Stock Management and Procurement";		
                $data['stock_count'] = $this->Core->get_item(0, 1, 0, 0, 0, 0, 0, $searchstr);		
                $data['stock_data'] = $this->Core->get_item(1, 0, $start, NUM_ROWS, 0, 0, 0, $searchstr);		
                $data['stock_mode'] = 0;
    
                // PAGINATION		                
                $page_data['base_url'] = WEB.'/stock/index/page/';
                $page_data['total_rows'] = $data['stock_count'];
                $page_data['per_page'] = NUM_ROWS;
                $page_data['uri_segment'] = 4;
                $page_data['num_links'] = NUM_LINKS;
                $page_data['use_page_numbers'] = TRUE;
                $page_data['full_tag_open'] = 'Page: ';
                $this->pagination->initialize($page_data); 		
    
                // TEMPLATE
                $this->load->view('header', $data);	
                $this->load->view('stock', $data);
                $this->load->view('footer');
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function cat($page = NULL, $page_num = NULL)
	{	
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     
                $post = $this->input->post();
                if ($post['searchcat']) : 
                    $searchstr = $post['searchcat'] ? $post['searchcat'] : 0;
                    $data['post'] = $post;
                elseif ($post['item_catname']) : 
        
                    // FORM VALIDATION
                    $this->form_validation->set_rules('item_catname', $post['item_catname'], 'is_unique[tbl_category.cat_name]');                    
                    $this->form_validation->set_message('is_unique', '"%s" is already exist');
        
                    if ($this->form_validation->run() != FALSE) :
                        $cat_name = $post['item_catname'] ? $post['item_catname'] : NULL;
                        $data['post'] = $post;
                        $add_cat = $this->Core->cat_action($cat_name, 'add');
                        
                        if ($add_cat) :                    
                            $last_cat_id = $this->db->insert_id();   
                            //AUDIT TRAIL
                            $log = $this->Core->log_action("CAT_CREATE", $last_cat_id, $this->profile_id());
                
                            echo '<script type="text/javascript">alert("Stock category has been added.");</script>';
                            echo '<script>window.location.href = "'.WEB.'/stock/cat";</script>';
                        endif;
                    endif;
                else :
                    $searchstr = 0;
                    $data['post'] = NULL;
                endif;
    
                $pages = $page_num ? (int)$page_num : 1 ;
                $start = NUM_ROWS * ($pages - 1);   
    
                // DATA
                $data['session_data'] = $this->session->all_userdata();
                $data['page_title'] = "iRS Stock Category Management";		
                $data['cat_count'] = $this->Core->get_category(0, 1, 0, 0, 0, 0, $searchstr);		
                $data['cat_data'] = $this->Core->get_category(1, 0, $start, NUM_ROWS, 0, 0, $searchstr);		
    
                // PAGINATION		                
                $page_data['base_url'] = WEB.'/stock/cat/page/';
                $page_data['total_rows'] = $data['cat_count'];
                $page_data['per_page'] = NUM_ROWS;
                $page_data['uri_segment'] = 4;
                $page_data['num_links'] = NUM_LINKS;
                $page_data['use_page_numbers'] = TRUE;
                $this->pagination->initialize($page_data);
           
                // TEMPLATE
                $this->load->view('header', $data);	
                $this->load->view('cat', $data);
                $this->load->view('footer');
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function add($id = NULL, $id_num = NULL)
	{	        
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     
                $post = $this->input->post();
                if ($post) $data['post'] = $post;
                else $data['post'] = NULL;    
            
                // DATA
                $data['session_data'] = $this->session->all_userdata();	
                $data['stock_mode'] = 2;
                $data['cat'] = $this->Core->get_cat();
                $data['unit'] = $this->Core->get_unit();
            
                // FORM VALIDATION
                $this->form_validation->set_rules('item_quantity', 'Quantity', 'required');
                $this->form_validation->set_rules('item_name', 'Item Name', 'required');
                $this->form_validation->set_rules('item_desc', 'Item Description', 'required');
                $this->form_validation->set_rules('item_price', 'Unit Price', 'required|numeric');
                $this->form_validation->set_rules('item_supplier', 'Item Supplier', 'required');
                $this->form_validation->set_rules('item_critical', 'Critical', 'required|numeric');
                $this->form_validation->set_rules('item_order', 'Item Order Quantity', 'required|numeric');
                if ($post['item_cat'] == 1000) :
                $this->form_validation->set_rules('item_catname', 'Item Category', 'required|is_unique[tbl_category.cat_name]');
                $this->form_validation->set_message('is_unique', '%s you\'ve entered is already there');
                endif;
        
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "iRS Stock Management and Procurement : Add New Stock";
        
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('stock', $data);
                    $this->load->view('footer');
                else :
                    if ($post['item_cat'] == 1000) :                    
                        $post['item_cat'] = $this->Core->cat_action($post['item_catname'], 'add');	                    
                    endif;
                    $add_item = $this->Core->item_action($post, 'add');	                
                    
                    if ($add_item) :                    
                        $last_item_id = $this->db->insert_id();   
                        //AUDIT TRAIL
                        $log = $this->Core->log_action("STOCK_CREATE", $last_item_id, $this->profile_id());
                        // ITEM LOG                    
                        $this->Core->ilog_action($last_item_id, 'STOCK CREATE', $post['item_quantity'], $data['session_data']['session_uid']);
            
                        echo '<script type="text/javascript">alert("Item has been added.");</script>';
                        echo '<script>window.location.href = "'.WEB.'/stock";</script>';
                    endif;
                endif;
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
    
    public function edit($id = NULL, $id_num = NULL)
	{	        
		if($this->session->userdata(SESSION_NAME)) :
            if ($this->profile_level() != 8 && $this->profile_level() != 9) :
                echo '<script type="text/javascript">alert("You\'re not authorized to access this page.");</script>';
				echo '<script>window.location.href = "'.WEB.'";</script>';
            else :     
                $post = $this->input->post();
                if ($post) :
                    $data['post'] = $post;
                else :
                    $data['post'] = NULL;    
                endif;
            
                // DATA
                $data['referral'] = $this->agent->is_referral();
                $data['referrer'] = $this->agent->referrer();
                $data['session_data'] = $this->session->all_userdata();	
                $data['stock_data'] = $this->Core->get_item(0, 0, 0, 0, 0, $id_num, 0, 0);
                $data['stock_mode'] = 1;
                $data['cat'] = $this->Core->get_cat();
                $data['procure_data'] = $this->Core->get_procure(1, 0, 0, 0, $data['stock_data']['item_id'], 0, 2, 0);
            
                // FORM VALIDATION
                $this->form_validation->set_rules('item_name', 'Item Name', 'required');
                $this->form_validation->set_rules('item_desc', 'Item Description', 'required');
                $this->form_validation->set_rules('item_supplier', 'Item Supplier', 'required');
                $this->form_validation->set_rules('item_critical', 'Item Critical', 'required|numeric');
                $this->form_validation->set_rules('item_order', 'Item Order Quantity', 'required|numeric');
                if ($post['item_cat'] == 1000) :
                $this->form_validation->set_rules('item_catname', 'Item Category', 'required|is_unique[tbl_category.cat_name]');
                $this->form_validation->set_message('is_unique', '%s you\'ve entered is already there');
                endif;
        
                if ($this->form_validation->run() == FALSE) :
                    // DATA
                    $data['page_title'] = "iRS Stock Management and Procurement : Edit Stock";
        
                    // TEMPLATE
                    $this->load->view('header', $data);
                    $this->load->view('stock', $data);
                    $this->load->view('footer');
                else :
                    if ($post['item_cat'] == 1000) :                    
                        $post['item_cat'] = $this->Core->cat_action($post['item_catname'], 'add');
                    endif;
                    $update_item = $this->Core->item_action($post, 'update');	
                    if ($update_item) :  
                        //AUDIT TRAIL
                        $log = $this->Core->log_action("STOCK_UPDATE", $id_num, $this->profile_id());
                        echo '<script type="text/javascript">alert("Item has been updated.");</script>';
                        echo '<script>window.location.href = "'.($post['referrer'] ? $post['referrer'] : WEB.'/stock').'";</script>';
                    endif;
                endif;
            endif;
		else :
			echo '<script>window.location.href = "'.WEB.'";</script>';
		endif;	
	}
}

/* End of file stock.php */
/* Location: ./application/controllers/stock.php */