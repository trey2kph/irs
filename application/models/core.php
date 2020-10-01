<?php

class Core extends CI_Model
{	
    function profile_id() {
        $pro_id = $this->session->userdata('session_uid');
        return $pro_id;
    }
	
	function get_trans($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $uid = 0, $approver = 0, $status = 0, $search = 0, $all = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY t.trans_update DESC) as ROW_NUMBER, ";
		$sql .= " t.trans_id, t.trans_uid, u.user_fullname, u.user_dept, t.trans_order, t.trans_originorder, t.trans_adjust, t.trans_remarks, 
            trans_reqremarks, trans_appremarks, t.trans_date, t.trans_approvedate, t.trans_admindate, t.trans_releasedate, t.trans_update, 
            t.trans_status, dbo.UNIXToDataID(t.trans_date) AS trans_dateid
			FROM tbl_transaction t, tbl_user u
			WHERE u.user_id = t.trans_uid ";
		if ($all != 0) : $sql .= " AND t.trans_status >= 0 ";        
        else : $sql .= " AND t.trans_status != 0 "; endif;
		if ($id != 0) $sql .= " AND t.trans_id = ".$id." ";
		if ($uid != 0) $sql .= " AND t.trans_uid = ".$uid." ";
		if ($approver != 0) $sql .= " AND t.trans_uid IN (SELECT appr_userid FROM tbl_approver WHERE appr_approverid = ".$approver.") ";
        if ($status == 20) : $sql .= " AND t.trans_status > 1 AND t.trans_status != 8 ";
        elseif ($status == 30) : $sql .= " AND t.trans_status > 2 AND t.trans_status != 8 AND t.trans_status != 7 AND t.trans_status != 4 ";
        //$sql .= " AND t.trans_status = 3 ";
        elseif ($status != 0) : $sql .= " AND t.trans_status = ".$status." "; 
        endif;
		if ($search != 0 || $search != NULL) $sql .= " AND (t.trans_order LIKE '%".$search."%' OR u.user_fullname LIKE '%".$search."%' OR t.trans_id LIKE '%".$search."%' OR dbo.UNIXToDataID(t.trans_date) LIKE '%".$search."%') ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
        elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_trans_by_unix($dateid = NULL)
	{
		$sql = "SELECT t.trans_id, t.trans_order, t.trans_originorder, t.trans_adjust, t.trans_remarks, 
            t.trans_reqremarks, t.trans_appremarks, t.trans_date, t.trans_approvedate, t.trans_admindate, t.trans_releasedate, t.trans_update, 
            t.trans_status, dbo.UNIXToDataID(t.trans_date) AS trans_dateid
			FROM tbl_transaction t
			WHERE t.trans_status >= 0 ";
		if ($dateid) $sql .= " AND dbo.UNIXToDataID(t.trans_date) LIKE '%".$dateid."%' ";

        $result = $this->db->limit(1, 0);
		$result = $this->db->query($sql);
		$result = $result->row_array(); 
        
		return $result;
	}
    
    function get_trans_by_date($from = 0, $to = 0)
	{
		$sql = "SELECT t.trans_id, t.trans_uid, u.user_fullname, t.trans_order, t.trans_price, t.trans_date, t.trans_approvedate, t.trans_admindate, t.trans_releasedate, t.trans_update, t.trans_status
			FROM tbl_transaction t, tbl_user u
			WHERE u.user_id = t.trans_uid 
			AND t.trans_status != 0 ";
		if ($from && $to) $sql .= " AND t.trans_update BETWEEN ".$from." AND ".$to." ";
		$sql .= " ORDER BY t.trans_date DESC ";

		$result = $this->db->query($sql);
		$result = $result->result(); 
        
		return $result;
	}
    
    function get_adminapp_trans($date = 0)
	{
        $from = strtotime($date." 00:00:00");
        $to = strtotime($date." 23:59:00");
        
		$sql = "SELECT t.trans_id, t.trans_uid, u.user_empnum, d.dept_abbr, t.trans_order, t.trans_price, t.trans_date, t.trans_approvedate, t.trans_update, t.trans_status, t.trans_admindate, t.trans_reqremarks
			FROM tbl_transaction t, tbl_user u, tbl_dept d
			WHERE u.user_id = t.trans_uid 
			AND d.dept_id = u.user_dept
            AND u.user_level = 1
			AND t.trans_status >= 3 ";
		if ($date) $sql .= " AND t.trans_admindate BETWEEN ".$from." AND ".$to." ";
		$sql .= " ORDER BY t.trans_date DESC ";

		$result = $this->db->query($sql);
		$result = $result->result(); 
        
		return $result;
	}
    
    function get_pendtrans_by_date($from = 0, $to = 0)
	{
		$sql = "SELECT t.trans_id, t.trans_uid, u.user_fullname, t.trans_order, t.trans_price, t.trans_date, t.trans_update, t.trans_status
			FROM tbl_transaction t, tbl_user u
			WHERE u.user_id = t.trans_uid 
			AND t.trans_status = 4 ";
		if ($from && $to) : $sql .= " AND t.trans_update BETWEEN ".$from." AND ".$to." "; endif;
		$sql .= " ORDER BY t.trans_date DESC ";

		$result = $this->db->query($sql);
		$result = $result->result(); 
        
		return $result;
	}
    
	function get_transcount_dept($from = 0, $to = 0)
	{
		$sql="SELECT SUM(t.trans_price) AS transprice, COUNT(t.trans_id) AS transcount, d.dept_name ";
		$sql.=" FROM tbl_transaction t, tbl_user u, tbl_dept d
			WHERE u.user_id = t.trans_uid
            AND d.dept_id = u.user_dept
            AND u.user_level = 1
			AND t.trans_status = 9 ";
        if ($from != 0 || $to != 0) :  
            $timefrom = strtotime(date($from." 00:00:00"));
            $timeto = strtotime(date($to." 23:59:59"));
            $sql .= " AND t.trans_update > ".$timefrom." AND t.trans_update < ".$timeto." ";  
        endif;
        $sql.=" GROUP BY u.user_dept, d.dept_name ";
		$sql.=" ORDER BY transcount DESC ";

		$result = $this->db->query($sql);
		$result = $result->result(); 
			
		return $result;
	}
    
    function get_transcount_status($from = 0, $to = 0)
	{
		$sql="SELECT SUM(t.trans_id) AS transtotal, COUNT(t.trans_id) AS transcount, t.trans_status ";
		$sql.=" FROM tbl_transaction t ";
        if ($from != 0 || $to != 0) :  
            $timefrom = strtotime(date($from." 00:00:00"));
            $timeto = strtotime(date($to." 23:59:59"));
            $sql .= " WHERE t.trans_update > ".$timefrom." AND t.trans_update < ".$timeto." ";  
        endif;
        $sql.=" GROUP BY t.trans_status ";
		$sql.=" ORDER BY t.trans_status ASC ";

		$result = $this->db->query($sql);
		$result = $result->result(); 
			
		return $result;
	}
    
    function get_transtotal($from = 0, $to = 0)
	{
		$sql = "SELECT t.trans_id, t.trans_uid, u.user_fullname, t.trans_order, t.trans_date, t.trans_status
			FROM tbl_transaction t, tbl_user u
			WHERE u.user_id = t.trans_uid ";
        if ($from != 0 || $to != 0) :  
            $timefrom = strtotime(date($from." 00:00:00"));
            $timeto = strtotime(date($to." 23:59:59"));
            $sql .= " AND t.trans_update > ".$timefrom." AND t.trans_update < ".$timeto." ";  
        endif;

		$result = $this->db->query($sql);
		$result = $result->num_rows();
		return $result;
	}
    
	function get_transcount_by_item($from = 0, $to = 0)
	{
		$sql = "SELECT t.trans_order";
		$sql .= " FROM tbl_transaction t
			WHERE (t.trans_status = 9 OR t.trans_status = 5) ";
        if ($from != 0 || $to != 0) :  
            $timefrom = strtotime(date($from." 00:00:00"));
            $timeto = strtotime(date($to." 23:59:59"));
            $sql .= " AND t.trans_update > ".$timefrom." AND t.trans_update < ".$timeto." ";  
        endif;
		$result = $this->db->query($sql);
		$result = $result->result();
			
		return $result;
	}
    
    function get_pend($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $from = 0, $to = 0, $status = 0, $groupby = 0)
	{
        $sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY pi_itemid DESC) as ROW_NUMBER, ";
		$sql .= " pi_itemid, SUM(pi_qty) AS quantity FROM tbl_penditem WHERE pi_status != 0 ";
        $from = $from == 0 ? strtotime(date("Y-m-d 00:00:00")) : strtotime(date($from." 00:00:00"));
        $to = $to == 0 ? strtotime(date("Y-m-d 23:59:59")) : strtotime(date($to." 23:59:59"));
        $sql .= " AND pi_date > ".$from." AND pi_date < ".$to." ";
		if ($id != 0) $sql .= " AND pi_id = ".$id." ";
        if ($status == 2) : $sql .= " AND pi_status >= 1 "; 
        elseif ($status != 0) : $sql .= " AND pi_status = ".$status." "; 
        endif;
        if ($groupby != 0) $sql .= " GROUP BY pi_itemid ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
        elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_pend_by_item($isquery = 0, $count = 0, $start = 0, $limit = 0, $item = 0, $status = 0)
	{
        $sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY p.pi_id DESC) as ROW_NUMBER, ";
		$sql .= " p.pi_id, p.pi_itemid, i.item_name, p.pi_unit, p.pi_date, p.pi_status FROM tbl_penditem p ";
        $sql .= " LEFT JOIN tbl_items i ON i.item_id = p.pi_itemid ";
        $sql .= " WHERE p.pi_status != 0 ";
        if ($item != 0) $sql .= " AND p.pi_itemid = ".$item." ";
        if ($status == 2) : $sql .= " AND p.pi_status >= 1 "; 
        elseif ($status != 0) : $sql .= " AND p.pi_status = ".$status." "; 
        endif;
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
        elseif ($item != 0) $result = $result->row_array(); 
		return $result;
	}
    
    /*function get_pend($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $uid = 0, $status = 0, $search = 0)
	{
		$sql = "SELECT p.pend_id, p.pend_transid, p.pend_uid, u.user_fullname, p.pend_order, p.pend_date, p.pend_status
			FROM tbl_pending p, tbl_user u
			WHERE u.user_id = p.pend_uid 
			AND p.pend_status != 0 ";        
		if ($id != 0) $sql .= " AND p.pend_id = ".$id." ";
		if ($uid != 0) $sql .= " AND p.pend_uid = ".$uid." ";
        if ($status == 2) : $sql .= " AND p.pend_status >= 1 "; 
        elseif ($status != 0) : $sql .= " AND p.pend_status = ".$status." "; 
        endif;
		if ($search != 0 || $search != NULL) $sql .= " AND (p.pend_order LIKE '%".$search."%' OR p.pend_id LIKE '%".$search."%') ";
		$sql .= " ORDER BY p.pend_date DESC ";
		if ($limit != 0) $sql .= " LIMIT ".$start.", ".$limit;

		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
        elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}*/
	
	function get_announcement()
	{
		$sql = "SELECT TOP 1 a.ann_text, a.ann_date
			FROM tbl_announcement a
			WHERE a.ann_id = 1 ";
		$result = $this->db->query($sql);
        $result = $result->row_array(); 
		return $result;
	}
    
    function update_announcement($anntext)
	{
		$data = array(
            'ann_text'	      =>	$anntext,
            'ann_creator'	  =>	$this->profile_id(),
            'ann_date'        =>	date("U")
        );
		$this->db->where('ann_id', 1);
        $announcement = $this->db->update('tbl_announcement', $data);
		if ($announcement) return $data['ann_date'];
        else return FALSE;
	}

	function get_item($isquery = 0, $count = 0, $start = 0, $limit = 0, $cat = 0, $id = 0, $status = 0, $search = 0, $all = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY i.item_name ASC) as ROW_NUMBER, ";
		$sql .= " i.item_id, c.cat_name, i.item_quantity, i.item_qrelease, i.item_critical, i.item_order, i.item_max, u.unit_name, i.item_name, i.item_cat, i.item_desc, i.item_price, i.item_supplier, i.item_status FROM tbl_items i, tbl_units u, tbl_category c ";
        if ($all != 0) : $sql .= " WHERE i.item_status >= 0 ";
        else : $sql .= " WHERE i.item_status != 0 "; endif;
        $sql .= " AND u.unit_id = i.item_unitid AND c.cat_id = i.item_cat ";
		if ($id != 0) $sql .= " AND i.item_id = ".$id." ";
		if ($cat != 0) $sql .= " AND i.item_cat = ".$cat." ";
        if ($status != 0) $sql .= " AND i.item_status = ".$status." ";
		if ($search != 0 || $search != NULL) $sql .= " AND i.item_name LIKE '%".$search."%' ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_item_from_log($date1, $date2, $status, $type, $cat = 0)
    {
        if ($type == 1) :
            $logfrom = strtotime($date1." 00:00:00");
            $logto = strtotime($date2." 23:59:59");
        elseif ($type == 2) :
            $logfrom = strtotime($date1."-01 00:00:00");
            $logto = strtotime($date2."-".mdate('%t')." 23:59:59");
        endif;
            
        $sql = "SELECT i.item_id, i.item_quantity, i.item_name, i.item_cat, i.item_desc
			FROM tbl_items i, tbl_itemlog il
			WHERE i.item_status != 0 
            AND il.ilog_itemid = i.item_id
            AND il.ilog_date BETWEEN ".$logfrom." AND ".$logto." ";
        if ($status != 0) $sql .= " AND i.item_status = ".$status." ";        
        if ($cat != 0) $sql .= " AND i.item_cat = ".$cat." ";        
        $sql .= " GROUP BY i.item_id, i.item_quantity, i.item_name, i.item_cat, i.item_desc";
		$sql .= " ORDER BY i.item_name ASC ";
        
        $result = $this->db->query($sql);
		$result = $result->result();  
		return $result;
    }

	function get_procure($isquery = 0, $count = 0, $start = 0, $limit = 0, $item = 0, $id = 0, $status = 0, $search = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY p.pcure_date DESC) as ROW_NUMBER, ";
		$sql .= " p.pcure_id, p.pcure_quantity, p.pcure_price, p.pcure_supplier, p.pcure_ponumber, p.pcure_invoice, p.pcure_date, i.item_name, u.user_fullname 
            FROM tbl_procure p, tbl_items i, tbl_user u ";
        $sql .= " WHERE p.pcure_status >= 0 ";
        $sql .= " AND i.item_id = p.pcure_itemid AND u.user_id = p.pcure_user ";
		if ($id != 0) $sql .= " AND p.pcure_id = ".$id." ";
		if ($item != 0) $sql .= " AND p.pcure_itemid = ".$item." ";
        if ($status != 0) $sql .= " AND p.pcure_status = ".$status." ";
		if ($search != 0 || $search != NULL) $sql .= " AND p.pcure_supplier LIKE '%".$search."%' ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;

        if ($limit != 0) : $result = $this->db->limit($limit, $start); endif;
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_idept_from_log($date1, $date2, $itemid = 0, $dept = 0)
    {
        $logfrom = strtotime($date1." 00:00:00");
        $logto = strtotime($date2." 23:59:59");
            
        $sql = "SELECT SUM(il.ilog_qty) AS qty_total
            FROM tbl_itemlog il, tbl_user u, tbl_dept d
            WHERE u.user_id = il.ilog_userid 
            AND d.dept_id = u.user_dept
            AND u.user_level = 1
            AND il.ilog_task = 'REQUESTOR ITEM RELEASE' 
            AND il.ilog_status >= 2             
            AND il.ilog_date BETWEEN ".$logfrom." AND ".$logto." ";
        if ($itemid != 0) $sql .= " AND il.ilog_itemid = ".$itemid." ";        
        if ($dept != 0) $sql .= " AND u.user_dept = ".$dept." ";        
        $sql .= " GROUP BY il.ilog_itemid ";
        
        $result = $this->db->query($sql);
		$result = $result->result();  
		return $result;
    }
    
    function get_ddept_from_log($date1, $date2, $itemid = 0, $dept = 0)
    {
        $logfrom = strtotime($date1." 00:00:00");
        $logto = strtotime($date2." 23:59:59");
            
        $sql = "SELECT SUM(il.ilog_qty) AS qty_total, d.dept_abbr
            FROM tbl_itemlog il, tbl_user u, tbl_dept d, tbl_items i
            WHERE u.user_id = il.ilog_userid             
            AND i.item_id = il.ilog_itemid AND i.item_status != 0
            AND d.dept_id = u.user_dept
            AND u.user_level = 1
            AND il.ilog_task = 'REQUESTOR ITEM RELEASE' 
            AND il.ilog_status = 2             
            AND il.ilog_date BETWEEN ".$logfrom." AND ".$logto." ";
        if ($itemid != 0) $sql .= " AND il.ilog_itemid = ".$itemid." ";        
        if ($dept != 0) $sql .= " AND u.user_dept = ".$dept." ";        
        $sql .= " GROUP BY d.dept_id, d.dept_abbr ";
        
        $result = $this->db->query($sql);
		$result = $result->result();  
		return $result;
    }
    
    function get_iddept_from_log($date1, $date2, $itemid = 0, $dept = 0)
    {
        $logfrom = strtotime($date1." 00:00:00");
        $logto = strtotime($date2." 23:59:59");
            
        $sql = "SELECT SUM(il.ilog_qty) AS qty_total, d.dept_abbr
            FROM tbl_itemlog il, tbl_user u, tbl_dept d, tbl_items i
            WHERE u.user_id = il.ilog_userid 
            AND i.item_id = il.ilog_itemid AND i.item_status != 0
            AND d.dept_id = u.user_dept
            AND u.user_level = 1
            AND il.ilog_task = 'REQUESTOR ITEM RELEASE' 
            AND il.ilog_status = 2             
            AND il.ilog_date BETWEEN ".$logfrom." AND ".$logto." ";
        if ($itemid != 0) $sql .= " AND il.ilog_itemid = ".$itemid." ";        
        if ($dept != 0) $sql .= " AND u.user_dept = ".$dept." ";        
        $sql .= " GROUP BY d.dept_id, il.ilog_itemid, d.dept_abbr ";
        
        $result = $this->db->query($sql);
		$result = $result->result();  
		return $result;
    }
    
    function get_stock($in_or_out, $item_id, $date1, $date2, $type)
    {
        if ($type == 1) :
            $logfrom = strtotime($date1." 00:00:00");
            $logto = strtotime($date2." 23:59:59");
        elseif ($type == 2) :
            $logfrom = strtotime($date1."-01 00:00:00");
            $logto = strtotime($date2."-".mdate('%t')." 23:59:59");
        endif;
        
        if ($in_or_out == "IN") :
            $where = " AND (ilog_task = 'STOCK PLUS' OR ilog_task = 'STOCK CREATE') ";
        else :
            $where = " AND (ilog_task = 'STOCK MINUS' OR ilog_task = 'REQUISITION RELEASE') ";        
        endif;
            
        $sql = "SELECT SUM(il.ilog_qty) AS iqty
			FROM tbl_itemlog il
			WHERE il.ilog_status != 0 
            AND il.ilog_date BETWEEN ".$logfrom." AND ".$logto." 
            AND il.ilog_itemid = ".$item_id." ".$where;
        $sql .= " GROUP BY il.ilog_itemid WITH ROLLUP ";
        
        $result = $this->db->query($sql);
		$result = $result->result_array();  
        if ($result) return $result;
        else return NULL;
    }
    
    function get_zero_stock($isquery = 0, $count = 0, $start = 0, $limit = 0, $critical = 0)
    {            
        $sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY i.item_id ASC) as ROW_NUMBER, ";
		$sql .= " i.item_id
			FROM tbl_items i
			WHERE i.item_status != 0 "; 
        if ($critical != 0) : $sql .= " AND i.item_quantity <= i.item_critical ";
        else : $sql .= " AND i.item_quantity = 0 "; endif;
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
        $result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();		
		return $result;
    }
    
    function get_category($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $status = 0, $search = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY c.cat_name ASC) as ROW_NUMBER, ";
		$sql .= " c.cat_id, c.cat_name, c.cat_status
			FROM tbl_category c 
            WHERE c.cat_status != 0 ";
        if ($id != 0) $sql .= " AND c.cat_id = ".$id." ";
        if ($status != 0) $sql .= " AND c.cat_status = ".$status." ";
		if ($search != 0 || $search != NULL) $sql .= " AND c.cat_name LIKE '%".$search."%' ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}

	function get_user($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $empnum = 0, $level = 0, $search = 0, $all = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY u.user_fullname ASC) as ROW_NUMBER, ";
		$sql .= " u.user_id, u.user_empnum, u.user_level, u.user_fullname, u.user_dept, u.user_telno, u.user_email, u.user_status FROM tbl_user u ";
        if ($all != 0) : $sql .= " WHERE u.user_status >= 0 ";
        else : $sql .= " WHERE u.user_status > 0 "; endif;
        $sql .= " AND u.user_level != 9 ";
		if ($id != 0) $sql .= " AND u.user_id = ".$id." ";
        if ($level != 0) $sql .= " AND u.user_level = ".$level." ";
		if ($empnum != 0) $sql .= " AND u.user_empnum = ".$empnum." ";
		if ($search != 0 || $search != NULL) $sql .= " AND (u.user_fullname LIKE '%".$search."%' OR u.user_empnum LIKE '%".$search."%') ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;

		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();		
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_sadmin_user($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY u.user_fullname ASC) as ROW_NUMBER, ";
		$sql .= " u.user_id, u.user_empnum, u.user_level, u.user_fullname, u.user_dept, u.user_telno, u.user_email, u.user_status FROM tbl_user u WHERE u.user_status > 0 AND u.user_level = 9 ";
		if ($id != 0) $sql .= " AND u.user_id = ".$id." ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;

        if ($limit != 0) : $result = $this->db->limit($limit, $start); endif;
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();		
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_user_thru_approver($isquery = 0, $count = 0, $start = 0, $limit = 0, $appid = 0, $search = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY u.user_fullname ASC) as ROW_NUMBER, ";
		$sql .= " u.user_id, u.user_level, u.user_empnum, u.user_fullname, d.dept_name, u.user_dept, u.user_telno, u.user_email, u.user_status 
			FROM tbl_user u, tbl_approver a, tbl_dept d
			WHERE u.user_status > 0 
            AND u.user_level = 1 
            AND d.dept_id = u.user_dept
            AND a.appr_userid = u.user_id";
		if ($appid != 0) $sql .= " AND a.appr_approverid = ".$appid." ";
		if ($search != 0 || $search != NULL) $sql .= " AND (u.user_fullname LIKE '%".$search."%' OR u.user_empnum LIKE '%".$search."%') ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;
        
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();		
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_users_approver($userid = 0)
	{
		$sql = "SELECT a.appr_approverid
			FROM tbl_approver a ";
		if ($userid != 0) $sql .= " WHERE a.appr_userid = ".$userid." ";

		$result = $this->db->query($sql);
		$result = $result->result_array();  
		return $result;
	}
    
    function get_approver_users($userid = 0)
	{
		$sql = "SELECT a.appr_userid
			FROM tbl_approver a ";
		if ($userid != 0) $sql .= " WHERE a.appr_approverid = ".$userid." ";

		$result = $this->db->query($sql);
		$result = $result->result_array();  
		return $result;
	}
    
    function get_approver($dept = 0, $uid = 0)
	{
		$sql = "SELECT u.user_id, u.user_fullname
			FROM tbl_user u
			WHERE u.user_status > 0 
            AND u.user_level = 2 ";
		if ($dept != 0) $sql .= " AND ".$dept." IN (SELECT * FROM dbo.splitstring(u.user_dept)) ";
        if ($uid != 0) $sql .= " AND u.user_id != ".$uid." ";
		$sql .= " ORDER BY u.user_fullname ASC ";

		$result = $this->db->query($sql);
		$result = $result->result();  
		return $result;
	}
    
    function get_dept($id = 0, $division = 0)
	{
		$sql = "SELECT d.dept_id, d.dept_name, d.dept_abbr, d.dept_division
			FROM tbl_dept d 
            WHERE d.dept_status = 2 ";
        if ($id != 0) $sql .= " AND d.dept_id = ".$id." ";    
        if ($division != 0) $sql .= " AND d.dept_division = ".$division." ";    
        $sql .= " ORDER BY d.dept_name ASC ";

		$result = $this->db->query($sql);
        if ($id != 0) $result = $result->row_array(); 
		else $result = $result->result();  
		return $result;
	}
    
    function get_department($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $division = 0, $search = 0)
	{
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY d.dept_name ASC) as ROW_NUMBER, ";
		$sql .= "  d.dept_id, d.dept_name, d.dept_abbr, d.dept_division, d.dept_status
			FROM tbl_dept d 
            WHERE d.dept_status >= 1 ";
        if ($id != 0) $sql .= " AND d.dept_id = ".$id." ";    
        if ($search != 0 || $search != NULL) $sql .= " AND d.dept_name LIKE '%".$search."%' ";    
        if ($division != 0) $sql .= " AND d.dept_division = ".$division." ";    
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;

		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();		
		elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_unit()
	{
		$sql = "SELECT un.unit_id, un.unit_name
			FROM tbl_units un 
            ORDER BY un.unit_name ASC ";

		$result = $this->db->query($sql);
		$result = $result->result();  
		return $result;
	}
    
    function get_cat($id = 0)
	{
		$sql = "SELECT c.cat_id, c.cat_name
			FROM tbl_category c 
            WHERE c.cat_status = 2 ";
        if ($id != 0) $sql .= " AND c.cat_id = ".$id." ";    
        $sql .= " ORDER BY c.cat_name ASC ";

		$result = $this->db->query($sql);
        if ($id != 0) $result = $result->row_array(); 
		else $result = $result->result();  
		return $result;
	}
	
	function get_set($count = 0)
	{        
		$sql="SELECT TOP 1 s.set_announce, s.set_annexpire, s.set_mailfoot, s.set_numrows FROM tbl_setting s ";
        $result = $this->db->query($sql);
		if ($count == 1) $result = $result->num_rows();		
		else $result = $result->row_array();  
		return $result;
	}
    
    function check_if_exceed($id, $qty)
	{
		$item = $this->get_item(0, 0, 0, 0, 0, $id, 2, 0);
        if ($item['item_quantity'] < $qty) return $item['item_quantity'].' '.$item['unit_name'];
        else return FALSE;
	}
    
    function check_if_exceed_nonunit($id, $qty)
	{
		$item = $this->get_item(0, 0, 0, 0, 0, $id, 2, 0);
        if ($item['item_quantity'] < $qty) return $item['item_quantity'];
        else return NULL;
	}
    
    function check_if_qr_exceed($id, $qty)
	{
		$item = $this->get_item(0, 0, 0, 0, 0, $id, 2, 0);
        if ($item) :
            if ($item['item_qrelease'] < $qty) return $item['item_qrelease'].' '.$item['unit_name'];
            else return FALSE;
        else :
            return "0";
        endif;
	}
    
    function check_if_qr_exceed_nonunit($id, $qty)
	{
		$item = $this->get_item(0, 0, 0, 0, 0, $id, 2, 0);
        if ($item) :
                if ($item['item_qrelease'] < $qty) return strval($item['item_qrelease']);
                else return NULL;
        else :
            return "0";
        endif;
	}
    
    function get_logs($isquery = 0, $count = 0, $start = 0, $limit = 0, $id = 0, $uid = 0, $task = 0, $from = 0, $to = 0, $searchstr = 0)
	{
        $logfrom = strtotime($from." 00:00:00");
        $logto = strtotime($to." 23:59:59");
        
        $nlogfrom = strtotime("2015-06-06 00:00:00");
        $nlogto = strtotime(date("Y-m-d H:i:s"));
        
		$sql="SELECT [outer].* FROM ( ";
        $sql .= " SELECT ROW_NUMBER() OVER(ORDER BY l.logs_date DESC) as ROW_NUMBER, ";
		$sql .= " l.logs_id, l.logs_userid, u.user_fullname, l.logs_task, l.logs_date, l.logs_dataid, l.logs_status
			FROM tbl_logs l, tbl_user u
			WHERE u.user_id = l.logs_userid 
			AND l.logs_status != 0 
            AND (l.logs_task != 'ADD_CART' AND l.logs_task != 'MINUS_CART') ";
		if ($uid != 0) $sql .= " AND l.logs_userid = ".$uid." ";
		if ($task != 0 || $task != NULL) $sql .= " AND l.logs_task LIKE '%".$task."%' ";
        if ($from && $to) : $sql .= " AND l.logs_date BETWEEN ".$logfrom." AND ".$logto." ";
        else : $sql .= " AND l.logs_date BETWEEN ".$nlogfrom." AND ".$nlogto." ";
        endif;
		if ($searchstr != 0 || $searchstr != NULL) $sql .= " AND l.logs_dataid LIKE '%".$searchstr."%' ";
        $sql .= ") AS [outer] ";
        if ($limit) : 
            $sql .= " WHERE [outer].[ROW_NUMBER] BETWEEN ".(intval($start) + 1)." AND ".intval($start + $limit)." ORDER BY [outer].[ROW_NUMBER] ";
        endif;

        if ($limit != 0) : $result = $this->db->limit($limit, $start); endif;
		$result = $this->db->query($sql);
		if ($isquery == 1) $result = $result->result(); 
		elseif ($count == 1) $result = $result->num_rows();
        elseif ($id != 0) $result = $result->row_array(); 
		return $result;
	}
    
    function get_data_from_logs($id = 0, $task = 0)
	{
        switch ($task) {
        
		    case 'STOCK_CREATE' :
            case 'STOCK_UPDATE' :
            case 'STOCK_PLUS' :
            case 'STOCK_MINUS' :
            case 'ITEM_DISPLAY' :
            case 'ITEM_UNDISPLAY' :
            case 'ITEM_PEND' :
            case 'ADD_CART' :
            case 'MINUS_CART' :
            case 'REMOVE_CART' :
                $log_idata = 'Item ID: '.$id.'<br />';
                $item_data = $this->get_item(0, 0, 0, 0, 0, $id, 0, 0, 1);
                $log_idata .= $item_data['item_name'];
                break;       
            case 'TRANSACTION_CREATE' :
            case 'TRANSACTION_CANCEL' :
            case 'TRANSACTION_ENDORSE' :
            case 'TRANSACTION_PENDING' :
            case 'TRANSACTION_ADMIN_APPROVE' :
            case 'TRANSACTION_PEND' :
            case 'TRANSACTION_RELEASE' :
            case 'TRANSACTION_DISAPPROVE' :
            case 'TRANSACTION_CLOSE' :
                $trans_data = $this->get_trans_by_unix($id);
                $log_idata .= 'Transaction ID: '.$id.'<br />'; 
                $order_value = html_entity_decode($trans_data['trans_order'], ENT_QUOTES); 
                $order_value = unserialize($order_value);
                foreach ($order_value as $orderrow) :
                    $log_idata .= $orderrow['qty'].' - '.$orderrow['options']['unit'].' of '.$orderrow['name'].'<br />';
                endforeach;
                break;
            case 'USER_CREATE' :
            case 'USER_UPDATE' :
            case 'APPROVED_USER' :
            case 'DISAPPROVED_USER' :
                $log_idata = 'User ID: '.$id.'<br />';
                $user_data = $this->get_user(0, 0, 0, 0, $id, 0, 0, 0, 1);
                $log_idata .= $user_data['user_fullname'];
                break;
            case 'CAT_CREATE' :
            case 'CAT_DISPLAY' :
            case 'CAT_UNDISPLAY' :
                $log_idata = 'Stock Category ID: '.$id.'<br />';
                $cat_data = $this->get_cat($id);
                $log_idata .= $cat_data['cat_name'];
                break;              
            default :
                $log_idata = 'n/a';
        }
        return $log_idata;
	}
    
    /* CHART - START */
    
    function get_trans_count($from = NULL, $to = NULL, $all = 0)
	{        
        $cfrom = strtotime($from." 00:00:00");
        $cto = strtotime($to." 23:59:59");
        
		$sql = "SELECT COUNT(trans_id) AS numtrans, trans_status";
        $sql .= " FROM tbl_transaction";
        if ($all) : $sql .= " WHERE trans_status >= 1"; 
        else : $sql .= " WHERE trans_status >= 3 AND trans_status != 7"; 
        endif;
        if ($from && $to) : $sql .= " AND trans_date >= ".$cfrom." AND trans_date <= ".$cto; endif;
        $sql .= " GROUP BY trans_status";
        
        $result = $this->db->query($sql);
        $result = $result->result(); 
        return $result;
    }
    
    function get_trans_count_status($from = NULL, $to = NULL, $status)
	{        
        $cfrom = strtotime($from." 00:00:00");
        $cto = strtotime($to." 23:59:59");
        
		$sql = "SELECT TOP 5 COUNT(trans_id) AS numtrans, CONVERT(varchar(7), dbo.UNIXToDateTime(trans_date), 126) AS ndate";
        $sql .= " FROM tbl_transaction";
        $sql .= " WHERE trans_status = ".$status;
        if ($from && $to) : $sql .= " AND trans_date >= ".$cfrom." AND trans_date <= ".$cto; endif;
        $sql .= " GROUP BY CONVERT(varchar(7), dbo.UNIXToDateTime(trans_date), 126)";
        
        $result = $this->db->query($sql);
        $result = $result->result(); 
        return $result;
    }
    
    function get_trans_count_status2($from = NULL, $to = NULL, $status = NULL)
	{        
        $cfrom = strtotime($from." 00:00:00");
        $cto = strtotime($to." 23:59:59");
        
		$sql = "SELECT COUNT(trans_id) AS numtrans, replace(convert(varchar, dbo.UNIXToDateTime(trans_date), 111), '/', '-') AS ndate";
        $sql .= " FROM tbl_transaction";
        if ($status) : $sql .= " WHERE trans_status = ".$status;
        else : $sql .= " WHERE trans_status >= 1"; endif;
        if ($from && $to) : $sql .= " AND trans_date >= ".$cfrom." AND trans_date <= ".$cto; endif;
        $sql .= " GROUP BY replace(convert(varchar, dbo.UNIXToDateTime(trans_date), 111), '/', '-')";
        
        $result = $this->db->query($sql);
        $result = $result->result(); 
        return $result;
    }
    
    function get_trans_count_dept($status = NULL)
	{                
		$sql = "SELECT TOP 5 COUNT(t.trans_id) AS transcount, d.dept_abbr ";
        $sql .= " FROM tbl_transaction t, tbl_user u, tbl_dept d
			WHERE u.user_id = t.trans_uid
            AND d.dept_id = u.user_dept
            AND u.user_level = 1";
        $sql .= " GROUP BY d.dept_abbr 
        ORDER BY transcount DESC";
        
        $result = $this->db->query($sql);
        $result = $result->result(); 
        return $result;
    }
    
    /* CHART - END */
    

	function trans_action($value, $action, $id = 0, $userid = 0)
	{
		$value = extract($value);

		switch ($action) {

			case 'delete':

				$data = array(
					'trans_status'	=>	0
				);
				$this->db->where('trans_id', $id);
				$trans_deleted = $this->db->update('tbl_transaction', $data);

				if($trans_deleted) {
					return TRUE;
				} else {
					return FALSE;
				}			

			break;
            
            case 'pending':

				$data = array(
					'trans_status'	=>	4
				);
				$this->db->where('trans_id', $id);
				$trans_pended = $this->db->update('tbl_transaction', $data);

				if($trans_pended) {
					return TRUE;
				} else {
					return FALSE;
				}			

			break;

			case 'approve':
                            
                if ($approve == 2) :
                    $data = array(
                        'trans_appremarks'      =>	$remarks ? $remarks : "",
                        'trans_status'	        =>	$approve,
                        'trans_approver'	    =>	$userid,
                        'trans_approvedate'	    =>	date("U"),
                        'trans_update'  	    =>	date("U")
                    );
                elseif ($approve == 3 || $approve == 7) :
                    $data = array(
                        'trans_remarks'	        =>	$remarks ? $remarks : "",
                        'trans_status'	        =>	$approve,
                        'trans_admin'           =>	$userid,
                        'trans_admindate'	    =>	date("U"),
                        'trans_update'  	    =>	date("U")
                    );
                elseif ($approve == 4) :
                    $data = array(
                        'trans_status'	        =>	4,
                        'trans_admin'           =>	$userid,
                        'trans_orderdate'	    =>	date("U"),
                        'trans_update'  	    =>	date("U")
                    );
                elseif ($approve == 5) :
                    $data = array(
                        'trans_status'	        =>	5,
                        'trans_releasedate'	    =>	date("U"),
                        'trans_releaseuser'	    =>	$userid,
                        'trans_update'  	    =>	date("U")
                    );
                elseif ($approve == 8) :
                    $data = array(
                        'trans_status'	        =>	$approve,
                        'trans_approver'	    =>	$userid,
                        'trans_approvedate'	    =>	date("U"),
                        'trans_update'  	    =>	date("U")
                    );
                elseif ($approve == 9) :
                    $data = array(
                        'trans_status'	        =>	9
                    );
                endif;
            
				$this->db->where('trans_id', $id);
				$trans_approved = $this->db->update('tbl_transaction', $data);

				if($trans_approved) {;
					return TRUE;
				} else {
					return FALSE;
				}			

			break;
		}
	}
    
    function trans_edit($value, $id = 0)
	{
        $trans_data = $this->get_trans(0, 0, 0, 0, $id, 0, 0, 0, 0);
        $trans_order = html_entity_decode($trans_data['trans_order'], ENT_QUOTES);
        $trans_order = unserialize($trans_order);
        $pend_order = array();
        $pend_price = 0;
    
        foreach($trans_order as $torders) :
            foreach($value as $postval) :
                if($postval['value'] == 0) :
                    if($postval['rowid'] == $torders['rowid']) :        
                        unset($trans_order[$postval['rowid']]);
                    endif;
                else :
                    if($postval['rowid'] == $torders['rowid']) :        
                        $trans_order[$postval['rowid']]['qty'] = $postval['value'];
                        $trans_order[$postval['rowid']]['subtotal'] = $postval['value'] * $torders['price'];
                    endif;                    
                endif;
            endforeach;
        endforeach;
        
        $trans_order = serialize($trans_order);       
        $trans_order = htmlentities($trans_order, ENT_QUOTES);
        
        $data = array(
			'trans_order'	=>	$trans_order,
			'trans_adjust'  =>	2,
            'trans_update'  =>	date("U")
		);

        $this->db->where('trans_id', $id);
        $trans_edit = $this->db->update('tbl_transaction', $data);
        
        return $trans_edit;
    }
    
    function trans_update($value, $id = 0)
	{
        $trans_data = $this->get_trans(0, 0, 0, 0, $id, 0, 0, 0, 0);
        $trans_order = html_entity_decode($trans_data['trans_order'], ENT_QUOTES);
        $trans_order = unserialize($trans_order);
        $pend_order = array();
        $pend_price = 0;
    
        foreach($trans_order as $torders) :
            foreach($value as $postval) :
                if($postval['value'] == 0) :
                    if($postval['rowid'] == $torders['rowid']) :
                        //ADD PENDING DATA        
                        $pend['item_id'] = $trans_order[$postval['rowid']]['id'];                                        
                        $pend['qty'] = $trans_order[$postval['rowid']]['qty'];
                        $pend['item'] = $trans_order[$postval['rowid']]['name'];                                        
                        $pend['unit'] = $trans_order[$postval['rowid']]['options']['unit'];  
        
                        // ADD PENDING ITEM ON PENDING ARRAY                        
                        $pend_order[$postval['rowid']]['rowid'] = $postval['rowid'];
                        $pend_order[$postval['rowid']]['id'] = $trans_order[$postval['rowid']]['id'];
                        $pend_order[$postval['rowid']]['qty'] = $trans_order[$postval['rowid']]['qty'];
                        $pend_order[$postval['rowid']]['price'] = $trans_order[$postval['rowid']]['price'];
                        $pend_order[$postval['rowid']]['name'] = $trans_order[$postval['rowid']]['name'];
                        $pend_order[$postval['rowid']]['options']['unit'] = $trans_order[$postval['rowid']]['options']['unit'];
                        $pend_order[$postval['rowid']]['subtottal'] = $trans_order[$postval['rowid']]['subtottal'];
        
                        $pend_subtotal = $trans_order[$postval['rowid']]['qty'] * $trans_order[$postval['rowid']]['price'];
        
                        $pend_order[$postval['rowid']]['subtottal'] = $pend_subtotal;
        
                        $pend_price += $pend_subtotal;
        
                        // ADD PENDING ITEM AS GENERAL
                        $add_pending = $this->add_pending($pend, $id, $trans_data['trans_uid']);
        
                        unset($trans_order[$postval['rowid']]);
                        //AUDIT TRAIL
                        $log = $this->Core->log_action("ITEM_PEND", $pend['item_id'], $this->profile_id());
                    endif;
                else :
                    if($postval['rowid'] == $torders['rowid']) :
                        //ADD PENDING DATA        
                        $pend['item_id'] = $trans_order[$postval['rowid']]['id'];                                        
                        $pend['qty'] = $trans_order[$postval['rowid']]['qty'] - $postval['value'];
                        $pend['item'] = $trans_order[$postval['rowid']]['name'];                                        
                        $pend['unit'] = $trans_order[$postval['rowid']]['options']['unit'];
        
                        // ADD PENDING ITEM ON PENDING ARRAY 
                        $pend_qty = $trans_order[$postval['rowid']]['qty'] - $postval['value'];        
        
                        $pend_order[$postval['rowid']]['rowid'] = $postval['rowid'];
                        $pend_order[$postval['rowid']]['id'] = $trans_order[$postval['rowid']]['id'];
                        $pend_order[$postval['rowid']]['qty'] = $pend_qty;
                        $pend_order[$postval['rowid']]['price'] = $trans_order[$postval['rowid']]['price'];
                        $pend_order[$postval['rowid']]['name'] = $trans_order[$postval['rowid']]['name'];
                        $pend_order[$postval['rowid']]['options']['unit'] = $trans_order[$postval['rowid']]['options']['unit'];
        
                        $pend_subtotal = $pend_qty * $trans_order[$postval['rowid']]['price'];
        
                        $pend_order[$postval['rowid']]['subtottal'] = $pend_subtotal;
        
                        $pend_price += $pend_subtotal;
        
                        // ADD PENDING ITEM AS GENERAL
                        $add_pending = $this->add_pending($pend, $id, $trans_data['trans_uid']);
        
                        $trans_order[$postval['rowid']]['qty'] = $postval['value'];
                        $trans_order[$postval['rowid']]['subtotal'] = $postval['value'] * $torders['price'];
                        //AUDIT TRAIL
                        $log = $this->Core->log_action("ITEM_PEND", $pend['item_id'], $this->profile_id());
                    endif;                    
                endif;
            endforeach;
        endforeach;
        
        $trans_order = serialize($trans_order);       
        $trans_order = htmlentities($trans_order, ENT_QUOTES);
        
        $data = array(
			'trans_order'	=>	$trans_order,
			'trans_adjust'  =>	1,
            'trans_update'  =>	date("U")
		);

        $this->db->where('trans_id', $id);
        $trans_edit = $this->db->update('tbl_transaction', $data);
        
        $pend_order = serialize($pend_order);       
        $pend_order = htmlentities($pend_order, ENT_QUOTES);
        
        $pdata = array(
			'trans_uid'             =>	$trans_data['trans_uid'],
			'trans_order'           =>	$pend_order,
			'trans_originorder'	    =>	$trans_data['trans_originorder'],
			'trans_adjust'          =>	0,
			'trans_pendorig'        =>	$id,
            'trans_price'           =>	number_format($pend_price, 2, '.', ''),
			'trans_date'	        =>	date("U"),
            'trans_update'          =>	date("U"),
			'trans_status'          =>	4
		);

        $trans_pendadd = $this->db->insert('tbl_transaction', $pdata);
        $trans_pendaddid = $this->db->insert_id();
        
        return $trans_pendaddid;
    }
    
    function add_pending($value, $id, $uid)
	{

        $data = array(
            'pi_itemid'     =>	$value['item_id'],
            'pi_unit'       =>	$value['unit'],
            'pi_qty'        =>	$value['qty'],
            'pi_date'       =>	date("U"),
            'pi_status'     =>	1
        );

        $pend_add = $this->db->insert('tbl_penditem', $data);

        if($pend_add) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /*function add_pending($value, $id, $uid)
	{
		$pend = serialize($value);       
        $pend = htmlentities($pend, ENT_QUOTES);

        $data = array(
            'pend_transid'      =>	$id,
            'pend_uid'          =>	$uid,
            'pend_order'        =>	$pend,
            'pend_status'       =>	1,
            'pend_date'         =>	date("U")
        );

        $pend_add = $this->db->insert('tbl_pending', $data);

        if($pend_add) {
            return TRUE;
        } else {
            return FALSE;
        }
    }*/
    
    function pend_action($value, $action, $id = 0)
	{
		$value = extract($value);

		switch ($action) {

			case 'status':

				$data = array(
					'pend_status'	=>	($pend_status == 2 ? 1 : 2)
				);
				$this->db->where('pend_id', $id);
				$pend_solve = $this->db->update('tbl_pending', $data);

				if($pend_solve) {
					if ($pend_status == 2) return 1;
					else return 2;
				} else {
					return FALSE;
				}			

			break;
		}
	}
    
    function item_action($value, $action, $id = 0)
	{
		$value = extract($value);

		switch ($action) {
            case 'add':	

				$data = array(
					'item_quantity'	   =>	$item_quantity,
                    'item_qrelease'	   =>	$item_quantity,
					'item_critical'    =>	$item_critical ? $item_critical : 0,
					'item_order'       =>	$item_order ? $item_order : 0,
					'item_max'         =>	$item_max ? $item_max : 0,
					'item_price'       =>	$item_price ? number_format($item_price, 2) : number_format(1, 2),
					'item_unitid'      =>	$item_unitid,
					'item_name'        =>	$item_name,
					'item_desc'        =>	$item_desc,
					'item_supplier'	   =>	strtoupper($item_supplier),
					'item_cat'         =>	$item_cat,
					'item_status'	   =>	$item_status,
					'item_date'        =>	date("U")
				);

				$item_add = $this->db->insert('tbl_items', $data);

				if($item_add) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'update':

				$data = array(
					'item_critical'    =>	$item_critical ? $item_critical : 0,
					'item_order'       =>	$item_order ? $item_order : 0,
					'item_max'         =>	$item_max ? $item_max : 0,
					'item_name'        =>	$item_name,
					'item_desc'        =>	$item_desc,
					'item_price'	   =>	$item_price ? number_format($item_price, 2) : number_format(1, 2),
					'item_supplier'	   =>	strtoupper($item_supplier),
					'item_cat'         =>	$item_cat,
					'item_status'	   =>	$item_status
				);
				$this->db->where('item_id', $item_id);
				$item_updated = $this->db->update('tbl_items', $data);				

				if($item_updated) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'update_proc':

				$data = array(
					'item_price'       =>	number_format($price, 2),
					'item_supplier'	   =>	strtoupper($supplier)
				);
				$this->db->where('item_id', $id);
				$item_updated = $this->db->update('tbl_items', $data);				

				if($item_updated) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'plus_qty':
            
				$this->db->where('item_id', $id);
                $this->db->set('item_quantity', 'item_quantity + '.$quantity, FALSE);
                $this->db->set('item_qrelease', 'item_qrelease + '.$quantity, FALSE);
				$item_plusone = $this->db->update('tbl_items');				
            
                $item = $this->get_item(0, 0, 0, 0, 0, $id, 0, 0);

				if($item_plusone) {
					return $item['item_quantity'];
				} else {
					return FALSE;
				}

			break;
            
            case 'minus_qty':
            
                $itemini = $this->get_item(0, 0, 0, 0, 0, $id, 0, 0);
            
                if ($itemini['item_quantity'] > $itemini['item_qrelease']) :
            
                    if ($itemini['item_qrelease'] != 0 && $itemini['item_qrelease'] >= $quantity) :            
            
                        $this->db->where('item_id', $id);
                        $this->db->set('item_quantity', 'item_quantity - '.$quantity, FALSE);
                        $this->db->set('item_qrelease', 'item_qrelease - '.$quantity, FALSE);
                        $item_minusone = $this->db->update('tbl_items');	
            
                        $item = $this->get_item(0, 0, 0, 0, 0, $id, 0, 0);
                        $item_count = $item['item_quantity'];
            
                    endif;
            
                elseif ($itemini['item_quantity'] == $itemini['item_qrelease']) :
            
                    $this->db->where('item_id', $id);
                    $this->db->set('item_quantity', 'item_quantity - '.$quantity, FALSE);
                    $this->db->set('item_qrelease', 'item_qrelease - '.$quantity, FALSE);
                    $item_minusone = $this->db->update('tbl_items');				
            
                    $item = $this->get_item(0, 0, 0, 0, 0, $id, 0, 0);
            
                    if ($item['item_quantity'] < 0) :
                        $data = array(
                            'item_quantity' => 	0,
                            'item_qrelease' => 	0
                        );
                        $this->db->where('item_id', $id);
                        $item_zero = $this->db->update('tbl_items', $data);				
                        $item_count = 0;
                    else:
                        $item_count = $item['item_quantity'];
                    endif;
            
                endif;

				if($item_minusone) {
					return $item_count;
				} else {
					return FALSE;
				}

			break;

			case 'delete':

				$data = array(
					'item_status'	=>	0
				);
				$this->db->where('item_id', $id);
				$item_deleted = $this->db->update('tbl_items', $data);

				if($item_deleted) {
					return TRUE;
				} else {
					return FALSE;
				}			

			break;

			case 'status':

				$data = array(
					'item_status'	=>	($item_status == 2 ? 1 : 2)
				);
				$this->db->where('item_id', $id);
				$item_approve = $this->db->update('tbl_items', $data);

				if($item_approve) {
					if ($item_status == 2) return 1;
					else return 2;
				} else {
					return FALSE;
				}			

			break;
		}
	}
    
    function procure_action($value, $action, $id = 0)
	{
		$value = extract($value);

		switch ($action) {
            case 'add':	

				$data = array(
					'pcure_itemid'	   =>	$itemid,
					'pcure_quantity'   =>	$quantity,
                    'pcure_supplier'   =>	strtoupper($supplier),
					'pcure_ponumber'   =>	strtoupper($ponum),
					'pcure_price'      =>	number_format($price, 2),
					'pcure_invoice'    =>	strtoupper($invoice),
					'pcure_user'       =>	$userid,
					'pcure_status'	   =>	2,
					'pcure_date'       =>	date("U")
				);

				$pcure_add = $this->db->insert('tbl_procure', $data);

				if($pcure_add) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;
            case 'delete':	

				$data = array(
					'pcure_status'	=>	0
				);
				$this->db->where('pcure_id', $id);
				$pcure_deleted = $this->db->update('tbl_procure', $data);

				if($pcure_deleted) {
					return TRUE;
				} else {
					return FALSE;
				}			

			break;
		}
	}
    
    function cat_action($cat_name, $action, $id = 0)
	{
		switch ($action) {
            case 'add':	

				$data = array(
					'cat_name'         =>	$cat_name,
					'cat_status'       =>	2
				);

				$cat_add = $this->db->insert('tbl_category', $data);	                    
                $cat_addid = $this->db->insert_id();	                    
                //AUDIT TRAIL
                $log = $this->Core->log_action("CAT_CREATE", $cat_addid, $this->profile_id());

				if($cat_add) {
					return $cat_addid;
				} else {
					return FALSE;
				}

			break;

			case 'status':

                $value = extract($cat_name);
				$data = array(
					'cat_status'	=>	($cat_status == 2 ? 1 : 2)
				);
				$this->db->where('cat_id', $id);
				$cat_approve = $this->db->update('tbl_category', $data);

				if($cat_approve) {
					if ($cat_status == 2) return 1;
					else return 2;
				} else {
					return FALSE;
				}			

			break;
		}
	}
    
    function item_adapp($item_id, $qty)
    {
        $this->db->where('item_id', $item_id);
        $this->db->set('item_qrelease', 'item_qrelease - '.$qty, FALSE);
        $itemqrelease = $this->db->update('tbl_items');				
    
        if($itemqrelease) {
            echo TRUE;
        } else {
            echo FALSE;
        }
    }
    
    function item_release($item_id, $qty)
    {
        $this->db->where('item_id', $item_id);
        $this->db->set('item_quantity', 'item_quantity - '.$qty, FALSE);
        $itemrelease = $this->db->update('tbl_items');				
    
        if($itemrelease) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

	function user_action($value, $action, $id = 0)
	{
		$value = extract($value);

		switch ($action) {
			case 'add':
			case 'add_approve':

				if ($action == "add_approve") $user_status = 2;
				else $user_status = 1;
				
				$user_passw = md5($user_password1);

				$data = array(
					'user_empnum'	=>	$user_empnum,
					'user_level'	=>	$user_level,
					'user_passw'	=>	$user_passw,
					'user_fullname'	=>	$user_fullname,
					'user_dept'		=>	$user_dept,
					'user_telno'	=>	$user_telno,
					'user_email'	=>	$user_email,
					'user_status'	=>	$user_status,
					'user_date'		=>	date("U")
				);

				$user_add = $this->db->insert('tbl_user', $data);
                $last_id = $this->db->insert_id();
            
                //AUDIT TRAIL
                $log = $this->Core->log_action("USER_CREATE", $last_id, $this->profile_id());    
            
                foreach($user_approvers as $ua) :
                    $data = array(
                        'appr_approverid'   =>	$ua,
                        'appr_userid'       =>	$last_id
				    );              
                    $appr_add = $this->db->insert('tbl_approver', $data);
                endforeach;                    

				if($user_add) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'update_profile':

				$data = array(
					'user_empnum'	=>	$user_empnum,
					'user_fullname' => 	$user_fullname,
					'user_telno'	=> 	$user_telno, 
					'user_email'	=> 	$user_email
				);
				$this->db->where('user_id', $user_id);
				$profile_updated = $this->db->update('tbl_user', $data);

				if($profile_updated) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;
            
            case 'update':

				$data = array(
					'user_empnum'	=>	$user_empnum,
					'user_fullname' => 	$user_fullname,
					'user_level'    => 	$user_level,
					'user_dept'		=> 	$user_dept, 
					'user_telno'	=> 	$user_telno, 
					'user_email'	=> 	$user_email
				);
				$this->db->where('user_id', $user_id);
				$user_updated = $this->db->update('tbl_user', $data);	
            
                //AUDIT TRAIL
                $log = $this->Core->log_action("USER_UPDATE", $user_id, $this->profile_id());
            
                $this->db->where('appr_userid', $user_id);
                $this->db->delete('tbl_approver'); 
                
                if ($user_approvers) :
                    foreach($user_approvers as $ua) :
                        $data = array(
                            'appr_approverid'   =>	$ua,
                            'appr_userid'       =>	$user_id
                        );              
                        $appr_add = $this->db->insert('tbl_approver', $data);
                    endforeach;     
                endif;

				if($user_updated) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'edit_password':

				$npassword = md5($user_password1);
			
				$data = array(
					'user_passw'	=>	$npassword
				);
				$this->db->where('user_id', $id);
				$user_edit_password = $this->db->update('tbl_user', $data);		

				if($user_edit_password) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'delete':

				$data = array(
                    'user_empnum'   =>  'DELETED',
                    'user_email'   =>  'DELETED',
					'user_status'	=>	0
				);
				$this->db->where('user_id', $id);
				$user_deleted = $this->db->update('tbl_user', $data);

				if($user_deleted) {
					return TRUE;
				} else {
					return FALSE;
				}			

			break;

			case 'approve':

				$data = array(
					'user_status'	=>	($user_status == 2 ? 1 : 2)
				);
				$this->db->where('user_id', $id);
				$user_approved = $this->db->update('tbl_user', $data);

				if($user_approved) {
					if ($user_status == 2) return 1;
					else return 2;
				} else {
					return FALSE;
				}			

			break;
		}
	}

	function dept_action($value, $action, $id = 0)
	{
		$value = extract($value);

		switch ($action) {
			case 'add':
                
                $dept_status = 2;

				$data = array(
					'dept_name'	    =>	$dept_name,
					'dept_abbr'	    =>	$dept_abbr,
					'dept_division' =>	$dept_division,
					'dept_status' =>	$dept_status
				);

				$dept_add = $this->db->insert('tbl_dept', $data);
                $last_id = $this->db->insert_id();
            
                //AUDIT TRAIL
                $log = $this->Core->log_action("DEPT_CREATE", $last_id, $this->profile_id());    

				if($dept_add) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;
            
            case 'update':

				$data = array(
					'dept_name'	    =>	$dept_name,
					'dept_abbr'	    =>	$dept_abbr
				);
				$this->db->where('dept_id', $dept_id);
				$dept_updated = $this->db->update('tbl_dept', $data);	
            
                //AUDIT TRAIL
                $log = $this->Core->log_action("DEPT_UPDATE", $dept_id, $this->profile_id());

				if($dept_updated) {
					return TRUE;
				} else {
					return FALSE;
				}

			break;

			case 'delete':

				$data = array(
					'dept_status'	=>	0
				);
				$this->db->where('dept_id', $id);
				$dept_deleted = $this->db->update('tbl_dept', $data);

				if($dept_deleted) {
					return TRUE;
				} else {
					return FALSE;
				}			

			break;

			case 'approve':

				$data = array(
					'dept_status'	=>	($dept_status == 2 ? 1 : 2)
				);
				$this->db->where('dept_id', $id);
				$dept_approved = $this->db->update('tbl_dept', $data);

				if($dept_approved) {
					if ($dept_status == 2) return 1;
					else return 2;
				} else {
					return FALSE;
				}			

			break;
		}
	}

	function add_csess($value, $uid)
	{
		$sql = "SELECT csess_id
			FROM tbl_cartsession 
			WHERE csess_uid = ".$uid;

		$query = $this->db->query($sql);
		$result = $query->num_rows();

		if($result <= 0) :
			$data = array(
				'csess_uid'		=>	$uid,
				'csess_value'	=>	$value,
				'csess_status'	=>	2,
				'csess_ip'		=>	$this->input->ip_address(),
				'csess_date'	=>	date("U")
			);

			$csess_add = $this->db->insert('tbl_cartsession', $data);
		else :
			$data = array(
				'csess_value'	=>	$value,
				'csess_ip'		=>	$this->input->ip_address(),
				'csess_date'	=>	date("U")
			);
			$this->db->where('csess_uid', $uid);
			$csess_add = $this->db->update('tbl_cartsession', $data);
		endif;

		return $csess_add;
	}

	function del_csess($uid)
	{		
		$data = array(
			'csess_uid'		=>	$uid
		);
		$csess_delete = $this->db->delete('tbl_cartsession', $data);

		return $csess_delete;
	}

	function retrieve_csess($uid)
	{		
		$sql = "SELECT csess_value
			FROM tbl_cartsession 
			WHERE csess_uid = ".$uid;

		$query = $this->db->query($sql);
		if ($result = $query->row_array()) :
			$csess_value = html_entity_decode($result['csess_value'], ENT_QUOTES); 
			$csess_value = unserialize($csess_value);

			return $csess_value;
		endif;
	}

	function db_cart()
	{
		$session_data = $this->session->all_userdata();		
		$cart_to_store = $this->cart->contents();
		$cart_array = serialize($cart_to_store);
		$cart_array = htmlentities($cart_array, ENT_QUOTES);

		if ($cart_to_store) :
			$this->add_csess($cart_array, $session_data['session_uid']);
		else :
			$this->del_csess($session_data['session_uid']);						
		endif;
	}	

	function do_cart()
	{
		$session_data = $this->session->all_userdata();
		if (!$this->cart->contents()) : 
			if ($csess_data = $this->retrieve_csess($session_data['session_uid'])) :

				$csess_array = array(
	               'cart_contents'		=> $csess_data
	           	);

				$this->session->set_userdata($csess_array);

				$cart_item = '<div class="cartupper">';

                $item_price = 0;
				foreach ($csess_data as $citems):
					$cart_item .= '<a title="Remove Item" class="removecart nodecor cursorpoint" attribute="'.$citems['id'].'"><i class="fa fa-times-circle fa-lg redtext"></i></a>&nbsp;&nbsp;<a title="Add 1 Unit" class="pluscart nodecor cursorpoint" attribute="'.$citems['id'].'" attribute2="'.$citems['price'].'" attribute3="'.$citems['limit'].'"><i class="fa fa-plus-square fa-lg greentext"></i></a>&nbsp;&nbsp;<a title="Deduct 1 Unit" class="minuscart nodecor cursorpoint" attribute="'.$citems['id'].'"><i class="fa fa-minus-square fa-lg greentext"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<b>'.$citems['qty'].' '; 
					foreach ($citems['options'] as $option_name => $option_value):
						$option_value = ($citems['qty'] > 1 ? $option_value."s" : $option_value);
						$option_value = ($option_value == "boxs" ? "boxes" : $option_value);
						$option_value = ($option_value == "inchs" ? "inches" : $option_value);
						$cart_item .= $option_name == "unit" ? $option_value : "";
					endforeach;
					$cart_item .= ' of '.$citems['name'].'</b><br />';
                    $item_price += $citems['qty'] * $citems['price'];
				endforeach;

				$cart_item .= '</div><div class="centertalign centermargin margintop30"><input type="hidden" name="trans_price" value="'.$item_price.'" /><button name="btnreviewcart" class="reviewcart btn">Checkout <i class="fa fa-shopping-basket"></i></button>&nbsp;<button name="btnclearcart" class="clearcart redbtn">Clear Requisition Slip <i class="fa fa-eraser"></i></button></div>';	

			else :
				$cart_item = '<center><br><b>Requisition Slip is empty</b><br><br><br>Click <span class="smallbtn">Add to Order <i class="fa fa-caret-right"></i></span> on the left to place an order</center>';
			endif;
		else: 
			$cart_item = '<div class="cartupper">';
            
            $item_price = 0;
			foreach ($this->cart->contents() as $citems):
				$cart_item .= '<a title="Remove Item" class="removecart nodecor cursorpoint" attribute="'.$citems['id'].'"><i class="fa fa-times-circle fa-lg redtext"></i></a>&nbsp;&nbsp;<a title="Add 1 Unit" class="pluscart nodecor cursorpoint" attribute="'.$citems['id'].'" attribute2="'.$citems['price'].'" attribute3="'.$citems['limit'].'"><i class="fa fa-plus-square fa-lg greentext"></i></a>&nbsp;&nbsp;<a title="Deduct 1 Unit" class="minuscart nodecor cursorpoint" attribute="'.$citems['id'].'"><i class="fa fa-minus-square fa-lg greentext"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<b>'.$citems['qty'].' '; 
				if ($this->cart->has_options($citems['rowid']) == TRUE):
					foreach ($this->cart->product_options($citems['rowid']) as $option_name => $option_value):
						$option_value = ($citems['qty'] > 1 ? $option_value."s" : $option_value);
						$option_value = ($option_value == "boxs" ? "boxes" : $option_value);
						$option_value = ($option_value == "inchs" ? "inches" : $option_value);
						$cart_item .= $option_name == "unit" ? $option_value : "";
					endforeach;
				endif;
				$cart_item .= ' of '.$citems['name'].'</b><br />';
                $item_price += $citems['qty'] * $citems['price'];
			endforeach;

			$cart_item .= '</div><div class="centertalign centermargin margintop30"><input type="hidden" name="trans_price" value="'.$item_price.'" /><button name="btnreviewcart" class="reviewcart btn">Checkout <i class="fa fa-shopping-basket"></i></button>&nbsp;<button name="btnclearcart" class="clearcart redbtn">Clear Requisition Slip <i class="fa fa-eraser"></i></button></div>';
		endif;

		return $cart_item;
	}

	function review_cart()
	{
		$cart_item = '<div class="cartrupper"><center><b>Are you sure with your order</b></center><br />';

        $item_price = 0;
		foreach ($this->cart->contents() as $citems):
			$cart_item .= $citems['qty'].' '; 
			if ($this->cart->has_options($citems['rowid']) == TRUE):
				foreach ($this->cart->product_options($citems['rowid']) as $option_name => $option_value):
					$option_value = ($citems['qty'] > 1 ? $option_value."s" : $option_value);
					$option_value = ($option_value == "boxs" ? "boxes" : $option_value);
					$option_value = ($option_value == "inchs" ? "inches" : $option_value);
					$cart_item .= $option_name == "unit" ? $option_value : "";
				endforeach;
			endif;
			$cart_item .= ' of '.$citems['name'].'<br />';
            $item_price += $citems['qty'] * $citems['price'];
		endforeach;

		$cart_item .= '</div><div class="lefttalign centermargin margintop30"><b>Your Remarks</b><br><textarea name="reqremark" id="reqremark" cols="20" rows="4" maxlength="1000" class="reqremark"></textarea></div><div class="centertalign centermargin margintop30 reviewbtn"><input type="button" name="btncheckcart" class="processcart btn" value="Yes" attribute="'.$item_price.'" />&nbsp;<input type="button" name="btndocart" class="docart redbtn" value="No" /></div>';

		return $cart_item;
	}

	function process_cart($cart_price, $reqremark, $srflevel = 0)
	{
		$session_data = $this->session->all_userdata();		
		
		$cart_data = $this->cart->contents();

		$cart_array = serialize($cart_data);
		$cart_array = htmlentities($cart_array, ENT_QUOTES);

		$data = array(
			'trans_uid'             =>	$session_data['session_uid'],
			'trans_order'           =>	$cart_array,
			'trans_originorder'	    =>	$cart_array,
            'trans_reqremarks'	    =>	$reqremark,
			'trans_adjust'          =>	0,
            'trans_price'           =>	number_format($cart_price, 2, '.', ''),
			'trans_date'	        =>	date("U"),
            'trans_update'          =>	date("U"),
			'trans_status'          =>	$srflevel ? 2 : 1
		);

		$trans_add = $this->db->insert('tbl_transaction', $data);
        $trans_addid = $this->db->insert_id();
        
        //AUDIT TRAIL
        $log = $this->Core->log_action("TRANSACTION_CREATE", date("Y").'-'.$data['trans_date'], $this->profile_id());
        
		$clear_cart = $this->cart->destroy();
		$this->Core->db_cart();

		if($trans_add) {
			return $trans_addid;
		} else {
			return 0;
		}
	}
    
    # ITEM LOGS

	function ilog_action($itemid, $task, $qty, $userid)
	{		
		if ($itemid && $task && $qty && $userid) :            
            $data = array(
				'ilog_itemid'	=>	$itemid,
				'ilog_task'	    =>	$task,
				'ilog_qty'  	=>	$qty,
				'ilog_userid'	=>	$userid,
                'ilog_ipadd'	=>	$_SERVER['REMOTE_ADDR'],
				'ilog_date'	    =>	date("U"),
                'ilog_status'   =>  2                
			);

			$ilog_add = $this->db->insert('tbl_itemlog', $data);

			if($ilog_add) {
				return TRUE;
			} else {
				return FALSE;
			}
		else :
			return FALSE;
		endif;
	}
    
    # LOGS (AUDIT TRAIL)

	function log_action($task, $data = 0, $userid)
	{		
		if ($task && $userid) :
            $data = array(
				'logs_userid'	=>	$userid,
				'logs_date'	    =>	date("U"),
				'logs_task'	    =>	$task,
				'logs_dataid'	=>	$data,
                'logs_status'   =>  2                
			);

			$log_add = $this->db->insert('tbl_logs', $data);

			if($log_add) {
				return TRUE;
			} else {
				return FALSE;
			}
		else :
			return FALSE;
			return FALSE;
		endif;
	}
    
    # SETTING
    
    function set_update($value)
	{
		$value = extract($value);        
        $numrow = $this->get_set(1);
        
        $set_timestamp = mdate("%U");
        
        if ($numrow != 0) :
            $data = array(
                'set_announce'	=>	$set_announce,
                'set_annexpire' => 	strtotime($set_annexpire." 00:00:00"),
                'set_mailfoot'  => 	$set_mailfoot, 
                'set_numrows'	=> 	$set_numrows, 
                'set_date'      => 	$set_timestamp
            );
            $set_update = $this->db->update('tbl_setting', $data);		            
        else :
            $data = array(
                'set_announce'	=>	$set_announce,
                'set_annexpire' => 	strtotime($set_annexpire." 00:00:00"),
                'set_mailfoot'  => 	$set_mailfoot, 
                'set_numrows'	=> 	$set_numrows, 
                'set_date'      => 	$set_timestamp
            );
            $set_update = $this->db->insert('tbl_setting', $data);
        endif;

        if($set_update) :
            return TRUE;
        else :
            return FALSE;
        endif;
	}
    
    # MISCELLANEOUS
    
    function curPageURL() 
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}

	function display_level($level)
	{
		switch ($status) {
			case 1: return "Requestor"; break;
			case 2: return "Approver"; break;
			case 6: return "Admin Read-Only"; break;
			case 7: return "Admin Assistant"; break;
			case 8: return "Admin"; break;
			case 9: return "Super Admin"; break;
		}
	}

	function display_status($status, $level)
	{
		switch ($status) {
            case 0: return "<div class='statdiv redstat'>Cancelled</div>"; break;
			case 1: if ($level == 2) : return "<div class='statdiv greenstat'>For your Approval</div>"; else : return "<div class='statdiv graystat'>For Approval</div>"; endif; break;
			case 2: if ($level == 8) : return "<div class='statdiv greenstat'>Endorse to You</div>"; else : return "<div class='statdiv graystat'>Approved</div>"; endif; break;
			case 3: if ($level == 7) : return "<div class='statdiv greenstat'>Endorse to You</div>"; elseif ($level == 8) : return "<div class='statdiv graystat'>Endorse for Release</div>"; else : return "<div class='statdiv purplestat'>For Release</div>"; endif; break;
			case 4: return "<div class='statdiv orangestat'>Pending</div>"; break;
			case 5: if ($level == 7 || $level == 8) : return "<div class='statdiv graystat'>Waiting to be Close by Requestor</span>"; else : return "<div class='statdiv greenstat'>Item Released"; endif; break;
            case 7: return "<div class='statdiv redstat'>Admin Declined</div>"; break;
			case 8: return "<div class='statdiv redstat'>Declined</div>"; break;
			case 9: return "<div class='statdiv bluestat'>Closed</div>"; break;
		}
	}

}

?>