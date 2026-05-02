            <!-- Footer Ends -->
        </section>
        <!-- Main Content Ends -->
        
        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/scanner/jquery.scannerdetection.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.scrollTo.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/js/jquery.nicescroll.js" type="text/javascript"></script>

        <!-- sweet alerts -->
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/sweet-alert/sweet-alert.init.js"></script>


        <!-- Dashboard -->

        <!-- Todo -->
        <script src="<?php echo base_url('assets'); ?>/js/jquery.todo.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify.min.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notify-metro.js"></script>
        <script src="<?php echo base_url('assets'); ?>/assets/notifications/notifications.js"></script>

        <script type="text/javascript">
            var noCart = "<?php echo $idPending; ?>";

            var totalPurchaseUrl = "<?php echo base_url('penjualan/totalPurchasePending'); ?>";
            var diskonPeritemUrl = "<?php echo base_url('penjualan/diskonPeritemPanelPending'); ?>";
            var urlDiskonDisplay = "<?php echo base_url('penjualan/diskonMemberDisplayPending'); ?>";
            var urlMemberPoin    = "<?php echo base_url('penjualan/data_customer_poinPending'); ?>";
            var urlNilaiReimburs = "<?php echo base_url('penjualan/viewNilaiReimbursPending'); ?>";
            var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkirPending'); ?>";
            var urlViewDiskon = "<?php echo base_url('penjualan/viewDiskonPending'); ?>";
            var urlGrandTotal = "<?php echo base_url('penjualan/viewGrandTotalPending'); ?>";

            jQuery(document).ready(function($) {
                jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd"
                });

                var dataUrl = "<?php echo base_url('penjualan/viewCartPending'); ?>";
                $('#data-input').load(dataUrl,{noCart : noCart});

                //price right panel
                $('#total_purchase').load(totalPurchaseUrl,{noCart : noCart});

                //diskon peritem
                $('#diskonPeritem').load(diskonPeritemUrl,{noCart : noCart});

                //diskon member
                $('#diskonMember').load(urlDiskonDisplay,{noCart : noCart});

                //diskon member
                $('#data-customer').load(urlMemberPoin,{noCart : noCart});

                //poin reimburs
                $('#poin-value-reimburs').load(urlNilaiReimburs,{noCart : noCart});

                //view ongkir
                $('#ongkirText').load(urlViewOngkir,{noCart : noCart});

                //view diskon
                $('#diskon_promosi').load(urlViewDiskon,{noCart : noCart});

                //grand total
                $('#grand_total').load(urlGrandTotal,{noCart : noCart});

                $(function() {  
                    $("#tableNiceScroll").niceScroll({cursorcolor:"#00F"});
                });
            });

           

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
                                            var qty     = 1;

                                            if(parseInt(stok) > 0){
                                                var urlCart = "<?php echo base_url('penjualan/insertCartPending'); ?>";
                                                
                                                $.post(urlCart,{noCart : noCart, sku : sku, harga : harga, stok : stok, qty : qty},function(hasil){

                                                    if(hasil=='0'){
                                                        alert("Stok Tidak Mencukupi");
                                                    } else {
                                                        var dataUrl = "<?php echo base_url('penjualan/viewCartPending'); ?>";
                                                        $('#data-input').load(dataUrl,{noCart : noCart});
                                                        viewPricePanel();
                                                        
                                                    }
                                                });

                                            } else {
                                                alert("Stok Tidak Mencukupi");
                                            }
                                            
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
                var totalPurchaseUrl = "<?php echo base_url('penjualan/totalPurchasePending'); ?>";
                var diskonPeritemUrl = "<?php echo base_url('penjualan/diskonPeritemPanelPending'); ?>";
                var urlDiskonDisplay = "<?php echo base_url('penjualan/diskonMemberDisplayPending'); ?>";
                var urlMemberPoin    = "<?php echo base_url('penjualan/data_customer_poinPending'); ?>";
                var urlNilaiReimburs = "<?php echo base_url('penjualan/viewNilaiReimbursPending'); ?>";
                var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkirPending'); ?>";
                var urlViewDiskon = "<?php echo base_url('penjualan/viewDiskonPending'); ?>";
                var urlGrandTotal = "<?php echo base_url('penjualan/viewGrandTotalPending'); ?>";

                //price right panel
                $('#total_purchase').load(totalPurchaseUrl,{noCart : noCart});

                //diskon peritem
                $('#diskonPeritem').load(diskonPeritemUrl,{noCart : noCart});

                //diskon member
                $('#diskonMember').load(urlDiskonDisplay,{noCart : noCart});

                //diskon member
                $('#data-customer').load(urlMemberPoin,{noCart : noCart});

                //poin reimburs
                $('#poin-value-reimburs').load(urlNilaiReimburs,{noCart : noCart});

                //view ongkir
                $('#ongkirText').load(urlViewOngkir,{noCart : noCart});

                //view diskon
                $('#diskon_promosi').load(urlViewDiskon,{noCart : noCart});

                //grand total
                $('#grand_total').load(urlGrandTotal,{noCart : noCart});
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
                                            var qty     = 1;

                                            if(parseInt(stok) > 0){
                                                var urlCart = "<?php echo base_url('penjualan/insertCartPending'); ?>";
                                                
                                                $.post(urlCart,{noCart : noCart, sku : sku, harga : harga, stok : stok, qty : qty},function(hasil){

                                                    if(hasil=='0'){
                                                        alert("Stok Tidak Mencukupi");
                                                    } else {
                                                        var dataUrl = "<?php echo base_url('penjualan/viewCartPending'); ?>";
                                                        $('#data-input').load(dataUrl,{noCart : noCart});
                                                        viewPricePanel();
                                                    }
                                                });

                                            } else {
                                                alert("Stok Tidak Mencukupi");
                                            }
                                            
                                        });

                                        $('#produk-ajax').select2("val","");
                                      }   
                });
            });
           
            $('#ongkir').on("keyup",function(){
                var ongkir = $(this).val();
                var urlOngkir = "<?php echo base_url('penjualan/insertOngkirPending'); ?>";

                $.post(urlOngkir,{ongkir : ongkir, noCart : noCart},function(){
                    var urlViewOngkir = "<?php echo base_url('penjualan/viewOngkirPending'); ?>";
                    $('#ongkirText').load(urlViewOngkir,{noCart : noCart});
                    viewPricePanel();
                });
            });

            $('#diskon').on("keyup",function(){
                var diskon      = $('#diskon').val();
                var urlDiskon   = "<?php echo base_url('penjualan/insertDiskonPending'); ?>";

                $.post(urlDiskon,{diskon : diskon,noCart : noCart},function(){
                    var urlViewDiskon = "<?php echo base_url('penjualan/viewDiskonPending'); ?>";
                    $('#diskon_promosi').load(urlViewDiskon,{noCart : noCart});
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
                                        var urlDiskon = "<?php echo base_url('penjualan/saveDiskonMemberPending'); ?>";
                                        $.post(urlDiskon,{noCart : noCart, totalDiskon : totalDiskon, idCustomer : id_customer},function(){
                                            //
                                            var urlDiskonDisplay = "<?php echo base_url('penjualan/diskonMemberDisplay'); ?>";
                                            $('#diskonMember').load(urlDiskonDisplay,{noCart : noCart});
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
                            data : {noCart : noCart},
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

                sub_account = "<?php echo base_url('penjualan/sub_account'); ?>";
                tempo_form  = "<?php echo base_url('penjualan/tempo_form'); ?>";
                
                if(type != 5){
                    $('#tempo-place').load(sub_account,{id : type});
                } else {
                    $('#tempo-place').load(tempo_form);
                }
                
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
                var noCart = "<?php echo $this->input->get("idPending"); ?>";
                var type_bayar = $('#type_bayar').val();
                var keterangan = $('#keterangan').val();
                var jumlah_bayar = $('#jumlah_bayar').val();
                var subAccount = $('#subAccount').val();
                var jatuh_tempo = $('#jatuhTempo').val();

                //opsi pengiriman
                var namaPenerima = $('#namaPenerima').val();
                var noHPPenerima = $('#kontakPenerima').val();
                var ekspedisi = $('#ekspedisi').val();
                var alamatPenerima = $('#alamatPenerima').val();
                var provinsi = $('#provinsiPenerima').val();
                var kabupaten = $('#kabupatenPenerima').val();
                var kecamatan = $('#kecamatanPenerima').val();                

                var urlPenjualan = "<?php echo base_url('penjualan/penjualanSqlPending'); ?>";

                $.ajax({
                            method: "POST",
                            url: urlPenjualan,
                            data: {noCart : noCart, type_bayar : type_bayar, keterangan : keterangan, jumlah_bayar : jumlah_bayar, sub_account : subAccount, jatuh_tempo : jatuh_tempo, namaPenerima : namaPenerima, noHPPenerima : noHPPenerima, ekspedisi : ekspedisi, alamatPenerima : alamatPenerima, provinsi : provinsi, kabupaten : kabupaten, kecamatan : kecamatan},
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
                var diskonMember     = $('#diskon').val();
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

        </script>
    </body>
</html>
