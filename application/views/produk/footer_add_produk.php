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


        <script src="<?php echo base_url('assets'); ?>/js/jquery.app.js"></script>
        <!-- Chat -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.chat.js"></script>
        <!-- Dashboard -->
        
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
        /* ==============================================
             Counter Up
             =============================================== */
             var spinner = "<?php echo base_url('produk/spinner'); ?>";

            jQuery(document).ready(function($) {
               $('#jenis_produk').change(function(){
                    id = $('#jenis_produk').val();

                    produksi        = "<?php echo base_url('produk/form_produk_produksi'); ?>";
                    non_produksi    = "<?php echo base_url('produk/form_produk_non_produksi'); ?>";

                    if(id==1){
                        $('#form-tambah-produk').load(produksi);
                    } else if(id==2) {
                        $('#form-tambah-produk').load(non_produksi);
                    } else{
                        $('#form-tambah-produk').empty();
                    }
               });
            });

            $('#kategori').change(function(){
                kategori = $('#kategori').val();
                url = "<?php echo base_url('produk/get_subkategori'); ?>";

                $('#sub_kategori').load(url,{id_kategori : kategori});
            });

            function setNewSKU(){
                var subkategori_3 = $('#subkategori_3').val();

                $.ajax({
                            method      : "POST",
                            url         : "<?php echo base_url('produk/getNewSKU'); ?>",
                            data        : {id_subkategori_3 : subkategori_3},
                            success     : function(data){
                                            $('#sku').val(subkategori_3+data);
                                          }
                       });
            }

            $('#sku').change(function(){
                var sku = $('#sku').val();

                $.ajax({
                            method      : "POST",
                            url         : "<?php echo base_url('produk/cekSKUIfExist'); ?>",
                            data        : {sku : sku},
                            success     : function(data){
                                            if(data == 1){
                                                $('#skuAlert').text("*SKU Telah Terpakai");
                                                $('#sku').val('');
                                            } else {
                                                $('#skuAlert').empty();
                                            }
                                          }
                       });
            });

            $('#addProdukSql').on("click",function(){
                var sku             = $('#sku').val();
                var qr_code         = $('#qr_code').val();
                var namaProduk      = $('#namaProduk').val();
                var isi             = $('#isi').val();
                var satuan          = $('#satuan').val();
                var tempat          = $('#tempat').val();
                var kategori        = $('#kategori').val();
                var kategori2       = $('#subkategori_2').val();
                var kategori3       = $('#subkategori_3').val();
                var brand       	= $('#Brand').val();

                jsonObj = [];

                $('input[id=hargaJual]').each(function(){
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();
                    var supplier    = $('#Supplier'+idStore).val();

                    item = {};
                    
                    item['hargaBeli'] = hargaBeli;
                    item['hargaJual'] = hargaJual;
                    item['idStore']   = idStore;
                    item['supplier']  = supplier;

                    jsonObj.push(item);
                });

                if(sku=='' || namaProduk=='' || satuan=='' || kategori==''){
                    if(sku==''){
                        $('#skuAlert').text("*SKU Required");
                    }

                    if(namaProduk==''){
                        $('#namaProdukAlert').text('*Nama Produk Required');
                    }

                    if(satuan==''){
                        $('#satuanAlert').text('*Satuan Required');
                    }

                    if(kategori==''){
                        $('#kategoriAlert').text("*Kategori Required");
                    }
                } else {
                    $.ajax({
                                method      : "POST",
                                url         : "<?php echo base_url('produk/tambahProdukNonProduksiSQL'); ?>",
                                data        : {hargaJual : JSON.stringify(jsonObj),sku : sku, namaProduk : namaProduk,isi : isi,  satuan : satuan, tempat : tempat, kategori : kategori, kategori2 : kategori2, kategori3 : kategori3, brand:brand, qr_code:qr_code},
                                beforeSend  : function(){
                                                $('#form-tambah-produk').load(spinner);
                                              }
                          }).done(function(){
                                urlForm = "<?php echo base_url('produk/add_produk'); ?>";
                                //$('#form-tambah-produk').load(urlForm); 

                                $.Notification.notify('success', 'top right', 'Tambah Produk', 'Produk Berhasil Ditambahkan');
                                setTimeout(function(){window.location=urlForm;},1000);
                          });
                }

            });

            function hitungMargin(urut){
                var margin = 0;
                margin = ((Number($('#hrgProdukJual'+urut).val())-Number($('#hrgProduk'+urut).val()))/Number($('#hrgProdukJual'+urut).val())*100).toFixed(2);
                $('#margin'+urut).val(margin);

                $('input[id=hargaJual]').each(function(){
                    var margin = 0;
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();

                    margin = ((Number(hargaJual)-Number(hargaBeli))/Number(hargaJual)*100).toFixed(2);
                    $('#margin'+idStore).val(margin);
                });
            }
            function hitungHarga(urut){
                $('input[id=hargaJual]').each(function(){
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();

                    var harga1 = 0;
                    var margin = Number($('#margin'+idStore).val());
                    harga1 = hargaBeli/((100-margin)/100);
                    $(this).val((harga1).toFixed(2));
                });
            }
            jQuery("select").select2({
                width: '100%'
            });
        </script>
    </body>
</html>
