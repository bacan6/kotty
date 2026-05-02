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
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
        /* ==============================================
             Counter Up
             =============================================== */
            jQuery(document).ready(function($) {
                jQuery('#datepicker').datepicker({
                    format: "yyyy-mm-dd"
                });

                jQuery('#datepicker2').datepicker({
                    format: "yyyy-mm-dd"
                });

                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });


                daftar_request  = "<?php echo base_url('purchase_approval/wait_approve_list'); ?>";
                spinner         = "<?php echo base_url('purchase_approval/spinner'); ?>"; 

                $('#daftar-request-wait').load(daftar_request);

            });

            $('.select2-input').on('keyup',function(){
                sku_barang = $(this).val();

                url = "<?php echo base_url('purchase_request/select2_dropdown_goods'); ?>";
                $('#sku_barang').load(url,{sku_barang});
            });

            $('#sku_barang_list').on("change",function(){
                sku = $(this).val();

                satuan          = "<?php echo base_url('purchase_request/satuan_barang'); ?>";
                harga_terakhir  = "<?php echo base_url('purchase_request/harga_terakhir_beli'); ?>";

                $('#satuan-request').load(satuan,{sku : sku});
                $('#harga-request').load(harga_terakhir,{sku : sku});
            });

            $('#add-form-request').on("click",function(){
                no          = parseInt($('#sdf').val());
                urutan      = no+1; 
                data_form   = "<?php echo base_url('purchase_request/data_form'); ?>";

                $.get(data_form,{no : urutan},function(data){
                    $('#daftar-request').append(data);
                    $('#sdf').val(urutan);
                })
            });

            $('.wait-approve-button').on("click",function(){
                
                url = "<?php echo base_url('purchase_approval/wait_approve_list'); ?>";

                $.ajax({
                    method      :'GET',
                    beforeSend  : function(){
                                    $('#daftar-request-wait').load(spinner);
                                   },
                }).done(function(data){
                    $('#daftar-request-wait').load(url);
                });

            });

            $('.telah-disetujui').on("click",function(){
                url = "<?php echo base_url('purchase_approval/daftar_request_approved'); ?>";

                $.ajax({
                    method      :'GET',
                    beforeSend  : function(){
                                    $('#daftar-request-wait').load(spinner);
                                   },
                }).done(function(data){
                    $('#daftar-request-wait').load(url);
                });
            });

            $('.ditolak-list').on("click",function(){
                url = "<?php echo base_url('purchase_approval/daftar_request_ditolak'); ?>";

                $.ajax({
                    method      :'GET',
                    beforeSend  : function(){
                                    $('#daftar-request-wait').load(spinner);
                                   },
                }).done(function(data){
                    $('#daftar-request-wait').load(url);
                });
            });
        </script>
    </body>
</html>
