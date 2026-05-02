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

        	$('#invoiceRetur').load(urlInvoiceRetur,{noInvoice});

        	$('#submit-retur').on("click",function(){
        		jsonObj = [];

		    	$('input[id=produk]').each(function(){
		    		var idProduk = $(this).data('sku');
		    		var hargaJual = $(this).data('harga');
		    		var qty = $(this).val();
		    		var diskon = $(this).data('diskon');

		    		item = {};

		    		item['idProduk'] = idProduk;
		    		item['hargaJual']  = hargaJual;
		    		item['qty']   = qty;
		    		item['diskon']   = diskon;

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
        </script>
    </body>
</html>
