<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class TestTime extends CI_Controller { 
    
    function display()
    { 
        $request_data = $this->Core->get_adminapp_trans("2015-08-05");	
        var_dump($request_data);
    }
}

?>