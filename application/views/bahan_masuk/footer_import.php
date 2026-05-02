
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
        <script src="<?php echo base_url('assets'); ?>/assets/dropzone/dropzone.min.js"></script>
        <script type="text/javascript">
        	$("#dropzone").dropzone({ 
        		url: "<?php echo base_url('bahan_masuk/importReceiveitemSQL'); ?>",
        		maxFiles : 5,
        		addRemoveLinks : true,
        		acceptedFiles : 'application/pdf,text/csv,text/html,text/plain,text/tab-separated-values,application/xls,application/excel,application/vnd.ms-excel,application/vnd.ms-excel; charset=binary,application/msexcel,application/x-excel,application/x-msexcel,application/x-ms-excel,application/x-dos_ms_excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        		init : function(){
                        
        				this.on("success",function(file,response){
	                        $.Notification.notify('success','top right', 'Berhasil', 'Import SO Berhasil');
	                    });
                        this.on("sending", function(file, xhr, formData){
                            var no_po = $("#no_po").val();
                            formData.append("no_po", no_po);
                        });
        			   }
        	});
        </script>
        <script>
            function receiveMe(){
                var urlReceive = "<?php echo base_url('bahan_masuk/good_receipt?no_po='); ?>";
                var no_po = $('#no_po').val();
                window.location = urlReceive+no_po;
            }
            function bukaLink(){
                $('#linkTutup').show();
            }
        </script>
    </body>
</html>
