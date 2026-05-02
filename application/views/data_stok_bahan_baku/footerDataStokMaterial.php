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
            jQuery(".select2").select2({
                width: '100%'
            });

            $("#dataStok").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                ajax: {
                  url: "<?php echo base_url('dataStokBahanBaku/datatablesBahanBaku'); ?>",
                  type:'POST'
                }
            });

            $('#filterDataStok').on("click",function(){
            	var idKategori = $("#kategori").val();
            	var stokSign = $('#stok').val();
            	var stokValue = $('#stokValue').val();
            	var priceSign = $('#priceSign').val();
            	var priceSignValue = $('#priceSignValue').val();

            	var urlDataStok = "<?php echo base_url('dataStokBahanBaku/filterBahanBaku'); ?>";

            	$.ajax({
            				method	: "POST",
            				url 	: urlDataStok,
            				data 	: {idKategori : idKategori, stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue},
            				success	: function(response){
            							$('#content').html(response);
            							$('#myModal').hide();
            							$('.modal-backdrop').remove();

            							var urlButton = "<?php echo base_url('dataStokBahanBaku/buttonExportBahanBaku'); ?>";
                                        $('#buttonExport').load(urlButton,{idKategori : idKategori,stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue});
            						 }
            	});
            });
        </script>
    </body>
</html>
