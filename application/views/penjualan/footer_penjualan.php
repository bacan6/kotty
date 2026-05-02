        <!-- ctrl = Input Bayar | shift = Lanjut Belanja -->
            <!-- Footer Ends -->
            </section>
        <!-- Main Content Ends -->
        
        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/scanner/jquery.scannerdetection.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/wow.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.nicescroll.js" type="text/javascript"></script>

        <!-- sweet alerts -->
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.init.js"></script>


        <!-- Dashboard -->
        

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.app.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/mousetrap/mousetrap.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            /*
            Mousetrap.bind('ctrl', function() {
                $('#s2id_produk-ajax').hide();
                $('#jumlah_bayar').focus();
                return false;
            });
            Mousetrap.bind('shift', function() {
                $('#s2id_produk-ajax').show();
                $('#s2id_produk-ajax').focus();
                return false;
            });
            */ 

          


            var totalPurchaseUrl = "<?php echo base_url('penjualan/totalPurchase'); ?>";
            var diskonPeritemUrl = "<?php echo base_url('penjualan/diskonPeritemPanel'); ?>";
            var urlDiskonDisplay = "<?php echo base_url('penjualan/diskonMemberDisplay'); ?>";
            var urlMemberPoin    = "<?php echo base_url('penjualan/data_customer_poin'); ?>";
            var urlNilaiReimburs = "<?php echo base_url('penjualan/viewNilaiReimburs'); ?>";
            var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkir'); ?>";
            var urlViewDiskon = "<?php echo base_url('penjualan/viewDiskon'); ?>";
            var urlGrandTotal = "<?php echo base_url('penjualan/viewGrandTotal'); ?>";
            var urlSurcharge = "<?php echo base_url('penjualan/viewSurcharge'); ?>";
            var urlViewVoucher = "<?php echo base_url('penjualan/viewVoucher'); ?>";
            var urlViewVoucherFisik = "<?php echo base_url('penjualan/viewVoucherFisik'); ?>";
            var sudahDiproses = false;
            <?php 
            // $arUsr = array(45,44,43,40,47,46);
            //if(in_array($idUser,$arUsr)){?>
                //var verifyApproval = "<?php //echo base_url('penjualan/verifyApprovalPass'); ?>";
            <?php //}else{ ?>
                var verifyApproval = "<?php echo base_url('penjualan/verifyApproval'); ?>";
            <?php //} ?>

            jQuery(document).ready(function($) {
                
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd"
                });

                
                
                $(document).on('keyup',function(evt) {
                    if (evt.key === 'Esc') {
                    //alert('Esc key pressed.');
                    }
                });
                $(document).keydown(function (e) {
                 var key = e.which;
                 if(key == 115){
                    $('#select2-drop').hide();
                    $('#jumlah_bayar').focus();
                    return false;
                  }
                  if(key == 114){
                    $('#produk-ajax').select2('open');
                    return false;
                  }
                  
                  
                });
                $('#jumlah_bayar').keyup(function (e) {
                 var key = e.which;
                 if(key == 13){
                    $('#button-submit').click();
                  }
                });
                $('#seri_voucher').keypress(function (e) {
                    var key = e.which;

                    if(sudahDiproses) return false;
                    if(key == 13){
                        viewPricePanel();
                    }
                });

                var dataUrl = "<?php echo base_url('penjualan/viewCart'); ?>";
                $('#data-input').load(dataUrl);

                //price right panel
                setTimeout(function(){ $('#total_purchase').load(totalPurchaseUrl); }, 400);
                

                //diskon peritem
                setTimeout(function(){ $('#diskonPeritem').load(diskonPeritemUrl); }, 500);

                //diskon member
                $('#diskonMember').load(urlDiskonDisplay);

                //diskon member
                $('#data-customer').load(urlMemberPoin);

                //poin reimburs
                $('#poin-value-reimburs').load(urlNilaiReimburs);

                //view ongkir
                $('#ongkirText').load(urlViewOngkir);

                //view diskon
                
                setTimeout(function(){ $('#diskon_promosi').load(urlViewDiskon); }, 400);

                //view surcharge
                var type = $('#type_bayar').val();
                var subAccount = $('#subAccount').val();
                $('#surcharge').load(urlSurcharge,{type:type,subAccount:subAccount});
                   
                setTimeout(function(){ $('#voucher').load(urlViewVoucher); }, 800);  
                
                var seri_voucher = $("#seri_voucher").val();
                setTimeout(function(){$('#voucherFisik').load(urlViewVoucherFisik,{seri_voucher : seri_voucher});},680);
                
                //grand total
                setTimeout(function(){ $('#grand_total').load(urlGrandTotal); }, 1200);

                $(function() {  
                    $("#tableNiceScroll").niceScroll({cursorcolor:"#00F"});
                });
            });

           $("#tableNiceScroll").niceScroll();

            $(document).scannerDetection(function(val){
                var sku = val;
                //get data produk
                urlProduk = "<?php echo base_url('penjualan/getDataProduk'); ?>"; 

                $.ajax({
                            type     : "POST",
                            url      : urlProduk,
                            dataType : 'json',
                            data     : {sku : sku},
                            success  : function(response){
                                        $.each(response, function(x,obj){
                                            var harga   = obj.harga;
                                            var stok    = obj.stok;
                                            var hpp    = obj.hpp;
                                            var id_produk    = obj.id_produk;
                                            var qty     = 1;
                                        // if (parseInt(hpp)>=parseInt(harga)){
                                        //     alert("Cek data harga produk, harga beli lebih besar dari harga jual.");
                                        //     return false;
                                        // }{
                                            //if(parseInt(stok) > 0){
                                                var urlCart = "<?php echo base_url('penjualan/insertCart'); ?>";
                                                
                                                $.post(urlCart,{sku : id_produk, harga : harga, stok : stok, qty : qty, hpp : hpp},function(hasil){

                                                    //if(hasil=='0'){
                                                       // alert("Stok Tidak Mencukupi");
                                                   // } else {
                                                        var dataUrl = "<?php echo base_url('penjualan/viewCart'); ?>";
                                                        $('#data-input').load(dataUrl);
                                                        viewPricePanel();
                                                        
                                                    //}
                                                });

                                        //    } else {
                                        //       alert("Stok Tidak Mencukupi");
                                        //     }
                                        //} 
                                            $('#s2id_autogen1_search').val('');
                                            
                                        });

                                        $('#produk-ajax').select2("val","");
                                      },
                            error : function(){
                                        alert('Barang Tidak Ditemukan');
                                    }   
                });
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
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

            $('#provinsiPenerima').change(function(){
                url = "<?php echo base_url('penjualan/list_kabupaten'); ?>";

                id = $('#provinsiPenerima').val();

                $('#kabupatenPenerima').load(url,{id : id});
            });

            $('#kabupatenPenerima').change(function(){
                url= "<?php echo base_url('penjualan/list_kecamatan'); ?>";
                
                id = $('#kabupatenPenerima').val();

                $('#kecamatanPenerima').load(url,{id : id});
            });

            function viewPricePanel(){
                var totalPurchaseUrl = "<?php echo base_url('penjualan/totalPurchase'); ?>";
                var diskonPeritemUrl = "<?php echo base_url('penjualan/diskonPeritemPanel'); ?>";
                var urlDiskonDisplay = "<?php echo base_url('penjualan/diskonMemberDisplay'); ?>";
                var urlMemberPoin    = "<?php echo base_url('penjualan/data_customer_poin'); ?>";
                var urlNilaiReimburs = "<?php echo base_url('penjualan/viewNilaiReimburs'); ?>";
                var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkir'); ?>";
                var urlViewDiskon = "<?php echo base_url('penjualan/viewDiskon'); ?>";
                var urlSurcharge = "<?php echo base_url('penjualan/viewSurcharge'); ?>";
                var urlGrandTotal = "<?php echo base_url('penjualan/viewGrandTotal'); ?>";

                var cekBSI = $("#subAccount").val();
                if(cekBSI=='1'){
                    $("#btnBSI").show();
                }else{
                    if($("#btnBSI").is(":visible")){
                        var diskon      = 0;
                        var urlDiskon   = "<?php echo base_url('penjualan/insertDiskon'); ?>";

                        $.post(urlDiskon,{diskon : diskon},function(){
                            viewPricePanel();
                        });
                        $("#btnBSI").hide();
                    }
                }

                //price right panel
                setTimeout(function(){ $('#total_purchase').load(totalPurchaseUrl); }, 500);

                //diskon peritem
                setTimeout(function(){ $('#diskonPeritem').load(diskonPeritemUrl); }, 2500);

                //diskon member
                $('#diskonMember').load(urlDiskonDisplay);

                //diskon member
                $('#data-customer').load(urlMemberPoin);

                //poin reimburs
                $('#poin-value-reimburs').load(urlNilaiReimburs);

                //view ongkir
                $('#ongkirText').load(urlViewOngkir);

                //view diskon
                setTimeout(function(){ $('#diskon_promosi').load(urlViewDiskon); }, 400);

                //view surcharge
                var type = $('#type_bayar').val();
                var subAccount = $('#subAccount').val();
                $('#surcharge').load(urlSurcharge,{type:type,subAccount:subAccount});
                
                setTimeout(function(){ $('#voucher').load(urlViewVoucher); }, 800); 
                
                var seri_voucher = $("#seri_voucher").val();
                setTimeout(function(){$('#voucherFisik').load(urlViewVoucherFisik,{seri_voucher : seri_voucher});},680);
                
                //grand total
                setTimeout(function(){ $('#grand_total').load(urlGrandTotal); }, 1200);
            }

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
                    quietMillis : 10,
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
                minimumInputLength: 2,
            });

            function formatAngka(angka) {
                 if (typeof(angka) != 'string') angka = angka.toString();
                 var reg = new RegExp('([0-9]+)([0-9]{3})');
                 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
                 return angka;
            }

            $('#produk-ajax').change(function(){
                var sku = $(this).val();
                //get data produk
                urlProduk = "<?php echo base_url('penjualan/getDataProduk'); ?>"; 

                $.ajax({
                            type     : "POST",
                            url      : urlProduk,
                            dataType : 'json',
                            data     : {sku : sku},
                            success  : function(response){
                                        $.each(response, function(x,obj){
                                            var harga   = obj.harga;
                                            var stok    = obj.stok;
                                            var hpp    = obj.hpp;
                                            var id_produk    = obj.id_produk;
                                            var qty     = 1;
                                        // if (parseInt(hpp)>=parseInt(harga)){
                                        //     alert("Cek data harga produk, harga beli lebih besar dari harga jual.");
                                        //     return false;
                                        // }{
                                            //if(parseInt(stok) > 0){
                                                var urlCart = "<?php echo base_url('penjualan/insertCart'); ?>";
                                                
                                                $.post(urlCart,{sku : id_produk, harga : harga, stok : stok, qty : qty, hpp : hpp},function(hasil){

                                                    //if(hasil=='0'){
                                                        //alert("Stok Tidak Mencukupi");
                                                    //} else {
                                                        var dataUrl = "<?php echo base_url('penjualan/viewCart'); ?>";
                                                        $('#data-input').load(dataUrl);
                                                        viewPricePanel();
                                                    //}
                                                });

                                            // } else {
                                            //     alert("Stok Tidak Mencukupi");
                                            //     var dataUrl = "<?php echo base_url('penjualan/viewCart'); ?>";
                                            //     $('#data-input').load(dataUrl);
                                            //     viewPricePanel();
                                            // }
                                       // }
                                        });

                                        $('#produk-ajax').select2("val","");
                                      }   
                });
            });
           
            $('#ongkir').on("keyup",function(){
                var ongkir = $(this).val();
                var urlOngkir = "<?php echo base_url('penjualan/insertOngkir'); ?>";

                $.post(urlOngkir,{ongkir : ongkir},function(){
                    var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkir'); ?>";
                    $('#ongkirText').load(urlViewOngkir);
                    viewPricePanel();
                });
            });

            $('#diskon').on("keyup",function(){
                var diskon      = $('#diskon').val();
                var urlDiskon   = "<?php echo base_url('penjualan/insertDiskon'); ?>";

                $.post(urlDiskon,{diskon : diskon},function(){
                    var urlViewDiskon = "<?php echo base_url('penjualan/viewDiskon'); ?>";
                    $('#diskon_promosi').load(urlViewDiskon);
                    viewPricePanel();
                });
            });

            $(document).on("change","#customer-form",function(){
                id_customer = $('#customer-form').val();

                $.ajax({
                            type    : 'POST',
                            url     : "<?php echo base_url('penjualan/get_diskon_customer'); ?>",
                            data    : {id : id_customer},
                            success : function(diskon){
                                        displayDiskonMember(diskon,id_customer);
                                        
                                      }
                      });
            });

            function displayDiskonMember(diskon,id_customer){
                $.ajax({
                            type    : "POST",
                            url     : totalPurchaseUrl,
                            success : function(totalPurchase){  
                                        total = totalPurchase.split('.').join("");

                                        totalDiskon = (diskon/100)*total;

                                        //simpan di database
                                        var urlDiskon = "<?php echo base_url('penjualan/saveDiskonMember'); ?>";
                                        $.post(urlDiskon,{totalDiskon : totalDiskon, idCustomer : id_customer},function(){
                                            //
                                            var urlDiskonDisplay = "<?php echo base_url('penjualan/diskonMemberDisplay'); ?>";
                                            $('#diskonMember').load(urlDiskonDisplay);
                                            viewPricePanel();
                                        });

                                        //$('#diskon_text').text(formatAngka(totalDiskon));
                            }
                });
            }

             $('#jumlah_bayar').on("keyup", function(){
                jumlah_bayar = $('#jumlah_bayar').val();

               $.ajax({
                            type        : "POST",
                            url         : urlGrandTotal,
                            success     : function(totalPurchase){
                                            var grandTotal = totalPurchase.split('.').join("");

                                            $('#payment_total_notif').css("display","");    

                                            $('#total_belanja_notif').text(formatAngka(grandTotal));
                                            $('#jumlah_bayar_notif').text(formatAngka(jumlah_bayar));
                                            $('#kembali_notif').text(formatAngka(jumlah_bayar-grandTotal));
                            }
               });
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
                $("#keterangan").focus();

                sub_account = "<?php echo base_url('penjualan/sub_account'); ?>";
                tempo_form  = "<?php echo base_url('penjualan/tempo_form'); ?>";
                
                if(type != 5){
                    $('#tempo-place').load(sub_account,{id : type});
                } else {
                    $('#tempo-place').load(tempo_form);
                }
                viewPricePanel();
            });  



            $('#button-submit').on("click",function(){
                submitPenjualan();    
            });

            $('#jumlah_bayar').keypress(function (e) {
             var key = e.which;
             if(key == 13){
                submitPenjualan();
              }
            }); 
            

            $('#pendingTrx').on("click",function(){
                $.ajax({
                            method : "POST",
                            url : "<?php echo base_url('penjualan/pendingTrx'); ?>",
                            beforeSend : function(){
                                            $('#CssLoader').show();
                                         },
                            success : function(){
                                        location.reload(true);
                                      }
                });
            });

            function submitPenjualan(){
                if (sudahDiproses) return false;
                // if (!confirm('Yakin melanjutkan?')) return false;
                var urlTotalKeseluruhan = "<?php echo base_url('penjualan/totalKeseluruhan'); ?>";
                var type_bayar = $('#type_bayar').val();
                var jumlah_bayar = $('#jumlah_bayar').val();

                $.ajax({
                            type        : "POST",
                            url         : urlTotalKeseluruhan,
                            success     : function(totalPurchase){

                                    <?php if ($idUser==6 || $idUser==40){ ?>
                                             prosesPenjualan();
                                        <?php } else { ?>
                                            if (type_bayar == 5){
                                                prosesPenjualan();
                                            }else{
                                                if(Number(jumlah_bayar) < Number(totalPurchase) || jumlah_bayar==''){
                                                    $.Notification.notify('error','top right', 'Gagal', jumlah_bayar+'Jumlah bayar lebih kecil dari total belanja='+totalPurchase);
                                                } else {
                                                    prosesPenjualan();
                                                }
                                            }
                                        <?php } ?>
                                            
                                          }
                });
            }

            function prosesPenjualan(){
                sudahDiproses = true;
                var type_bayar = $('#type_bayar').val();
                var keterangan = $('#keterangan').val();
                var jumlah_bayar = $('#jumlah_bayar').val();
                var subAccount = $('#subAccount').val();
                var jatuh_tempo = $('#jatuhTempo').val();
                var seri_voucher = $('#seri_voucher').val();

                var no_online = "<?php echo $_GET['struk']?>";

                //opsi pengiriman
                var namaPenerima = $('#nama_penerima').val();
                var noHPPenerima = $('#kontakPenerima').val();
                var ekspedisi = $('#ekspedisi').val();
                var alamatPenerima = $('#alamatPenerima').val();
                var provinsi = $('#provinsiPenerima').val();
                var kabupaten = $('#kabupatenPenerima').val();
                var kecamatan = $('#kecamatanPenerima').val();

                if(type_bayar!=5 && type_bayar!=1 && subAccount==''){
                    $.Notification.notify('error','top right', 'Gagal Menyimpan Data', 'Harap memilih detail cara pembayaran'); 
                }         

                var urlPenjualan = "<?php echo base_url('penjualan/penjualan_sql'); ?>";
                
                $.ajax({
                            method: "POST",
                            url: urlPenjualan,
                            data: {no_online:no_online,seri_voucher:seri_voucher,type_bayar : type_bayar, keterangan : keterangan, jumlah_bayar : jumlah_bayar, sub_account : subAccount, jatuh_tempo : jatuh_tempo, namaPenerima : namaPenerima, noHPPenerima : noHPPenerima, ekspedisi : ekspedisi, alamatPenerima : alamatPenerima, provinsi : provinsi, kabupaten : kabupaten, kecamatan : kecamatan},
                            beforeSend : function(){
                                               $('#CssLoader').show(); 
                                          },
                            success : function(noInv){
                                            var urlRedirect = "<?php echo base_url('penjualan/invoice_penjualan?no_invoice='); ?>"+noInv;
                                            window.location.replace(urlRedirect);
                                          }
                });
            }


            function isEmail(email) {
              var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
              return regex.test(email);
            }

            $('#noMember').change(function(){
                var noMember = $('#noMember').val();

                var url = "<?php echo base_url('penjualan/cekNoMemberIfDuplicate'); ?>";

                $.post(url,{noMember : noMember},function(data){
                    if(data==1){
                        $('#labelNoMember').text('**No Member Duplicate');
                        $('#noMember').val('');
                    } else {
                        $('#labelNoMember').empty();
                    }
                });
            });

            $('#email').change(function(){
                var email = $(this).val();

                var check = isEmail(email);

                if(check==false){
                    $('#labelEmail').text('**Email Not Valid');
                    $('#email').val('');
                } else {
                    $('#labelEmail').empty();
                }
            });

            $('#simpanMember').on("click",function(){
                var noMember         = $('#noMember').val();
                var namaCustomer     = $('#namaCustomer').val();
                var kontak           = $('#kontak').val();
                var email            = $('#email').val();
                var tanggalLahir     = $('#tanggalLahir').val();
                var kategoriCustomer = $('#kategoriCustomer').val();
                var diskonMember     = $('#setDiskonMember').val();
                var alamat           = $('#alamat').val();
                var provinsi         = $('#provinsi').val();
                var kabupaten        = $('#list-kabupaten').val();
                var kecamatan        = $('#list-kecamatan').val();

                var url = "<?php echo base_url('penjualan/simpanMember'); ?>";

                if(noMember=='' || namaCustomer=='' || kontak=='' || email==''){
                    if(noMember==''){
                        $('#labelNoMember').text('**No Member Required');
                    }

                    if(namaCustomer==''){
                        $('#labelNamaCust').text('**Nama Cust Required');
                    }

                    if(kontak==''){
                        $('#labelKontak').text('**Kontak Required');
                    }

                    if(email==''){
                        $('#labelEmail').text('**Email Required');
                    }
                } else {
                    $.ajax({
                                type        : "POST",
                                url         : url,
                                data        : {noMember : noMember, namaCustomer : namaCustomer, kontak : kontak, email : email, tanggalLahir : tanggalLahir,kategoriCustomer : kategoriCustomer, diskonMember : diskonMember, alamat : alamat, provinsi : provinsi, kabupaten : kabupaten, kecamatan : kecamatan},
                                beforeSend  : function(){
                                                $('#simpanMember').text('Harap Tunggu');
                                              }
                    }).done(function(data){
                        if(data==1){
                            $.Notification.notify('success','top right', 'Tambah Customer', 'Customer Berhasil Ditambahkan');
                            $('#myModal1').modal('hide');

                        } else {
                            $.Notification.notify('error','top right', 'Tambah Customer', 'Customer Gagal Ditambahkan');
                            $('#myModal1').modal('hide');
                        }
                    });
                }
            });

            $('#hidePengiriman').on("click",function(){
                var namaPenerima = $('#NamaPenerima').val();
                var noHP = $('#kontakPenerima').val();
                var ekspedisi = $('#ekspedisi').val();
                var alamat = $('#alamatPenerima').val();
                var provinsi = $('#provinsiPenerima').val();
                var kabupaten = $('#kabupatenPenerima').val();
                var kecamatan = $('#kecamatanPenerima').val();

                if(namaPenerima=='' || noHP=='' || ekspedisi=='' || alamat=='' || provinsi=='' || kabupaten=='' || kecamatan==''){
                    $.Notification.notify('error','top right', 'Gagal Menyimpan Data', 'Harap Lengkapi Semua Form'); 
                } else {
                    $('#opsiPengirimanModal').modal('hide');
                    ('#opsiPengirimanModal').modal('hide');
                }   
            });

            $('#alamatCustomer').on("click",function(){
                var checkBox = $('#alamatCustomer').prop('checked');

                if($('#alamatCustomer').is(":checked")){
                    var idCustomer = $('#customer-form').val();

                    //check sudah pilih customer belum
                    if(idCustomer==''){
                        $.Notification.notify('error','top right', 'Oooops', 'Anda belum memilih customer'); 
                    } else {
                        var urlCustomer = "<?php echo base_url('penjualan/viewAlamatCustomer'); ?>";

                        $('#alamatContent').load(urlCustomer,{idCustomer : idCustomer},function(){
                            $('select.select2').select2({
                                width: '100%'
                            });    
                        });
                    }
                } else {
                    var emptyAlamatCust = "<?php echo base_url('penjualan/emptyAlamatCust'); ?>";

                    $('#alamatContent').load(emptyAlamatCust,function(){
                        $('select.select2').select2({
                                width: '100%'
                        });
                    });
                }
            });
            $('#select2-chosen-1').focus();
            $('#select2-chosen-1').click();

            $('#verifyApproval').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();
                var baris = $("#chartID").val();


                $('#'+baris).prop('readonly', false);
                $("#approvalKasir").modal('hide');
                $('#'+baris).focus();
                $("#passApprover").val('');
                $('#labelpwd').html('');

                
                // $.ajax({
        		// 	method : "POST",
        		// 	url : verifyApproval,
        		// 	dataType : 'json',
        		// 	data : {user : user, pw : pw},
        		// 	success : function(response){		
				// 	    var setuju = response.setuju;
				// 	    if (setuju==1){
                //             $('#'+baris).prop('readonly', false);
                //             $("#approvalKasir").modal('hide');
                //             $('#'+baris).focus();
                //             $("#passApprover").val('');
                //             $('#labelpwd').html('');
                //         }else {
                //             $("#passApprover").val('');
                //             $("#passApprover").focus();
                //             $('#'+baris).prop('readonly', true);
                //             $('#labelpwd').html('Pengguna tidak ditemukan...');
                //         }
        		// 	}
                // });
            });
            $('#verifyApprovalCancel').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();

                $('#cancelButton').show();
                $("#approvalKasir").modal('hide');
                $("#passApprover").val('');
                $('#labelpwd').html('');

                
                //$.ajax({
        			//method : "POST",
        		// 	url : verifyApproval,
        		// 	dataType : 'json',
        		// 	data : {user : user, pw : pw},
        		// 	success : function(response){		
				// 	    var setuju = response.setuju;
				// 	    if (setuju==1){
                //             $('#cancelButton').show();
                //             $("#approvalKasir").modal('hide');
                //             $("#passApprover").val('');
                //             $('#labelpwd').html('');
                //         }else {
                //             $("#passApprover").val('');
                //             $("#passApprover").focus();
                //             $('#cancelButton').hide();
                //             $('#labelpwd').html('Pengguna tidak ditemukan...');
                //         }
        		// 	}
                // });
            });
            $('#verifyApprovalPending').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();

                $('#pendingTrx').show();
                $("#approvalKasir").modal('hide');
                $("#passApprover").val('');
                $('#labelpwd').html('');

               
                // $.ajax({
        		// 	method : "POST",
        		// 	url : verifyApproval,
        		// 	dataType : 'json',
        		// 	data : {user : user, pw : pw},
        		// 	success : function(response){		
				// 	    var setuju = response.setuju;
				// 	    if (setuju==1){
                //             $('#pendingTrx').show();
                //             $("#approvalKasir").modal('hide');
                //             $("#passApprover").val('');
                //             $('#labelpwd').html('');
                //         }else {
                //             $("#passApprover").val('');
                //             $("#passApprover").focus();
                //             $('#pendingTrx').hide();
                //             $('#labelpwd').html('Pengguna tidak ditemukan...');
                //         }
        		// 	}
                // });
            });
            $('#verifyApprovalDelete').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();
                var baris = $("#chartID").val();

                $('#'+baris).show();
                $("#approvalKasir").modal('hide');
                $("#passApprover").val('');
                $('#labelpwd').html('');

                
                // $.ajax({
        		// 	method : "POST",
        		// 	url : verifyApproval,
        		// 	dataType : 'json',
        		// 	data : {user : user, pw : pw},
        		// 	success : function(response){		
				// 	    var setuju = response.setuju;
				// 	    if (setuju==1){
                //             $('#'+baris).show();
                //             $("#approvalKasir").modal('hide');
                //             $("#passApprover").val('');
                //             $('#labelpwd').html('');
                //         }else {
                //             $("#passApprover").val('');
                //             $("#passApprover").focus();
                //             $('#'+baris).hide();
                //             $('#labelpwd').html('Pengguna tidak ditemukan...');
                //         }
        		// 	}
                // });
            });
            $('#verifyApprovalQty').on("click",function(){
                var user = $("#userApprover").val();
                var pw = $("#passApprover").val();
                var baris = $("#chartID").val();

                $('#'+baris).prop('readonly', false);
                $("#approvalKasir").modal('hide');
                $("#passApprover").val('');
                $('#labelpwd').html('');

                
                // $.ajax({
        		// 	method : "POST",
        		// 	url : verifyApproval,
        		// 	dataType : 'json',
        		// 	data : {user : user, pw : pw},
        		// 	success : function(response){		
				// 	    var setuju = response.setuju;
				// 	    if (setuju==1){
                //             $('#'+baris).prop('readonly', false);
                //             $("#approvalKasir").modal('hide');
                //             $("#passApprover").val('');
                //             $('#labelpwd').html('');
                //         }else {
                //             $("#passApprover").val('');
                //             $("#passApprover").focus();
                //             $('#'+baris).prop('readonly', true);
                //             $('#labelpwd').html('Pengguna tidak ditemukan...');
                //         }
        		// 	}
                // });
            });
            <?php if (($idUser==6 || ($idUser==40)) && $kategori_member==2){ ?> 
                $("#pendingTrx").hide();
                $("#cancelButton").hide();
            <?php } ?>
            
            //
            $("#pendingTrx").hide();
            $("#cancelButton").hide();
            
            $('#labelvrf').html('');
            $('#lockAll').on("click",function(){
                $("#pendingTrx").hide();
                $("#cancelButton").hide();
                $('.hapusCart').hide();
                $("#passApprover").val('');
                $("#approvalKasir").modal('hide');
                $(".jumlahBeli").prop('readonly', true);
                
                $('#labelvrf').html('');
            });
            $('#diskon').on("change",function(){
                var diskon      = $('#diskon').prop('readonly', true);
            });
            
            $("#button_login").hide();

            <?php 
            // $arUsr = array(45,44,43,40,47,46);
            //if(in_array($idUser,$arUsr)){?>

            <?php //}else{ ?>
                $("#verifyApproval").hide();
                $("#verifyApprovalDelete").hide();
                $("#verifyApprovalQty").hide();
                $("#verifyApprovalPending").hide();
                $("#verifyApprovalCancel").hide();
            <?php //} ?>
            
			function login_selectuser(device_name, sn) {
                var element = $("#select_scan").find('option:selected'); 
                var username = element.attr("username"); 

                // new fingerprint
                var webhook = encodeURIComponent(element.attr("webhook_new").trim());
                var user = encodeURIComponent(element.attr("user_new").trim());
                var fingerprint = encodeURIComponent(element.attr("fingerprint").trim());

				$("#button_login").attr("href","finspot:FingerspotVer;"+$('#select_scan').val());
                $("#button_login_new").attr("href","fpsolusi://identify?webhook="+webhook+"&username="+user+'&fingerprint='+fingerprint).get(0).click();
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
                                                $("#verifyApproval").show();
                                                $("#verifyApprovalDelete").show();
                                                $("#verifyApprovalQty").show();
                                                $("#verifyApprovalPending").show();
                                                $("#verifyApprovalCancel").show();
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
            $('#button_login').on("click",function(){
               //if(username=='') return false;
               
            });
            // MULAI BSI DISKON
            $('#nama_penerima').on("keyup",function(){
                this.value = this.value.replace(/\D/g,'');
            });

            $('#btnCekBSI').on("click", function(){
                nama_penerima = $('#nama_penerima').val();
                var urlCekBSI = "<?php echo base_url('penjualan/cek_BSI'); ?>";

                if(nama_penerima.length<16 || nama_penerima.length>16) {
                    alert('Nomor kartu 16 digit');
                    return false;
                }

               $.ajax({
                            type        : "POST",
                            url         : urlGrandTotal,
                            success     : function(totalPurchase){
                                            var grandTotal = totalPurchase.split('.').join("");

                                            if(grandTotal>=750000){
                                                $.ajax({
                                                    method : "POST",
                                                    url : urlCekBSI,
                                                    data : {nama_penerima:nama_penerima},
                                                    success : function(response2){
                                                        if(response2==0){
                                                            var diskon      = 50000;
                                                            var urlDiskon   = "<?php echo base_url('penjualan/insertDiskon'); ?>";

                                                            $.post(urlDiskon,{diskon : diskon},function(){
                                                                viewPricePanel();
                                                            });
                                                            $("#bsiModal").modal('hide');
                                                        }else{
                                                            alert('Kartu sudah terpakai hari ini');
                                                        }
                                                        
                                                    }
                                                });
                                            }else{
                                                alert('Cek nominal belanja.');
                                            }
                            }
               });
            });

            

        </script>
    </body>
</html>
