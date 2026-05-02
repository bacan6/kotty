<div id="payment_total_notif" style="width: 350px;position: fixed;z-index: 1;right: 8px;top:8px;display: none;" >
    <div class="alert alert-danger" style="opacity: 0.9;">
        <table width="100%" style="font-size: 18px;">
            <tr>
                <td width="50%">Total Belanja</td>
                <td align="right" id="total_belanja_notif"></td>
            </tr>

            <tr>
                <td width="50%">Jumlah Bayar</td>
                <td align="right" id="jumlah_bayar_notif"></td>
            </tr>

            <tr>
                <td width="50%">Kembali</td>
                <td align="right" id="kembali_notif"></td>
            </tr>
        </table>
    </div>
</div>


<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <form id="submit-penjualan" method="post">	
                    <div class="row">
                        <div class="col-md-12" align="right">
                            <a href="<?php echo base_url('penjualan/data_penjualan'); ?>"> <i class="fa fa-book"></i> Data Penjualan </a> | <a href="<?php echo base_url('penjualan/reservasi'); ?>"> <i class="fa fa-history"></i> Reservasi </a> | | <a href="<?php echo base_url('penjualan/daftar_tunggu'); ?>"> <i class="fa fa-clock-o"></i> Daftar Pending </a>
                        </div>

                        <div class="col-md-12" style="text-align: right;margin-top: 30px;">
                            <button id="button-submit" type="submit" class="btn btn-icon btn-primary m-b-5" onclick="submitForm('<?php echo base_url('penjualan/penjualan_sql'); ?>')"> <i class=" fa fa-check-square-o"></i> Submit</button>

                            <button type="submit" class="btn btn-icon btn-primary m-b-5" onclick="submitForm('<?php echo base_url('penjualan/penjualan_tunggu_sql'); ?>')"> <i class=" fa fa-clock-o"></i> Tunggu</button>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px;">
                		<div class="col-md-8">                			
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                        				<input type="hidden" id="produk-ajax" name="customer" style="width: 100%;">
                        			</div>

                        			<div class="form-group" style="margin-top: 20px;">
                        				<table class="table table-striped" style="font-size: 13px;">
                        					<thead>
            	            					<tr style="background: #2A303A;color:white;font-weight: bold;">
            	            						<td width="10%">SKU</td>
            	            						<td>Nama Produk</td>
            	            						<td width="13%" align="right">Harga Jual</td>
            	            						<td width="13%" align="center">Qty</td>
            	            						<td width="13%" align="right">Total Harga</td>
                                                    <td width="13%" align="right">Discount</td>
                                                    <td width="13%" align="right">Grand Total</td>
            	            						<td width="3%"></td>
            	            					</tr>
                        					</thead>
                        					<tbody id="data-input">
                                                <?php
                                                        $no = 1;
                                                        foreach($data_produk as $row){
                                                    ?>
                        						<tr id="form-input<?php echo $no; ?>">
                                                    <td><?php echo $row->id_produk; ?></td>
                                                    <td><?php echo $row->nama_produk; ?></td>
                                                    <td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
                                                    <td align="right">
                                                        <?php
                                                            $max = $this->model1->data_stok_toko($row->id_produk,$id_store);
                                                        ?>
                                                        <input type="number" name="jumlah_beli[]" class="form-control qty_beli<?php echo $no; ?>" min="0" max="<?php echo $max; ?>" id="jumlah_beli" value="<?php echo $row->qty?>" required/>
                                                        <input type="hidden" name="sku[]" value="<?php echo $row->id_produk; ?>"/> 
                                                        <input type="hidden" name="harga[]" value="<?php echo $row->harga; ?>"/> 
                                                        <input type="hidden" name="hpp[]" value="<?php echo $row->hpp; ?>"/>
                                                        <input type="hidden" name="id_produk" class="sku<?php echo $no; ?>" value="<?php echo $row->id_produk; ?>"/>
                                                        <input type="hidden" class="total_beli_hidden" id="total_beli_hidden<?php echo $no; ?>" value="<?php echo $row->qty*$row->harga; ?>">
                                                        <input type="hidden" class="harga_beli<?php echo $no; ?>" value="<?php echo $row->harga;  ?>"/>
                                                        <input type="hidden" id="total_beli_kategori<?php echo $no; ?>" class="id_kategori[]" data-id_kategori="<?php echo $row->id_kategori; ?>" data-value_kategori=""/>
                                                    </td>
                                                    <td align="right" id="total_harga<?php echo $no; ?>"></td>
                                                    <td align="right">
                                                        <input type="number" class="total_diskon_hidden form-control" id="total_diskon_hidden<?php echo $no; ?>" name="diskon_item[]" required>
                                                    </td>
                                                    <td align="right" id="grandTotal<?php echo $no; ?>"></td>
                                                    <td><a class="hapus-form" id="<?php echo $no; ?>"><i class="fa fa-trash"></i></a></td>
                                                </tr>

                                                <?php $no++; } ?>
                                                <input type="hidden" id="sdf" value="<?php echo $no; ?>">
                        					</tbody>
                        				</table>
                        			</div>
                                </div>
                            </div>

                            <!--<div class="row" style="position:fixed;bottom:10px;">
                              <a href="#myModal" data-toggle="modal" class="btn btn-rounded m-b-5" style="background: #ddd;"><i class="fa fa-gift"></i> Free Item</a>
                              <a class="btn btn-rounded m-b-5" style="background: #ddd;"><i class="fa fa-credit-card"></i> Voucher</a>
                              <a class="btn btn-rounded m-b-5" style="background: #ddd;"><i class="fa fa-bank"></i> Tax</a>
                            </div>-->

                		</div>

                		<div class="col-md-4" style="border-left:solid 0.1px #ccc;padding-left: 15px;">
                			<div class="row">
                				<div class="col-md-12" style="border-bottom: solid 0.1px #ccc;">
                					<table class="table" style="font-size: 14px;">
                						<tr>
                							<td width="50%" style="font-weight: bold;color:#25aff4;"><i class="fa fa-crosshairs"></i> Subtotal</td>
                							<td id="total_purchase" align="right" style="font-weight: bold;color:#25aff4;"></td>
                						</tr>
                						<tr>
                							<td><i class="fa fa-car"></i> Ongkir</td>
                							<td id="ongkir_text" align="right"></td>
                						</tr>
                						<tr>
                							<td><i class="fa fa-money"></i> Diskon Channel</td>
                							<td id="diskon_text" align="right"></td>
                						</tr>
                                        <tr>
                                            <td><i class="fa fa-bullhorn"></i> Diskon Promosi</td>
                                            <td id="diskon_promosi" align="right"></td>
                                        </tr>

                                        <tr>
                                            <td><i class="fa fa-bullhorn"></i> Diskon Otomatis</td>
                                            <td id="diskon_otomatis" align="right"></td>
                                        </tr>
                                        <tr>
                                            <td><i class='fa fa-tree'></i> Poin Reimbursment</td>
                                            <td id="poin-value-reimburs" align="right"></td>
                                        </tr>
                						<tr>
                							<td style="color:#25aff4;"><i class='fa fa-bank'></i> <b>TOTAL</b></td>
                							<td id="grand_total" align="right" style="font-weight: bold;"></td>
                						</tr>
                					</table> 
                				</div>
                			</div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user-plus"></i></span>
                                            <input type="hidden" id="customer-form" name="customer" style="width: 100%;">
                                        </div>
                                    </div>

                                    <div class="form-group" id="data-customer">

                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                            <input type="text" id="ongkir" name="ongkir" class="form-control" placeholder="Ongkir">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-minus"></i></span>
                                            <input type="text" class="form-control" placeholder="Diskon" id="diskon">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            <input type="text" class="form-control" placeholder="Jumlah Bayar" id="jumlah_bayar" name="jumlah_bayar" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            <select class="form-control" name="type_bayar" id="type_bayar">
                                                <?php
                                                    foreach($payment_type->result() as $pt){
                                                ?>
                                                    <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="tempo-place">
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                            <textarea class="form-control" placeholder="Keterangan" name="keterangan"></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group" align="right">
                                        <input type="hidden" id="total_purchase_temp" name="total_purchase" value="0"/>
                                        <input type="hidden" id="diskon_temp" name="diskon" value="0"/>
                                        <input type="hidden" id="ongkir_temp" value="0"/>
                                        <input type="hidden" id="diskon_promosi_temp" name="diskon_promosi_temp" value="0"/>
                                        <input type="hidden" id="diskon_otomatis_temp" name="diskon_otomatis_temp" value="0"/>
                                        <input type="hidden" id="poin_temp" name="poin_temp" value="0"/>
                                    </div>
                                </div>
                            </div>
                		</div>
                	</div>
                </form>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<div id="myModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Free Item</h4>
            </div>                             
            <div class="modal-body" id="free-item-form">
                <div class="form-group">
                    <input type="hidden" id="mySelect2" name="free-item" style="width: 100%;" />
                </div>                                   
            </div>                                    
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


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
               data_form = "<?php echo base_url('penjualan/data_form'); ?>";
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
                id_customer = $('#customer-form').val();
                $.ajax({
                            type    : 'POST',
                            url     : "<?php echo base_url('penjualan/get_diskon_customer'); ?>",
                            data    : {id : id_customer},
                            success : function(diskon){
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
                                      }
                      });

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

                //SET DISKON
                id_customer = $('#customer-form').val();

                $.ajax({
                            type    : 'POST',
                            url     : "<?php echo base_url('penjualan/get_diskon_customer'); ?>",
                            data    : {id : id_customer},
                            success : function(diskon){
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
                                      }
                      });
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

                $('#payment_total_notif').css("display","");    

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

            function submitForm(action) {
                var form = document.getElementById('submit-penjualan');
                form.action = action;
                form.submit();
            }

        </script>

<?php
    $xo = 1;
    foreach($data_produk as $row){
?>
                                               <script type="text/javascript">
                                                        $('.hapus-form').on("click",function(){
                                                            id = this.id;
                                                            $('#form-input'+id).remove();
                                                        });  

                                                        function formatAngka(angka) {
                                                             if (typeof(angka) != 'string') angka = angka.toString();
                                                             var reg = new RegExp('([0-9]+)([0-9]{3})');
                                                             while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
                                                             return angka;
                                                        }

                                                        $('.qty_beli<?php echo $xo; ?>').on("change",function(){
                                                            jumlah_beli = $('.qty_beli<?php echo $xo; ?>').val();
                                                            harga       = $('.harga_beli<?php echo $xo; ?>').val();

                                                            total_harga = jumlah_beli*harga;

                                                            $('#total_beli_hidden<?php echo $xo; ?>').val(total_harga);
                                                            $('#total_beli_kategori<?php echo $xo; ?>').data('value_kategori',total_harga);

                                                            $('#total_harga<?php echo $xo; ?>').text(formatAngka(total_harga));


                                                            var sum = 0;

                                                           $("input[class *= 'total_beli_hidden']").each(function(){
                                                                sum += +$(this).val();
                                                           });
                                                     
                                                            $("#total_purchase").text(formatAngka(sum));

                                                            //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
                                                            $("#total_purchase_temp").val(sum);
                                                            

                                                            //cek discount
                                                            sku = $('.sku<?php echo $xo; ?>').val();

                                                            $.ajax({
                                                                    type    : "POST",
                                                                    url     : "<?php echo base_url('penjualan/cek_diskon'); ?>",
                                                                    data    : {sku : sku},
                                                                    success : function(result){
                                                                                if(result==1){
                                                                                    $.ajax({
                                                                                        type    : "POST",
                                                                                        url     : "<?php echo base_url('penjualan/ambil_nilai_diskon'); ?>",
                                                                                        data    : {sku : sku, qty : jumlah_beli},
                                                                                        success : function(diskon){

                                                                                            if(diskon > 0){
                                                                                                nilai_diskon = diskon;
                                                                                            } else {
                                                                                                nilai_diskon = 0;
                                                                                            }

                                                                                            $('#discount<?php echo $xo; ?>').text(formatAngka(nilai_diskon*jumlah_beli));
                                                                                            $('#grandTotal<?php echo $xo; ?>').text(formatAngka(total_harga-(nilai_diskon*jumlah_beli)));
                                                                                            $('#total_diskon_hidden<?php echo $xo; ?>').val(nilai_diskon*jumlah_beli);

                                                                                            var diskon_otomatis = 0;

                                                                                            $("input[class *= 'total_diskon_hidden']").each(function(){
                                                                                                diskon_otomatis += +$(this).val();
                                                                                            });
                                                                 
                                                                                            $("#diskon_otomatis").text(formatAngka(diskon_otomatis));

                                                                                            //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
                                                                                            simpan_diskon(diskon_otomatis);

                                                                                            diskon          = $('#diskon_temp').val();
                                                                                            sum             = $('#total_purchase_temp').val();
                                                                                            diskon_promosi  = $('#diskon_promosi_temp').val();
                                                                                            poin            = $('#poin_temp').val();
                                                                                            ongkir          = $('#ongkir_temp').val();
                                                                                            diskon_otomatis = $('#diskon_otomatis_temp').val();

                                                                                            grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                                                                                            $('#grand_total').text(formatAngka(grand_total));
                                                                                            $('#total_belanja_notif').text(formatAngka(grand_total));

                                                                                        }
                                                                                    });
                                                                                } 
                                                                              }
                                                            });
                                                        });

                                                        $('#total_diskon_hidden<?php echo $xo; ?>').on("change",function(){
                                                            //ambil nilai quantity dan harga 
                                                            jumlah_beli = $('.qty_beli<?php echo $xo; ?>').val();
                                                            harga       = $('.harga_beli<?php echo $xo; ?>').val();
                                                            
                                                            total_harga = jumlah_beli*harga;

                                                            //ambil nilai diskon
                                                            diskon      = $('#total_diskon_hidden<?php echo $xo; ?>').val();

                                                            //tampilkan di harga total
                                                            $('#grandTotal<?php echo $xo; ?>').text(formatAngka(total_harga-diskon));

                                                            var diskon_otomatis = 0;

                                                            $("input[class *= 'total_diskon_hidden']").each(function(){
                                                                diskon_otomatis += +$(this).val();
                                                            });
                                                                 
                                                            $("#diskon_otomatis").text(formatAngka(diskon_otomatis));

                                                            //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
                                                            simpan_diskon(diskon_otomatis);

                                                            diskon          = $('#diskon_temp').val();
                                                            sum             = $('#total_purchase_temp').val();
                                                            diskon_promosi  = $('#diskon_promosi_temp').val();
                                                            poin            = $('#poin_temp').val();
                                                            ongkir          = $('#ongkir_temp').val();
                                                            diskon_otomatis = $('#diskon_otomatis_temp').val();

                                                            grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

                                                            $('#grand_total').text(formatAngka(grand_total));
                                                            $('#total_belanja_notif').text(formatAngka(grand_total));
                                                        });
                                                    </script>
<?php
    $xo++; }
?>

</body>
</html>
