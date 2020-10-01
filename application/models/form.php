<?php

class Form extends CI_Model
{	
	function time_dropdown($name)
	{
		$timearray = array("06:00:00"=>"6:00AM","06:30:00"=>"6:30AM","07:00:00"=>"7:00AM","07:30:00"=>"7:30AM","08:00:00"=>"8:00AM","08:30:00"=>"8:30AM","09:00:00"=>"9:00AM","09:30:00"=>"9:30AM","10:00:00"=>"10:00AM","10:30:00"=>"10:30AM","11:00:00"=>"11:00AM","11:30:00"=>"11:30AM","12:00:00"=>"12:00NN","12:30:00"=>"12:30PM","13:00:00"=>"1:00PM","13:30:00"=>"1:30PM","14:00:00"=>"2:00PM","14:30:00"=>"2:30PM","15:00:00"=>"3:00PM","15:30:00"=>"3:30PM","16:00:00"=>"4:00PM","16:30:00"=>"4:30PM","17:00:00"=>"5:00PM","17:30:00"=>"5:30PM","18:00:00"=>"6:00PM","18:30:00"=>"6:30PM","19:00:00"=>"7:00PM","19:30:00"=>"7:30PM","20:00:00"=>"8:00PM","20:30:00"=>"8:30PM","21:00:00"=>"9:00PM","21:30:00"=>"9:30PM","22:00:00"=>"10:00PM","22:30:00"=>"10:30PM","23:00:00"=>"11:00PM");

		return form_dropdown($name, $timearray);
	}

	function onetoten_dropdown($name, $id)
	{
		$timearray = array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 20=>20, 30=>30, 40=>40, 50=>50, 60=>60, 70=>70, 80=>80, 90=>90, 100=>100, 200=>200, 500=>500, 1000=>1000);

		$attr = 'id = "'.$id.'"';
		return form_dropdown($name, $timearray, '', $attr);
	}		
    
    function level_dropdown($name, $selected, $attr)
	{
		$levelarray = array(0=>"Select level...", 1=>"Requestor", 2=>"Approver", 3=>"SRF", 5=>"Report Viewer", 6=>"Admin Head", 7=>"Admin Assistant", 8=>"Admin");

		return form_dropdown($name, $levelarray, $selected, $attr);
	}	
    
    function level_show($level_id)
	{
		$levelarray = array(1=>"Requestor", 2=>"Approver", 3=>"SRF", 6=>"Admin Head", 7=>"Admin Assistant", 8=>"Admin");
        foreach ($levelarray as $id => $value) :
            if ($level_id == $id) : return $value; endif;
        endforeach;
	}
    
    function non_admin_level_dropdown($name, $selected, $attr)
	{
		$levelarray = array(0=>"Select level...", 1=>"Requestor", 2=>"Approver");

		return form_dropdown($name, $levelarray, $selected, $attr);
	}
    
    function istatus_dropdown($name, $selected)
	{
		$istatarray = array(2=>"Display", 1=>"Not to Display");

		return form_dropdown($name, $istatarray, $selected);
	}
    
    function tstatus_dropdown($name, $id, $selected)
	{
		$tstatarray = array(0=>"All Status",1=>"For Approval", 2=>"Endorsed to Supply", 3=>"For Release", 4=>"Pending", 5=>"Item Released", 9=>"Closed", 8=>"Rejected");

		$attr = 'id = "'.$id.'"';
		return form_dropdown($name, $tstatarray, $selected, $attr);
	}
    
    function iunit_dropdown($name, $id, $selected)
	{
        $unitarray = array();
        $unitvalue = $this->Core->get_unit();
        foreach ($unitvalue as $value) :
            $unitarray[$value->unit_id] = $value->unit_name;
        endforeach;        

		$attr = 'id = "'.$id.'"';
		return form_dropdown($name, $unitarray, $selected, $attr);
	}
    
    function icat_dropdown($name, $id, $selected)
	{
        $catarray = array();
        if ($name != 'item_cat') $catarray['0'] = "All Categories";
        $catvalue = $this->Core->get_cat();
        foreach ($catvalue as $value) :
            $catarray[$value->cat_id] = $value->cat_name;
        endforeach;        
        if ($name == 'item_cat') $catarray['1000'] = "Other...";

		$attr = 'id = "'.$id.'"';
		return form_dropdown($name, $catarray, $selected, $attr);
	}
    
    function year_dropdown($name, $id, $selected)
	{
		$yeararray = array();
        $yearlast10 = mdate('%Y', strtotime('-10 years'));
        for($i=mdate('%Y'); $i>=$yearlast10; $i--)
        {
            $yeararray[$i] = $i;
        }

		$attr = 'id = "'.$id.'"';
		return form_dropdown($name, $yeararray, $selected, $attr);
	}
    
    function month_dropdown($name, $id, $selected)
	{
		$montharray = array(1=>"January", 2=>"February", 3=>"March", 4=>"April", 5=>"May", 6=>"June", 7=>"July", 8=>"August", 9=>"September", 10=>"October", 11=>"November", 12=>"December");

		$attr = 'id = "'.$id.'"';
		return form_dropdown($name, $montharray, $selected, $attr);
	}
    
    function task_dropdown($name, $id, $selected)
	{	
        $taskarray = array('0'=>'All Task', 'LOGIN'=>'Login', 'LOGOUT'=>'Logout', 'STOCK_CREATE'=>'Create Item', 'STOCK_UPDATE'=>'Update Item', 'STOCK_PLUS'=>'Plus Stock', 'STOCK_MINUS'=>'Minus Stock', 'ITEM_DISPLAY'=>'Item Set Display', 'ITEM_UNDISPLAY'=>'Item Set Undisplay', 'ADD_CART'=>'Add to Cart', 'MINUS_CART'=>'Minus to Cart', 'REMOVE_CART'=>'Remove Cart', 'TRANSACTION_CREATE'=>'Create Transaction', 'TRANSACTION_CANCEL'=>'Cancel Transaction', 'TRANSACTION_ENDORSE'=>'Approved Transaction', 'TRANSACTION_PENDING'=>'Pending Transaction', 'TRANSACTION_ADMIN_APPROVE'=>'Admin Approve Transaction', 'TRANSACTION_RELEASE'=>'Release Transaction', 'TRANSACTION_DISAPPROVE'=>'Disapprove Transaction', 'TRANSACTION_CLOSE'=>'Close Transaction', 'USER_CREATE'=>'Create User', 'USER_UPDATE'=>'Update User', 'APPROVED_USER'=>'User Set Approve', 'DISAPPROVED_USER'=>'User Set Disapprove', 'CAT_CREATE'=>'Create Category', 'CAT_DISPLAY'=>'Category Set Display', 'CAT_UNDISPLAY'=>'Category Set Undisplay');

		$attr = 'id = "'.$id.'" class="width100"';
		return form_dropdown($name, $taskarray, $selected, $attr);
	}
    
    function task_dropdown_short($name, $id, $selected)
	{	
        $taskarray = array('0'=>'All Task', 'LOGIN'=>'Login', 'LOGOUT'=>'Logout', 'STOCK'=>'Stock', 'ITEM'=>'Item', 'CART'=>'Cart', 'TRANSACTION'=>'Transaction', 'USER'=>'User', 'CAT'=>'Category');

		$attr = 'id = "'.$id.'" class="width100"';
		return form_dropdown($name, $taskarray, $selected, $attr);
	}
    
    function user_dropdown($name, $id, $selected)
	{
        $userarray = array();
        $userarray['0'] = "All User";
        $uservalue = $this->Core->get_user(1, 0, 0, 0, 0, 0, 0, 0);
        foreach ($uservalue as $value) :
            $userarray[$value->user_id] = $value->user_fullname;
        endforeach;        

		$attr = 'id = "'.$id.'" class="width100"';
		return form_dropdown($name, $userarray, $selected, $attr);
	}
    
    function dept_dropdown($name, $id, $selected)
	{
        $deptarray = array();
        $deptvalue = $this->Core->get_dept();
        foreach ($deptvalue as $value) :
            $deptarray[$value->dept_id] = $value->dept_name;
        endforeach;        

		$attr = 'id = "'.$id.'" class="width200"';
		return form_dropdown($name, $deptarray, $selected, $attr);
	}
    
    function dept_dropmulti($name, $id, $selected)
	{
        $deptarray = array();
        $selected = explode(',', $selected);
        $deptvalue = $this->Core->get_dept();
        foreach ($deptvalue as $value) :
            
            $deptarray[$value->dept_id] = $value->dept_name;
        endforeach;        

		$attr = 'id = "'.$id.'" size=6 class="width200"';
		return form_multiselect($name, $deptarray, $selected, $attr);
	}
}

?>