    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <?php if ($level > 2) : ?>
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                <?php else : ?>   
                <a href="<?php echo WEB?>" title="View Transactions">
                    <div class="floatmainbutton cursorpoint"><i class="fa fa-dashboard fa-lg whitetext mediumtext"></i></div>
                </a> 
                <div class="lowerrightbig">
                <?php endif; ?>
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">My Profile</div>
                    <div class="user_list">
                        <form name="frmupdateprofile" method="POST" enctype="multipart/form-data"> 
                            <div class="fields">
                                <div class="lfield valigntop">Employee No. <!--span class="redtext">*</span--></div>
                                <div class="rfield valigntop"><input type="text" name="user_empnum" value="<?=$post['user_empnum'] ? $post['user_empnum'] : $user_data['user_empnum'];?>" class="txtbox" readonly /><?php echo '<br /><span class="redtext">'.form_error('user_empnum').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Name <!--span class="redtext">*</span--></div>
                                <div class="rfield valigntop"><input type="text" name="user_fullname" value="<?=$post['user_fullname'] ? $post['user_fullname'] : $user_data['user_fullname'];?>" class="txtbox" readonly /><?php echo '<br /><span class="redtext">'.form_error('user_fullname').'</span>'; ?></div>
                            </div>
                            
                            <div class="fields">
                                <div class="lfield valigntop">Contact Number <!--span class="redtext">*</span--></div>
                                <div class="rfield valigntop"><input type="text" name="user_telno" value="<?=$post['user_telno'] ? $post['user_telno'] : $user_data['user_telno'];?>" class="txtbox" readonly /><?='<br /><span class="redtext">'.form_error('user_telno').'</span>';?></div>
                            </div>
                            
                            <div class="fields">
                                <div class="lfield valigntop">Email Address <!--span class="redtext">*</span--></div>
                                <div class="rfield valigntop"><input type="text" name="user_email" value="<?=$post['user_email'] ? $post['user_email'] : $user_data['user_email'];;?>" class="txtbox" readonly /><?php echo '<br /><span class="redtext">'.form_error('user_email').'</span>'; ?></div>
                            </div>
                            
                            <div class="fields">
                                <i>To update your password, please fill up textbox below</i>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Password</div>
                                <div class="rfield valigntop"><input type="password" name="user_password1" autocomplete="off" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_password1').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Confirm Password</div>
                                <div class="rfield valigntop"><input type="password" name="user_password2" autocomplete="off" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('user_password2').'</span>'; ?></div>
                            </div>
                            <div class="fields centertalign margintop10">                        
                                <input type="hidden" name="user_id" value="<?php echo $user_data['user_id']; ?>" />                      
                                <input type="submit" name="btnadduser" value="Update My Profile" class="btn" />
                                <?php if ($level <= 2) : ?>
                                <input type="button" name="btncanceluser" value="Back" onClick="window.history.back();" class="redbtn" />
                                <?php endif; ?>
                            </div>
                            <div class="fields margintop10">  
                                <i class="redtext">* required</i>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>