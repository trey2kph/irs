    <!-- BODY -->
    
    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12"><?php echo $page_title; ?></div>  
                    
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/stock/cat" method="POST" enctype="multipart/form-data">
                                Search Category&nbsp;<input type="text" name="searchcat" id="searchcat" value="<?php echo $post['searchcat']; ?>" placeholder="by name..." class="txtbox" />&nbsp;<button type="submit" name="btncatsearch" class="btn"><i class="fa fa-search"></i> Search</button><?php if ($post['searchcat']) : ?>&nbsp;<button type="button" name="btncatall" onClick="window.location.href = window.location.href;" class="btn"><i class="fa fa-refresh"></i> View All</button><?php endif; ?>
                            </form>
                        </div>
                    </div>
                    <div class="cat_list">
                        <table class="tdata" width="100%">
                            <tr>
                                <th width="20%">ID</th>
                                <th width="50%">Category Name</th>
                                <th width="30%">Active</th>
                            </tr>
                            <?php if ($cat_data) : ?>                                
                                <?php foreach ($cat_data as $row) : ?>
                                <tr>
                                    <td><?php echo $row->cat_id; ?></td>
                                    <td><?php echo $row->cat_name; ?></td>
                                    <td align="center" class="cstatusDiv<?php echo $row->cat_id; ?>"><?php echo $row->cat_status == 2 ? '<a title="Click to deactivate Category ID #'.$row->cat_id.'" class="statusCat cursorpoint underlined" attribute="'.$row->cat_id.'" attribute2="'.$row->cat_status.'"><i class="fa fa-check fa-lg greentext"></i></a>' : '<a title="Click to activate Category ID #'.$row->cat_id.'" class="statusCat cursorpoint underlined" attribute="'.$row->cat_id.'" attribute2="'.$row->cat_status.'"><i class="fa fa-times fa-lg redtext"></i></a>'; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                            <?php endif; ?>
                            <tr class="tdataform">
                                <td colspan="3">
                                    <form action="<?php echo WEB; ?>/stock/cat" method="POST" enctype="multipart/form-data">
                                        <input type="text" name="item_catname" id="item_catname" placeholder="Category Name..." class="txtbox" />&nbsp;<button type="submit" name="btnaddcat" class="smallbtn"><i class="fa fa-plus"></i> Add Stock Category</button>&nbsp;&nbsp;&nbsp;<?php echo '<span class="redtextp">'.form_error('item_catname').'</span>'; ?>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>