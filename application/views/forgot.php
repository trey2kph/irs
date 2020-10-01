    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    
                </div>
                <div class="lowerright margintop100">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12"><?=$page_title;?></div>
                    <span class="redtext"><?=validation_errors();?></span>
                    <form name="frmadduser" method="POST" enctype="multipart/form-data">                                                                        
                        <div class="fields">
                            <div class="lfield valigntop">Email Address <span class="redtext">*</span></div>
                            <div class="rfield valigntop"><input type="text" name="user_email" value="<?=$post['user_email'];?>" class="txtbox" /></div>
                        </div>
                        <div class="fields centertalign margintop10">                        
                            <input type="submit" name="btnadduser" value="Submit" class="btn" />&nbsp<input type="button" name="btncancel" value="Cancel" class="redbtn" onClick="parent.location='<?php echo WEB; ?>/'" />
                        </div>
                        <div class="fields margintop10">  
                            <i class="redtext">* required</i>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>