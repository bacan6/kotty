<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Work Order</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;">
            			<button class="btn btn-success btn-lg" id="prosesWO"><i class="fa fa-save"></i></button>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 10px;">
            		<div class="col-md-6" style="border-right:solid 1px #ccc;">
            			<table width="100%">
            				<tr style="height: 50px;">
            					<td style="font-weight: bold;">Tanggal Penyelesaian Order</td>
            					<td>:</td>
            					<td><input type="text" class="form-control datepicker" id="datePromise" readonly/></td>
            				</tr>

            				<tr>
            					<td width="30%" style="font-weight: bold;">Vendor / Supplier</td>
            					<td width="1%">:</td>
            					<td>
            						<select class="select2" id="supplier">
            							<option value="">--Pilih Vendor--</option>
            							<?php
            								foreach($supplier as $dt){
            							?>
            							<option value="<?php echo $dt->id_supplier; ?>"><?php echo $dt->supplier; ?></option>
            							<?php } ?>
            						</select>
            					</td>
            				</tr>

                                    <tr style="height: 50px;">
                                          <td style="font-weight: bold;">Pemohon</td>
                                          <td>:</td>
                                          <td><input type="text" class="form-control" id="pemohon"/></td>
                                    </tr>
            			</table>
            		</div>

            		<div class="col-md-6">
            			<table width="100%">
                                    <tr style="height: 50px;">
                                          <td style="font-weight: bold;">Alamat Pengiriman</td>
                                          <td>:</td>
                                          <td><textarea class="form-control" id="alamatPengiriman"></textarea></td>
                                    </tr>
            			</table>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 20px;border-top: solid 1px #ddd;padding: 10px;">
            		<div class="col-md-6" style="border-right: solid 1px #ccc;">
            			<div class="row">
            				<div class="col-md-12">
            					<label>Daftar Bahan Baku :</label>
            					<input type="hidden" id="bahanAjax" style="width: 100%;">
            				</div>
            			</div>

            			<div class="row" style="margin-top: 10px;"> 
            				<div class="col-md-12">
            					<table class="table table-bordered">
            						<thead>
	            						<tr style="font-weight: bold;">
	            							<td width="40%">Nama Bahan</td>
	            							<td>Satuan</td>
	            							<td align="right">Harga</td>
	            							<td width="20%">Qty</td>
	            							<td align="right">Total</td>
	            							<td width="2%"></td>
	            						</tr>
            						</thead>

            						<tbody id="daftarBahanBaku">
            						</tbody>
            					</table>
            				</div>
            			</div>
            		</div>

            		<div class="col-md-6">
            			<div class="row">
            				<div class="col-md-12">
            					<label>Konversi Ke :</label>
            					<input type="hidden" id="produkAjax" style="width: 100%;" />
            				</div>
            			</div>

            			<div class="row" style="margin-top: 10px;">
            				<div class="col-md-12">
            					<table class="table table-bordered">
            						<thead>
	            						<tr style="font-weight: bold;">
	            							<td width="15%">SKU</td>
	            							<td width="40%">Nama Produk</td>
	            							<td width="20%">Qty</td>
	            							<td width="2%"></td>
	            						</tr>
            						</thead>

            						<tbody id="convertItem">
            						</tbody>
            					</table>
            				</div>
            			</div>
            		</div>
            	</div>

            	<div class="row" style="padding: 10px;margin-top: 10px;border-top: solid 1px #ccc;">
            		<div class="col-md-6" style="border-right: solid 1px #ccc;">
            			<label>Daftar Biaya :</label>

            			<table width="100%" class="table">
            				<tr>
            					<td width="60%">
            						<input type="text" class="form-control" id="jenisBiaya" placeholder="Contoh : Ongkos Jahit">
            					</td>
            					<td width="30%">
            						<input type="text" class="form-control" id="biaya" placeholder="Contoh : 10000" />
            					</td>
            				</tr>

            				<tr>
            					<td width="60%">
            						<input type="text" class="form-control" id="jenisBiaya">
            					</td>
            					<td width="30%">
            						<input type="text" class="form-control" id="biaya"/>
            					</td>
            				</tr>

            				<tr>
            					<td width="60%">
            						<input type="text" class="form-control" id="jenisBiaya">
            					</td>
            					<td width="30%">
            						<input type="text" class="form-control" id="biaya"/>
            					</td>
            				</tr>

            				<tr>
            					<td width="60%">
            						<input type="text" class="form-control" id="jenisBiaya">
            					</td>
            					<td width="30%">
            						<input type="text" class="form-control" id="biaya"/>
            					</td>
            				</tr>

            				<tr>
            					<td width="60%">
            						<input type="text" class="form-control" id="jenisBiaya">
            					</td>
            					<td width="30%">
            						<input type="text" class="form-control" id="biaya"/>
            					</td>
            				</tr>
            			</table>
            		</div>

            		<div class="col-md-6">
            			<label>Keterangan</label>
            			<textarea id="keterangan" name="keterangan"></textarea>
            		</div>
            	</div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

