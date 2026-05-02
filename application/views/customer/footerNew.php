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
            var kategori = '<?php echo $kategori?>';
            $("#customerDatatables").DataTable({
                ordering: false,
                processing: false,
                serverSide: true,
                lengthMenu: [[10, 25, 50,100, 500], [10, 25, 50,100, 500]],
                ajax: {
                    url: "<?php echo base_url('customer/datatables'); ?>",
                    data: {kategori:kategori},
                    type:'POST'
                }
            });

    $('#verifyApproval').on("click",function(){
    var user = $("#userApprover").val();
    var pw = $("#passApprover").val();

    var verifyApproval = "<?php echo base_url('penjualan/verifyApproval'); ?>";
    $.ajax({
        method : "POST",
        url : verifyApproval,
        dataType : 'json',
        data : {user : user, pw : pw},
        success : function(response){		
            var setuju = response.setuju;
            if (setuju==1){
                $(".izin").css('display', '');
                $("#approvalCustomer").modal('hide');
                $("#passApprover").val('');
                $('#labelpwd').html('');
            }else {
                $("#passApprover").val('');
                $(".izin").css('display', 'none');
                $('#labelpwd').html('Pengguna tidak ditemukan...');
            }
        }
    });
    });
    function login_selectuser(device_name, sn) {
    var element = $("#select_scan").find('option:selected'); 
    var username = element.attr("username"); 

    $("#button_login").attr("href","finspot:FingerspotVer;"+$('#select_scan').val());
    $("#userApprover").val(username);
    $('#labelvrf').html('');

    var username = $("#userApprover").val();
    if(username!='' && username!=undefined) {
        $("#button_login").show();
        $('#labelvrf').html('loading...');
        var open_verifikasi = "<?php echo base_url('penjualan/open_verifikasi'); ?>";
        var cek_verifikasi = "<?php echo base_url('penjualan/cek_verifikasi'); ?>";
            $.ajax({
                method : "POST",
                url : open_verifikasi,
                dataType : 'json',
                data : {username : username},
                success : function(resp){
                    var username = resp.username;	
                    setTimeout(function() { 
                        $.ajax({
                            method : "POST",
                            url : cek_verifikasi,
                            dataType : 'json',
                            data : {username : username},
                            success : function(response2){	
                                var stat = response2.status;
                                if(stat==1){
                                    $('#passApprover').val(response2.ps);
                                    $('#labelvrf').html('verification success!');
                                    setTimeout(function() { $('#labelvrf').html(''); },5000);
                                    
                                    $('#labelpwd').html('');
                                    $("#verifyApproval").hide();
                                    $(".izin").css('display', '');
                                }else{
                                    $('#passApprover').val('');
                                    $('#labelpwd').html('verification failed!');
                                    setTimeout(function() { $('#labelpwd').html(''); },3000);
                                    
                                    $('#labelvrf').html('');
                                }
                            }
                        });
                    }, 5000);
                }
            });
    }

    }
        </script>
    </body>
</html>
