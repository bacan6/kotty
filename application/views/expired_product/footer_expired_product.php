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

        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

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

        <script type="text/javascript">
            var urlCartPO = "<?php echo base_url('expired_product/cartEP'); ?>";

            jQuery(document).ready(function(e) {
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
                });

                $('#data-input').load(urlCartPO);
/*
                $('#supplier').change(function(){
                	var supplier = $(this).val();
                	$.ajax({
                                method      : "POST",
                                url         : '<?php echo base_url('expired_product/ajax_produk_supplier'); ?>',
                                data        : {supplier : supplier},
                                success     : function(noInv){
                                                
                                                $('#data-input').load(urlCartPO);
                                              }
                    });
                });
                */
            });

            $(document).keydown(function(event) {
                if (event.ctrlKey && event.keyCode === 13) {
                    submitPO();
                }
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });

            $('#sku').change(function(){
               var idProduk = $(this).val();

               var urlInsertCartPo = "<?php echo base_url('expired_product/insertCartPO'); ?>";

               $.ajax({
                            method      : 'POST',
                            url         : urlInsertCartPo,
                            data        : {idProduk : idProduk},
                            success     : function(response){
                                            if(response != 0){
                                               // $.Notification.notify('warning','top right', 'Terinput', 'Produk telah terinput sebelumnya');
                                                
                                                $('html, body').animate({scrollTop: $("#row"+response).offset().top}, 1000);

                                                $('#row'+response).css({"box-shadow" : "1px 0px 10px red"});

                                                setTimeout( function(){$('#row'+response).css({"box-shadow" : ""});} , 4000);
                                                $("#sku").select2("val","");

                                            } else {
                                                $('#data-input').load(urlCartPO);
                                                $("#sku").select2("val","");
                                            }
                                          } 
               });
            });

            $('#prosesPO').on("click",function(){
                submitPO();
            });

            function submitPO(){

                var urlInsertPO = "<?php echo base_url('expired_product/insertPO'); ?>";
                var keterangan = $('#keterangan').val();
                
                    $.ajax({
                                method      : "POST",
                                url         : urlInsertPO,
                                data        : {keterangan:keterangan},
                                beforeSend  : function(){
                                                $('#prosesPO').text('Harap Tunggu...');
                                                $('#CssLoader').show(); 
                                              },
                                success     : function(noInv){
                                                window.location.replace("<?php echo base_url('expired_product/form_po?no_po='); ?>"+noInv);
                                              }
                    });
                
            }

            

            function formatAngka(angka) {
                 if (typeof(angka) != 'string') angka = angka.toString();
                 var reg = new RegExp('([0-9]+)([0-9]{3})');
                 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
                 return angka;
            }

            $(document).on("change", "#jumlah_beli, #harga_beli", function(){
                var sum = 0;
                $("input[class *= 'total_beli_hidden']").each(function(){
                    sum += +$(this).val();
                });
                $("#total_purchase").text(formatAngka(sum));
                $("#total_purchase_temp").val(sum);
            });

            $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('expired_product/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 100,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 3,
            });


        </script>
    </body>
</html>
