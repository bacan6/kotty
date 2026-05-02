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
        <script src="<?php echo base_url('assets'); ?>/js/wow.min.jfs"></script>
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
        /* ==============================================
             Counter Up
             =============================================== */            
            var noInvoice = "<?php echo $_GET['no_invoice']; ?>";   
            var urlRiwayatPembayaran = "<?php echo base_url('data_piutang/riwayatPembayaran'); ?>";
            var urlDataPembayaran = "<?php echo base_url('data_piutang/dataPembayaran'); ?>";

            $('#riwayatPembayaran').load(urlRiwayatPembayaran,{noInvoice : noInvoice});
            $('#dataPembayaran').load(urlDataPembayaran,{noInvoice : noInvoice});

            $('#type_bayar').change(function(){
                type = $('#type_bayar').val();

                sub_account = "<?php echo base_url('data_piutang/sub_account'); ?>";

                $('#sub-account').load(sub_account,{id : type});
            }); 
            

            $('#simpanPembayaran').on("click",function(){
               var nominal = $('#nominal').val();
               var typeBayar = $('#type_bayar').val();
               var subAccount = $('#subAccount').val();
               var keterangan = $('#keterangan').val();

               var urlPembayaran = "<?php echo base_url('data_piutang/bayar_piutang_sql'); ?>";

               $.ajax({ 
                            method : "POST",
                            url : urlPembayaran,
                            data : {noInvoice : noInvoice, nominal : nominal, typeBayar : typeBayar, subAccount : subAccount, keterangan : keterangan},
                            beforeSend : function(){
                                            $('#simpanPembayaran').prop('didsabled',true);
                                         },
                            error : function(){
                                        $.Notification.notify('danger','top right', 'error', 'Gagal Menyimpan Data');
                                    },
                            success : function(){
                                        $.Notification.notify('success','top right', 'Sukses', 'Data berhasil disimpan');
                                        $('#riwayatPembayaran').load(urlRiwayatPembayaran,{noInvoice : noInvoice});
                                        $('#dataPembayaran').load(urlDataPembayaran,{noInvoice : noInvoice});
                                      }

               }); 
            });

            $('.lunasiPiutang').on("click",function(){
                var urlLunasiPiutang = "<?php echo base_url('data_piutang/lunasiPiutang'); ?>";

                swal({   
                    title: "Anda yakin ?",   
                    text: "Status piutang akan berubah menjadi lunas!",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Yes!",   
                    closeOnConfirm: false 
                }, function(){   
                    $.ajax({
                            method : "POST",
                            url : urlLunasiPiutang,
                            data : {noInvoice : noInvoice},
                            beforeSend : function(){
                                            $('.lunasiPiutang').prop('disabled',true);
                                         },
                            success : function(){
                                        $('#riwayatPembayaran').load(urlRiwayatPembayaran,{noInvoice : noInvoice});
                                        $('#dataPembayaran').load(urlDataPembayaran,{noInvoice : noInvoice});
                                        swal("Sukses!", "Status piutang berubah", "success"); 
                                      }
                    });
                    
                });


            });
        </script>
    </body>
</html>
