	// JavaScript Document

	function updateInOut() {
        
        from = $('#inout_date_from').val();
        to = $('#inout_date_to').val();

        $.ajax(
        {
            url: "<?php echo WEB; ?>/ajax/updateinout",
            data: "inout_date_from=" + from + "&inout_date_to=" + to,
            type: "POST",
            complete: function(){
                $("#loading").hide();
            },
            success: function(data) {
                $("#inout_list_inside").html(data);
            }
        })
        
    }
    setInterval("updateInOut()", 60000);