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

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>
        <script type="text/javascript">
            var netSales = $("#net-sales").html();
            var setorBefore = $("#setorBefore").val();
            $("#n100k").on("keyup change", function(e){
                $("#p100k").html(formatAngka(Number(this.value)*100000));
                $("#s100k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n75k").on("keyup change", function(e){
                $("#p75k").html(formatAngka(Number(this.value)*75000));
                $("#s75k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n50k").on("keyup change", function(e){
                $("#p50k").html(formatAngka(Number(this.value)*50000));
                $("#s50k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n20k").on("keyup change", function(e){
                $("#p20k").html(formatAngka(Number(this.value)*20000));
                $("#s20k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n10k").on("keyup change", function(e){
                $("#p10k").html(formatAngka(Number(this.value)*10000));
                $("#s10k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n5k").on("keyup change", function(e){
                $("#p5k").html(formatAngka(Number(this.value)*5000));
                $("#s5k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n2k").on("keyup change", function(e){
                $("#p2k").html(formatAngka(Number(this.value)*2000));
                $("#s2k").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n1kp").on("keyup change", function(e){
                $("#p1kp").html(formatAngka(Number(this.value)*1000));
                $("#s1kp").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n1kc").on("keyup change", function(e){
                $("#p1kc").html(formatAngka(Number(this.value)*1000));
                $("#s1kc").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n500").on("keyup change", function(e){
                $("#p500").html(formatAngka(Number(this.value)*500));
                $("#s500").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n200").on("keyup change", function(e){
                $("#p200").html(formatAngka(Number(this.value)*200));
                $("#s200").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#n100").on("keyup change", function(e){
                $("#p100").html(formatAngka(Number(this.value)*100));
                $("#s100").html('x '+this.value+' = ');
                hitungTotal();
            });
            $("#voucher").on("keyup change", function(e){
                $("#pVoucher").html(formatAngka(Number(this.value)));
                $("#sVoucher").html('@');
                hitungTotal();
            });

            $("#penggantian").on("keyup change", function(e){
                $("#spenggantian").html(formatAngka(Number(this.value)));
                hitungTotal();
            });

            function hitungTotal(){
                var total = Number($("#n100").val()*100) + Number($("#n200").val()*200) + Number($("#n500").val()*500) +
                            Number($("#n1kc").val()*1000) + Number($("#n1kp").val()*1000) + 
                            Number($("#n2k").val()*2000) + Number($("#n5k").val()*5000) + 
                            Number($("#n10k").val()*10000) + Number($("#n20k").val()*20000) + Number($("#n50k").val()*50000) + 
                            Number($("#n75k").val()*75000) + Number($("#n100k").val()*100000) + 
                            Number($("#voucher").val()) + Number($("#penggantian").val());
                $("#total").html(formatAngka(total)+'<br>'+formatAngka(setorBefore));
                //+'<br>'+formatAngka(total-netSales)
            }   

            function formatAngka(angka) {
                 if (typeof(angka) != 'string') angka = angka.toString();
                 var reg = new RegExp('([0-9]+)([0-9]{3})');
                 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
                 return angka;
            }

            $('#submit-setoran').on("click",function(){
                var n100    = $("#n100").val();
                var n200    = $("#n200").val();
                var n500    = $("#n500").val();
                var n1kc    = $("#n1kc").val();
                var n1kp    = $("#n1kp").val();
                var n2k     = $("#n2k").val()
                var n5k     = $("#n5k").val();
                var n10k    = $("#n10k").val();
                var n20k    = $("#n20k").val();
                var n50k    = $("#n50k").val();
                var n75k    = $("#n75k").val();
                var n100k   = $("#n100k").val();
                var catatan = $("#catatan").val();
                var penggantian = $("#penggantian").val();
                var voucher = $("#voucher").val();

		    	$.ajax({
		    				method : "POST",
		    				url : "<?php echo base_url('penjualan/setorankasirsql'); ?>",
		    				data : {n100:n100,n200:n200,n500:n500,n1kc:n1kc, n1kp:n1kp, n2k:n2k,n5k:n5k, n10k:n10k,n20k:n20k,n50k:n50k,n75k:n75k,n100k:n100k,catatan:catatan,penggantian:penggantian,voucher:voucher },
		    				beforeSend : function(){
		    								$('#submit-setoran').prop('disabled',true);
		    								$('#submit-setoran').text('Harap Tunggu...');
		    							 },
		    				success : function(noInv){
		    							$.Notification.notify('success', 'top right', 'Sukses', 'Data Berhasil disimpan');

                                        var urlRedirect = "<?php echo base_url('penjualan/invoice_setorankasir?no_setor='); ?>"+noInv;
                                        window.location.replace(urlRedirect);
		    						  }
		    	});
        	});
        </script>
        
    </body>
</html>
