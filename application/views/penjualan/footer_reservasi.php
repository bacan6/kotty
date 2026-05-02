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
        <script src="<?php echo base_url('assets'); ?>/js/wow.min.jfs"></script>
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
        /* ==============================================
             Counter Up
             =============================================== */
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });

                jQuery('#datepicker').datepicker({
                    format: "yyyy-mm-dd"
                });

                jQuery('#datepicker2').datepicker({
                    format: "yyyy-mm-dd"
                });

                jQuery('#datepicker3').datepicker({
                    format: "yyyy-mm-dd"
                });


                $('#profil-address').on("click",function(){
                    isCheck = $(this).val();

                    //check apakah checkbox sudah di ceklis
                    if($(this).is(':checked') ) {
                        //check customer empty or not
                        customer_form = $('#customer-form').val();

                        if(customer_form!=''){
                            //BACA DATA ALAMAT
                           /**$.ajax({
                                        url      : "<?php echo base_url('penjualan/get_alamat_customer'); ?>",
                                        data     : {id : customer_form},
                                        dataType : 'json',
                                        success  : function(response){
                                                        $.each(response, function(x,obj){
                                                            $('#alamat').val(obj.alamat);
                                                            $('input:text[name=no_hp]').val(obj.kontak);
                                                            $("select[name=provinsi] option[value='"+ obj.idProvinsi +"']").attr('selected','true').change();            
                                                        });
                                                  }
                            });**/

                            url = "<?php echo base_url('penjualan/use_profil_address'); ?>";

                            $('#profil-address-form').load(url,{id : customer_form});

                        } else {
                            
                            //uncheck jika tidak memilih
                            $.Notification.notify('error', 'top right', 'Gunakan alamat profil gagal', 'Anda belum memilih customer');
                            $('#profil-address').attr('checked',false);
                        }
                    } else {
                     
                    }
                });
            });

            $('#provinsi').change(function(){
                url = "<?php echo base_url('penjualan/list_kabupaten'); ?>";
                id = $('#provinsi').val();
                $('#list-kabupaten').load(url,{id : id});
            });


            $('#list-kabupaten').change(function(){
                url= "<?php echo base_url('penjualan/list_kecamatan'); ?>";
                
                id = $('#list-kabupaten').val(); 

                $('#list-kecamatan').load(url,{id : id});
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });

            $('#customer-form').select2({
                placeholder: "Pilih Data Customer",
                ajax: {
                    url         : '<?php echo base_url('penjualan/ajax_customer'); ?>',
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

            $('#produk-ajax').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('penjualan/ajax_produk'); ?>',
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

            function formatAngka(angka) {
                 if (typeof(angka) != 'string') angka = angka.toString();
                 var reg = new RegExp('([0-9]+)([0-9]{3})');
                 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
                 return angka;
            }

            $('#produk-ajax').change(function(){
               data_form = "<?php echo base_url('penjualan/data_form_reservasi'); ?>";
               sku = $('#produk-ajax').val();
               no     = parseInt($('#sdf').val());
               urutan = no+1; 

               $.get(data_form,{no : urutan, sku : sku},function(data){
                    $('#data-input').append(data);
                    $('#sdf').val(urutan);
                    $('#default').remove();
               });
            });

            $("#voucher").change(function(){
                $("input[class *='id_kategori']").each(function(){
                    total = 0;
 
                    if($(this).data('id_kategori')==5){
                        total += +$(this).data('id_kategori');
                    }
                });

                alert(total);   
            });

            function simpan_diskon(diskon_otomatis){
                $("#diskon_otomatis_temp").val(diskon_otomatis);
            }

            $(document).on("change", "#jumlah_beli", function(){
               //SET JUMLAH BARU UNTUK DISKON
                                        diskon = 0;
                                        //ambil nilai diskon channel
                                        total_temp      = $('#total_purchase_temp').val();
                                        total_diskon    = (diskon/100)*total_temp; 
                                        diskon_otomatis = $("#diskon_otomatis_temp").val();

                                        $('#diskon_text').text(formatAngka(total_diskon));
                                        $('#diskon_temp').val(total_diskon);

                                        //ambil nilai diskon promosi atau freetext
                                        diskon_promosi_val  = $('#diskon').val(); 
                                        diskon_promosi      = (diskon_promosi_val/100)*(total_temp-total_diskon);
                                        $('#diskon_promosi').text(formatAngka(diskon_promosi));
                                        $('#diskon_promosi_temp').val(diskon_promosi);

                                        //SET GRAND TOTAL
                                        ongkir = $('#ongkir_temp').val();
                                        sum    = $('#total_purchase_temp').val();

                                        //ambil nilai poin
                                        poin = $('#poin_temp').val();

                                        grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                                        //$('#grand_total').text(formatAngka(grand_total));
                                        //$('#total_belanja_notif').text(formatAngka(grand_total));
                                     
                                     
            });

            $(document).on("change","#customer-form",function(){
                id_customer = $('#customer-form').val();

                $.ajax({
                            type    : 'POST',
                            url     : "<?php echo base_url('penjualan/get_diskon_customer'); ?>",
                            data    : {id : id_customer},
                            success : function(diskon){
                                        //ambil nilai diskon
                                        total_temp     = $('#total_purchase_temp').val();
                                        total_diskon   = (diskon/100)*total_temp; 

                                        $('#diskon_text').text(formatAngka(total_diskon));
                                        $('#diskon_temp').val(total_diskon);
                                        
                                        //reset poin reimbursf
                                        $('#poin-reimbursment').val('0');
                                        $('#poin-value-reimburs').empty();

                                        //SET GRAND TOTAL
                                        diskon          = $('#diskon_temp').val();
                                        sum             = $('#total_purchase_temp').val();
                                        diskon_promosi  = $('#diskon_promosi_temp').val();
                                        poin            = $('#poin_temp').val();
                                        ongkir          = $('#ongkir_temp').val();
                                        diskon_otomatis = $('#diskon_otomatis_temp').val();

                                        grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                                        $('#grand_total').text(formatAngka(grand_total));
                                        $('#total_belanja_notif').text(formatAngka(grand_total));
                                      }
                      });
            });

            $(document).on("click", ".hapus-form", function(){
                var sum = 0;

                $("input[class *= 'total_beli_hidden']").each(function(){
                    sum += +$(this).val();
                });

                $("#total_purchase").text(formatAngka(sum));

                //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
                $("#total_purchase_temp").val(sum);
                

                var diskon_otomatis = 0;

                $("input[class *= 'total_diskon_hidden']").each(function(){
                    diskon_otomatis += +$(this).val();
                });
             
                $("#diskon_otomatis").text(formatAngka(diskon_otomatis));

                //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
                simpan_diskon(diskon_otomatis);

                //SET Diskon
                                        diskon  = 0;
                                        //ambil nilai diskon
                                        total_temp      = $('#total_purchase_temp').val();
                                        total_diskon    = (diskon/100)*total_temp; 
                                        diskon_otomatis = $('#diskon_otomatis_temp').val();

                                        $('#diskon_text').text(formatAngka(total_diskon));
                                        $('#diskon_temp').val(total_diskon);

                                        //ambil nilai diskon promosi atau freetext
                                        diskon_promosi_val  = $('#diskon').val(); 
                                        diskon_promosi      = (diskon_promosi_val/100)*(total_temp-total_diskon);
                                        $('#diskon_promosi').text(formatAngka(diskon_promosi));
                                        $('#diskon_promosi_temp').val(diskon_promosi);

                                        //SET GRAND TOTAL
                                        //SET GRAND TOTAL
                                        ongkir = $('#ongkir_temp').val();
                                        poin = $('#poin_temp').val();

                                        grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                                        $('#grand_total').text(formatAngka(grand_total));
                                        $('#total_belanja_notif').text(formatAngka(grand_total));
                                      
            });            

            $('#ongkir').change(function(){
                ongkir = $(this).val();
                $('#ongkir_text').text(formatAngka(ongkir));
                $('#ongkir_temp').val(ongkir);

                //SET GRAND TOTAL
                diskon          = $('#diskon_temp').val();
                sum             = $('#total_purchase_temp').val();
                diskon_promosi  = $('#diskon_promosi_temp').val();
                diskon_otomatis = $('#diskon_otomatis_temp').val();
                poin = $('#poin_temp').val();

                if(ongkir==''){
                    ongkir = 0;
                }

                grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                $('#grand_total').text(formatAngka(grand_total));
                $('#total_belanja_notif').text(formatAngka(grand_total));
            });

            $('#diskon').change(function(){
                diskon = $('#diskon').val();
                customer = $('#customer-form').val();

                if(diskon < 0 || diskon > 1000000000000){
                    $.Notification.notify('error', 'top right', 'Setting Diskon Gagal', 'Pastikan diskon berada pada angka 0 - 100');
                    $('#diskon').val('0');
                } else {

                        total_temp      = $('#total_purchase_temp').val();
                        diskon_channel  = $('#diskon_temp').val();
                        ongkir          = $('#ongkir_temp').val();
                        poin            = $('#poin_temp').val();
                        diskon_otomatis = $('#diskon_otomatis_temp').val()
                        nilai_diskon    = diskon;
                        diskon_otomatis = $('#diskon_otomatis_temp').val();

                        $('#diskon_promosi_temp').val(nilai_diskon);
                        $('#diskon_promosi').text(formatAngka(nilai_diskon));

                        grand_total = (parseInt(ongkir)+parseInt(total_temp))-(parseInt(diskon_channel)+parseInt(nilai_diskon)+parseInt(poin)+parseInt(diskon_otomatis));
                        $('#grand_total').text(formatAngka(grand_total));
                        $('#total_belanja_notif').text(formatAngka(grand_total));
                    
                }
            });

            $(document).on("change","#customer-form",function(){
                id_customer = $('#customer-form').val();

                $.ajax({
                            type    : 'POST',
                            url     : "<?php echo base_url('penjualan/get_diskon_customer'); ?>",
                            data    : {id : id_customer},
                            success : function(diskon){
                                        //ambil nilai diskon
                                        total_temp     = $('#total_purchase_temp').val();
                                        total_diskon   = (diskon/100)*total_temp; 

                                        $('#diskon_text').text(formatAngka(total_diskon));
                                        $('#diskon_temp').val(total_diskon);
                                        
                                        //reset poin reimburs
                                        $('#poin-reimbursment').val('0');
                                        $('#poin-value-reimburs').empty();

                                        //SET GRAND TOTAL
                                        ongkir          = $('#ongkir_temp').val();
                                        sum             = $('#total_purchase_temp').val();
                                        diskon_promosi  = $('#diskon_promosi_temp').val();
                                        poin = $('#poin_temp').val();
                                        diskon_otomatis = $('#diskon_otomatis_temp').val();

                                        grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                                        $('#grand_total').text(formatAngka(grand_total));
                                        $('#total_belanja_notif').text(formatAngka(grand_total));
                                      }
                      });
            });

             $('#jumlah_bayar').on("keyup", function(){
                jumlah_bayar = $('#jumlah_bayar').val();

                diskon          = $('#diskon_temp').val();
                sum             = $('#total_purchase_temp').val();
                diskon_promosi  = $('#diskon_promosi_temp').val();
                poin            = $('#poin_temp').val();
                ongkir          = $('#ongkir_temp').val();
                diskon_otomatis = $('#diskon_otomatis_temp').val();

                grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                //$('#payment_total_notif').css("display","");    

                $('#jumlah_bayar_notif').text(formatAngka(jumlah_bayar));
                $('#kembali_notif').text(formatAngka(jumlah_bayar-grand_total));
            });

            $('#payment_total_notif').on("click",function(){
                $('#payment_total_notif').css("display","none");
            });


            $('#customer-form').change(function(){
                id_customer = $('#customer-form').val();

                url = "<?php echo base_url('penjualan/data_customer_poin'); ?>";
                
                $('#data-customer').load(url,{id : id_customer});
            });


            $('#type_bayar').change(function(){
                type = $('#type_bayar').val();

                sub_account = "<?php echo base_url('penjualan/sub_account'); ?>";
                tempo_form  = "<?php echo base_url('penjualan/tempo_form'); ?>";
                
                if(type != 5){
                    $('#tempo-place').load(sub_account,{id : type});
                } else {
                    $('#tempo-place').load(tempo_form);
                }
                
            });  

            $('#submit-penjualan').submit(function(){
                $('#button-submit').prop("disabled",true);
            });

        </script>
    </body>
</html>
