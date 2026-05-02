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

            jQuery(".select2").select2({
                width: '100%'
            });

            $("#datatable").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                ajax: {
                        url: "<?php echo base_url('bahan_masuk/datatablesPO'); ?>",
                        type:'POST'
                }
            });

            $('#filterDatatables').on("click",function(){
                var tanggalPO = $('#tanggalPO').val();
                var tanggalKirim = $('#tanggalKirim').val();
                var supplier = $('#supplier').val();
                var status = $('#status').val();

                var urlFilter = "<?php echo base_url('bahan_masuk/POFilter'); ?>";

                $.ajax({
                            method      : "POST",
                            url         : urlFilter,
                            data        : {tanggalPO : tanggalPO, tanggalKirim : tanggalKirim, supplier : supplier, status : status},
                            success     : function(response){
                                            $('#myModal').modal('hide');
                                            $('#contentPO').html(response);
                                          }
                });
            });
        </script>
    </body>
</html>
