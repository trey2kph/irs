<?php 
class Error extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct(); 
    } 

    public function index() 
    {         
        $data['page_title'] = "Page Not Found";
        $data['session_data'] = $this->session->all_userdata();
        
        $this->load->view('header', $data);
        $this->load->view('error');
        $this->load->view('footer');
    } 
} 
?> 