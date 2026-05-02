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
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
        	var noPO = "<?php echo $_GET['no_tagihan']; ?>";
            var urlRiwayatPembayaran = "<?php echo base_url('finance/riwayatPembayaran'); ?>"
            var urlInvoiceReceive = "<?php echo base_url('finance/invoiceReceive'); ?>";
            var urlTagihan = "<?php echo base_url('finance/dataTagihan'); ?>";

            $(document).ready(function(){
                $('#invoiceReceive').load(urlInvoiceReceive,{noPO : noPO});
            	$('#riwayatPembayaran').load(urlRiwayatPembayaran,{noPO : noPO});
                $('#dataTagihan').load(urlTagihan,{noPO : noPO});
            });
            
            $('#simpanPembayaran').on("click",function(){
                var jumlahPembayaran = $('#jumlahPembayaran').val();
                var tipeBayar = $('#tipeBayar').val();
                var keterangan = $('#keterangan').val();

                var urlSubmitPembayaran = "<?php echo base_url('finance/submitPembayaran'); ?>";

                $.ajax({
                            method : "POST",
                            url : urlSubmitPembayaran,
                            data : {jumlahPembayaran : jumlahPembayaran, tipeBayar : tipeBayar, keterangan : keterangan, noPO : noPO},
                            beforeSend : function(){
                                            $('#simpanPembayaran').text("Harap Tunggu...");
                                            $('#simpanPembayaran').prop('disabled',true);
                                         },
                            success : function(response){
                                        $.Notification.notify('success','top right', 'Berhasil', 'Data Pembayaran Telah Terinput');
                                        $('#simpanPembayaran').prop('disabled',false);
                                        $('#riwayatPembayaran').load(urlRiwayatPembayaran,{noPO : noPO});
                                        $('#simpanPembayaran').text("Submit");
                                        $('#dataTagihan').load(urlTagihan,{noPO : noPO});
                                        var urlRedirect = "<?php echo base_url('finance/invoicePembayaran?no_payment='); ?>"+response;
                                                window.open(urlRedirect,"_blank");
                                      },
                            error : function(){
                                        alert("Error");
                                    }
                });
            });

            $('#jatuhTempo').on("click",function(){
                var noPO = $(this).data('no_po');
                var urlJatuhTempo = "<?php echo base_url('finance/jatuhTempoForm'); ?>";

                $('.modal-body').load(urlJatuhTempo,{noPO : noPO});
            });
        </script>
    </body>
</html>
