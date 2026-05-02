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

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>

        <script type="text/javascript">
            var urlCartPO = "<?php echo base_url('purchase_order/cartPO'); ?>";

            jQuery(document).ready(function(e) {
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
                });
                function saveRevisi(){
                    jsonObj = [];
                    $( ".revisi" ).each(function() {
                        id_produk = $( this ).data('id');
                        no_so = $( this ).data('no');
                        revisi = $( this ).val();
                        item = {};

                        item['id_produk']   = id_produk;
                        item['revisi']      = revisi;
                        item['no_so']       = no_so;

                        jsonObj.push(item);
                        
                    });
                    $.ajax({
                        method  : "POST",
                        url     : "<?php echo base_url('so_peritem/simpanRevisi'); ?>",
                        data    : {revisiItem : JSON.stringify(jsonObj)},
                        beforeSend : function(){
                                        $('#approvalSO').text('Harap Tunggu...');
                                        $('#approvalSO').prop('disabled',true);
                                        },
                        success : function(response){
                                    $.Notification.notify('success','top right', 'Berhasil', 'Nilai revisi sudah disimpan'); 
                                    window.location.reload();
                                    },
                    });
                }
                

                $('#verifyApproval').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();

                var verifyApproval = "<?php echo base_url('so_peritem/verifyApproval'); ?>";
                    $.ajax({
                        method : "POST",
                        url : verifyApproval,
                        dataType : 'json',
                        data : {user : user, pw : pw},
                        success : function(response){		
                            var setuju = response.setuju;
                            if (setuju==1){
                                $("#passApprover").val('');
                                $('#labelpwd').html('');
                                saveRevisi();
                            }else {
                                $("#passApprover").val('');
                                $("#passApprover").focus();
                                $('#labelpwd').html('Pengguna tidak ditemukan...');
                            }
                        }
                    });
                });
            });

            
            

        </script>
    </body>
</html>
