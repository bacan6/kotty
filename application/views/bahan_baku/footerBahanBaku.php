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
            
            $("#tableStok").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('bahan_baku/datatablesBahanBaku'); ?>",
                    type:'POST'
                }
            });

            $('#simpanBahanBaku').on("click",function(){
                var namaBahan   = $('#namaBahan').val();
                var harga       = $('#harga').val();
                var kategori    = $('#kategori').val();
                var satuan      = $('#satuan').val();
                var sku         = $('#sku').val();

                if(namaBahan=='' || harga=='' || kategori=='' || satuan=='' || sku==''){
                    $.Notification.notify('error','top right', 'Error', 'Harap Isi Semua Form'); 
                } else {
                    var urlInsertBahanBaku = "<?php echo base_url('bahan_baku/insertBahanBaku'); ?>";

                    $.ajax({
                                method      : "POST",
                                url         : urlInsertBahanBaku,
                                data        : {sku : sku,namaBahan : namaBahan, harga : harga, kategori : kategori, satuan : satuan},
                                beforeSend  : function(){
                                                $('#simpanBahanBaku').prop('disabled',true);
                                              },
                                success     : function(){
                                                $('#simpanBahanBaku').prop('disabled',false);

                                                $('#namaBahan').val('');
                                                $('#harga').val(''); 
                                                $('#kategori').val('');
                                                $('#satuan').val('');
                                                $('#sku').val('');

                                                $.Notification.notify('success','top right', 'Sukses', 'Bahan Baku Telah Ditambah'); 
                                              }
                    });
                }
            });

            $('#editBahanBaku').on("click",function(){
                var namaBahan   = $('#namaBahan').val();
                var harga       = $('#harga').val();
                var kategori    = $('#kategori').val();
                var satuan      = $('#satuan').val();
                var status      = $('#status').val();
                var sku         = $('#sku').val();

                if(namaBahan=='' || harga=='' || kategori=='' || satuan==''){
                    $.Notification.notify('error','top right', 'Error', 'Harap Isi Semua Form'); 
                } else {
                    var urlEditBahanBaku = "<?php echo base_url('bahan_baku/editBahanBakuSQL'); ?>";

                    $.ajax({
                                method      : "POST",
                                url         : urlEditBahanBaku,
                                data        : {namaBahan : namaBahan, harga : harga, kategori : kategori, satuan : satuan, sku : sku, status : status},
                                beforeSend  : function(){
                                                $('#editBahanBaku').prop('disabled',true);
                                                $('#editBahanBaku').text("Harap Tunggu...");
                                              },
                                success     : function(){
                                                $('#editBahanBaku').prop('disabled',false);
                                                $('#editBahanBaku').text("Simpan");
                                                $.Notification.notify('success','top right', 'Sukses', 'Bahan Baku Telah Diedit'); 
                                              }
                    });
                }
            });

            $('#sku').on("change",function(){
                var sku = $(this).val();

                var urlCekSku = "<?php echo base_url('bahan_baku/cekSKU'); ?>";

                $.post(urlCekSku,{sku : sku},function(response){
                    if(response==0){
                        $('#skuAlert').text('SKU Telah Terpakai');
                        $('#sku').val('');
                    } else {
                        $('#skuAlert').text('');
                    }
                });
            });
        </script>
    </body>
</html>
