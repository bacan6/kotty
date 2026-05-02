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
            jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
            });

            var supplier        = "<?php echo base_url('supplier/data_supplier'); ?>";
            $('#data-supplier').load(supplier);

            $('.add-supplier-sql').on("click",function(){
                nama_supplier   = $('#nama_supplier').val();
                kontak_supplier = $('#kontak_supplier').val();
                email_supplier  = $('#email_supplier').val();
                alamat_supplier = $('#alamat_supplier').val();
                no_rekening     = $('#no_rekening').val();
                bank            = $('#bank').val();
                atas_nama       = $('#atas_nama').val();
                termOfPay       = $('#termOfPay').val();
                jenisBayar      = $('#jenisBayar').val();
                jenisRetur      = $('#jenisRetur').val();
                jenisPKP        = $('#jenisPKP').val();

                url = "<?php echo base_url('supplier/add_supplier_sql'); ?>";

                $.post(url,{nama_supplier : nama_supplier, email_supplier : email_supplier, kontak_supplier : kontak_supplier, alamat_supplier : alamat_supplier, no_rekening : no_rekening, bank : bank, atas_nama : atas_nama, termOfPay:termOfPay,jenisBayar:jenisBayar,jenisRetur:jenisRetur,jenisPKP:jenisPKP}, function(data){
                    $('#data-supplier').load(supplier);
                    $('#add-supplier').modal('hide');

                    $('#nama_supplier').val('');
                    $('#kontak_supplier').val('');
                    $('#email_supplier').val('');
                    $('#alamat_supplier').val('');
                    $('#no_rekening').val('');
                    $('#bank').val('');
                    $('#atas_nama').val('');
                    $('#termOfPay').val('');
                    $('#jenisBayar').val('');
                    $('#jenisRetur').val('');
                    $('#jenisPKP').val('');

                    if(data > 0){
                        $.Notification.notify('success', 'top right', 'Supplier', 'Supplier Berhasil Ditambahkan');
                    } else {
                         $.Notification.notify('danger', 'top right', 'Supplier', 'Supplier Gagal Ditambahkan');
                    }
                });
            });
        </script>
    </body>
</html>
