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
        <script type="text/javascript">
            var idStore = "<?php echo $_GET['idStore']; ?>";
            var urlDataCart = "<?php echo base_url('transferStok/viewCart'); ?>";

            $('#dataCart').load(urlDataCart,{idStore : idStore});

            jQuery(".select2").select2({
                width: '100%'
            });

            $('#produkAjax').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('transferStok/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 500,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params,
                            idStore : idStore
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text,
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 3,
            });

            $('#produkAjax').on("click",function(){
                idProduk = $(this).val();

                var urlInsertCart = "<?php echo base_url('transferStok/insertCart'); ?>";

                $.ajax({
                            method      : "POST",
                            url         : urlInsertCart,
                            data        : {idProduk : idProduk, idStore : idStore},
                            success     : function(response){

                                            /**
                                                0 = Stok Tidak Mencukupi
                                                1 = insert cart
                                                2 = telah terinput
                                            **/

                                            if(response == "NotEnoughStock"){
                                                //IF STOK NOT ENOUGH
                                                $.Notification.notify('error','top right', 'Stok Tidak Mencukupi', 'Stok saat ini = 0');
                                            } else if (response > 0){
                                                // IF CART ALREADY INPUTTED
                                                $('html, body').animate({scrollTop: $("#row"+response).offset().top},1000);
                                                $('#row'+response).css({"box-shadow" : "1px 0px 10px red"});
                                                setTimeout( function(){$('#row'+response).css({"box-shadow" : ""});},4000);
                                            } else {
                                                //IS STOK > 0 THEN INSERT AND VIEW CART
                                                $('#produkAjax').select2("val","");
                                                $('#dataCart').load(urlDataCart, {idStore : idStore});
                                            }
                                        }
                });
            });

            $('#doTransfer').on("click",function(){
                doTransfer();
            });

            

             $(document).keydown(function(event) {
                if (event.ctrlKey && event.keyCode === 13) {
                    doTransfer();
                }
            });

            function doTransfer(){
                var tokoTujuan = $('#tokoTujuan').val();
                var keterangan = $('#keterangan').val();
                var transferFrom = idStore;

                var urlDoTransfer = "<?php echo base_url('transferStok/doTransfer'); ?>";

                $.ajax({    
                            method      : "POST",
                            url         : urlDoTransfer,
                            data        : {tokoTujuan : tokoTujuan, keterangan : keterangan, transferFrom : transferFrom},
                            beforeSend  : function(){
                                            $('#CssLoader').show(); 
                                            $('#doTransfer').prop("disabled",true);
                                            $('#doTransfer').text("Harap Tunggu...");
                                          },
                            success     : function(noTransfer){
                                            window.location.replace("<?php echo base_url('transferStok/invoiceTransfer?noTransfer='); ?>"+noTransfer);
                                          }
                });
            }
	   
        </script>
    </body>
</html>
