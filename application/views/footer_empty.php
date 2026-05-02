 <!-- Page Content Ends -->
            <!-- ================== -->

            <!-- Footer Start -->
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
        <!--<script src="<?php echo base_url('assets'); ?>/js/jquery.dashboard.js"></script>-->

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                if ($('#myTable').length) {
                    $('#myTable').DataTable();
                }
            });

            jQuery(".select2").select2({
                width: '100%'
            });

            jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose : true
                });

            jQuery(document).on('click', '.topcust-csv', function(e) {
                e.preventDefault();
                if (typeof window._topCustCsvBase === 'undefined') return;
                var delim = jQuery(this).data('delim');
                var q = jQuery('#formTopCustomer').serialize();
                window.location = window._topCustCsvBase + '?' + q + '&delimiter=' + encodeURIComponent(delim);
            });
            jQuery(document).on('click', '.topcust-pres-csv', function(e) {
                e.preventDefault();
                if (typeof window._topCustPresCsvBase === 'undefined') return;
                var delim = jQuery(this).data('delim');
                var q = jQuery('#formTopCustomerPresence').serialize();
                window.location = window._topCustPresCsvBase + '?' + q + '&delimiter=' + encodeURIComponent(delim);
            });
        </script>
    </body>
</html>
