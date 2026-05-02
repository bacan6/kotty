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
            var dataUrl = "<?php echo base_url('bahan_keluar/viewCart'); ?>";
         
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                jQuery('#datepicker').datepicker({
                    format: "yyyy-mm-dd"
                });

                jQuery('#datepicker2').datepicker({
                    format: "yyyy-mm-dd"
                });

                $('#data-input').load(dataUrl);
                
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });

            $('#sku').on("change",function(){
                var sku = $(this).val();

                urlProduk = "<?php echo base_url('bahan_keluar/getDataProdukWarehouse'); ?>";

                $.ajax({
                            type            : "POST",
                            url             : urlProduk,
                            dataType        : 'json',
                            data            : {sku, sku},
                            success         : function(stok){
                                                    if(parseInt(stok) > 0){
                                                        var urlCart = "<?php echo base_url('bahan_keluar/insertCart'); ?>";

                                                        $.post(urlCart,{sku : sku},function(response){
                                                            if(response != 0){
                                                                $('html, body').animate({scrollTop: $("#row"+response).offset().top}, 1000);

                                                                $('#row'+response).css({"box-shadow" : "1px 0px 10px red"});

                                                                setTimeout( function(){$('#row'+response).css({"box-shadow" : ""});} , 4000);
                                                            } else {
                                                                $('#data-input').load(dataUrl);
                                                                $('#sku').select2("val","");
                                                            } 

                                                            
                                                        });
                                                    } else {
                                                        $.Notification.notify('error','top right', 'Tidak ada stok', 'Stok Saat Ini 0');  
                                                    }
                                          
                                              }
                });
            });

            $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('bahan_keluar/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 100,
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
                                'text': item.text
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 3,
            });

            $('#submitMutasi').on("click",function(){
                submitMutasi();
            });

            $(document).keydown(function(event) {
                if (event.ctrlKey && event.keyCode === 13) {
                    submitMutasi();
                }
            });

            function submitMutasi(){
                var namaPenerima        = $('#namaPenerima').val();
                var storeTujuan         = $('#storeTujuan').val();
                var keterangan          = $('#keterangan').val();

                if(namaPenerima=='' || storeTujuan==''){

                    if(namaPenerima==''){
                        $('#namaPenerimaLabel').text('Nama Penerima Harus Di Isi');
                    }

                    if(storeTujuan==''){
                        $('#storeTujuanLabel').text("Store Tujuan Harus Di Isi");
                    }
                } else {
                    $.ajax({
                                method      : "POST",
                                url         : "<?php echo base_url('bahan_keluar/prosesBarangKeluar'); ?>",
                                data        : {namaPenerima : namaPenerima, storeTujuan : storeTujuan, keterangan : keterangan},
                                beforeSend  : function(){
                                                $('#submitMutasi').prop("disabled",true);
                                                $('#submitMutasi').text("Harap Tunggu");
                                                $('#CssLoader').show(); 
                                              },
                                success     : function(inv){
                                                window.location.replace("<?php echo base_url('bahan_keluar/invoice_pengeluaran_barang?no_keluaran='); ?>"+inv);
                                              },
                                error       : function(){
                                                alert("Mutasi Error");
                                              }
                    });
                }
            }
        </script>
    </body>
</html>
