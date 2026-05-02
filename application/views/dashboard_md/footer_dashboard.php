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
            $(document).ready(function(){
                var urlData = "<?php echo base_url('dashboard_md/dataMD'); ?>";
                var urlInv = "<?php echo base_url('dashboard/dataInv'); ?>";


                $.ajax({
                            method  : "POST",
                            url : urlData,
                            dataType : 'json',
                            data : {tanggal : ""},
                            success : function(response){
                                        $.each(response, function(x,obj){
                                            //var prepo = obj.prepo;
                                            var waitingmd = obj.waitingmd;
                                            var waitingsupplier = obj.waitingsupplier;
                                            var waitingdelivery = obj.waitingdelivery;
                                            var waitingreceive = obj.waitingreceive;
                                            var omseth1 = obj.omseth1;
                                            var belanja = obj.belanja;
                                            
                                            var todaypo = obj.todaypo;
                                            var todayreceive = obj.todayreceive;
                                            var todaytransfer = obj.todaytransfer;
                                            var todaytransferrec = obj.todaytransferrec;
                                            var todayretur = obj.todayretur;
                                            var transferblm = obj.transferblm;

                                            //$('#prepo').text(addCommas(prepo));
                                            $('#waitingmd').text(addCommas(waitingmd));
                                            $('#waitingsupplier').text(waitingsupplier);
                                            $('#waitingdelivery').text(addCommas(waitingdelivery));
                                            $('#waitingreceive').text(waitingreceive);
                                            // $('#omseth1').text(addCommas(omseth1));
                                            $('#belanja').text(addCommas(belanja));
                                            $('#transferblm').text(addCommas(transferblm));

                                            $('#todaypo').text(addCommas(todaypo));
                                            $('#todayreceive').text(addCommas(todayreceive));
                                            $('#todaytransfer').text(addCommas(todaytransfer));
                                            $('#todaytransferrec').text(addCommas(todaytransferrec));
                                            $('#todayretur').text(addCommas(todayretur));
                                        });
                                      }
                });

                

                var dateStart = "<?php echo date('Y-m'); ?>-01";
                var dateEnd = "<?php echo date('Y-m-d'); ?>";
                var type = "day";

                var urlLiniMasa = "<?php echo base_url('dashboard/liniMasa'); ?>";

                // $.ajax({
                //             method : "POST",
                //             url : urlLiniMasa,
                //             data : {dateStart : dateStart, dateEnd : dateEnd,type : type},
                //             success : function(response){
                //                         $('#graph').html(response);
                //                       }
                // });

                
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
				var dayFilter = "<?php echo base_url('dashboard/dayFilter'); ?>";

				$('#filter').load(dayFilter);
			});

            $('#bulan').on("click",function(){
                var bulanFilter = "<?php echo base_url('dashboard/bulanFilter'); ?>";

                $('#filter').load(bulanFilter);
            });

            $('#tahun').on("click",function(){
                var tahunFilter = "<?php echo base_url('dashboard/tahunFilter'); ?>";

                $('#filter').load(tahunFilter);
            });

            $('#hariLiniMasa').on("click",function(){
                var url = "<?php echo base_url('dashboard/hariLiniMasa'); ?>";

                $('#dateRange').load(url);
            });

            $('#bulanLiniMasa').on("click",function(){
                var url = "<?php echo base_url('dashboard/bulanLiniMasa'); ?>";

                $('#dateRange').load(url);
            });

            $('#tahunLiniMasa').on("click",function(){
                var url = "<?php echo base_url('dashboard/tahunLiniMasa'); ?>";

                $('#dateRange').load(url);
            });
            jQuery(".select2").select2({
                width: '270px'
            });

            $('#prepo_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/prepo'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Pre-PO belum di-SO toko');
			});
            $('#waitingmd_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/waitingmd'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Menunggu Approval MD');
			});
            $('#waitingsupplier_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/waitingsupplier'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Menunggu Konfirmasi Supplier');
			});
            $('#waitingdelivery_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/waitingdelivery'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Orderan belum diantar');
			});
            $('#waitingreceive_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/waitingreceive'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Orderan sudah diantar tapi belum receive');
			});
            $('#transferblm_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/transferblm'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Transfer Stok belum diterima');
			});
            $('#lowperformance_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/lowperformance'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Performance < 80%');
			});
            $('#ssr1_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/ssr1'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('SSR Brand < 0.6');
			});
            $('#ssr3_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/ssr3'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('SSR Brand > 4');
			});
            $('#todaypo_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/todaypo'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('PO Hari ini');
			});
            $('#todayreceive_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/todayreceive'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Receive Hari ini');
			});
            $('#todaytransfer_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/todaytransfer'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Transfer Stok Dikirim Hari ini');
			});
            $('#todaytransferrec_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/todaytransferrec'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Transfer Stok Diterima Hari ini');
			});
            $('#todayretur_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/todayretur'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Retur Hari ini');
			});
            $('#belanja_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/belanja'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Order Hari ini');
			});

            $('#leadMD_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/leadMD?status=Approval~1'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Lead Time MD');
			});
            $('#leadSupplier_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/leadMD?status=Konfirmasi~2'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Lead Time Supplier');
			});
            $('#leadDriver_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/leadMD?status=Pengantaran~4'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Lead Time Pengantaran');
			});
            $('#leadReceive_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/leadMD?status=Receive~1'); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Lead Time Tim Receive');
			});

            $('#topGreen_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/top1000?status=green&id_toko='.$_SESSION['id_toko']); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Top 1000 - Hijau');
			});
            $('#topYellow_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/top1000?status=yellow&id_toko='.$_SESSION['id_toko']); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Top 1000 - Kuning');
			});
            $('#topRed_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/top1000?status=red&id_toko='.$_SESSION['id_toko']); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Top 1000 - Merah');
			});
            $('#topBlack_a').on("click",function(){
				var url = "<?php echo base_url('dashboard_md/top1000?status=black&id_toko='.$_SESSION['id_toko']); ?>";

				$('#myModalBody').load(url);
                $('#myModalLabel').html('Top 1000 - Hitam');
			});

    	</script>

    </body>
</html>
