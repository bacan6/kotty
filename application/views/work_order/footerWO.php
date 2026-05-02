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
        <script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>" type="text/javascript"></script>
       	<script type="text/javascript">
            CKEDITOR.replace('keterangan');

       		var urlDaftarBahanBaku = "<?php echo base_url('workOrder/viewCartBahanBaku'); ?>";
       		var urlCartProdukConvert = "<?php echo base_url('workOrder/viewCartProdukConvert'); ?>";

       		$(document).ready(function(){
       			$('#daftarBahanBaku').load(urlDaftarBahanBaku);
       			$('#convertItem').load(urlCartProdukConvert);
       		});

       		jQuery('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                autoclose : true
            });

            jQuery(".select2").select2({
                width: '100%'
            });

            $('#bahanAjax').select2({
                placeholder: "Pilih Data Material",
                ajax: {
                    url         : '<?php echo base_url('workOrder/ajaxBahan'); ?>',
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

            $('#bahanAjax').on("click",function(){
            	var sku = $(this).val();

            	var insertUrl = "<?php echo base_url('workOrder/insertCart'); ?>";

            	$.ajax({
            				method 		: "POST",
            				url 		: insertUrl,
            				data 		: {sku : sku},
            				success 	: function(response){
            								if(response==0){
            									$.Notification.notify('error','top right', 'Stok Tidak Mencukupi', 'Stok saat ini = 0');
            								} else if(response==1){
            									$.Notification.notify('warning','top right', 'Ooops', 'Barang sudah terinput');
            								} else {
            									$('#daftarBahanBaku').load(urlDaftarBahanBaku);
            								}
            								
            							  }
            	});
            });

            $('#produkAjax').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('workOrder/ajaxProduk'); ?>',
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

           	$('#produkAjax').change(function(){
               var idProduk = $(this).val();

               var urlInsertCartPo = "<?php echo base_url('workOrder/insertCartFG'); ?>";

               $.ajax({
                            method      : 'POST',
                            url         : urlInsertCartPo,
                            data        : {idProduk : idProduk},
                            success     : function(response){
                                            if(response==1){
                                                $.Notification.notify('warning','top right', 'Terinput', 'Produk telah terinput sebelumnya');
                                            } else {
                                           	 	$('#convertItem').load(urlCartProdukConvert);
                                            }
                                          }
               });
            });

            $('#prosesWO').on("click",function(){
                var datePromise = $('#datePromise').val();
                var vendor = $('#supplier').val();
                var pemohon = $('#pemohon').val();
                var keterangan = CKEDITOR.instances.keterangan.getData();
                var alamatPengiriman = $('#alamatPengiriman').val();
                var urlProsesWO = "<?php echo base_url('workOrder/prosesWO'); ?>";

                kindPay = [];
                biaya   = [];

                $('input[id=jenisBiaya]').each(function(){
                    var jenisBiaya  = $(this).val();

                    item = {};
                    item['jenisBiaya'] = jenisBiaya;
                    kindPay.push(item);
                });

                $('input[id=biaya]').each(function(){
                    var payment = $(this).val();

                    row = {};
                    row['biaya'] = payment;
                    biaya.push(row);
                });

                if(datePromise=='' || vendor=='' || pemohon==''){
                    if(datePromise==''){
                        $.Notification.notify('warning','top right', 'Warning !', 'Harap isi tanggal penyelesaian');
                    }

                    if(vendor==''){
                        $.Notification.notify('warning','top right', 'Warning !', 'Harap pilih vendor');
                    }

                    if(pemohon==''){
                         $.Notification.notify('warning','top right', 'Warning !', 'Harap isi pemohon');
                    }
                } else {
                    $.ajax({
                                method      : "POST",
                                url         : urlProsesWO,
                                data        : {jenisBiaya : JSON.stringify(kindPay),biaya : JSON.stringify(biaya), datePromise : datePromise, vendor : vendor, pemohon : pemohon, keterangan : keterangan, alamatPengiriman : alamatPengiriman},
                                beforeSend  : function(){
                                                $('#prosesWO').text("Harap Tunggu...");
                                                $('#prosesWO').prop('disabled',true);
                                              },
                                success     : function(inv){
                                                window.location.replace("<?php echo base_url('workOrder/formWO?noWO='); ?>"+inv);
                                              }
                    });
                }
            });
       	</script>
    </body>
</html>
