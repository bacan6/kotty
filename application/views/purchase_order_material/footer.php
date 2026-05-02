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

        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

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

        <script type="text/javascript">
            var urlCartPO = "<?php echo base_url('purchaseOrderMaterial/cartPO'); ?>";

            jQuery(document).ready(function($) {
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
                });

                $('#data-input').load(urlCartPO);
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });

            $('#sku').change(function(){
               var idProduk = $(this).val();

               var urlInsertCartPo = "<?php echo base_url('purchaseOrderMaterial/insertCartPO'); ?>";

               $.ajax({
                            method      : 'POST',
                            url         : urlInsertCartPo,
                            data        : {idProduk : idProduk},
                            success     : function(response){
                                            if(response==1){
                                                $.Notification.notify('warning','top right', 'Terinput', 'Produk telah terinput sebelumnya');
                                            }

                                            $('#data-input').load(urlCartPO);
                                          }
               });
            });

            $('#prosesPO').on("click",function(){
                var jatuhTempo = $('#jatuhTempo').val();
                var tanggalKirim = $('#tanggalKirim').val();
                var supplier = $('#supplier').val();
                var keterangan = $('#keterangan').val();
                var alamatPengiriman = $('#alamatPengiriman').val();

                var urlInsertPO = "<?php echo base_url('purchaseOrderMaterial/insertPO'); ?>";

                if(jatuhTempo=='' || tanggalKirim=='' || supplier==''){
                    if(jatuhTempo==''){
                        $.Notification.notify('error', 'top right', 'Jatuh Tempo', 'Harap Isi Tanggal Jatuh Tempo');
                    }   

                    if(tanggalKirim==''){
                        $.Notification.notify('error', 'top right', 'Tanggal Kirim', 'Harap Isi Tanggal Kirim');
                    }

                    if(supplier==''){
                        $.Notification.notify('error', 'top right', 'Supplier', 'Harap Pilih Supplier');
                    }
                } else {
                    $.ajax({
                                method      : "POST",
                                url         : urlInsertPO,
                                data        : {jatuhTempo : jatuhTempo, tanggalKirim : tanggalKirim, supplier : supplier, keterangan : keterangan,alamatPengiriman : alamatPengiriman},
                                beforeSend  : function(){
                                                $('#prosesPO').prop("disabled",true);
                                              },
                                success     : function(noInv){
                                                $('#prosesPO').prop("disabled",true);
                                                window.location.replace("<?php echo base_url('purchaseOrderMaterial/formPOMaterial?no_po='); ?>"+noInv);
                                              }
                    });
                }
            });

            function formatAngka(angka) {
                 if (typeof(angka) != 'string') angka = angka.toString();
                 var reg = new RegExp('([0-9]+)([0-9]{3})');
                 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
                 return angka;
            }

            $(document).on("change", "#jumlah_beli, #harga_beli", function(){
                var sum = 0;
                $("input[class *= 'total_beli_hidden']").each(function(){
                    sum += +$(this).val();
                });
                $("#total_purchase").text(formatAngka(sum));
                $("#total_purchase_temp").val(sum);
            });

            $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('purchaseOrderMaterial/ajaxProduk'); ?>',
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

            $('.sendEmail').on("click",function(){
                var noPo            = this.id;
                var idSupplier      = $(this).data('idsupplier');

                //cek that supplier has email or not
                var urlCekEmail = "<?php echo base_url('purchase_order/cekEmailSupplier'); ?>"; 
                $.post(urlCekEmail,{idSupplier : idSupplier}, function(param){
                    if(param==0){
                        $.Notification.notify('error', 'top right', 'Supplier', 'Data Email Supplier Belum Dilengkapi');
                    } else {
                        var urlSendEmail = "<?php echo base_url('purchase_order/sendEmailPOSupplier'); ?>";

                        $.ajax({
                                    method      : "POST",
                                    data        : {noPo : noPo, idSupplier : idSupplier},
                                    url         : urlSendEmail,
                                    beforeSend  : function(){
                                                    $('.sendEmail').text("Harap Tunggu...");
                                                  },
                        }).done(function(data){
                            $('.sendEmail').html("<i class='fa fa-envelope'></i> Send By Email ");

                            if(data=='1'){
                                $.Notification.notify('success', 'top right', 'Email', 'Email Terkirim');
                            } else {
                                $.Notification.notify('error', 'top right', 'Email', 'Email Gagal Terkirim');
                            }
                        });
                    }
                });
            });


        </script>
    </body>
</html>
