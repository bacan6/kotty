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
         <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/spinner/spinner.min.js"></script>
        <script type="text/javascript">
            var noWO = "<?php echo $this->input->get("noWO"); ?>";

            var urlPesanan = "<?php echo base_url('penerimaanWorkOrder/daftarPesanan'); ?>";
            var urlMaterial = "<?php echo base_url('penerimaanWorkOrder/daftarMaterial'); ?>";
            var urlRiwayatPenerimaan = "<?php echo base_url('penerimaanWorkOrder/riwayatPenerimaan'); ?>";
            var urlRiwayatAdjusment = "<?php echo base_url('penerimaanWorkOrder/riwayatAdjusment'); ?>";

            $('#daftarPesanan').load(urlPesanan,{noWO : noWO});
            $('#daftarMaterial').load(urlMaterial,{noWO : noWO});
            $('#riwayatPenerimaan').load(urlRiwayatPenerimaan,{noWO : noWO});
            $('#riwayatAdjusment').load(urlRiwayatAdjusment,{noWO: noWO});    

            jQuery(".select2").select2({
                width: '100%'
            });

            $('#submitProduk').on("click",function(){
                var diterimaOleh    = $('#diterimaOleh').val();
                var diperiksaOleh   = $('#diperiksaOleh').val();
                var diterimaDi      = $('#diterimaDi').val();
                var idSupplier      = $("#idSupplier").val();
                var noWO            = $('#noWO').val();

                jsonObj = [];

                $('input[id=listProduk]').each(function(){
                    var idProduk = $(this).data('id_produk');
                    var qty = $(this).val();

                    item={};

                    item['idProduk'] = idProduk;
                    item['qty'] = qty;

                    jsonObj.push(item);
                });

                if(diperiksaOleh=='' || diterimaOleh=='' || diterimaDi==''){

                    if(diperiksaOleh==''){
                        $.Notification.notify('error','top right', 'Error', 'Harap lengkapi nama pemeriksa');
                    } 

                    if(diterimaOleh==''){
                        $.Notification.notify('error','top right', 'Error', 'Harap lengkapi nama penerima');
                    }

                    if(diterimaDi==''){
                         $.Notification.notify('error','top right', 'Error', 'Harap pilih tempat penerimaan barang');
                    }

                } else {
                    var urlReceiveWOSQL = "<?php echo base_url('penerimaanWorkOrder/receiveWOResultSQL'); ?>";

                    $.ajax({
                                method      : "POST",
                                url         : urlReceiveWOSQL,
                                data        : {noWO : noWO, idSupplier : idSupplier, diterimaOleh : diterimaOleh, diperiksaOleh : diperiksaOleh, diterimaDi : diterimaDi, listProduk : JSON.stringify(jsonObj)},
                                beforeSend  : function(){
                                                $('#submitProduk').prop("disabled",true);
                                                $('#submitProduk').text('Harap Tunggu...');
                                              },
                                success     : function(response){
                                                $('#submitProduk').text('Submit');
                                                $('#submitProduk').prop('disabled',false);

                                                $('.qtyAjax').val('');
                                                $('#diterimaOleh').val('');
                                                $('#diperiksaOleh').val('');
                                                $('#diterimaDi').select2("val","");

                                                $('#daftarPesanan').load(urlPesanan,{noWO : noWO});
                                                $('#riwayatPenerimaan').load(urlRiwayatPenerimaan,{noWO : noWO});
                                                
                                                var urlRedirect = "<?php echo base_url('penerimaanWorkOrder/invoiceReceive?no_receive='); ?>"+response;
                                                window.open(urlRedirect,"_blank");
                                              }
                    });
                }
            });

            $('#submitAdjusment').on("click",function(){
                var urlSubmitAdjust = "<?php echo base_url('penerimaanWorkOrder/submitAdjust'); ?>";
                var keterangan = $('#keterangan').val();

                jsonObj = [];

                $('input[id=adjustItem]').each(function(){
                    var sku = $(this).data('sku');
                    var qty = $(this).val();

                    item = {};

                    item['sku'] = sku;
                    item['qty'] = qty;

                    jsonObj.push(item); 
                });

                $.ajax({
                            method      : "POST",
                            url         : urlSubmitAdjust,
                            data        : {keterangan : keterangan, dataItem : JSON.stringify(jsonObj), noWO : noWO},
                            beforeSend  : function(){
                                            $("#submitAdjusment").prop("disabled",true);
                                            $('#submitAdjusment').text("Harap Tunggu...");
                                          },
                            success     : function(){
                                            $("#submitAdjusment").prop("disabled",false);
                                            $('#submitAdjusment').text("Submit");
                                            $('#riwayatAdjusment').load(urlRiwayatAdjusment,{noWO: noWO});  
                                            $('#daftarMaterial').load(urlMaterial,{noWO : noWO});
                                            $('.qtyAjax').val('');
                                            $.Notification.notify('success','top right', 'Sukses', 'Adjusment Telah Berhasil Diinput');
                                          }
                });
            });

            $('.changeStatus').on("click",function(){
                var status = this.id;   
                var urlChangeStatus = "<?php echo base_url('penerimaanWorkOrder/changeStatus'); ?>";

                $.post(urlChangeStatus,{status : status, noWO : noWO}, function(response){
                    window.location.replace(response);
                });
            });

            $('.qtyAjaxPesanan').change(function(){
                var qty = $(this).val();
                var sku = $(this).data('sku');

                $.ajax({
                            method      : "POST",
                            url         : "<?php echo base_url('penerimaanWorkOrder/maxStokAdj'); ?>",
                            data        : {sku : sku},
                            success     : function(response){
                                            if(qty > response){
                                                $.Notification.notify('error','top right', 'Error', 'Stok bahan baku tidak mencukupi');
                                                $('.sku'+sku).val(qty-1);
                                            }
                                          }
                });
            });
        </script>
    </body>
</html>
