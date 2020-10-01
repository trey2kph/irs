    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <?php if ($level > 3) : ?>
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                <?php else : ?> 
                <?php if ($level == 2) : ?>
                <a href="<?php echo WEB?>" title="View Transactions">
                    <div class="floatmainbutton cursorpoint"><i class="fa fa-bars fa-2x whitetext mediumtext"></i></div>
                </a>
                <?php endif; ?>   
                <div class="lowerrightbig">
                <?php endif; ?>
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS User Management</div>
                    <?php if ($user_mode == 2) : ?>
                    <div class="user_list">
                        <form name="frmadduser" method="POST" enctype="multipart/form-data"> 
                            <div class="fields">
                                <div class="lfield valigntop">Employee No. <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_empnum" value="<?=$post['user_empnum'];?>" class="txtbox" maxlength="12" /><?php echo '<br /><span class="redtext">'.form_error('user_empnum').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Name <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_fullname" value="<?=$post['user_fullname'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_fullname').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Level <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><?php echo $this->Form->level_dropdown("user_level", $post['user_level'], 'id="user_level" class="user_level"'); ?><?php echo '<br /><span class="redtext">'.form_error('user_level').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Department/Project <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><?php echo $this->Form->dept_dropdown("user_dept", "user_dept", $post['user_dept']); ?><?php echo '<br /><span class="redtext">'.form_error('user_dept').'</span>'; ?></div>
                            </div>
                            
                            <div class="fields">
                                <div class="lfield valigntop">Contact Number <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_telno" value="<?=$post['user_telno'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_telno').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Approver <span class="redtext">*</span></div>
                                <div class="rfield valigntop">
                                    <select name="user_approvers[]" id="user_approvers" class="user_approvers width200" multiple>
                                        
                                        <?php if ($post['user_level'] == 1) {
                                            $approver = $this->Core->get_approver();
    
                                            $sel = '';
                                            if ($approver) {   
                                                foreach ($approver as $appr) {
                                                    if (in_array($appr->user_id, $post['user_approvers'])) 
                                                    {                                                        
                                                        $ua_select = 'selected="selected"';
                                                    }
                                                    else
                                                    {                                                        
                                                        $ua_select = '';
                                                    }
                                                    $sel .= '<option value="'.$appr->user_id.'" '.$ua_select.'>'.$appr->user_fullname.'</option>';
                                                }   
                                            }
    
                                            echo $sel;   
                                        } ?>
                                    </select>
                                    <?php echo '<br /><span class="redtext">'.form_error('user_approvers').'</span>'; ?>
                                </div>                                
                            </div>    
                            <div class="fields">
                                <div class="lfield valigntop">Email Address <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_email" value="<?=$post['user_email'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_email').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Password <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="password" name="user_password1" autocomplete="off" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_password1').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Confirm Password <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="password" name="user_password2" autocomplete="off" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_password2').'</span>'; ?></div>
                            </div>
                            <div class="fields centertalign margintop10">                        
                                <input type="submit" name="btnadduser" value="Register" class="btn" />&nbsp;<input type="button" name="btncancel" value="Cancel" class="redbtn" onClick="parent.location='<?php echo WEB; ?>/user'" />
                            </div>
                            <div class="fields margintop10">  
                                <i class="redtext">* required</i>
                            </div>
                        </form> 
                    </div>
                    <?php elseif ($user_mode == 1) : ?>
                    <div class="user_list">
                        <form name="frmedituser" method="POST" enctype="multipart/form-data">                                                
                            <div class="fields">
                                <div class="lfield valigntop">Employee No. <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_empnum" value="<?=$post['user_empnum'] ? $post['user_empnum'] : $user_data['user_empnum'];?>" class="txtbox" maxlength="12" /><?php echo '<br /><span class="redtext">'.form_error('user_empnum').'</span>'; ?></div>
                            </div>    
                            <div class="fields">
                                <div class="lfield valigntop">Name <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_fullname" value="<?=$post['user_fullname'] ? $post['user_fullname'] : $user_data['user_fullname'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_fullname').'</span>'; ?></div>
                            </div>    
                            <div class="fields">
                                <div class="lfield valigntop">Level</div>
                                <div class="rfield valigntop"><?php echo $this->Form->level_dropdown("user_level", $post['user_level'] ? $post['user_level'] : $user_data['user_level'], 'id="user_level" class="user_level"'); ?><?php echo '<br /><span class="redtext">'.form_error('user_level').'</span>'; ?></div>
                            </div>  
                            <div class="fields">
                                <div class="lfield valigntop">Department/Project <span class="redtext">*</span></div>
                                <div class="rfield valigntop">
                                    <?php if ($user_data['user_level'] == 2) : ?>
                                    <?php echo $this->Form->dept_dropmulti("user_dept[]", "user_dept", $post['user_dept'] ? $post['user_dept'] : $user_data['user_dept']); ?><?php echo '<br /><span class="redtext">'.form_error('user_dept').'</span>'; ?>
                                    <?php else : ?>
                                    <?php echo $this->Form->dept_dropdown("user_dept", "user_dept", $post['user_dept'] ? $post['user_dept'] : $user_data['user_dept']); ?><?php echo '<br /><span class="redtext">'.form_error('user_dept').'</span>'; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="fields">
                                <div class="lfield valigntop">Approver <span class="redtext">*</span></div>
                                <div class="rfield valigntop">
                                    <select name="user_approvers[]" id="user_approvers" class="user_approvers width200" multiple="multiple"<?php if ($user_data['user_level'] != 1) : ?> size="1"<?php endif; ?>><?php if ($user_data['user_level'] == 1) : ?>
                                    <?php 
                                        $user_appr = $this->Core->get_users_approver($user_data['user_id']);                                        
                                        $uappr = array();
                                        foreach ($user_appr as $ua) :
                                            array_push($uappr, $ua['appr_approverid']);
                                        endforeach;
                                        $user_appr = $uappr;
                                        $approver = $this->Core->get_approver($user_data['user_dept'], $user_data['user_id']);
                                        $sel = '';
                                        if ($approver) {   
                                            foreach ($approver as $appr) {
                                                if (in_array($appr->user_id, $user_appr)) 
                                                {                                                        
                                                    $ua_select = 'selected="selected"';
                                                }
                                                else
                                                {                                                        
                                                    $ua_select = '';
                                                }
                                                $sel .= '<option value="'.$appr->user_id.'" '.$ua_select.'>'.$appr->user_fullname.'</option>';
                                            }   
                                        }

                                        echo $sel;   
                                    ?>
                                    <?php else : 
                                        
                                        echo "0";
    
                                    endif; ?></select>
                                    <?php echo '<br /><span class="redtext">'.form_error('user_approvers').'</span>'; ?>
                                </div>                                
                            </div>                              
                                
                            <div class="fields">
                                <div class="lfield valigntop">Email Address <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_email" value="<?=$post['user_email'] ? $post['user_email'] : $user_data['user_email'];;?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_email').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Contact Number <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="user_telno" value="<?=$post['user_telno'] ? $post['user_telno'] : $user_data['user_telno'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_telno').'</span>'; ?></div>
                            </div>
                            <div class="fields centertalign margintop10">   
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_data['user_id']; ?>" />                      
                                <input type="submit" name="btnedituser" value="Save" class="btn" />&nbsp;                            
                                <input type="button" name="btnback" value="Back" onClick="parent.location='<?php echo WEB; ?>/user'" class="redbtn" />
                            </div>
                            <div class="fields margintop10">  
                                <i class="redtext">* required</i>
                            </div>
                        </form> 
                    </div>
                    <?php else : ?>
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/user/" method="POST" enctype="multipart/form-data">
                                Search User&nbsp;<input type="text" name="searchuser" id="searchuser" value="<?php echo $post['searchuser']; ?>" placeholder="by name or username..." class="txtbox" />&nbsp;<button name="btnusersearch" class="btn"><i class="fa fa-search"></i> Search</button><?php if ($post['searchuser']) : ?>&nbsp;<button type="button" name="btnuserall" id="userall" class="btn"><i class="fa fa-refresh"></i> View All</button><?php endif; ?><?php if ($profile_level != 2) : ?>&nbsp;<button type="button" name="btnuseradd" onClick="parent.location='<?php echo WEB; ?>/user/add'" class="btn"><i class="fa fa-plus"></i> Add User</button><?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <div class="user_list">
                        <table class="tdata"> 
                            <tr>
                                <th width="5%">User ID</th>
                                <?php if ($profile_level == 2) : ?>
                                <th width="45%">Name</th>          
                                <?php else : ?>
                                <th width="25%">Name</th>          
                                <?php endif; ?>
                                <th width="20%">Department/Project</th>                                
                                <th width="20%">Email Address</th>                             
                                <th width="10%">Status</th>                                
                                <?php if ($profile_level != 2) : ?>
                                <th width="20%" colspan="3">Manage</th>
                                <?php endif; ?>
                            </tr>
                            <?php if ($user_data) : ?>
                            <?php foreach ($user_data as $row) : ?>
                            <?php if ($row->user_level != 9 && $row->user_level != 8) : ?>
                            <?php 
                                $deptname = "";
                                $dcnt = 0;
                                $deptval = explode(",", $row->user_dept);
                                foreach ($deptval as $dval) :
                                    $deptdata = $this->Core->get_dept(trim($dval));
                                    $deptname .= ($dcnt > 0 ? ', ' : '').$deptdata['dept_name'];
                                    $dcnt++;
                                endforeach;
                            ?>
                            <tr>
                                <td><?php echo $row->user_id; ?></td>
                                <td><b><?php echo strtoupper($row->user_fullname); ?></b><br /><?php echo strtoupper($row->user_empnum); ?></td>
                                <td><?php echo $deptname; ?></td>
                                <td><?php echo $row->user_email; ?></td>
                                <td align="center" class="ustatusDiv<?php echo $row->user_id; ?>"><?php echo $row->user_status == 2 ? '<a title="Click to lock User ID #'.$row->user_id.'" class="approveUser cursorpoint underlined" attribute="'.$row->user_id.'" attribute2="'.$row->user_status.'"><i class="fa fa-unlock-alt fa-lg greentext"></i></a>' : '<a title="Click to unlock User ID #'.$row->user_id.'" class="approveUser cursorpoint underlined" attribute="'.$row->user_id.'" attribute2="'.$row->user_status.'"><i class="fa fa-lock fa-lg redtext"></i></a>'; ?></td>                                
                                <?php if ($profile_level != 2) : ?>
                                <td align="center"><a title="Click to edit User ID #<?php echo $row->user_id; ?>" href="<?php echo WEB; ?>/user/edit/<?php echo $row->user_id; ?>" class='underlined'><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                <td align="center"><a title="Click to delete User ID #<?php echo $row->user_id; ?>" class="delUser cursorpoint underlined" attribute="<?php echo $row->user_id; ?>"><i class="fa fa-trash-o fa-lg redtext"></i></a></td>
                                <td align="center"><?php if ($row->user_email) : ?><a title="Click to send password to User ID #<?php echo $row->user_id; ?>" class="passUser cursorpoint underlined" attribute="<?php echo $row->user_id; ?>" attribute2="<?php echo $row->user_email; ?>"><i class="fa fa-key fa-lg greentext"></i></a><?php endif; ?></td>
                                <?php endif; ?>
                            </tr>    
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>
                                <td colspan="8" align="center">No user data found</td>
                            </tr>   
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>