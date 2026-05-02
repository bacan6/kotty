 <!-- Page Content Ends -->
            <!-- ================== -->

            <!-- Footer Start -->
            <footer class="footer">
               <?php echo $footer; ?>
            </footer>
            <!-- Footer Ends -->
        </section>
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
            jQuery(".select2").select2({
                width: '100%'
            });

            jQuery('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                autoclose : true
            });

            $('#submitLaporan').on("click",function(){
                var dateStart = $('#dateStart').val();
                var dateEnd = $('#dateEnd').val();
                var supplier = $('#supplier').val();
                var tipeBayar = $('#tipeBayar').val();
                var noPO = $('#noPO').val();
                var noPayment = $('#noPayment').val();

                var urlSubmit = "<?php echo base_url('laporan/dataHutangTerbayar'); ?>";

                $.ajax({
                            method : "POST",
                            url : urlSubmit,
                            data : {dateStart : dateStart, dateEnd : dateEnd, supplier : supplier, tipeBayar : tipeBayar, noPO : noPO, noPayment : noPayment},
                            beforeSend : function(){
                                            $('#content').html("<tr><td colspan='9'>Harap Tunggu...</td></tr>");
                                         },
                            error : function(){
                                        alert("Error");
                                    },
                            success : function(data){
                                        $('#content').html(data);
                                      }
                }); 
            });
        </script>
    </body>
</html>
