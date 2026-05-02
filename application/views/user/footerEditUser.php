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
        <!-- Dashboard 
        <script src="<?php echo base_url('assets'); ?>/js/jquery.dashboard.js"></script>-->

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/datatables/dataTables.bootstrap.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>
        <script type="text/javascript">
        	$('#email').change(function(){
        		var email = $(this).val();
        		var urlCheckEmail = "<?php echo base_url('setting/checkEmailIfExist'); ?>";

        		$.post(urlCheckEmail,{email : email}, function(result){
        			if(result > 0){
        				$.Notification.notify('error','top right', 'Error', 'Email telah terdaftar');
        				$('#email').val('');
        			}
        		});
        	});

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });
            

        	$('#username').change(function(){
        		var username = $(this).val();
        		var urlCheckUsername = "<?php echo base_url('setting/checkUsernameIfExist'); ?>";

        		$.post(urlCheckUsername,{username : username}, function(result){
        			if(result > 0){
        				$.Notification.notify('error','top right', 'Error', 'Username telah terdaftar');
        				$('#username').val('');
        			}
        		});
        	});

        	$('.editUser').on("click",function(){
        		var urlEditUser = "<?php echo base_url('setting/editUserSQL'); ?>";

                var idUser = "<?php echo $_GET['id_user']; ?>";
        		var namaDepan = $('#namaDepan').val();
        		var namaBelakang = $('#namaBelakang').val();
        		var noHP = $('#noHP').val();
        		var email = $('#email').val();
        		var username = $('#username').val();
        		var password = $('#password').val();
                var password_pda = $('#password_pda').val();
                var status = $('#status').val();
                var toko = $('#toko').val();
                var is_admin = $('#is_admin').val();

                menu = [];
                $('input[id="menu"]:checked').each(function(){
                    var id = $(this).data('id');
                    menu.push(id);
                });
                
                submenu = [];
                $('input[id="submenu"]:checked').each(function(){
                    var id = $(this).data('id');
                    submenu.push(id);
                });
                
                brand = [];
                $('select[id="brand"] option:selected').each(function(){
                    var id = $(this).data('id');
                    brand.push(id);
                });

                if(namaDepan=='' || namaBelakang=='' || email=='' || username==''){
                    if(namaDepan==''){
                        $.Notification.notify('error','top right', 'Gagal','Harap isi nama depan');
                    }

                    if(namaBelakang==''){
                        $.Notification.notify('error','top right', 'Gagal','Harap isi nama belakang');
                    }

                    if(email==''){
                        $.Notification.notify('error','top right', 'Gagal','Harap isi email');
                    }

                    if(username==''){
                        $.Notification.notify('error','top right', 'Gagal','Harap isi username');
                    }

                } else {

            		$.ajax({
            					method : "POST",
            					url : urlEditUser,
            					data : {status : status, is_admin:is_admin,idUser : idUser,password_pda:password_pda, namaDepan : namaDepan, namaBelakang : namaBelakang, noHP : noHP, email : email, username : username, password : password, menu : JSON.stringify(menu), submenu : JSON.stringify(submenu),brand : JSON.stringify(brand), toko : toko},
            					success : function(){
                                            window.location.replace("<?php echo base_url('setting/user'); ?>");
                                            $.Notification.notify('success','top right', 'Sukses', 'Berhasil mengubah data user');
            							  },
                                beforeSend : function(){
                                                $('.editUser').text("...Harap Tunggu");
                                                $('.editUser').prop("disabled",true);
                                              }
            		});

                }
        	});
        </script>
    </body>
</html>
