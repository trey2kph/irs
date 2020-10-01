	// JavaScript Document

	function updateStock() {
        
        searchitem = $('#searchitem').val();

        $.ajax(
        {
            url: "<?php echo WEB; ?>/ajax/updatestock",
            data: "searchitem=" + searchitem,
            type: "POST",
            complete: function(){
                $("#loading").hide();
            },
            success: function(data) {
                $("#stock_list").html(data);
            }
        })
        
    }
    setInterval("updateStock()", 60000);