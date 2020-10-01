<!-- FOOTER -->
            
            </div>
            </div>				        
          </div>
        	
          <div id="bottom" class="bottom">
            <div id="bottomcontainer" class="bottomcontainer">
              
            </div>				        
          </div>
          <div id="footer" class="footer">
            <div id="footercontainer" class="footercontainer">
            	
              <div id="copyright" class="copyright">
                <div class="lcopyright whitetext roboto mediumtext2">
                	&copy; <?php echo date("Y"); ?> Megaworld Corporation - ISM Department, All Rights Reserved<br />
                  <span class="vsmalltext">Page rendered in {elapsed_time} seconds</span>
                </div>
              </div>
            </div>				        
        	
          </div>
        
		</div>
        
    <!-- JAVASCRIPTS -->
    <script type="text/javascript" src="<?php echo JS; ?>/jquery-ui.js"></script>   

    <!-- JS PLUGINS -->
    <script type="text/javascript" src="<?php echo WEB; ?>/js/plugins<?php echo ($this->router->fetch_class() == 'irs' && ($level == 1 || $level == 3) && (!$this->uri->segments[4] || $this->uri->segments[4] == 1)) ? '/1' : ''; ?>"></script>
    
    <!-- LOCAL JAVASCRIPTS -->
    
    <?php $classname = $this->uri->segment(1); ?>
    <?php if ($classname == 'irs' || $classname == 'trans' || $classname == NULL) : ?>
    <script type="text/javascript" src="<?php echo WEB; ?>/js/dashboard_js"></script> 
    <?php elseif ($classname == 'inventory') : ?>
    <script type="text/javascript" src="<?php echo WEB; ?>/js/inventory_js"></script> 
    <script type="text/javascript" src="<?php echo WEB; ?>/js/inout_js"></script> 
    <?php elseif ($classname == 'stock') : ?>
    <script type="text/javascript" src="<?php echo WEB; ?>/js/stock_js"></script> 
    <?php elseif ($classname == 'pending') : ?>
    <script type="text/javascript" src="<?php echo WEB; ?>/js/pending_js"></script> 
    <?php endif; ?>
        
  </body>
</html>