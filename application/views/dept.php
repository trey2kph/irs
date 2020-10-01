    <!-- BODY -->

    <div id="subcontainer" class="subcontainer">        
        <div id="lowersub" class="lowersub tbpadding10">
            
            <div id="lowerlist" class="lowerlist minheight150">
                <div class="lowerleft">&nbsp;
                    <?php if ($session_data) : $this->load->view('menu', $session_data); endif; ?>
                </div>
                <div class="lowerright">
                    <div id="ltitle" class="robotobold cattext2 dbluetext marginbottom12">iRS Department/Project Management</div>
                    <?php if ($dept_mode == 2) : ?>
                    <div class="dept_list">
                        <form name="frmadddept" method="POST" enctype="multipart/form-data"> 
                            <div class="fields">
                                <div class="lfield valigntop">Name <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="dept_name" value="<?=$post['dept_name'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('dept_name').'</span>'; ?></div>
                            </div>
                            <div class="fields">
                                <div class="lfield valigntop">Abbreviation <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="dept_abbr" value="<?=$post['dept_abbr'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('dept_abbr').'</span>'; ?></div>
                            </div>
                            <div class="fields centertalign margintop10">                        
                                <input type="hidden" name="dept_division" value="100" />
                                <input type="submit" name="btnadddept" value="Save" class="btn" />&nbsp;<input type="button" name="btncancel" value="Cancel" class="redbtn" onClick="parent.location='<?php echo WEB; ?>/dept'" />
                            </div>
                            <div class="fields margintop10">  
                                <i class="redtext">* required</i>
                            </div>
                        </form> 
                    </div>
                    <?php elseif ($dept_mode == 1) : ?>
                    <div class="dept_list">
                        <form name="frmeditdept" method="POST" enctype="multipart/form-data">                 
                            <div class="fields">
                                <div class="lfield valigntop">Name <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="dept_name" value="<?=$post['dept_name'] ? $post['dept_name'] : $dept_data['dept_name'];?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('dept_name').'</span>'; ?></div>
                            </div>    
                            <div class="fields">
                                <div class="lfield valigntop">Abbreviation <span class="redtext">*</span></div>
                                <div class="rfield valigntop"><input type="text" name="dept_abbr" value="<?=$post['dept_abbr'] ? $post['dept_abbr'] : $dept_data['dept_abbr'];;?>" class="txtbox" /><?php echo '<br /><span class="redtext">'.form_error('dept_abbr').'</span>'; ?></div>
                            </div>
                            <div class="fields centertalign margintop10">   
                                <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $dept_data['dept_id']; ?>" />                      
                                <input type="submit" name="btneditdept" value="Save" class="btn" />&nbsp;                            
                                <input type="button" name="btnback" value="Back" onClick="parent.location='<?php echo WEB; ?>/dept'" class="redbtn" />
                            </div>
                            <div class="fields margintop10">  
                                <i class="redtext">* required</i>
                            </div>
                        </form> 
                    </div>
                    <?php else : ?>
                    <div class="data_console">
                        <div class="data_search">
                            <form action="<?php echo WEB; ?>/dept/" method="POST" enctype="multipart/form-data">
                                Search Department/Project&nbsp;<input type="text" name="searchdept" id="searchdept" value="<?php echo $post['searchdept']; ?>" placeholder="by name..." class="txtbox" />&nbsp;<button name="btndeptsearch" class="btn"><i class="fa fa-search"></i> Search</button><?php if ($post['searchdept']) : ?>&nbsp;<button type="button" name="btndeptall" id="deptall" class="btn"><i class="fa fa-refresh"></i> View All</button><?php endif; ?>&nbsp;<button type="button" name="btndeptadd" onClick="parent.location='<?php echo WEB; ?>/dept/add'" class="btn"><i class="fa fa-plus"></i> Add Department/Project</button>
                            </form>
                        </div>
                    </div>
                    <div class="dept_list">
                        <table class="tdata"> 
                            <tr>
                                <th width="5%">Department/<br>Project ID</th>
                                <th width="20%">Name</th>                                
                                <th width="20%">Abbreviation</th>                             
                                <th width="10%">Status</th>      
                                <th width="20%" colspan="2">Manage</th>
                            </tr>
                            <?php if ($dept_data) : ?>
                            <?php foreach ($dept_data as $row) : ?>
                            <?php if ($row->user_level != 9 && $row->user_level != 8) : ?>
                            <tr>
                                <td><?php echo $row->dept_id; ?></td>
                                <td><b><?php echo strtoupper($row->dept_name); ?></b></td>
                                <td><?php echo strtoupper($row->dept_abbr); ?></td>
                                <td align="center" class="dstatusDiv<?php echo $row->dept_id; ?>"><?php echo $row->dept_status == 2 ? '<a title="Click to deactivate Department/Project ID #'.$row->dept_id.'" class="approveDept cursorpoint underlined" attribute="'.$row->dept_id.'" attribute2="'.$row->dept_status.'"><i class="fa fa-circle fa-lg greentext"></i></a>' : '<a title="Click to activate Department/Project ID #'.$row->dept_id.'" class="approveDept cursorpoint underlined" attribute="'.$row->dept_id.'" attribute2="'.$row->dept_status.'"><i class="fa fa-circle fa-lg redtext"></i></a>'; ?></td>                                
                                <td align="center"><a title="Click to edit Department/Project ID #<?php echo $row->dept_id; ?>" href="<?php echo WEB; ?>/dept/edit/<?php echo $row->dept_id; ?>" class='underlined'><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                <td align="center"><a title="Click to delete Department/Project ID #<?php echo $row->dept_id; ?>" class="delDept cursorpoint underlined" attribute="<?php echo $row->dept_id; ?>"><i class="fa fa-trash-o fa-lg redtext"></i></a></td>
                            </tr>    
                            <?php endif; ?>
                            <?php endforeach; ?>
                            <?php else : ?>
                            <tr>
                                <td colspan="8" align="center">No department data found</td>
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