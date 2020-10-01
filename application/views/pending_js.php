	// JavaScript Document

	function updatePending() {
        
        searchfrom = $('#pend_date_from').val();
        searchto = $('#pend_date_to').val();

        $.ajax(
        {
            <?php 
                $pagenum = $_SERVER['HTTP_REFERER'];
                $pagenum = explode("/", preg_replace('#^https?://#', '', $pagenum)); 
                $pagenumber = $pagenum[5];
            ?>
            <?php if ($pagenumber) : ?>
            url: "<?php echo WEB; ?>/ajax/updatepend/page/<?php echo $pagenumber; ?>",
            <?php else : ?>            
            url: "<?php echo WEB; ?>/ajax/updatepend",
            <?php endif; ?>
            data: "searchfrom=" + searchfrom + "&searchto=" + searchto,
            type: "POST",
            complete: function(){
                $("#loading").hide();
            },
            success: function(data) {
                $("#pend_list").html(data);
            }
        })
        
    }
    setInterval("updatePending()", 60000);