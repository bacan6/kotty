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
            jQuery(".select2").select2({
                width: '100%'
            });
            $("#tableStok").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                ajax: {
                    url: "<?php echo base_url('kepala_kasir/datatablesUser'); ?>",
                    type:'POST'
                }
            });

            $('#simpanUser').on("click",function(){
                var Nama   = $('#Nama').val();
                var username       = $('#username').val();
                var pass    = $('#pass').val();


                if(Nama=='' || username=='' || pass==''){
                    $.Notification.notify('error','top right', 'Error', 'Harap Isi Semua Form'); 
                } else {
                    var urlInsert = "<?php echo base_url('kepala_kasir/insertUser'); ?>";

                    $.ajax({
                                method      : "POST",
                                url         : urlInsert,
                                data        : {Nama : Nama,username : username, pass : pass},
                                beforeSend  : function(){
                                                $('#simpanUser').prop('disabled',true);
                                              },
                                success     : function(){
                                                $('#simpanUser').prop('disabled',false);

                                                $('#Nama').val('');
                                                $('#username').val(''); 
                                                $('#pass').val('');
                                                $('#NA').val('');

                                                $.Notification.notify('success','top right', 'Sukses', 'User Telah Ditambah'); 
                                              }
                    });
                }
            });

            $('#editUser').on("click",function(){
                var Nama   = $('#Nama').val();
                var username       = $('#username').val();
                var pass    = $('#pass').val();
                var NA         = $('#NA').val();


                if(Nama=='' || username=='' || pass=='' || NA==''){
                    $.Notification.notify('error','top right', 'Error', 'Harap Isi Semua Form'); 
                } else {
                    var urlEdit = "<?php echo base_url('kepala_kasir/editUserSQL'); ?>";

                    $.ajax({
                                method      : "POST",
                                url         : urlEdit,
                                data        : {Nama : Nama,username : username, pass : pass, NA : NA},
                                beforeSend  : function(){
                                                $('#editUser').prop('disabled',true);
                                                $('#editUser').text("Harap Tunggu...");
                                              },
                                success     : function(){
                                                $('#editUser').prop('disabled',false);
                                                $('#editUser').text("Simpan");
                                                $.Notification.notify('success','top right', 'Sukses', 'User Telah Diedit'); 
                                              }
                    });
                }
            });

            $('#username').on("change",function(){
                var username = $(this).val();

                var urlCek = "<?php echo base_url('kepala_kasir/cekUser'); ?>";

                $.post(urlCekSku,{username : username},function(response){
                    if(response==0){
                        $('#skuAlert').text('Username Telah Terpakai');
                        $('#sku').val('');
                    } else {
                        $('#skuAlert').text('');
                    }
                });
            });
        </script>
    </body>
</html>
