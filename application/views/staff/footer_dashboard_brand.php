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

        <script src="<?php echo base_url('assets'); ?>/js/jquery.app.js"></script>
        <!-- Chat -->


        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>

        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
    	
    	<!-- chart js -->
		<!--<script src="<?php echo base_url('assets'); ?>/chartjs/dist/Chart.js" type="text/javascript"></script> -->
		<script src="<?php echo base_url('assets'); ?>/chartjs/dist/Chart.min.js" type="text/javascript"></script> 
		<!--<script src="<?php echo base_url('assets'); ?>/chartjs/dist/Chart.bundle.js" type="text/javascript"></script> -->
		<script src="<?php echo base_url('assets'); ?>/chartjs/dist/Chart.bundle.min.js" type="text/javascript"></script> 

    	<script type="text/javascript">
            var urlSalesPerHour = "<?php echo base_url('dashboard_brand/salesPerHour'); ?>";
            var urlSalesPerkategori = "<?php echo base_url('dashboard_brand/perkategoriSales'); ?>";
            var urlSalesPerkasir = "<?php echo base_url('dashboard_brand/salesPerKasir'); ?>";
            var urlFastMoving = "<?php echo base_url('dashboard_brand/fastMoving'); ?>";
            var urlSlowMoving = "<?php echo base_url('dashboard_brand/slowMoving'); ?>";
            var urlBrand1 = "<?php echo base_url('dashboard_brand/salesPerBrand1'); ?>";
            var urlBrand2 = "<?php echo base_url('dashboard_brand/salesPerBrand2'); ?>";
            var urlTebusMurah = "<?php echo base_url('dashboard_brand/tebusMurah'); ?>";

            $(document).ready(function(){
                var urlData = "<?php echo base_url('dashboard_brand/dataPenjualan'); ?>";

                var dateStart = "<?php echo date('Y-m'); ?>-01";
                var dateEnd = "<?php echo date('Y-m-d'); ?>";
                var type = "day";


               
                $('#salesPerkategori').load(urlSalesPerkategori,{tanggal : ""});
                // $('#salesPerKasir').load(urlSalesPerkasir,{tanggal : ""});
                $('#fastMoving').load(urlFastMoving,{tanggal : ""});
                //$('#slowMoving').load(urlSlowMoving,{tanggal : ""});
                $('#salesPerBrand1').load(urlBrand1,{tanggal : ""});
                $('#salesPerBrand2').load(urlBrand2,{tanggal : ""});
                //$('#tebusMurah').load(urlTebusMurah,{tanggal : ""});
            });
            
    		function addCommas(nStr){
			    nStr += '';
			    x = nStr.split('.');
			    x1 = x[0];
			    x2 = x.length > 1 ? '.' + x[1] : '';
			    var rgx = /(\d+)(\d{3})/;
			    while (rgx.test(x1)) {
			        x1 = x1.replace(rgx, '$1' + '.' + '$2');
			    }
			    return x1 + x2;
			}

            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                return "rgb(" + r + "," + g + "," + b + ")";
            }

			$('#hari').on("click",function(){
				var dayFilter = "<?php echo base_url('dashboard_brand/dayFilter'); ?>";

				$('#filter').load(dayFilter);
			});	

            $('#bulan').on("click",function(){
                var bulanFilter = "<?php echo base_url('dashboard_brand/bulanFilter'); ?>";

                $('#filter').load(bulanFilter);
            });

            $('#tahun').on("click",function(){
                var tahunFilter = "<?php echo base_url('dashboard_brand/tahunFilter'); ?>";

                $('#filter').load(tahunFilter);
            });

            $('#hariLiniMasa').on("click",function(){
                var url = "<?php echo base_url('dashboard_brand/hariLiniMasa'); ?>";

                $('#dateRange').load(url);
            });

            $('#bulanLiniMasa').on("click",function(){
                var url = "<?php echo base_url('dashboard_brand/bulanLiniMasa'); ?>";

                $('#dateRange').load(url);
            });

            $('#tahunLiniMasa').on("click",function(){
                var url = "<?php echo base_url('dashboard_brand/tahunLiniMasa'); ?>";

                $('#dateRange').load(url);
            });
            jQuery(".select2").select2({
                width: '270px'
            });
    	</script>

    </body>
</html>
