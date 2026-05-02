 <!-- Page Content Ends -->
            <!-- ================== -->

            <!-- Footer Start -->
            <footer class="footer">
                <?php echo $footer; ?>
            </footer>
            <!-- Footer Ends -->



        </section>
        <!-- Main Content Ends -->
        


        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/modernizr.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/pace.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/wow.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.nicescroll.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/chat/moment-2.2.1.js"></script>

        <!-- Counter-up -->
        <script src="<?php echo base_url('assets'); ?>/js/waypoints.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.counterup.min.js" type="text/javascript"></script>

        <!-- sweet alerts -->
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.init.js"></script>

        <script src="<?php echo base_url('assets'); ?>/js/jquery.app.js"></script>
        <!-- Chat -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.chat.js"></script>
        <!-- Dashboard -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.dashboard.js"></script>

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

        <script type="text/javascript">
        	var noInvoice = "<?php echo $no_invoice; ?>";
        	var urlInvoiceRetur = "<?php echo base_url('penjualan/invoiceRetur'); ?>";
            <?php 
            $arUsr = array(45,44,43,40,34);
            if(in_array($idUser,$arUsr)){?>
                var verifyApproval = "<?php echo base_url('penjualan/verifyApprovalPass'); ?>";
            <?php }else{ ?>
                var verifyApproval = "<?php echo base_url('penjualan/verifyApproval'); ?>";
            <?php } ?>

            jQuery(".select2").select2({
                width: '100%'
            });

        	$('#invoiceRetur').load(urlInvoiceRetur,{noInvoice});

        	$('#submit-retur').on("click",function(){
        		jsonObj = [];

		    	$('input[id=produk]').each(function(){
		    		var idProduk = $(this).data('sku');
		    		var hargaJual = $(this).data('harga');
		    		var qty = $(this).val();
                    var diskon = $(this).data('diskon');
                    var hpp = $(this).data('hpp');

		    		item = {};

		    		item['idProduk'] = idProduk;
		    		item['hargaJual']  = hargaJual;
		    		item['qty']   = qty;
                    item['diskon']   = diskon;
                    item['hpp']   = hpp;

		    		jsonObj.push(item);
		    	});

		    	$.ajax({
		    				method : "POST",
		    				url : "<?php echo base_url('penjualan/returPenjualanSQL'); ?>",
		    				data : {noInvoice : noInvoice, dataProduk : JSON.stringify(jsonObj)},
		    				beforeSend : function(){
		    								$('#submit-retur').prop('disabled',true);
		    								$('#submit-retur').text('Harap Tunggu...');
		    							 },
		    				success : function(){
		    							$('#invoiceRetur').load(urlInvoiceRetur,{noInvoice});
		    							$.Notification.notify('success', 'top right', 'Sukses', 'Data Berhasil Diretur');
		    							$('#submit-retur').prop('disabled',false);
		    							$('#submit-retur').text('Simpan');
                                        $('.produkFill').val('');
		    						  }
		    	});
        	});
            // $('#verifyApproval').on("click",function(){
            //     var user = $("#userApprover").val();
            //     var pw = $("#passApprover").val();

            //     var verifyApproval = "<?php echo base_url('penjualan/verifyApprovalPass'); ?>";
            //     $.ajax({
        	// 		method : "POST",
        	// 		url : verifyApproval,
        	// 		dataType : 'json',
        	// 		data : {user : user, pw : pw},
        	// 		success : function(response){		
			// 		    var setuju = response.setuju;
			// 		    if (setuju==1){
            //                 $("#submit-retur").css('display', '');
            //                 $("#minta-setuju").css('display', 'none');
            //                 $("#approvalKasir").modal('hide');
            //                 $("#passApprover").val('');
            //                 $('#labelpwd').html('');
            //             }else {
            //                 $("#passApprover").val('');
            //                 $("#passApprover").focus();
            //                 $("#submit-retur").css('display', 'none');
            //                 $("#minta-setuju").css('display', '');
            //                 $('#labelpwd').html('Pengguna tidak ditemukan...');
            //             }
        	// 		}
            //     });
            // });
            $('#verifyApproval').on("click",function(){
                verifyMe();
            });
            function verifyMe(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();
                var baris = $("#chartID").val();

                $.ajax({
        			method : "POST",
        			url : verifyApproval,
        			dataType : 'json',
        			data : {user : user, pw : pw},
        			success : function(response){		
					    var setuju = response.setuju;
					    if (setuju==1){
                           $("#submit-retur").css('display', '');
                            $("#minta-setuju").css('display', 'none');
                            $("#approvalKasir").modal('hide');
                            $("#passApprover").val('');
                            $('#labelpwd').html('');
                        }else {
                            $("#passApprover").val('');
                            $("#passApprover").focus();
                            $("#submit-retur").css('display', 'none');
                            $("#minta-setuju").css('display', '');
                            $('#labelpwd').html('Pengguna tidak ditemukan...');
                        }
        			}
                });
            }
            function login_selectuser(device_name, sn) {
                var element = $("#select_scan").find('option:selected'); 
                var username = element.attr("username"); 

				$("#button_login").attr("href","finspot:FingerspotVer;"+$('#select_scan').val());
                $("#userApprover").val(username);
                $('#labelvrf').html('');

                // new fingerprint
                var webhook = encodeURIComponent(element.attr("webhook_new").trim());
                var user = encodeURIComponent(element.attr("user_new").trim());
                var fingerprint = encodeURIComponent(element.attr("fingerprint").trim());

                $("#button_login_new").attr("href","fpsolusi://identify?webhook="+webhook+"&username="+user+'&fingerprint='+fingerprint).get(0).click();

                var username = $("#userApprover").val();
                if(username!='' && username!=undefined) {
                    $("#button_login").show();
                    $('#labelvrf').html('loading...');
                    var open_verifikasi = "<?php echo base_url('penjualan/open_verifikasi'); ?>";
                    var cek_verifikasi = "<?php echo base_url('penjualan/cek_verifikasi'); ?>";
                        $.ajax({
                            method : "POST",
                            url : open_verifikasi,
                            dataType : 'json',
                            data : {username : username},
                            success : function(resp){
                                var username = resp.username;	
                                setTimeout(function() { 
                                    $.ajax({
                                        method : "POST",
                                        url : cek_verifikasi,
                                        dataType : 'json',
                                        data : {username : username},
                                        success : function(response2){	
                                            var stat = response2.status;
                                            if(stat==1){
                                                $('#passApprover').val(response2.ps);
                                                $('#labelvrf').html('verification success!');
                                                setTimeout(function() { $('#labelvrf').html(''); },5000);
                                                
                                                $('#labelpwd').html('');
                                                $("#verifyApproval").hide();
                                                $("#submit-retur").css('display', '');
                                                $("#minta-setuju").css('display', 'none');
                                            }else{
                                                $('#passApprover').val('');
                                                $('#labelpwd').html('verification failed!');
                                                setTimeout(function() { $('#labelpwd').html(''); },3000);
                                                
                                                $('#labelvrf').html('');
                                            }
                                        }
                                    });
                                }, 5000);
                            }
                        });
                }
               
			}
            $('#button_login').on("click",function(){
               //if(username=='') return false;
               
            });
        </script>
    </body>
</html>
