							<form style="padding: 10px;">
							    <?php
							    	foreach($produk as $row){
								//$ro = ($idUser==51 || $idUser==45 || $idUser==41 || $idUser==22 || $idUser==1 || empty($idStore))? "":'readonly=readonly';
								$ro='';
							    ?>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label>Departemen</label> <label id="kategoriAlert" style="color:red;"></label> 
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori">
												<option value="">--Pilih Departemen--</option>
												<?php 
													foreach($show_kategori->result() as $kt){
												?>
												<option value="<?php echo $kt->id_kategori; ?>" <?php if($kt->id_kategori==$row->id_kategori){echo "selected";} ?>><?php echo $kt->kategori; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group" id="sub_kategori">
											<label>Kategori</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="subkategori_2">
												<option value="">--Pilih Kategori--</option>
												<?php
													$id_kategori = $row->id_kategori;
													$show_sub = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori));
													foreach($show_sub->result() as $kt1){
												?>
												<option value="<?php echo $kt1->id; ?>" <?php if($kt1->id==$row->id_subkategori){echo"selected";}?>><?php echo $kt1->kategori_level_1; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group" id="sub_kategori_2">
											<label>Subkategori</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="subkategori_3">
												<option value="">--Pilih Subkategori--</option>
												<?php
												$id_kategori_2 = $row->id_subkategori;
												$show_sub2 = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));
												foreach($show_sub2->result() as $kt2){
												?>
												<option value="<?php echo $kt2->id; ?>" <?php if($kt2->id==$row->id_subkategori_2){echo "selected";}?>><?php echo $kt2->kategori_3; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									</div>
							    <div class="row">
								    <div class="col-md-3">
									    <div class="form-group">
											<label>SKU</label> <label id="skuAlert" style="color:red;"></label> 
											<input type="text" id="sku" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $sku; ?>" />
											<input type="hidden" id="sku_awal"  value="<?php echo $sku; ?>"/>
											<input type="button" value="Generate SKU" class="btn btn-xs btn-info" id="generate">
											<div></div>
										</div>
									</div>
									<div class="col-md-3">
									    <div class="form-group">
											<label>QR-CODE</label> <label id="qrAlert" style="color:red;"></label> 
											<input type="text" id="qr_code" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $row->qr_code; ?>" />
											<div></div>
										</div>
									</div>

									<div class="col-md-6">
									    <div class="form-group">
											<label>Nama Produk</label> <label id="namaProdukAlert" style="color:red;"></label> 
											<input type="text" id="namaProduk" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $row->nama_produk; ?>" <?php echo $ro?>>
										</div>
									</div>
								</div>
								
								<div class="row">
									

									<div class="col-md-4">
										<div class="form-group">
											<label>Satuan</label> <label id="satuanAlert" style="color:red;"></label> 
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="satuan" required>
												<option value="">--Pilih Satuan--</option>
												<?php
													foreach($satuan->result() as $st){
												?>
												<option value="<?php echo $st->satuan; ?>" <?php if($row->satuan == $st->satuan){echo"selected";} ?>><?php echo $st->satuan; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
									    <div class="form-group">
											<label>Isi per Kardus</label> <label id="isiAlert" style="color:red;"></label> 
											<input type="text" id="isi" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="<?php echo $row->isi; ?>">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Status</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="status" required="">
												<option value="1" <?php if($row->status==1){echo "selected";} ?>>Aktif</option>
												<option value="0" <?php if($row->status==0){echo "selected";} ?>>Non Aktif</option>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="Brand">
                                                <option value="">--Pilih Brand--</option>
                                                <?php
                                                foreach($show_brand->result() as $brand){
                                                ?>
                                                <option value="<?php echo $brand->id_brand; ?>" <?php if($brand->id_brand==$row->id_brand){echo "selected";}?>><?php echo $brand->brand; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Tempat</label>
											<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tempat" required="">
												<?php
													foreach($stand as $dt){
												?>
												<option value="<?php echo $dt->id_stand; ?>" <?php if($dt->id_stand==$row->tempat){echo"selected";} ?>><?php echo $dt->stand; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
									
								
								
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12">
								        <div class="panel panel-color panel-primary">
								            <div class="panel-heading"> 
								                <h3 class="panel-title">Harga | Margin | Supplier</h3> 
								            </div> 
								                            
								            <div class="panel-body"> 
								                <?php
								                $cid = 0;
								                	foreach($store as $str){
								                		$cid++;
														$idStore2 = $str->id_store;
														
														$ro = $idStore2!=$idStore && ($idUser!=2 && $idUser!=1 && $idUser!=123 && $idUser!=162 && $idUser!=152 && $idUser!=22)? "readonly=readonly":'';

								                		$getPrice = $this->modelProduk->getPrice($idStore2,$sku);
                                                        $getHPP = $this->modelProduk->getHPP($idStore2,$sku);
														$getSupplier = $this->modelProduk->getSupplier($idStore2,$sku);
                                                        
								                		$margin = $getPrice>0?number_format((($getPrice-$getHPP)/$getPrice)*100,2):'0';
								                ?>
									                <div class="col-md-4">
														<div class="form-group">
															<h3><?php echo $str->store; ?></h3>
															<br><label>Harga Beli</label> <input type="text" <?php echo $ro?>  id="hpp<?php echo $str->id_store;?>" data-id_store="<?php echo $str->id_store;?>" value="<?php echo $getHPP; ?>" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                                            <br><label>Harga Jual</label>
															<input type="text" <?php echo $ro?>  id="hargaJual" data-id_store="<?php echo $str->id_store;?>" value="<?php echo $getPrice; ?>" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                                            <br><label>Margin</label><br>
															<input type="text" id="margin<?php echo $str->id_store;?>" <?php echo $ro;?> data-id_store="<?php echo $str->id_store;?>" value="<?php echo $margin; ?>" data-name="margin" onchange="javascript:hitungHarga(<?php echo $str->id_store;?>);" style="border:0;border-bottom: solid 0.5px #ccc;">%
															<br>
															<label>Supplier</label>
															<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="Supplier<?php echo $str->id_store;?>" data-id_store="<?php echo $str->id_store;?>" <?php echo $ro;?>>
																<option value="">--Pilih Supplier--</option>
																<?php
																foreach($show_supplier->result() as $supp){
																?>
																<option value="<?php echo $supp->id_supplier; ?>" 
																	<?php if($supp->id_supplier==$getSupplier){echo "selected";}?>><?php echo $supp->supplier; ?></option>
																<?php } ?>
															</select>
										
														</div>
													</div>
												<?php } ?>
								            </div> 
								        </div>
								    </div>
								</div>

							    

									
									
									
									<div class="col-md-12">
										<div class="form-group">
											<a id="editProdukSql" class="btn btn-primary">Submit</a>
										</div>
									</div>
								</div>

								<?php } ?>
							</form>


<script type="text/javascript">
	var spinner = "<?php echo base_url('produk/spinner'); ?>";

	$('#kategori').change(function(){
        kategori = $('#kategori').val();
        url = "<?php echo base_url('produk/get_subkategori'); ?>";

        $('#sub_kategori').load(url,{id_kategori : kategori});

        $('#sub_kategori_2').empty();
    });

	$('input[id=hargaJual]').on("change",function(){
		$('input[id=hargaJual]').each(function(){
            var hargaJual   = $(this).val();
            var idStore     = $(this).data('id_store');
            var hargaBeli   = $('#hpp'+idStore).val();
            selisih = hargaJual - hargaBeli;
            margin = (selisih/hargaJual)*100;

            $('#margin'+idStore).val(margin.toFixed(2));
        });
	});

	$('#generate').click(function(){
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
	/*
	$('input[data-name=margin]').on("change",function(){
		$('input[id=hargaJual]').each(function(){
			var hargaJual   = $('#hargaJual').val();
			var idStore     = $(this).data('id_store');
            var margin = $('#margin'+idStore).val();

            selisih = Number((margin/100)*hargaJual);

            hargaJual = Number(hargaBeli) + selisih;
            
            $(this).val(hargaJual.toFixed(0));
        });
	});*/
	$('#editProdukSql').on("click",function(){
                var sku             = $('#sku').val();
				var qr_code         = $('#qr_code').val();
				var sku_awal        = $('#sku_awal').val();
				var namaProduk      = $('#namaProduk').val();
				var isi      		= $('#isi').val();
                var satuan          = $('#satuan').val();
                var status 			= $('#status').val();
                var tempat          = $('#tempat').val();
                var kategori        = $('#kategori').val();
                var kategori2       = $('#subkategori_2').val();
                var kategori3       = $('#subkategori_3').val();
				var brand       	= $('#Brand').val();

                jsonObj = [];

                $('input[id=hargaJual]').each(function(){
                    var hargaJual   = $(this).val();
                    var idStore     = $(this).data('id_store');
                    var hargaBeli   = $('#hpp'+idStore).val();
					var supplier   = $('#Supplier'+idStore).val();

                    item = {};
                    
                    item['hargaBeli'] = hargaBeli;
                    item['hargaJual'] = hargaJual;
                    item['idStore']   = idStore;
					item['supplier']   = supplier;

                    jsonObj.push(item);
                });

                if(sku_awal=='' && sku=='' && namaProduk=='' && satuan=='' && kategori==''){
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
                                url         : "<?php echo base_url('produk/editProdukNonProduksiSQL'); ?>",
                                data        : {hargaJual : JSON.stringify(jsonObj),sku : sku,qr_code : qr_code,sku_awal : sku_awal, namaProduk : namaProduk, isi : isi, satuan : satuan, status : status, tempat : tempat, kategori : kategori, kategori2 : kategori2, kategori3 : kategori3,brand:brand},
                                beforeSend  : function(){
                                                $('#editProdukForm').load(spinner);
                                              }
                          }).done(function(){
                                urlForm = "<?php echo base_url('produk/formEditNonProduksi'); ?>";
                                $('#editProdukForm').load(urlForm,{sku : sku});

                                $.Notification.notify('success', 'top right', 'Edit Produk', 'Produk Berhasil Diubah');
								var urlRedirect = "<?php echo base_url('produk')?>";
                                window.location.replace(urlRedirect);
                          });
                }

    });

            function hitungHarga(store){
                $('input[id=hargaJual]').each(function(){
                    var idStore     = $(this).data('id_store');
					if (store == idStore){
						var hargaJual   = $(this).val();
						var hargaBeli   = $('#hpp'+idStore).val();

						var harga1 = 0;
						var margin = Number($('#margin'+idStore).val());
						harga1 = hargaBeli/((100-margin)/100);
						$(this).val((harga1).toFixed(2));
					}
                    
                });
            }
			jQuery("select").select2({
                width: '100%'
            });
</script>