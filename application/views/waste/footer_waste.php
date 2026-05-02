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
            $(document).ready(function(){
                var dataUrl = "<?php echo base_url('waste/viewCartWaste'); ?>";
                $('#data-input').load(dataUrl);
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });

            $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('waste/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 500,
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

            $('#sku').on("change",function(){
                var sku = $(this).val();

                urlProduk = "<?php echo base_url('waste/getDataProdukWarehouse'); ?>";

                $.ajax({
                            type            : "POST",
                            url             : urlProduk,
                            dataType        : 'json',
                            data            : {sku, sku},
                            success         : function(stok){
                                                    if(parseInt(stok) > 0){
                                                        var urlCart = "<?php echo base_url('waste/insertCartWaste'); ?>";

                                                        $.post(urlCart,{sku : sku},function(response){
                                                            if(response != 0){
                                                                $('html, body').animate({scrollTop: $("#row"+response).offset().top}, 1000);

                                                                $('#row'+response).css({"box-shadow" : "1px 0px 10px red"});

                                                                setTimeout( function(){$('#row'+response).css({"box-shadow" : ""});} , 4000);
                                                            } else {
                                                                var dataUrl = "<?php echo base_url('waste/viewCartWaste'); ?>";

                                                                $('#data-input').load(dataUrl);
                                                                
                                                            } 
                                                        });
                                                    } else {
                                                        $.Notification.notify('error','top right', 'Tidak ada stok', 'Stok Saat Ini 0');  
                                                    }
                                                    
                                                    $('#sku').select2("val","");    
                                              } 
                });
            });

            $('#waste-click').on("click",function(){
                var idWaste = $('#idWaste').val();
                var keterangan = $('#keterangan').val();
                var id_brand = $('#id_brand').val();
                var id_supplier = $('#id_supplier').val();
                var status = $('#status').val();

                var urlInsertWaste = "<?php echo base_url('waste/insertWaste'); ?>";

                if(idWaste=='' || id_brand=='' || id_supplier=='' || status==''){
                  $.Notification.notify('error','top right', 'Error', 'Isian belum lengkap');  
                  return false;  
                } else {
                    $.ajax({
                                method : "POST",
                                url : urlInsertWaste,
                                data : {status : status, id_supplier : id_supplier, id_brand : id_brand, idWaste : idWaste, keterangan : keterangan},
                                beforeSend : function(){
                                                $('#CssLoader').show(); 
                                             },
                                error : function(){
                                            alert("Error");
                                        },
                                success : function(noWaste){
                                            window.location.replace("<?php echo base_url('waste/invoice_waste?no_waste='); ?>"+noWaste);
                                          }
                    });
                }
            });
        </script>
    </body>
</html>
