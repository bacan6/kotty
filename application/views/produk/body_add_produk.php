<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Tambah Produk</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<!--<div class="row" style="margin-top: 20px;">
            		<div class="col-md-6">
            			<div class="form-group">
            				<label>Jenis Produk</label>
            				<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="jenis_produk">
            					<option value="">--Pilih Jenis Produk--</option>
                                <option value="1">Produksi</option>
            					<option value="2">Non Produksi</option>
            				</select>
            			</div>	        
            		</div>
            	</div>--> 

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
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>SKU</label> <label id="skuAlert" style="color:red;"></label> 
                                    <input type="text" id="sku" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" required/>
                                    <input type="button" value="Generate SKU" class="btn btn-xs btn-info" id="generate">
                                    <div></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>QR-CODE</label> <label id="qrAlert" style="color:red;"></label> 
                                    <input type="text" id="qr_code" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" />
                                    <div></div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Produk</label> <label id="namaProdukAlert" style="color:red;"></label> 
                                    <input type="text" id="namaProduk" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                            <div class="col-md-6">
								<div class="form-group">
									<label>Isi per Kardus</label> <label id="isiAlert" style="color:red;"></label> 
									<input type="text" id="isi" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" value="0">
								</div>
							</div>
                        </div>
                        <div class="row">
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
                            <div class="col-md-6">
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
                            

                            <div class="col-lg-12 col-md-12">
                                <div class="panel panel-color panel-primary">
                                    <div class="panel-heading"> 
                                        <h3 class="panel-title">Harga | Margin | Supplier</h3> 
                                    </div> 
                                                    
                                    <div class="panel-body"> 
                                        <?php
                                            foreach($store as $str){
                                        ?>
                                            <div class="col-md-4">
														<div class="form-group">
															<h3><?php echo $str->store; ?></h3>
															<br>
                                                            <label>Harga Beli</label>
                                                            <input type="text" id="hpp<?php echo $str->id_store;?>" data-id_store="<?php echo $str->id_store;?>" value="" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"><br>
                                                            <label>Harga Jual</label>
                                                            <input type="text" id="hargaJual" onchange="javascript:hitungMargin(<?php echo $str->id_store;?>);" data-id_store="<?php echo $str->id_store;?>" value="" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                                            <br>
                                                            <label>Margin</label>
                                                            <br>
															<input type="text" id="margin<?php echo $str->id_store;?>" onchange="javascript:hitungHarga(<?php echo $str->id_store;?>);" data-id_store="<?php echo $str->id_store;?>" value="" data-name="margin" style="border:0;border-bottom: solid 0.5px #ccc;">%
                                                             <br>
                                                             <label>Supplier</label>
                                                            <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="Supplier<?php echo $str->id_store;?>">
                                                                <option value="">--Pilih Supplier--</option>
                                                                <?php
                                                                foreach($show_supplier->result() as $supp){
                                                                ?>
                                                                <option value="<?php echo $supp->id_supplier; ?>" ><?php echo $supp->supplier; ?></option>
                                                                <?php } ?>
                                                            </select>
														</div>
													</div>
                                        <?php } ?>
                                    </div> 
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
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

