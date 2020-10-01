	// JavaScript Document

	$(function() {	

        <?php if ($mode == 1) : ?>
        if(localStorage.getItem('IRS_PopUp') != 1) {
            $(".floatdiv").removeClass("invisible");
            $("#fview").removeClass("invisible");            
        } else {
            $(".floatdiv").addClass("invisible");
            $("#fview").addClass("invisible");
        }

        $(".closebutton").on("click", function() {	
            $("#dismisspop").prop('checked', $(this).prop("checked"));
            if($("#dismisspop:checked").length) {
                localStorage.setItem('IRS_PopUp', 1);
            }
            $(".floatdiv").addClass("invisible");
            $("#fview").addClass("invisible");
        });
        <?php endif; ?>

		// scrollable cart
		$(window).scroll(function(){

            <?php if ($level > 2) : ?>
            
            if ($(document).scrollTop() >= 120) {
				document.getElementById('cmsmenu').style.position = "fixed";
				document.getElementById('cmsmenu').style.top = "110px";
			}	        
			else
			{
				document.getElementById('cmsmenu').style.position = "relative";
				document.getElementById('cmsmenu').style.top = "0px";
			}

            <?php endif; ?>
            
            if ($(document).scrollTop() >= 120) {
                $(".hupper").fadeIn();
			}	        
			else
			{
                $(".hupper").fadeOut(100);
			}

			if ($(document).scrollTop() >= 120) {
				document.getElementById('cart').style.position = "fixed";
				document.getElementById('cart').style.top = "130px";
				document.getElementById('cart').style.marginLeft = "621px";
			}	        
			else
			{
				document.getElementById('cart').style.position = "relative";
				document.getElementById('cart').style.top = "0px";
				document.getElementById('cart').style.marginLeft = "20px";
			}
            
		});

		// update announcement
		$(".btnupdateannounce").live("click", function() {	
			anntext = $('.txtannounce').val();
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/updateannounce/",
	            data: {anntext: anntext},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#annstat').html(data); 
		        }
		    })
		});

		// add to cart
		$(".addcart").live("click", function() {	
			itemid = $(this).attr('attribute');
			itemname = $(this).attr('attribute2');
            itemname = itemname.replace(/\#/g, "");
            itemname = itemname.replace(/\"/g, "");
            itemname = itemname.replace(/\'/g, "");
            itemname = itemname.replace(/\//g, "");
            itemname = itemname.replace(/\(/g, "");
            itemname = itemname.replace(/\)/g, "");
			unitname = $(this).attr('attribute3');
			quantity = $('#quantity' + itemid + ' option:selected').val();            
			price = $(this).attr('attribute4');
			limit = $(this).attr('attribute5');
            
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/addcart/",
	            data: {id: itemid, name: itemname, unit: unitname, quantity: quantity, price: price, limit: limit},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#cartitem').html(data); 
		        }
		    })
		});

		// plus cart
		$(".pluscart").live("click", function() {	
			itemid = $(this).attr('attribute');
            price = $(this).attr('attribute2');
			limit = $(this).attr('attribute3');
            
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/addcart/",
	            data: {id: itemid, quantity: 1, price: price, limit: limit},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#cartitem').html(data); 
		        }
		    })
		});

		// minus cart
		$(".minuscart").live("click", function() {	
			itemid = $(this).attr('attribute');
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/minuscart/",
	            data: {id: itemid, quantity: 1},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#cartitem').html(data); 
		        }
		    })
		});

		// remove cart
		$(".removecart").live("click", function() {	
			itemid = $(this).attr('attribute');
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/removecart/",
	            data: {id: itemid},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#cartitem').html(data); 
		        }
		    })
		});

		// clear cart
		$(".clearcart").live("click", function() {	
            var r = confirm("Are you sure you want to clear the requisition slip?");
			if (r == true)
			{
                $.ajax(
                {
                    url: "<?php echo WEB; ?>/ajax/clearcart/",
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        $('#cartitem').html(data); 
                    }
                })
            }
		});

		// review cart
		$(".reviewcart").live("click", function() {
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/reviewcart/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#cartitem').html(data); 
		        }
		    })
		});

		// back cart
		$(".docart").live("click", function() {
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/docart/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#cartitem').html(data); 
		        }
		    })
		});

		// process cart
		$(".processcart").live("click", function() {
            $(this).attr('disabled', true);
            price = $(this).attr('attribute');
            reqremark = $("#reqremark").val();
            $(".reviewbtn").html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/processcart/",
                data: 'price=' + price + '&reqremark=' + reqremark,
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	if (data == 0) { 
		        		alert("There's a problem on the system!");
		        	}
		        	else { 
		        		alert("Your requisition is on process and subject for approval from your approving officer. Some quantities may be adjust by your approver and admin's supplier");
	        			window.location.href='<?php echo WEB; ?>';
		        	}
		        }
		    })
		});

		// delete trans
		$(".delTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');

			var r = confirm("Are you sure you want to cancel the request?");
			if (r == true)
			{
                $(".managediv" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/deletetrans/",
			        data: {transid: transid, transdate: transdate},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

        // approve trans
        
		$(".apprTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            $("#transDiv").removeClass("invisible");			
            
            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/getappini/",
                data: {transid: transid, transdate: transdate},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    $("#transDivContent").html(data);
                }
            })

            return false;
			
		});

        // process approve
		$("#updateIni").live("click", function() {		

			transid = $('#transid').val(); 
            transdate = $('#transdate').val(); 
            appremarks = $('#transappremarks').val(); 
                        
            qtycount = $('#qtycount').val(); 
            var postRowid = new Array();
            var postVal = new Array();
            var i = 0;
            while(i < qtycount)
            {
                if ($('#qty' + i).attr('attribute')) { postRowid[i] = $('#qty' + i).attr('attribute'); } else { postRowid[i] = 0; }
                if ($('#qty' + i).val()) { postVal[i] = $('#qty' + i).val(); } else { postVal[i] = 123456789; }
                i++;
            }
            $(".divremark").html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
            
            approve = 2;

            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/approvetrans/",
                data: 'transid=' + transid + '&transdate=' + transdate + '&qtycount=' + qtycount + '&rowid=' + postRowid + '&val=' + postVal + '&approve=' + approve + '&remarks=' + appremarks,
                //data: {transid: transid, transdate: transdate, qtycount: qtycount, rowid: postRowid, val: postVal, approve: approve, remarks: appremarks},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    window.location.href=window.location.href;
                }
            })

            return false;
			
		});

        // disapprove trans
		$(".dapprTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            approve = 8;

			var r = confirm("Are you sure you want to disapproved this request?");
			if (r == true)
			{
                $(".manage2div" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/approvetrans/",
			        data: {transid: transid, transdate: transdate, approve: approve},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

        // admin approve trans
		$(".adminAppTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            $("#transDiv").removeClass("invisible");			
            
            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/getapptrans/",
                data: {transid: transid, transdate: transdate},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    $("#transDivContent").html(data);
                }
            })

            return false;
			
		});

        // process trans
		$("#updateRemarks").live("click", function() {		

			transid = $('#transid').val(); 
            transdate = $('#transdate').val(); 
            remarks = $('#transremarks').val(); 
                        
            qtycount = $('#qtycount').val(); 
            var postRowid = new Array();
            var postVal = new Array();
            var i = 0;
            while(i < qtycount)
            {
                if ($('#qty' + i).attr('attribute')) { postRowid[i] = $('#qty' + i).attr('attribute'); } else { postRowid[i] = 0; }
                if ($('#qty' + i).val()) { postVal[i] = $('#qty' + i).val(); } else { postVal[i] = 123456789; }
                i++;
            }
            $(".divremark").html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
            
            approve = 3;

            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/approvetrans/",
                data: 'transid=' + transid + '&transdate=' + transdate + '&qtycount=' + qtycount + '&rowid=' + postRowid + '&val=' + postVal + '&approve=' + approve + '&remarks=' + remarks,
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    window.location.href=window.location.href;
                }
            })

            return false;
			
		});

        // manage trans
		$(".manageTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            $("#transDiv").removeClass("invisible");			
            
            $.ajax(
            {                
                url: "<?php echo WEB; ?>/ajax/gettrans/",
                data: {transid: transid, transdate: transdate},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    $("#transDivContent").html(data);
                }
            })

            return false;
			
		});

        // cancel trans
		$(".cancelTrans").live("click", function() {		
            
			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');

            var r = confirm("Are you sure you want to mark this request as pending?");
			if (r == true)
			{
            
                $.ajax(
                {
                    //url: "<?php echo WEB; ?>",
                    url: "<?php echo WEB; ?>/ajax/pendtrans/",
                    data: {transid: transid, transdate: transdate},
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        alert("The request has been cancel and item has been marked as pending");
                        window.location.href=window.location.href;
                    }
                })

                return false;
            }
			
		});

        // submit manage trans
        $("#updateovertrans").live("click", function() {		

			transid = $('#transid').val(); 
            transdate = $('#transdate').val(); 
            qtycount = $('#qtycount').val(); 
            var postRowid = new Array();
            var postVal = new Array();
            var i = 0;
            while(i < qtycount)
            {
                if ($('#qty' + i).attr('attribute')) { postRowid[i] = $('#qty' + i).attr('attribute'); } else { postRowid[i] = 0; }
                if ($('#qty' + i).val()) { postVal[i] = $('#qty' + i).val(); } else { postVal[i] = 123456789; }
                i++;
            }
            $(".divovertrans").html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/updateovertrans/",
                data: 'transid=' + transid + '&transdate=' + transdate + '&qtycount=' + qtycount + '&rowid=' + postRowid + '&val=' + postVal,
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    window.location.href=window.location.href;
                }
            })

            return false;
			
		});

        // pending trans
		$(".pendTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            approve = 4;

			var r = confirm("Are you sure you want to pending this request to order insufficient stock?");
			if (r == true)
			{
                $(".managediv" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/approvetrans/",
			        data: {transid: transid, approve: approve},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

        // admin reject trans
		$(".adminRejTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            approve = 7;

			var r = confirm("Are you sure you want to reject this request prior for release?");
			if (r == true)
			{
                $(".managediv" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/approvetrans/",
			        data: {transid: transid, transdate: transdate, approve: approve},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

        // return trans
		$(".returnTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            approve = 2;

			var r = confirm("Are you sure you want to return this request to admin for review?");
			if (r == true)
			{
                $(".managediv" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/approvetrans/",
			        data: {transid: transid, transdate: transdate, approve: approve, return: 1},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});
 
        // release trans
		$(".releaseTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            approve = 5;

			var r = confirm("Are you sure you want to release the item of this request?");
			if (r == true)
			{
                $(".managediv" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/approvetrans/",
			        data: {transid: transid, transdate: transdate, approve: approve},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
                        if (data == "FULL") alert("One on the item is out of stock.");
			            else window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

        // close trans
		$(".closeTrans").live("click", function() {		

			transid = $(this).attr('attribute');
            transdate = $(this).attr('attribute2');
            approve = 9;

			var r = confirm("Did you received the item?\nAre you sure you want to close this request?");
			if (r == true)
			{
                $(".managediv" + transid).html('<i class="fa fa-refresh fa-spin fa-lg"></i>');
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/approvetrans/",
			        data: {transid: transid, transdate: transdate, approve: approve},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

        // view all trans
        $("#transall").live("click", function() {	
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/viewall_trans/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	window.location.href=window.location.href;
		        }
		    })
		});

        // view all req
        $("#reqall").live("click", function() {	
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/viewall_resitem/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	window.location.href=window.location.href;
		        }
		    })
		});

        // view all item (stock)
        $("#itemall_stock").live("click", function() {	
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/viewall_stock/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	window.location.href=window.location.href;
		        }
		    })
		});

        // view all user
        $("#userall").live("click", function() {	
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/viewall_user/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	window.location.href=window.location.href;
		        }
		    })
		});

        // view all user
        $("#deptall").live("click", function() {	
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/viewall_dept/",
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	window.location.href=window.location.href;
		        }
		    })
		});

        // plus stock new
		$(".plusstock").live("click", function() {	
			itemid = $(this).attr('attribute');
            quantity = $('#stockopt' + itemid).val();            
            
            $("#transDiv").removeClass("invisible");			
            
            $.ajax(
            {
                //url: "<?php echo WEB; ?>",
                url: "<?php echo WEB; ?>/ajax/getitem/",
                data: {itemid: itemid, quantity: quantity},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    $("#transDivContent").html(data);
                }
            })

            return false;
		    
		});
        
        $("#procureitem").live("click", function() {	
			invoice = $("#deliinvoice").val();
			ponum = $("#deliponum").val();
			price = $("#deliprice").val();
			supplier = $("#delisupplier").val();
			quantity = $("#deliqtycount").val();
			itemid = $("#deliitemid").val();
            
            if (invoice && ponum && price && supplier) {
            
                $.ajax(
                {
                    url: "<?php echo WEB; ?>/ajax/addprocure/",
                    data: {itemid: itemid, quantity: quantity, invoice: invoice, ponum: ponum, price: price, supplier: supplier},
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        $("#transDiv").addClass("invisible");
                        $("#pcuredata").html(data);
                    }
                })
                
                $.ajax(
                {
                    url: "<?php echo WEB; ?>/ajax/edititem/",
                    data: {itemid: itemid, price: price, supplier: supplier},
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        $("#item_price").val(data);
                        $("#item_supplier").val(supplier.toUpperCase());
                    }
                })
                
                $.ajax(
                {
                    url: "<?php echo WEB; ?>/ajax/plusstock/",
                    data: {itemid: itemid, quantity: quantity},
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        $('#stock' + itemid).html(data); 
                        if (data == 0) { 
                            $('#div' + itemid).addClass("redbg"); 
                            $('#div' + itemid).removeClass("lredbg");                         
                            $('#tr' + itemid).addClass("redtext"); 
                            $('#tr' + itemid).addClass("blinked"); 
                        }
                        else if (data <= 50) 
                        {
                            $('#div' + itemid).removeClass("redbg"); 
                            $('#div' + itemid).addClass("lredbg"); 
                            $('#tr' + itemid).addClass("redtext"); 
                            $('#tr' + itemid).removeClass("blinked"); 
                        }
                        else 
                        {
                            $('#div' + itemid).removeClass("redbg"); 
                            $('#div' + itemid).removeClass("lredbg"); 
                            $('#tr' + itemid).removeClass("redtext"); 
                            $('#tr' + itemid).removeClass("blinked"); 
                        }
                    }
                })
                
            } else {
                $(".iteminilist").effect('shake', {times: 3, distance: 5}, 420);
                $('.iteminilist').before('<div class="redtext bold">All fields are required!</div>');
            }

            return false;
		    
		});

        // plus stock old
		$(".plusstock2").live("click", function() {	
			itemid = $(this).attr('attribute');
            quantity = $('#stockopt' + itemid).val();
			//quantity = $('#stockopt' + itemid + ' option:selected').val();
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/plusstock/",
	            data: {itemid : itemid, quantity : quantity},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#stock' + itemid).html(data); 
                    if (data == 0) { 
                        $('#div' + itemid).addClass("redbg"); 
                        $('#div' + itemid).removeClass("lredbg");                         
                        $('#tr' + itemid).addClass("redtext"); 
                        $('#tr' + itemid).addClass("blinked"); 
                    }
                    else if (data <= 50) 
                    {
                        $('#div' + itemid).removeClass("redbg"); 
                        $('#div' + itemid).addClass("lredbg"); 
                        $('#tr' + itemid).addClass("redtext"); 
                        $('#tr' + itemid).removeClass("blinked"); 
                    }
                    else 
                    {
                        $('#div' + itemid).removeClass("redbg"); 
                        $('#div' + itemid).removeClass("lredbg"); 
                        $('#tr' + itemid).removeClass("redtext"); 
                        $('#tr' + itemid).removeClass("blinked"); 
                    }
		        }
		    })
		});

        // minus stock new
        
        $(".minusstock2").live("click", function() {
            
			itemid = $(this).attr('attribute');
            pcureid = $(this).attr('attribute2');
            quantity = $(this).attr('attribute3');
            actual = $('#stock' + itemid).html();

			var r = confirm("Are you sure you want to delete this procurement?");
			if (r == true)
			{
                $.ajax(
                {
                    url: "<?php echo WEB; ?>/ajax/delprocure/",
                    data: {itemid : itemid, pcureid : pcureid},
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        $("#pcuredata").html(data);
                    }
                })
                
                $.ajax(
                {
                    url: "<?php echo WEB; ?>/ajax/minusstock/",
                    data: {itemid : itemid, quantity : quantity, actual : actual},
                    type: "POST",
                    complete: function(){
                        $("#loading").hide();
                    },
                    success: function(data) {
                        $('#stock' + itemid).html(data); 
                    }
                })
                

			    return false;
			}
		    
		});

        // minus stock old
		$(".minusstock").live("click", function() {	
			itemid = $(this).attr('attribute');
            quantity = $('#stockopt' + itemid).val();
			//quantity = $('#stockopt' + itemid + ' option:selected').val();
            actual = $('#stock' + itemid).html();
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/minusstock/",
	            data: {itemid : itemid, quantity : quantity, actual : actual},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	$('#stock' + itemid).html(data); 
                    if (data == 0) { 
                        $('#div' + itemid).addClass("redbg"); 
                        $('#div' + itemid).removeClass("lredbg");                        
                        $('#tr' + itemid).addClass("redtext"); 
                        $('#tr' + itemid).addClass("blinked"); 
                    }
                    else if (data <= 50) 
                    {
                        $('#div' + itemid).removeClass("redbg"); 
                        $('#div' + itemid).addClass("lredbg"); 
                        $('#tr' + itemid).removeClass("redtext"); 
                        $('#tr' + itemid).removeClass("blinked"); 
                    }
                    else 
                    {
                        $('#div' + itemid).removeClass("redbg"); 
                        $('#div' + itemid).removeClass("lredbg"); 
                        $('#tr' + itemid).removeClass("redtext"); 
                        $('#tr' + itemid).removeClass("blinked"); 
                    }
		        }
		    })
		});

        // plus new stock
		$(".plusnstock").live("click", function() {	
            count = $('#stockcount').val(); 
			quantity = $('#stockopt option:selected').val();
            new_count = parseInt(count) + parseInt(quantity);
		    $('#stockcount').val(new_count); 
		});

        // minus new stock
		$(".minusnstock").live("click", function() {	
            count = $('#stockcount').val(); 
			quantity = $('#stockopt option:selected').val();
            new_count = count - quantity;
            if (new_count < 0) new_count = 0;
		    $('#stockcount').val(new_count); 
		});

        // other category name
        $('#item_cat').on("change", function() {
            if ($(this).val() == 1000)
            {  
                $('.catname').show(); 
            }
            else
            {  
                $('.catname').hide(); 
            }
        });

		// status pend
		$(".statusPend").live("click", function() {		

			pendid = $(this).attr('attribute');	
			pendstatus = $(this).attr('attribute2');	
            $(".pstatusDiv" + pendid).html('<i class="fa fa-refresh fa-spin"></i>');		

			$.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/statuspend/",
		        data: {pendid : pendid, pend_status : pendstatus},
		        type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		            $(".pstatusDiv" + pendid).html(data);
		        }
		    })

		    return false;
		});

		// status item
		$(".statusItem").live("click", function() {		

			itemid = $(this).attr('attribute');	
			itemstatus = $(this).attr('attribute2');	
            $(".istatusDiv" + itemid).html('<i class="fa fa-refresh fa-spin"></i>');		

			$.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/statusitem/",
		        data: {itemid : itemid, item_status : itemstatus},
		        type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		            $(".istatusDiv" + itemid).html(data);
		        }
		    })

		    return false;
		});

		// status cat
		$(".statusCat").live("click", function() {		

			catid = $(this).attr('attribute');	
			catstatus = $(this).attr('attribute2');		
            $(".cstatusDiv" + catid).html('<i class="fa fa-refresh fa-spin"></i>');	

			$.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/statuscat/",
		        data: {catid : catid, cat_status : catstatus},
		        type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		            $(".cstatusDiv" + catid).html(data);
		        }
		    })

		    return false;
		});

		// delete user
		$(".delUser").live("click", function() {		

			userid = $(this).attr('attribute');

			var r = confirm("Are you sure you want to delete");
			if (r == true)
			{
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/deleteuser/",
			        data: {userid : userid},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

		// send password user
		$(".passUser").live("click", function() {		

			userid = $(this).attr('attribute');
            useremail = $(this).attr('attribute2');

			var r = confirm("Are you sure you want to send his/her password to " + useremail);
			if (r == true)
			{
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/passuser/",
			        data: {userid : userid},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

		// approve user
		$(".approveUser").live("click", function() {		

			userid = $(this).attr('attribute');	
			userstatus = $(this).attr('attribute2');		
            $(".ustatusDiv" + userid).html('<i class="fa fa-refresh fa-spin"></i>');

			$.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/approveuser/",
		        data: {userid : userid, user_status : userstatus},
		        type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		            $(".ustatusDiv" + userid).html(data);
		        }
		    })

		    return false;
		});

        $(".user_level").change(function() {
            userid = $("#user_id").val();	
            userlevel = $("#user_level option:selected").val();
            userdept = $("#user_dept option:selected").val();
            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/approvesel/",
                data: {userlevel : userlevel, userdept : userdept, userid : userid},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    if (data == "0") {
                        $("#user_approvers").attr('size', 1);
                        $("#user_approvers").attr('multiple', 'multiple');
                    }
                    else { 
                        $("#user_approvers").attr('size', 4);
                        $("#user_approvers").attr('multiple', 'multiple');
                    }
                    $(".user_approvers").html(data);
                }
            })
            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/deptsel/",
                data: {userlevel : userlevel},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    if (data == "0") {
                        $("#user_dept").attr('size', 1);
                        $("#user_dept").attr('name', 'user_dept');
                        $("#user_dept").removeAttr('multiple', 'multiple');
                    }
                    else { 
                        $("#user_dept").attr('size', 6);
                        $("#user_dept").attr('name', 'user_dept[]');
                        $("#user_dept").attr('multiple', 'multiple');
                    }
                }
            })
        });

        $("#user_dept").change(function() {	
            userid = $("#user_id").val();
            userlevel = $("#user_level option:selected").val();
            userdept = $("#user_dept option:selected").val();
            $.ajax(
            {
                url: "<?php echo WEB; ?>/ajax/approvesel/",
                data: {userlevel : userlevel, userdept : userdept, userid : userid},
                type: "POST",
                complete: function(){
                    $("#loading").hide();
                },
                success: function(data) {
                    if (data == "0") {
                        $("#user_approvers").attr('size', 1);
                        $("#user_approvers").attr('multiple', 'multiple');
                    }
                    else { 
                        $("#user_approvers").attr('size', 4);
                        $("#user_approvers").attr('multiple', 'multiple');
                    }
                    $(".user_approvers").html(data);
                }
            })
        });

		// delete user
		$(".delDept").live("click", function() {		

			deptid = $(this).attr('attribute');

			var r = confirm("Are you sure you want to delete");
			if (r == true)
			{
				$.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/deletedept/",
			        data: {deptid : deptid},
			        type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			            window.location.href=window.location.href;
			        }
			    })

			    return false;
			}
			
		});

		// approve department
		$(".approveDept").live("click", function() {		

			deptid = $(this).attr('attribute');	
			deptstatus = $(this).attr('attribute2');		
            $(".dstatusDiv" + deptid).html('<i class="fa fa-refresh fa-spin"></i>');

			$.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/approvedept/",
		        data: {deptid : deptid, dept_status : deptstatus},
		        type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		            $(".dstatusDiv" + deptid).html(data);
		        }
		    })

		    return false;
		});

        // close manage trans
		$(".closemanagetrans").live("click", function() {		
			
            $("#transDiv").addClass("invisible");

            return false;
			
		});
                              
        // open pending item
		$(".btnpenditems").live("click", function() {
            window.location.href='<?php echo WEB; ?>/pending';
		});
                              
        // open pending transaction
		$(".btnpendtrans").live("click", function() {
            window.location.href='<?php echo WEB; ?>/pending/trans';
		});

		// login
        
        $("#username").bind('keyup', function (e) {
            if (e.which >= 97 && e.which <= 122) {
                var newKey = e.which - 32;
                e.keyCode = newKey;
                e.charCode = newKey;
            }

            $("#username").val(($("#username").val()).toUpperCase());
        });
        
		$("#username").live("keypress", function(e) {
	        if (e.keyCode == 13) {
	            username = $("#username").val();
				password = $("#password").val();
                referer = $("#referer").val();
			    $.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/loginprocess/",
		            data: {username : username, password : password},
		            type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			        	if (data == 0) { 
			        		$('#errortd').html('<span class="redtext mediumtext2 bold">Access denied</span>'); 
			        		$('.lowerlogin').effect('shake', {times: 3, distance: 10}, 500); 
			        	}
			        	else { 
                            if (referer) {
		        		        window.location.href='<?php echo WEB; ?>/' + referer;
                            }
                            else {
                                window.location.href='<?php echo WEB; ?>';
                            }
			        	}
			        }
			    })
	        }
		});

		$("#password").live("keypress", function(e) {
	        if (e.keyCode == 13) {
	            username = $("#username").val();
				password = $("#password").val();
                referer = $("#referer").val();
			    $.ajax(
			    {
			        url: "<?php echo WEB; ?>/ajax/loginprocess/",
		            data: {username : username, password : password},
		            type: "POST",
			        complete: function(){
			        	$("#loading").hide();
			    	},
			        success: function(data) {
			        	if (data == 0) { 
			        		$('#errortd').html('<span class="redtext mediumtext2 bold">Access denied</span>'); 
			        		$('.lowerlogin').effect('shake', {times: 3, distance: 10}, 500); 
			        	}
			        	else { 
                            if (referer) {
		        		        window.location.href='<?php echo WEB; ?>/' + referer;
                            }
                            else {
                                window.location.href='<?php echo WEB; ?>';
                            }
			        	}
			        }
			    })
	        }
		});

		$("#btnlogin").live("click", function() {	
			username = $("#username").val();
			password = $("#password").val();
            referer = $("#referer").val();
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/loginprocess/",
	            data: {username : username, password : password},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
		        	if (data == 0) { 
		        		$('#errortd').html('<span class="redtext mediumtext2 bold">Access denied</span>'); 
		        		$('.lowerlogin').effect('shake', {times: 3, distance: 10}, 500); 
		        	}
		        	else { 
                        if (referer) {
                            window.location.href='<?php echo WEB; ?>/' + referer;
                        }
                        else {
                            window.location.href='<?php echo WEB; ?>';
                        }
		        	}
		        }
		    })
		});

        // inventory print
		$(".invprint").live("click", function() {	
			search = $('#searchinv').val();
			cat = $('#searchcat option:selected').val();
            if (search) { searchurl = '_' + search; }
            else { searchurl = ''; }
            if (cat != 0) { caturl = '_cat' + cat; }
            else { caturl = ''; }
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/printinv/",
	            data: {search : search, cat : cat},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
                    newwindow = window.open('height=' + screen.height + ',width=' + screen.width);
                    newwindow.location.href='<?php echo WEB; ?>/assets/pdfs/inventory' + searchurl + caturl + '.pdf';
                    newwindow.moveTo(0, 0);
                    newwindow.resizeTo(screen.width, screen.height);
                    newwindow.focus();
		        }
		    })
		});

        // inventory csv
		$(".invcsv").live("click", function() {	
			search = $('#searchinv').val();
			cat = $('#searchcat option:selected').val();
            if (search) { searchurl = '_' + search; }
            else { searchurl = ''; }
            if (cat != 0) { caturl = '_cat' + cat; }
            else { caturl = ''; }
            
            newwindow = window.open('height=' + screen.height + ',width=' + screen.width);
            newwindow.location.href='<?php echo WEB; ?>/ajax/csvinv?search=' + search + '&cat=' + cat;
            newwindow.moveTo(0, 0);
            newwindow.resizeTo(screen.width, screen.height);
		});

        // in out print
		$(".inoutprint").live("click", function() {	
			from = $('#inout_date_from').val();
			to = $('#inout_date_to').val();
		    $.ajax(
		    {
		        url: "<?php echo WEB; ?>/ajax/printinout/",
	            data: {from : from, to : to},
	            type: "POST",
		        complete: function(){
		        	$("#loading").hide();
		    	},
		        success: function(data) {
                    newwindow = window.open();
                    newwindow.location.href='<?php echo WEB; ?>/assets/pdfs/inout_' + from + '_' + to + '.pdf';
                    newwindow.moveTo(0, 0);
                    newwindow.resizeTo(screen.width, screen.height);
                    newwindow.focus();
		        }
		    })
		});

        // in out csv
		$(".inoutcsv").live("click", function() {	
			from = $('#inout_date_from').val();
			to = $('#inout_date_to').val();
            
            newwindow = window.open('height=' + screen.height + ',width=' + screen.width);
            newwindow.location.href='<?php echo WEB; ?>/ajax/csvinout?from=' + from + '&to=' + to;
            newwindow.moveTo(0, 0);
            newwindow.resizeTo(screen.width, screen.height);
		});

        // consumpt csv
		$(".consumptcsv").live("click", function() {	
			from = $('#consumpt_date_from').val();
			to = $('#consumpt_date_to').val();
            
            newwindow = window.open('height=' + screen.height + ',width=' + screen.width);
            newwindow.location.href='<?php echo WEB; ?>/ajax/csvconsumpt?from=' + from + '&to=' + to;
            newwindow.moveTo(0, 0);
            newwindow.resizeTo(screen.width, screen.height);
		});

        // pend csv
		$(".pendcsv").live("click", function() {	
			from = $('#pend_date_from').val();
			to = $('#pend_date_to').val();
            
            newwindow = window.open('height=' + screen.height + ',width=' + screen.width);
            newwindow.location.href='<?php echo WEB; ?>/ajax/csvpending?from=' + from + '&to=' + to;
            newwindow.moveTo(0, 0);
            newwindow.resizeTo(screen.width, screen.height);
		});

        // report from
        $(".rep_date_from").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#replink1').attr('href', "<?php echo WEB; ?>/reports/summary?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val());
				$('#replink3').attr('href', "<?php echo WEB; ?>/reports/request?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val());
				$('#replink4').attr('href', "<?php echo WEB; ?>/reports/in_out?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink5').attr('href', "<?php echo WEB; ?>/reports/csv_reordering_point?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink6').attr('href', "<?php echo WEB; ?>/reports/csv_consumption?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink7').attr('href', "<?php echo WEB; ?>/reports/csv_pending?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
                $('#replink8').attr('href', "<?php echo WEB; ?>/reports/pending_request?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
                $('#replink9').attr('href', "<?php echo WEB; ?>/reports/csv_dailyapprove?to=" + $(".rep_date_to").val());
				$('#replink11').attr('href', "<?php echo WEB; ?>/reports/csv_consumption_price?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink12').attr('href', "<?php echo WEB; ?>/reports/overstock?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val());
			},
            onClose: function(selectedDate) {
                $(".rep_date_to").datepicker("option", "minDate", selectedDate);
            }
        });

        // report to
        $(".rep_date_to").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#replink1').attr('href', "<?php echo WEB; ?>/reports/summary?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val());
				$('#replink3').attr('href', "<?php echo WEB; ?>/reports/request?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val());
				$('#replink4').attr('href', "<?php echo WEB; ?>/reports/in_out?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink5').attr('href', "<?php echo WEB; ?>/reports/csv_reordering_point?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink6').attr('href', "<?php echo WEB; ?>/reports/csv_consumption?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink7').attr('href', "<?php echo WEB; ?>/reports/csv_pending?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink8').attr('href', "<?php echo WEB; ?>/reports/pending_request?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
                $('#replink9').attr('href', "<?php echo WEB; ?>/reports/csv_dailyapprove?to=" + $(".rep_date_to").val());
				$('#replink11').attr('href', "<?php echo WEB; ?>/reports/csv_consumption_price?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
				$('#replink12').attr('href', "<?php echo WEB; ?>/reports/overstock?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val());
			},
            onClose: function(selectedDate) {
                $(".rep_date_from").datepicker("option", "maxDate", selectedDate);

            }
        });

        // report cat
        $("#rep_cat").change(function(){
            $('#replink4').attr('href', "<?php echo WEB; ?>/reports/in_out?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
            $('#replink5').attr('href', "<?php echo WEB; ?>/reports/csv_reordering_point?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
            $('#replink6').attr('href', "<?php echo WEB; ?>/reports/csv_consumption?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
            $('#replink11').attr('href', "<?php echo WEB; ?>/reports/csv_consumption_price?from=" + $(".rep_date_from").val() + "&to=" + $(".rep_date_to").val() + "&cat=" + $("#rep_cat option:selected").val());
        });

        // pend from
        $(".pend_date_from").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#frmpendate').submit();
			},
            onClose: function(selectedDate) {
                $(".pend_date_to").datepicker("option", "minDate", selectedDate);
            }
        });

        // pend to
        $(".pend_date_to").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#frmpendate').submit();
			},
            onClose: function(selectedDate) {
                $(".pend_date_from").datepicker("option", "maxDate", selectedDate);
            }
        });

        // in out from
        $(".inout_date_from").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#frminvdate').submit();
			},
            onClose: function(selectedDate) {
                $(".inout_date_to").datepicker("option", "minDate", selectedDate);
            }
        });

        // in out to
        $(".inout_date_to").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#frminvdate').submit();
			},
            onClose: function(selectedDate) {
                $(".inout_date_from").datepicker("option", "maxDate", selectedDate);
            }
        });

        // consumpt from
        $(".consumpt_date_from").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#frmconsumpt').submit();
			},
            onClose: function(selectedDate) {
                $(".consumpt_date_to").datepicker("option", "minDate", selectedDate);
            }
        });

        // consumpt to
        $(".consumpt_date_to").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            numberOfMonths: 2,
			onSelect: function (dateText, inst) {
				$('#frmconsumpt').submit();
			},
            onClose: function(selectedDate) {
                $(".consumpt_date_from").datepicker("option", "maxDate", selectedDate);
            }
        });

        // consumpt dept
        $("#consumptdept").change(function(){
            $('#frmconsumpt').submit();
        });
        
        // input validation
        
        $(".numberonly").keydown(function(event) {

            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

            } else {
                event.preventDefault();
            }

            if(event.keyCode == 190)
                event.preventDefault(); 
            //if a decimal has been added, disable the "."-button

        });
        
        $(".decinumberonly").keydown(function(event) {

            if (event.shiftKey == true) {
                event.preventDefault();
            }

            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 110 || event.keyCode == 190) {

            } else {
                event.preventDefault();
            }

            if($(this).val().indexOf('.') == 1 && event.keyCode == 110 && event.keyCode == 190)
                event.preventDefault(); 

        });

        // log from
        $(".fromlogs").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            onClose: function(selectedDate) {
                $(".tologs").datepicker("option", "minDate", selectedDate);
            }
        });

        // logs to
        $(".tologs").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "2014-01-01",
            maxDate: "0D",
            changeMonth: true,
            onClose: function(selectedDate) {
                $(".fromlogs").datepicker("option", "maxDate", selectedDate);
            }
        });

        $(".expiredate").datepicker({ 
            dateFormat: 'yy-mm-dd',
            minDate: "-1D",
            maxDate: "1Y",
            changeMonth: true,
            beforeShowDay: function(date) {
                var day = date.getDay();
                return [(day != 0), ''];
            }
        });


	});