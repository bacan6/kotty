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
        <script type="text/javascript" src="<?php echo base_url(); ?>/datepicker/js/moment.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>/datepicker/js/bootstrap-datetimepicker.min.js"></script>

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
      
        <script type="text/javascript">
             $('.datetimepicker').datetimepicker({
             format: 'YYYY-MM-DD HH:mm:ss',
              widgetPositioning: {
                  horizontal: 'right',
                  vertical: 'bottom'
              }
          
        });

            var kupon        = "<?php echo base_url('kupon_member/data_kupon?id_customer='.$id_customer); ?>";
            $('#data-kupon').load(kupon);

        

           
          $('#submit').submit(function(e){
              e.preventDefault();
                var fdata = new FormData(this);
                var files = $('#gambar')[0].files;
                url = "<?php echo base_url('kupon/add_kupon_sql'); ?>";

             $.ajax({
                  url: url,
                  type: 'post',
                  data: fdata,
                  contentType: false,
                  processData: false,
                  success: function(data){
                    $('#submit').trigger("reset");
                    $('#data-kupon').load(kupon);
                    $('#add-kupon').modal('hide');

                    if(data > 0){
                        $.Notification.notify('success', 'top right', 'kupon', 'kupon Berhasil Ditambahkan');
                    } else {
                         $.Notification.notify('danger', 'top right', 'kupon', 'kupon Gagal Ditambahkan');
                    }
                }
             })
          
          });
          jQuery(".select2").select2({
            width: '100%'
        });
        $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('kupon/ajax_produk'); ?>',
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
