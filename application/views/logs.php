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
                            <form action="<?php echo WEB; ?>/logs/" method="POST" enctype="multipart/form-data">
                                Search Log&nbsp;<input type="text" name="searchlogs" id="searchlogs" value="<?php echo $post['searchlogs']; ?>" placeholder="by ID..." class="txtbox width75" />&nbsp;<?php echo $this->Form->user_dropdown('userlogs', 'userlogs', $post['userlogs']); ?>&nbsp;<?php echo $this->Form->task_dropdown_short('tasklogs', 'tasklogs', $post['tasklogs']); ?>&nbsp;From&nbsp;<input type="text" name="fromlogs" id="fromlogs" class="fromlogs width75 txtbox" value="<?php echo $post['fromlogs'] ? $post['fromlogs'] : '2015-06-06'; ?>" />&nbsp;To&nbsp;<input type="text" name="tologs" id="tologs" class="tologs width75 txtbox" value="<?php echo $post['tologs'] ? $post['tologs'] : mdate("%Y-%m-%d"); ?>" />&nbsp;<button type="submit" name="btnlogsearch" class="btn"><i class="fa fa-search"></i> Search</button>
                            </form>
                        </div>
                    </div>
                    <div id="log_list" class="log_list">
                        <table class="tdata" width="100%">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="20%">User</th>
                                <th width="20%">Task Code</th>
                                <th width="30%">Data</th>
                                <th width="25%">Date</th>
                            </tr>
                            <?php if ($logs_data) : ?>                                
                                <?php foreach ($logs_data as $row) : ?>
                                <tr>
                                    <td><?php echo $row->logs_id; ?></td>
                                    <td><?php echo $row->user_fullname; ?></td>
                                    <td><?php echo $row->logs_task; ?></td>
                                    <td><?php echo $row->logs_dataid == 0 ? 'n/a' : $this->Core->get_data_from_logs($row->logs_dataid, $row->logs_task); ?></td>
                                    <td><?php echo mdate("%M %j, %Y | %g:%i%a", $row->logs_date); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="centertalign">No logs record found</td>
                                </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="data_console">
                        <div class="pagination"><?php echo $this->pagination->create_links(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>