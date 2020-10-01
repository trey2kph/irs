    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">Page Not Found</div>
                    
                    <img src="<?php echo WEB; ?>/images/404page.png" class="inlineblock3" />
                                        
                    <div class="inlineblock3">Not to worry. You can either head back to <a onclick="window.history.back();" class="underlined cursorpoint">where you belong</a> or <a href="<?php echo WEB; ?>" class="underlined">on our homepage</a> or click one of the menu on the left or...<br>sit there and be worthless!</div>
                    
                </div>
            </div>
        </div>
    </div>