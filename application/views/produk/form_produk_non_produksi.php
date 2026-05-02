<div class="row">
            		<div class="col-md-12" id="form-tambah-produk">
                        <form>
                        <div class='row'>
                        <div class="col-md-4">
                                <div class="form-group">
                                    <label>Departemen</label> <label id="kategoriAlert" style="color:red;"></label> 
                                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori">
                                        <option value="">--Pilih Departemen--</option>
                                        <?php 
                                            foreach($show_kategori->result() as $kt){
                                        ?>
                                        <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group" id="sub_kategori">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" id="sub_kategori_2">
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>SKU</label> <label id="skuAlert" style="color:red;"></label> 
                                    <input type="text" id="sku" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" required/>
                                    <div></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Produk</label> <label id="namaProdukAlert" style="color:red;"></label> 
                                    <input type="text" id="namaProduk" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                </div>
                            </div>

                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Satuan</label> <label id="satuanAlert" style="color:red;"></label> 
                                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="satuan" required>
                                        <option value="">--Pilih Satuan--</option>
                                        <?php
                                            foreach($satuan as $st){
                                        ?>
                                        <option value="<?php echo $st->satuan; ?>"><?php echo $st->satuan; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <!--
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempat</label>
                                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tempat" required="">
                                        <?php
                                            foreach($stand as $dt){
                                        ?>
                                        <option value="<?php echo $dt->id_stand; ?>"><?php echo $dt->stand; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        -->
                            <div class="col-md-6">
								<div class="form-group">
									<label>Isi per Kardus</label> <label id="isiAlert" style="color:red;"></label> 
									<input type="text" id="isi" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="0">
								</div>
							</div>

                            <div class="col-lg-12 col-md-12">
                                <div class="panel panel-color panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">Harga Jual</h3> 
                                    </div> 
                                                    
                                    <div class="panel-body"> 
                                        <?php
                                            foreach($store as $str){
                                        ?>
                                            <div class="col-md-3">
														<div class="form-group">
															<label><?php echo $str->store; ?></label>
															<br>Harga Beli: <input type="text" id="hpp<?php echo $str->id_store;?>" data-id_store="<?php echo $str->id_store;?>" value="" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"><br>
                                                            Harga Jual: <br><input type="text" id="hargaJual" onchange="javascript:hitungMargin(<?php echo $str->id_store;?>);" data-id_store="<?php echo $str->id_store;?>" value="" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                                            <br>Margin: <br>
															<input type="text" id="margin<?php echo $str->id_store;?>" onchange="javascript:hitungHarga(<?php echo $str->id_store;?>);" data-id_store="<?php echo $str->id_store;?>" value="" data-name="margin" style="border:0;border-bottom: solid 0.5px #ccc;">%
														</div>
													</div>
                                        <?php } ?>
                                    </div> 
                                </div>
                            </div>

                            <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Supplier</label>
                                            <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="Supplier">
                                                <option value="">--Pilih Supplier--</option>
                                                <?php
                                                foreach($show_supplier->result() as $supp){
                                                ?>
                                                <option value="<?php echo $supp->id_supplier; ?>" ><?php echo $supp->supplier; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                            <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="Brand">
                                                <option value="">--Pilih Brand--</option>
                                                <?php
                                                foreach($show_brand->result() as $brand){
                                                ?>
                                                <option value="<?php echo $brand->id_brand; ?>" ><?php echo $brand->brand; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a id="addProdukSql" class="btn btn-primary">Submit</a>
                                </div>
                            </div>
                        </form>
            		</div>
            	</div>  

<script type="text/javascript">
	var spinner = "<?php echo base_url('produk/spinner'); ?>";

	$('#kategori').change(function(){
                kategori = $('#kategori').val();
                url = "<?php echo base_url('produk/get_subkategori'); ?>";

                $('#sub_kategori').load(url,{id_kategori : kategori});
            });

            $('#subkategori_3').change(function(){
                setNewSKU();
            });

            function setNewSKU(){
                var subkategori_3 = $('#subkategori_3').val();

                $.ajax({
                            method      : "POST",
                            url         : "<?php echo base_url('produk/getNewSKU'); ?>",
                            data        : {id_subkategori_3 : subkategori_3},
                            success     : function(data){
                                            $('#sku').val(subkategori_3+data);
                                          }
                       });
            }

            $('#sku').change(function(){
                var sku = $('#sku').val();

                $.ajax({
                            method      : "POST",
                            url         : "<?php echo base_url('produk/cekSKUIfExist'); ?>",
                            data        : {sku : sku},
                            success     : function(data){
                                            if(data == 1){
                                                $('#skuAlert').text("*SKU Telah Terpakai");
                                                $('#sku').val('');
                                            } else {
                                                $('#skuAlert').empty();
                                            }
                                          }
                       });
            });

            $('#addProdukSql').on("click",function(){
                var sku             = $('#sku').val();
                var namaProduk      = $('#namaProduk').val();
                var isi             = $('#isi').val();
                var satuan          = $('#satuan').val();
                var tempat          = $('#tempat').val();
                var kategori        = $('#kategori').val();
                var kategori2       = $('#subkategori_2').val();
                var kategori3       = $('#subkategori_3').val();
                var supplier       	= $('#Supplier').val();
                var brand       	= $('#Brand').val();

                jsonObj = [];

                $('input[id=hargaJual]').each(function(){
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();

                    item = {};
                    
                    item['hargaBeli'] = hargaBeli;
                    item['hargaJual'] = hargaJual;
                    item['idStore']   = idStore;

                    jsonObj.push(item);
                });

                if(sku=='' || namaProduk=='' || satuan=='' || kategori==''){
                    if(sku==''){
                        $('#skuAlert').text("*SKU Required");
                    }

                    if(namaProduk==''){
                        $('#namaProdukAlert').text('*Nama Produk Required');
                    }

                    if(satuan==''){
                        $('#satuanAlert').text('*Satuan Required');
                    }

                    if(kategori==''){
                        $('#kategoriAlert').text("*Kategori Required");
                    }
                } else {
                    $.ajax({
                                method      : "POST",
                                url         : "<?php echo base_url('produk/tambahProdukNonProduksiSQL'); ?>",
                                data        : {hargaJual : JSON.stringify(jsonObj),sku : sku, namaProduk : namaProduk,isi : isi,  satuan : satuan, tempat : tempat, kategori : kategori, kategori2 : kategori2, kategori3 : kategori3, supplier : supplier,brand:brand},
                                beforeSend  : function(){
                                                $('#form-tambah-produk').load(spinner);
                                              }
                          }).done(function(){
                                urlForm = "<?php echo base_url('produk/form_produk_non_produksi'); ?>";
                                $('#form-tambah-produk').load(urlForm); 

                                $.Notification.notify('success', 'top right', 'Tambah Produk', 'Produk Berhasil Ditambahkan');
                          });
                }

            });

            function hitungMargin(urut){
                var margin = 0;
                margin = ((Number($('#hrgProdukJual'+urut).val())-Number($('#hrgProduk'+urut).val()))/Number($('#hrgProdukJual'+urut).val())*100).toFixed(2);
                $('#margin'+urut).val(margin);

                $('input[id=hargaJual]').each(function(){
                    var margin = 0;
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();

                    margin = ((Number(hargaJual)-Number(hargaBeli))/Number(hargaJual)*100).toFixed(2);
                    $('#margin'+idStore).val(margin);
                });
            }
            function hitungHarga(urut){
                $('input[id=hargaJual]').each(function(){
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();

                    var harga1 = 0;
                    var margin = Number($('#margin'+idStore).val());
                    harga1 = hargaBeli/((100-margin)/100);
                    $(this).val((harga1).toFixed(2));
                });
            }
        </script>