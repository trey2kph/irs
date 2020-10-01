	// JavaScript Document

	function updateInventory() {
        
        searchinv = $('#searchinv').val();
        cat = $('#searchcat option:selected').val();

        $.ajax(
        {
            url: "<?php echo WEB; ?>/ajax/updateinv",
            data: "searchinv=" + searchinv + "&searchcat=" + cat,
            type: "POST",
            complete: function(){
                $("#loading").hide();
            },
            success: function(data) {
                $("#inv_list_inside").html(data);
            }
        })
        
    }
    setInterval("updateInventory()", 60000);