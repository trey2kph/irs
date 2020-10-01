                    <?php 
                        $classname = $this->uri->segment(1); 
        
                        // CHECK IF USER HAVE 2 UNCLOSE TRANSACTION
                        $checkunclose = $this->Core->get_trans(0, 1, 0, 0, 0, $session_uid, 0, 5, 0);
                        //var_dump($checkunclose);
                    ?>
                    
                    <div id="cmsmenu" class="cmsmenu mediumtext2">                        
                        <a href="<?php echo WEB; ?>"><div<?php if ($classname == 'irs' || $classname == NULL) : ?> class='dselected'<?php endif; ?>><i class="fa fa-dashboard"></i> Dashboard</div></a>
                        <?php if ($session_level != 2 && $session_level != 5 && $session_level != 6 && $session_level != 7 && $session_level != 8) : ?>
                        <a<?php if ($checkunclose >= 2) : ?> onclick="alert('Requisition is unavailable as you\'ve 2 unclosed transactions prior from release. Please close those transaction on your dashboard.');" class="cursorpoint"<?php else: ?> href="<?php echo WEB; ?>/requisition"<?php endif; ?>><div<?php if ($classname == 'requisition') : ?> class='dselected'<?php endif; ?>><i class="fa fa-shopping-cart"></i> Requisition</div></a>
                        <?php endif; ?>
                        <?php if ($session_level == 6 || $session_level >= 8) : ?>
                        <a href="<?php echo WEB; ?>/trans"><div<?php if ($classname == 'trans') : ?> class='dselected'<?php endif; ?>><i class="fa fa-list"></i> Transactions</div></a>
                        <?php endif; ?>
                        <?php if ($session_level != 1 && $session_level != 2 && $session_level != 3 && $session_level != 5 && $session_level != 6) : ?>
                        <?php if ($session_level != 7) : ?>
                        <a href="<?php echo WEB; ?>/stock"><div<?php if ($classname == 'stock') : ?> class='dselected'<?php endif; ?>><i class="fa fa-truck"></i> Stock</div></a>
                        <?php endif; ?>
                        <a href="<?php echo WEB; ?>/pending"><div<?php if ($classname == 'pending') : ?> class='dselected'<?php endif; ?>><i class="fa fa-inbox"></i> Pending</div></a>
                        <?php if ($session_level != 7) : ?>
                        <a href="<?php echo WEB; ?>/inventory"><div<?php if ($classname == 'inventory') : ?> class='dselected'<?php endif; ?>><i class="fa fa-tags"></i> Inventory</div></a>
                        <?php endif; ?>                    
                        <?php endif; ?>                        
                        <?php if ($session_level != 1 && $session_level != 2 && $session_level != 3 && $session_level != 7) : ?>
                        <a href="<?php echo WEB; ?>/reports"><div<?php if ($classname == 'reports') : ?> class='dselected'<?php endif; ?>><i class="fa fa-bar-chart-o"></i> Reports</div></a>
                        <?php endif; ?>
                        <?php if ($session_level != 1 && $session_level != 3 && $session_level != 5 && $session_level != 6 && $session_level != 7) : ?>                        
                        <a href="<?php echo WEB; ?>/user"><div<?php if ($classname == 'user') : ?> class='dselected'<?php endif; ?>><i class="fa fa-users"></i> Users</div></a>
                        <?php if ($session_level != 2) : ?>                        
                        <a href="<?php echo WEB; ?>/dept"><div<?php if ($classname == 'dept') : ?> class='dselected'<?php endif; ?>><i class="fa fa-sitemap"></i> Department/Project</div></a>
                        <a href="<?php echo WEB; ?>/setting"><div<?php if ($classname == 'setting') : ?> class='dselected'<?php endif; ?>><i class="fa fa-cogs"></i> Setting</div></a>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($session_level != 1 && $session_level != 2 && $session_level != 3 && $session_level != 5 && $session_level != 6 && $session_level != 7 && $session_level != 8) : ?>                        
                        <a href="<?php echo WEB; ?>/logs"><div<?php if ($classname == 'logs') : ?> class='dselected'<?php endif; ?>><i class="fa fa-book"></i> Logs</div></a>
                        <?php endif; ?>                        
                        <!--a href="<?php echo WEB; ?>/profile"><div<?php if ($classname == 'profile') : ?> class='dselected'<?php endif; ?>><i class="fa fa-key"></i> Profile</div></a-->
                    </div>