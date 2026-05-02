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
        <script type="text/javascript">
            jQuery(".select2").select2({
                width: '100%'
            });

            $("#penerimaanWO").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                ajax: {
                        url: "<?php echo base_url('penerimaanWorkOrder/datatablesPenerimaanWO'); ?>",
                        type:'POST'
                }
            });

            $('#filterDatatables').on("click",function(){
                var tanggalWO = $('#tanggalWO').val();
                var tanggalPenyelesaian = $('#tanggalPenyelesaian').val();
                var vendor = $('#supplier').val();
                var status = $('#status').val();
                var urlFilter = "<?php echo base_url('penerimaanWorkOrder/filterDatatables'); ?>";

                $.ajax({
                            method      : "POST",
                            url         : urlFilter,
                            data        : {tanggalWO : tanggalWO, tanggalPenyelesaian : tanggalPenyelesaian, vendor : vendor, status : status},
                            success     : function(data){
                                            $('#myModal').modal('hide');
                                            $('#dataWO').html(data);
                                          }
                });
            });
        </script>
    </body>
</html>
