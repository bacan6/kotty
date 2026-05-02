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
            jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
            });
            var urlDetailOrder = "<?php echo base_url('purchase_request/detailOrder'); ?>";
            var urlUpdateQty = "<?php echo base_url('purchase_request/updateQty'); ?>";
            var nopo = "<?php echo $_GET['no_po']; ?>";
            var id_toko = "<?php echo $noteInfo->id_toko;?>";
            var tanggal = "<?php echo $noteInfo->tanggal_po;?>";
            $(document).ready(function(){
                

                $('#detailOrder').load(urlDetailOrder,{noPo : nopo});


            });

            function saveApproval(id){
                var qty = $("#prd_"+id).val();
                $('#'+id).load(urlUpdateQty,{noPo : nopo, qty:qty, idProduk:id});
            }

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });
            $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('purchase_request/ajax_produk'); ?>',
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
            $('#sku').change(function(){
               var idProduk = $(this).val();

               var urlInsertReq = "<?php echo base_url('purchase_request/insertNewReq'); ?>";

               $.ajax({
                            method      : 'POST',
                            url         : urlInsertReq,
                            data        : {idProduk : idProduk,id_toko:id_toko,no_po:nopo,tanggal:tanggal},
                            success     : function(response){
                                            if(response != 0){
                                               // $.Notification.notify('warning','top right', 'Terinput', 'Produk telah terinput sebelumnya');
                                                
                                                $('html, body').animate({scrollTop: $("#row"+response).offset().top}, 1000);

                                                $('#row'+response).css({"box-shadow" : "1px 0px 10px red"});

                                                setTimeout( function(){$('#row'+response).css({"box-shadow" : ""});} , 4000);
                                                $("#sku").select2("val","");

                                            } else {
                                                $('#detailOrder').load(urlDetailOrder,{noPo : nopo});
                                                $("#sku").select2("val","");
                                            }
                                          } 
               });
            });
        </script>
    </body>
</html>
