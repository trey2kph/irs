    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12"><?php echo $page_title; ?></div>   
                    <div id="setting" class="setting">
                        <form name="frmsetting" method="POST" enctype="multipart/form-data">                        
                            <div class="fields">
                                <div class="lfield valigntop">Announcement</div>
                                <div class="rfield valigncenter"><textarea name="set_announce" class="txtarea" rows="3" placeholder="To disable announcement, leave it blank"><?php echo $setting['set_announce'] ? trim($setting['set_announce']) : $post['set_announce']; ?></textarea>
                                    <br>Will expire on&nbsp;<input type="text" name="set_annexpire" id="set_annexpire" class="txtbox expiredate width100" value="<?php echo $setting['set_annexpire'] ? mdate('%Y-%m-%d', $setting['set_annexpire']) : $post['set_annexpire']; ?>" />
                                </div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Mail Footer</div>
                                <div class="rfield valigntop"><textarea name="set_mailfoot" class="txtarea" rows="5"><?php echo $setting['set_mailfoot'] ? $setting['set_mailfoot'] : $post['set_mailfoot']; ?></textarea><?php echo '<span class="redtext">'.form_error('set_mailfoot').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Data per Page</div>
                                <div class="rfield valigntop">
                                    <select name="set_numrows" class="text40">                                    
                                        <?php $numrows = $setting['set_numrows'] ? $setting['set_numrows'] : 20; ?>
                                        <?php for($i=5; $i<=30; $i=$i+5) { ?>
                                        <option value="<?php echo $i; ?>" <?php echo $numrows == $i ? "selected" : ""; ?>><?php echo $i; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="fields centertalign margintop10">                        
                                <input type="submit" name="btneditset" value="Update" class="btn" />
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>