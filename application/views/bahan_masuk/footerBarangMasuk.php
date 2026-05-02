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
            var urlDetailOrder = "<?php echo base_url('bahan_masuk/detailOrder'); ?>";
            var urlInvoiceReceive = "<?php echo base_url('bahan_masuk/invoiceReceive'); ?>";
            var urlRiwayatPenerimaan = "<?php echo base_url('bahan_masuk/riwayatPenerimaan'); ?>";
            var diskon1 = 0; var diskon2 = 0; var diskon3 = 0;
            var totalDiskon=0;var hargaBeli = 0;
            function editHarga(idbaris){
                var hpp = $('#hrgProduk'+idbaris).val();
                var qty = $('#qtyProduk'+idbaris).val();
                var sku = $('#qtyProduk'+idbaris).data('id');
                var no_po = "<?php echo $_GET['no_po']; ?>";

                var urlUpdate = "<?php echo base_url('bahan_masuk/updateQtyCart'); ?>";

                $.ajax({
                    method      : 'POST',
                    url         : urlUpdate,
                    data        : {sku : sku,qty:qty,hpp:hpp,no_po:no_po} 
                });

                var tot = 0;var jml=0;var urut=0;
                $('#qtyProduk'+idbaris).data('price',hpp);
                var totalHarga = 0; totalDiskon = 0;var ppn=0;
                  $( ".harga" ).each(function() {
                    urut = $( this ).data('urut');
                    jml = $('#qtyProduk'+urut).val();
                    
                    hargaBeli = Number(jml)*Number($( this ).val())
                    
                    diskon3 = Number($('#diskon3'+urut).val())*jml;
                    
                    diskon1 = Number($('#diskon1'+urut).val())/100*(hargaBeli);
                    diskon2 = Number($('#diskon2'+urut).val())/100*(hargaBeli-diskon1);
                    totalDiskon = Number(totalDiskon) + diskon1 + diskon2 + diskon3;
                    
                    subtotal = hargaBeli-(diskon1 + diskon2 + diskon3);
                    
                    totalHarga = Number(totalHarga)+(subtotal);
                    //alert(totalHarga);
                    $("#subTotal"+urut).html(subtotal.toFixed(2));
                  });
                var diskonx = Number($("#diskon").val());
                totalHarga = Number(totalHarga) - diskonx;
                
                ppn = $('#PPN').val();
                if(ppn=='1'){
                        totalHarga = totalHarga+(0.11*totalHarga);
                    }
                $("#stTotal").html(totalHarga.toFixed(2));
                $("#stDiskon").html(totalDiskon.toFixed(2));
                hitungMargin(idbaris);
                return false;
            }
            editHarga();
            function hitungMargin(urut){
                var margin = 0;
                margin = ((Number($('#hrgProdukJual'+urut).val())-Number($('#hrgProduk'+urut).val()))/Number($('#hrgProdukJual'+urut).val())*100).toFixed(2);
                $('#margin'+urut).val(margin);
            }
            function hargaJual(urut){
                var harga1 = 0;
                var margin = Number($('#margin'+urut).val());
                harga1 = $('#hrgProduk'+urut).val()/((100-margin)/100);
                $('#hrgProdukJual'+urut).val((harga1).toFixed(2));
            }
            function editHarga2(diskon){
                var tot2 = 0;var jml2=0;var urut2=0;
                var totalHarga2 = 0; totalDiskon = 0;
                  $( ".harga" ).each(function() {
                    urut2 = $( this ).data('urut');
                    jml2 = $('#qtyProduk'+urut2).val();
                    hargaBeli = Number(jml2)*Number($( this ).val())
                    diskon1 = Number($('#diskon1'+urut2).val())/100*hargaBeli;
                    diskon2 = Number($('#diskon2'+urut2).val())/100*(hargaBeli-diskon1);
                    
                    diskon3 = Number($('#diskon3'+urut2).val())*jml2;
                    
                    totalDiskon = Number(totalDiskon) + diskon1 + diskon2 + diskon3;
                    totalHarga2 = Number(totalHarga2)+(hargaBeli-(diskon1 + diskon2 + diskon3));
                    $("#subTotal"+urut2).html(hargaBeli-(diskon1 + diskon2 + diskon3).toFixed(2));
                    //alert(totalHarga3);
                  });
                totalHarga2 = Number(totalHarga2) - Number(diskon);
                $("#stTotal").html(totalHarga2.toFixed(2));
                $("#stDiskon").html(totalDiskon.toFixed(2));
            }
            function editHarga3(){
                var tot3 = 0;var jml3=0;var urut3=0;
                var totalHarga3 = 0;
                  $( ".harga" ).each(function() {
                    urut3 = $( this ).data('urut');
                    jml3 = $('#qtyProduk'+urut3).val();
                    totalHarga3 = Number(totalHarga3)+(Number(jml3)*Number($( this ).val()));
                    //alert(totalHarga3);
                  });
                var diskonx3 = Number($("#diskon").val());
                totalHarga3 = Number(totalHarga3) - diskonx3;
                
                $("#stTotal").html(totalHarga3);
            }
            $(document).ready(function(){
                var nopo = "<?php echo $_GET['no_po']; ?>";

                $('#detailOrder').load(urlDetailOrder,{noPo : nopo});
                $('#invoiceReceive').load(urlInvoiceReceive,{noPo : nopo});
                $('#riwayatPenerimaan').load(urlRiwayatPenerimaan,{noPo : nopo});

                $('#sku').select2({
                    placeholder: "Pilih Data Produk",
                    ajax: {
                        url         : '<?php echo base_url('bahan_masuk/ajax_produk'); ?>',
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
                $('#sku').change(function(){
                    var idProduk = $(this).val();

                    var urlInsert = "<?php echo base_url('bahan_masuk/insertReceiveItem'); ?>";

                    $.ajax({
                        method      : 'POST',
                        url         : urlInsert,
                        data        : {idProduk : idProduk,nopo:nopo},
                        success     : function(response){
                                        if(response == 0){
                                            $.Notification.notify('error','top right', 'Ditolak', 'Produk sudah ada di daftar PO');
                                            $("#sku").select2("val","");
                                        } else {
                                            // window.location = '<?php //echo base_url('bahan_masuk/good_receipt?no_po=')?>'+nopo;
                                            $('#addAble').prepend(response);
                                        }
                                    } 
                    });
                    });
            });

            // Select2
            jQuery(".select2").select2({
                width: '100%'
            });
            $('#submitPenerimaan').on("click",function(){
                var diterimaOleh = $('#diterimaOleh').val();
                var diperiksaOleh = $('#diperiksaOleh').val();
                var tanggalTerima = $('#tanggalTerima').val();
                var diterimaDi = $('#diterimaDi').val();
                var PPN = $('#PPN').val();
                var diskon = $('#diskon').val();
                var noPo = $('#noPo').val();
                var idSupplier = $('#idSupplier').val();

                var status_receive = $('#status_receive').val();
                var keterangan_receive = $('#keterangan_receive').val();

                jsonObj = [];

                $('input[name=qty]').each(function(){
                    var qty = $(this).val();
                    var sku = $(this).data('id');
                    var urut = $(this).data('urut');
                    var harga = $('#hrgProduk'+urut).val();
                    var hargajual = $('#hrgProdukJual'+urut).val();
                    var bonus = $('#bonus'+urut).val();
                    var diskon1 = $('#diskon1'+urut).val();
                    var diskon2 = $('#diskon2'+urut).val();
                    var diskon3 = $('#diskon3'+urut).val();
                    item = {};

                    item['sku']     = sku;
                    item['qty']     = qty;
                    item['harga']   = harga;
                    item['hargajual']   = hargajual;
                    item['bonus']   = bonus;
                    item['diskon1']   = diskon1;
                    item['diskon2']   = diskon2;
                    item['diskon3']   = diskon3;

                    jsonObj.push(item);
                });

                if(diterimaOleh=='' || diperiksaOleh=='' || diterimaDi==''){
                    if(diterimaOleh==''){
                        $('#diterimaAlert').text("**Harap Isi Form Berikut");
                    }

                    if(diperiksaOleh==''){
                        $('#diperiksaAlert').text("**Harap Isi Form Berikut");
                    }

                    if(diterimaDi==''){
                        $('#diterimaDiAlert').text("**Harap Pilih Salah Satu");
                    }
                } else {
                    $.ajax({
                                    method  : "POST",
                                    url     : "<?php echo base_url('bahan_masuk/proses_receive_item'); ?>",
                                    data    : {produkItem : JSON.stringify(jsonObj), PPN:PPN,status_receive : status_receive, keterangan_receive : keterangan_receive, diterimaOleh : diterimaOleh, diperiksaOleh : diperiksaOleh, tanggalTerima : tanggalTerima,diterimaDi : diterimaDi, noPo : noPo, idSupplier : idSupplier,diskon:diskon},
                                    beforeSend : function(){
                                                    $('#submitPenerimaan').text('Harap Tunggu...');
                                                    $('#submitPenerimaan').prop('disabled',true);
                                                 },
                                    success : function(response){
                                                $('#detailOrder').load(urlDetailOrder,{noPo : noPo});
                                                $('#invoiceReceive').load(urlInvoiceReceive,{noPo : noPo});
                                                $('#riwayatPenerimaan').load(urlRiwayatPenerimaan,{noPo : noPo});

                                                $('#submitPenerimaan').text('Submit');
                                                $('#submitPenerimaan').prop('disabled',false);

                                                $('.qtyAjax').val('');
                                                $('#diterimaOleh').val('');
                                                $('#diperiksaOleh').val('');
                                                $('#diskon').val('');
                                                $('#diterimaDi').select2("val","");

                                                var urlRedirect = "<?php echo base_url('bahan_masuk/invoice_receive?no_receive='); ?>"+response;
                                                window.open(urlRedirect,"_blank");
                                              },
                    });
                }
            });

            // $('.qtyAjax').on("change",function(){
            //     var urlCekPenerimaan = "<?php echo base_url('bahan_masuk/qtyReceived'); ?>";
            //     var noPo = "<?php echo $_GET['no_po']; ?>";
            //     var idProduk = $(this).data('id');
            //     var max = $(this).data('max');
            //     var qty = $(this).val();
            //     var urut = $(this).data('urut');

            //     //cek penerimaan
            //     $.ajax({
            //                 method      : "POST",
            //                 url         : urlCekPenerimaan,
            //                 data        : {idProduk : idProduk, noPo : noPo},
            //                 success     : function(response){
            //                                 var orderAllow = max-response;
            //                                 if(qty > orderAllow){
            //                                     $('#qtyProduk'+urut).val(0);
            //                                     $.Notification.notify('error','top right', 'Ditolak', 'Nilai yang diinput melebihi jumlah order'); 
            //                                 }
            //                               }
            //     });
            // });
            $("#btn-diskon1").on("click",function(){
                var set = $('#setDiskon1').val();
                $(".diskon1").each(function( index, element ) {
                    $(element).val(set);
                });
                setTimeout(function(){editHarga();},2000);
            });
            $("#btn-diskon2").on("click",function(){
                var set = $('#setDiskon2').val();
                $(".diskon2").each(function( index, element ) {
                    $(element).val(set);
                });
                setTimeout(function(){editHarga();},2000);
            });
            $("#btn-diskon3").on("click",function(){
                var set = $('#setDiskon3').val();
                $(".diskon3").each(function( index, element ) {
                    $(element).val(set);
                });
                setTimeout(function(){editHarga();},2000);
            });
        </script>
    </body>
</html>
