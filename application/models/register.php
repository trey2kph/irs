<?php

class Register extends CI_Model
{
	
	function check_user($username)
	{
		$sql = "SELECT user_id 
			FROM tbl_user 
			WHERE user_empnum = '".$username."'";

		$query = $this->db->query($sql);
		$result = $query->num_rows();
		if($result <= 0) :
			return FALSE;
		else :
			return TRUE;
		endif;
	}		
	
	function check_member($username, $password)
	{		
		$password = md5($password);
		
		$sql = "SELECT user_id 
			FROM tbl_user 
			WHERE user_empnum = '".$username."' 
			AND user_passw = '".$password."' 
			AND user_status = 2 ";

		$query = $this->db->query($sql);
		$result = $query->num_rows();
		if($result <= 0) :
			return FALSE;
		else :
			return TRUE;
		endif;
	}

	function update_password($email, $new_password)
	{
		
		$md5_password = md5($new_password);

		$data = array(
           'user_passw' => $md5_password
        );

		$this->db->where('user_email', $email);
		$update_password = $this->db->update('tbl_user', $data); 
		$updated = $this->db->affected_rows();
		if($updated > 0) :
			return TRUE;
		else :
			return FALSE;
		endif;

	}		
	
	function get_member($username = 0, $email = 0)
	{
		$sql = "SELECT TOP 1 user_id, user_level, user_empnum, user_passw, user_fullname, user_email 
			FROM tbl_user 
			WHERE user_status = 2 ";
		if ($username != 0 || $username != NULL) $sql .= " AND user_empnum = '".$username."' ";
		if ($email != 0 || $email != NULL) $sql .= " AND user_email = '".$email."' ";

		$query = $this->db->query($sql);
		$result = $query->row_array(); 
		return $result;
	}

	function add_member($post)
	{
		
		$data = array(
			'user_empnum'	=>	$post['user_empnum'],
			'user_passw'	=>	md5($post['user_password1']),
            'user_level'	=>	$post['user_level'],
			'user_fullname'	=>	$post['user_fullname'],
			'user_dept'		=>	$post['user_dept'],
			'user_telno'	=>	$post['user_telno'],
			'user_email'	=>	$post['user_email'],
			'user_status'	=>	1,
			'user_date'		=>	date("U")
		);

		$member_add = $this->db->insert('tbl_user', $data);
        $last_id = $this->db->insert_id();    
        
        foreach($post['user_approvers'] as $ua) :
            $data = array(
                'appr_approverid'   =>	$ua,
                'appr_userid'       =>	$last_id
            );              
            $appr_add = $this->db->insert('tbl_approver', $data);
        endforeach;
        
		return $member_add;
	}	

	function random_password() {
	    $alphabet = array('a','b','c','d','e','f','g','h','i','j','k','m','n','p','r','s','t','u','v','x','y','z','1','2','3','4','5','6','7','8','9');
	    $pass = "";
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, count($alphabet)-1);
	        $pass .= $alphabet[$n];
	    }
	    return $pass;
	}

}

?>