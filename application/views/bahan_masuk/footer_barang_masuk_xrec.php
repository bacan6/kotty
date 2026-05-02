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
        

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>

        <script type="text/javascript">
            var urlCartPO = "<?php echo base_url('purchase_order_xrec/cartPO'); ?>";

            jQuery(document).ready(function(e) {
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
                });

                $('#data-input').load(urlCartPO);
                
                $('#brand').change(function(){
                	var supplier = $(this).val();
                	$.ajax({
                                method      : "POST",
                                url         : '<?php echo base_url('purchase_order_xrec/ajax_produk_supplier'); ?>',
                                data        : {supplier : supplier},
                                success     : function(noInv){
                                                
                                                $('#data-input').load(urlCartPO);
                                              }
                    });
                });
                
            });

            $(document).keydown(function(event) {
                if (event.ctrlKey && event.keyCode === 13) {
                    submitPO();
                }
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });

            $('#sku').change(function(){
               var idProduk = $(this).val();

               var urlInsertCartPo = "<?php echo base_url('purchase_order_xrec/insertCartPO'); ?>";

               $.ajax({
                            method      : 'POST',
                            url         : urlInsertCartPo,
                            data        : {idProduk : idProduk},
                            success     : function(response){
                                            if(response != 0){
                                               // $.Notification.notify('warning','top right', 'Terinput', 'Produk telah terinput sebelumnya');
                                                
                                                $('html, body').animate({scrollTop: $("#row"+response).offset().top}, 1000);

                                                $('#row'+response).css({"box-shadow" : "1px 0px 10px red"});

                                                setTimeout( function(){$('#row'+response).css({"box-shadow" : ""});} , 4000);
                                                $("#sku").select2("val","");

                                            } else {
                                                $('#data-input').load(urlCartPO);
                                                $("#sku").select2("val","");
                                            }
                                          } 
               });
            });

            $('#prosesPO').on("click",function(){
                submitPO();
            });
            $('#submitPenerimaan').on("click",function(){
                submitPO();
            });
            $('#kosongkanCart').on("click",function(){
                $.ajax({
                            method      : "POST",
                            url         : '<?php echo base_url('purchase_order_xrec/kosongkanCart'); ?>',
                            success     : function(){ 
                                            $('#data-input').load(urlCartPO);
                                            }
                });
            });
            

            function submitPO(){
                var diterimaOleh = $('#diterimaOleh').val();
                var diperiksaOleh = $('#diperiksaOleh').val();
                var tanggalTerima = $('#tanggalTerima').val();
                var diterimaDi = $('#diterimaDi').val();
                var PPN = $('#PPN').val();
                var diskon = $('#diskon').val();
                var noPo = $('#noPo').val();
                var idSupplier = $('#idSupplier').val();

                //$('#submitPenerimaan').prop('disabled',true);

                jsonObj = [];

                $('input[name=qty]').each(function(){
                    var qty = $(this).val();
                    var sku = $(this).data('produk');
                    var urut = $(this).data('urut');
                    var idProduk = $(this).data('produk');
                    var harga = $("#"+urut+".harga").val();
                    var hargajual = $("#"+urut+".hargajual").val();
                    var bonus = $('#bonus'+urut).val();
                    var diskon1 = $('#diskon1'+urut).val();
                    var diskon2 = $('#diskon2'+urut).val();
                    var diskon3 = $('#diskon3'+urut).val();
                    if (qty>0){
                        item = {};

                        item['sku']     = sku;
                        item['qty']     = qty;
                        item['harga']   = harga;
                        item['hargajual']   = hargajual;
                        item['bonus']   = bonus;
                        item['diskon1']   = diskon1;
                        item['diskon2']   = diskon2;
                        item['diskon3']   = diskon3;

                        jsonObj.push(item);
                    }
                    
                });

                var jatuhTempo = $('#jatuhTempo').val();
                var tanggalKirim = $('#tanggalKirim').val();
                var supplier = $('#supplier').val();
                var keterangan = $('#keterangan').val();
                var alamatPengiriman = $('#alamatPengiriman').val();

                var urlInsertPO = "<?php echo base_url('purchase_order_xrec/insertPO'); ?>";

                if(jatuhTempo=='' || tanggalKirim=='' || supplier=='' ||
                    diterimaOleh=='' || diperiksaOleh=='' || diterimaDi==''){
                    if(jatuhTempo==''){
                        $.Notification.notify('error', 'top right', 'Jatuh Tempo', 'Harap Isi Tanggal Jatuh Tempo');
                    }   

                    if(tanggalKirim==''){
                        $.Notification.notify('error', 'top right', 'Tanggal Kirim', 'Harap Isi Tanggal Kirim');
                    }

                    if(supplier==''){
                        $.Notification.notify('error', 'top right', 'Supplier', 'Harap Pilih Supplier');
                    }
                    if(diterimaOleh==''){
                        $('#diterimaAlert').text("**Harap Isi Form Berikut");
                        $.Notification.notify('error', 'top right', 'Penerima', 'Harap Isi Penerima');
                    }

                    if(diperiksaOleh==''){
                        $('#diperiksaAlert').text("**Harap Isi Form Berikut");
                        $.Notification.notify('error', 'top right', 'Pemeriksa', 'Harap Isi Pemeriksa');
                    }

                    if(diterimaDi==''){
                        $('#diterimaDiAlert').text("**Harap Pilih Salah Satu");
                        $.Notification.notify('error', 'top right', 'Cabang Penerima', 'Harap Isi Cabang Penerima');
                    }
                } else {
                    
                    $.ajax({
                                method      : "POST",
                                url         : urlInsertPO,
                                data        : {jatuhTempo : jatuhTempo, tanggalKirim : tanggalKirim, supplier : supplier, keterangan : keterangan,alamatPengiriman : alamatPengiriman},
                                beforeSend  : function(){
                                                $('#prosesPO').text('Harap Tunggu...');
                                                $('#CssLoader').show(); 
                                              },
                                success     : function(noInv){
                                                $.ajax({
                                                                method  : "POST",
                                                                url     : "<?php echo base_url('bahan_masuk/proses_receive_item'); ?>",
                                                                data    : {produkItem : JSON.stringify(jsonObj), diterimaOleh : diterimaOleh, PPN:PPN,diperiksaOleh : diperiksaOleh, tanggalTerima : tanggalTerima,diterimaDi : diterimaDi, noPo : noInv, idSupplier : idSupplier,diskon:diskon},
                                                                success     : function(response){
                                                                    window.location.replace("<?php echo base_url('purchase_order_xrec/form_po?no_po='); ?>"+noInv);
                                                                }
                                                });
                                                
                                              }
                    });
                }
            }

            

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
                    url         : '<?php echo base_url('purchase_order_xrec/ajax_produk'); ?>',
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
