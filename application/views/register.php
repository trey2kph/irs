    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12"><?=$page_title;?></div>
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
                            <div class="rfield valigntop"><?php echo $this->Form->non_admin_level_dropdown("user_level", $post['user_level'], 'id="user_level" class="user_level"'); ?><?php echo '<br /><span class="redtext">'.form_error('user_level').'</span>'; ?></div>
                        </div>                                   
                        <div class="fields">
                            <div class="lfield valigntop">Department <span class="redtext">*</span></div>
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
                            <input type="submit" name="btnadduser" value="Register" class="btn" />&nbsp<input type="button" name="btncancel" value="Cancel" class="redbtn" onClick="parent.location='<?php echo WEB; ?>/'" />
                        </div>
                        <div class="fields margintop10">  
                            <i class="redtext">* required</i>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>