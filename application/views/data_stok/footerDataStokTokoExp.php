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
            <?php
                if(empty($_GET['idToko'])){
                    $idToko = "";
                } else {
                    $idToko = $_GET['idToko'];
                }
            ?>

            var idToko = "<?php echo $idToko; ?>";

            jQuery(".select2").select2({
                width: '100%'
            });

	        $("#tableStok").DataTable({
				ordering: false,
				processing: false,
				serverSide: true,
				ajax: {
				  url: "<?php echo base_url('data_stok_toko_exp/datatablesStok?idToko'); ?>",
				  type:'POST',
                  data : {idToko : idToko}
				}
			});

            $('#kategori').change(function(){
                var kategori = $('#kategori').val();
                var url = "<?php echo base_url('kategoriDropdown/get_subkategori'); ?>";

                $('#sub_kategori').load(url,{id_kategori : kategori});
            });

            $('#filterDataStok').on("click",function(){
                var idKategori = $('#kategori').val();
                var subkategori = $('#subkategori_2').val();
                var subSubKategori = $('#subkategori_3').val();
                var stokSign = $('#stok').val();
                var stokValue = $('#stokValue').val();
                var priceSign = $('#priceSign').val();
                var priceSignValue = $('#priceSignValue').val();
                var idStand = $('#stand').val();
                var salePriceSign = $('#salePriceSign').val();
                var salePriceValue = $('#salePriceValue').val();
                var idSupplier = $('#idSupplier').val();

                $.ajax({
                            method  : "POST",
                            url     : "<?php echo base_url('data_stok_toko_exp/dataStokTokoFilter'); ?>",
                            data    : {idKategori : idKategori, subkategori : subkategori, subSubKategori : subSubKategori,stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue, idToko : idToko, idStand : idStand, salePriceSign : salePriceSign, salePriceValue : salePriceValue,idSupplier:idSupplier},
                            beforeSend : function(){
                                            $('#filterDataStok').text("Please Wait...");
                                            $('#filterDataStok').attr("disabled",true);
                                         },
                            success     : function(response){
                                            $('#content').html(response);
                                            $('#myModal').hide();
                                            $('.modal-backdrop').remove();
                                            $('#filterDataStok').text("Filter");
                                            $('#filterDataStok').attr("disabled",false);

                                            var urlButton = "<?php echo base_url('data_stok_toko_exp/buttonExportToko'); ?>";
                                            $('#buttonExport').load(urlButton,{idKategori : idKategori, subkategori : subkategori, subSubKategori : subSubKategori,stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue, idToko : idToko, salePriceSign : salePriceSign, salePriceValue : salePriceValue, idStand : idStand,idSupplier:idSupplier});
                                          }
                });
            });
       	</script>
    </body>
</html>
