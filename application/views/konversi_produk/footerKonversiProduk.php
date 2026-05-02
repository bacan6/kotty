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
            var urlViewCart = "<?php echo base_url('konversiProduk/viewCart'); ?>";

            $('#dataCart').load(urlViewCart);

            $('#produk-ajax').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('konversiProduk/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 500,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text,
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 3,
            });

            $('#produk-ajax').on("click",function(){
                var idProduk = $(this).val();

                var urlInsertConvert = "<?php echo base_url('konversiProduk/insertCart'); ?>";

                $.ajax({
                            method      : "POST",
                            url         : urlInsertConvert,
                            data        : {idProduk : idProduk},
                            success     : function(response){
                                            if(response==0){
                                                $.Notification.notify('error','top right', 'Stok Tidak Mencukupi', 'Stok saat ini = 0');
                                            } else if(response==2){
                                                $.Notification.notify('error','top right', 'Terinput', 'Barang sudah terinput');
                                            }

                                            $('#dataCart').load(urlViewCart);
                                          }
                });
            });

            $('#prosesConvert').on("click",function(){
                $.ajax({
                            url : "<?php echo base_url('konversiProduk/prosesKonversiSQL'); ?>",
                            beforeSend : function(){
                                            $('#prosesConvert').prop('disabled',true);
                                            $('#prosesConvert').text("Harap Tunggu...");
                                         },
                            success : function(noKonversi){
                                        var urlReplace = "<?php echo base_url('konversiProduk/formKonversi?noKonversi='); ?>"+noKonversi;
                                        window.location.replace(urlReplace);
                                      }
                });
            });
        </script>
    </body>
</html>
