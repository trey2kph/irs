	// JavaScript Document

	function updateDashboard() {
        
        searchstr = $('#searchtrans').val();
        searchstatus = $('#statustrans option:selected').val();

        $.ajax(
        {
            <?php 
                $pagenum = $_SERVER['HTTP_REFERER'];
                $pagenum = explode("/", preg_replace('#^https?://#', '', $pagenum)); 
                $pagenumber = $pagenum[5];
            ?>
            <?php if ($pagenumber) : ?>
            url: "<?php echo WEB; ?>/ajax/updatedash/page/<?php echo $pagenumber; ?>",
            <?php else : ?>            
            url: "<?php echo WEB; ?>/ajax/updatedash",
            <?php endif; ?>
            data: "searchtrans=" + searchstr + "&statustrans=" + searchstatus,
            type: "POST",
            complete: function(){
                $("#loading").hide();
            },
            success: function(data) {
                $("#dboard_list").html(data);
            }
        })

        $.ajax(
        {
            <?php 
                $pagenum = $_SERVER['HTTP_REFERER'];
                $pagenum = explode("/", preg_replace('#^https?://#', '', $pagenum)); 
                $pagenumber = $pagenum[5];
            ?>
            <?php if ($pagenumber) : ?>
            url: "<?php echo WEB; ?>/ajax/updatetrans/page/<?php echo $pagenumber; ?>",
            <?php else : ?>            
            url: "<?php echo WEB; ?>/ajax/updatetrans",
            <?php endif; ?>
            data: "searchtrans=" + searchstr + "&statustrans=" + searchstatus,
            type: "POST",
            complete: function(){
                $("#loading").hide();
            },
            success: function(data) {
                $("#tboard_list").html(data);
            }
        })
        
    }
    setInterval("updateDashboard()", 30000);