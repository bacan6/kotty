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

            jQuery(".select2").select2({
                width: '100%'
            });

            $("#datatable").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                ajax: {
                        url: "<?php echo base_url('retur/datatablesPO'); ?>",
                        type:'POST'
                }
            });

            $('.retur').change(function(){
                var idProduk = $(this).data('id');
                var noPO = $(this).data('no_po');
                var qty = $(this).val();
                var row = $(this).data('row');

                var urlCekPurchase = "<?php echo base_url('retur/cekPurchasePeritem'); ?>";

                $.ajax({
                            method : "POST",
                            url : urlCekPurchase,
                            data : {idProduk : idProduk, noPO : noPO, qty : qty},
                            success : function(response){
                                        if(response==0){
                                            $.Notification.notify('error','top right', 'Error', 'Melebihi Jumlah Barang Yang Diterima');
                                            $('#row'+row).val(0); 
                                        } else {

                                        }
                                      }
                });
            });

            $('#prosesRetur').on("click",function(){
                var noPO = "<?php echo $_GET['noPO']; ?>";

                jsonObj = [];

                $('input[name=retur]').each(function(){
                    var idProduk = $(this).data('id');
                    var qty = $(this).val();
                    var harga = $(this).data('harga');

                    item = {};

                    item['idProduk'] = idProduk;
                    item['qty'] = qty;
                    item['harga'] = harga;

                    jsonObj.push(item);
                });

                var urlRetur = "<?php echo base_url('retur/returSQL'); ?>";

                $.ajax({    
                            method      : "POST",
                            url         : urlRetur,
                            data        : {noPO : noPO, item : JSON.stringify(jsonObj)},
                            beforeSend  : function(){
                                            $('#CssLoader').show(); 
                                            
                                          },
                            success     : function(noRetur){
                                            window.location.replace("<?php echo base_url('retur/nota_retur?no_retur='); ?>"+noRetur);
                                          }
                });
            });
        </script>
    </body>
</html>
