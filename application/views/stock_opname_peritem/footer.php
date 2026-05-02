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
            var urlCartPO = "<?php echo base_url('so_peritem/cartEP'); ?>";
            var urlAjaxKategoriSO = "<?php echo base_url('so_peritem/ajax_kategori_so'); ?>";
            var urlAjaxProdukSupplier = "<?php echo base_url('so_peritem/ajax_produk_supplier'); ?>";

            function focusStokInputBySku(sku) {
                sku = $.trim(sku);
                if (!sku) return;
                var el = document.getElementById(sku);
                if (!el || !$(el).hasClass('stok_after')) return;
                $(el).focus().select();
                var $tr = $(el).closest('tr');
                if ($tr.length && $tr.offset()) {
                    $('html, body').animate({ scrollTop: $tr.offset().top - 80 }, 200);
                }
            }

            function loadKategoriSO(idBrand, done) {
                if (!idBrand) {
                    $('#kategori').empty().append('<option value="">--Pilih Kategori--</option>');
                    if (typeof done === 'function') done();
                    return;
                }
                $.post(urlAjaxKategoriSO, { id_brand: idBrand }, function (rows) {
                    var $k = $('#kategori');
                    var keep = $k.data('sel');
                    $k.empty().append('<option value="">--Pilih Kategori--</option>');
                    $.each(rows, function (i, r) {
                        $k.append($('<option></option>').val(r.id).text(r.text));
                    });
                    if (keep) {
                        $k.val(String(keep));
                        $k.removeData('sel');
                    }
                    if (typeof done === 'function') done();
                }, 'json');
            }

            jQuery(document).ready(function(e) {
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
                });

                function refreshCartSO() {
                    $('#data-input').load(urlCartPO);
                }
                var ib = $('#brand').val();
                if (ib) {
                    loadKategoriSO(ib, function () {
                        var kat = $('#kategori').val();
                        if (kat) {
                            $.post(urlAjaxProdukSupplier, { brand: ib, id_kategori: kat }, refreshCartSO);
                        } else {
                            refreshCartSO();
                        }
                    });
                } else {
                    refreshCartSO();
                }
/*
                $('#supplier').change(function(){
                	var supplier = $(this).val();
                	$.ajax({
                                method      : "POST",
                                url         : '<?php echo base_url('so_peritem/ajax_produk_supplier'); ?>',
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

               var urlInsertCartPo = "<?php echo base_url('so_peritem/insertCartPO'); ?>";

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

                var urlInsertPO = "<?php echo base_url('so_peritem/insertPO'); ?>";
                var keterangan = $('#keterangan').val();
                var brand       = $('#brand').val();
                var kategori    = $('#kategori').val();
                
                    $.ajax({
                                method      : "POST",
                                url         : urlInsertPO,
                                data        : {keterangan:keterangan,brand:brand,kategori:kategori},
                                beforeSend  : function(){
                                                $('#prosesPO').text('Harap Tunggu...');
                                                $('#CssLoader').show(); 
                                              },
                                success     : function(noInv){
                                                window.location.replace("<?php echo base_url('so_peritem/form_so?no_so='); ?>"+noInv);
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
                    url         : '<?php echo base_url('so_peritem/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 100,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params,
                            brand: $('#brand').val()
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

            $('#verifyApproval').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();
                var baris = $("#chartID").val();

                var verifyApproval = "<?php echo base_url('so_peritem/verifyApproval'); ?>";
                $.ajax({
        			method : "POST",
        			url : verifyApproval,
        			dataType : 'json',
        			data : {user : user, pw : pw},
        			success : function(response){		
					    var setuju = response.setuju;
					    if (setuju==1){
                            $('#'+baris).prop('disabled', false);
                            $("#approvalSO").modal('hide');
                            $('#'+baris).focus();
                            $("#passApprover").val('');
                            $('#labelpwd').html('');
                        }else {
                            $("#passApprover").val('');
                            $("#passApprover").focus();
                            $('#'+baris).prop('disabled', true);
                            $('#labelpwd').html('Pengguna tidak ditemukan...');
                        }
        			}
                });
            });

            $('#brand').on('change', function(){
                var brand = $(this).val();
                $("#sku").select2("val","");
                loadKategoriSO(brand, function () {
                    $.post(urlAjaxProdukSupplier, { brand: brand, id_kategori: '' }, function () {
                        $('#data-input').load(urlCartPO);
                    });
                });
            });

            $('#kategori').on('change', function () {
                var brand = $('#brand').val();
                var id_kategori = $(this).val();
                $.post(urlAjaxProdukSupplier, { brand: brand, id_kategori: id_kategori }, function () {
                    $('#data-input').load(urlCartPO);
                });
            });

            $(document).on('keydown', '#jumpToSku', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    focusStokInputBySku($(this).val());
                }
            });


        </script>
    </body>
</html>
