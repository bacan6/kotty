<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>
<link href="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-gear"></i> Setting Promo Bundling</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
            			<a href="<?php echo base_url('promo_bundling/daftar_po'); ?>"><i class="fa fa-book"></i> Daftar Promo Bundling</a>
            		</div>
            	</div>

                
            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-6">
                        <div class="form-group">
                          <label>Store <span style="color:red">*</span></label>
                      
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <select class="select2" id="store">
                                <?php
                                  foreach($store as $kt){
                                ?>
                                <option value="<?php echo $kt->id_store; ?>"><?php echo $kt->store; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" placeholder="Tanggal Mulai" id="tanggalMulai" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" placeholder="Tanggal Selesai" id="tanggalSelesai" readonly>
                            </div>
                        </div>
            			<div class="form-group">
                            <div class="input-group">
                                <label>Diskon Bundle (%) <span style="color:red">*</span></label>
                                <input placeholder="70" type="text" id="diskon" class="form-control">
                            </div>
                        </div>
            		</div>

            		<div class="col-md-6">
            			<div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <textarea id="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class="input-group">
                                <label>Jam Mulai <span style="color:red">*</span></label>
                                <input placeholder="Jam Mulai" type="text" id="timepicker1" class="form-control timepicker">
                            </div>
                            <div class="input-group">
                            <label>Jam Selesai <span style="color:red">*</span></label>
                                <input placeholder="Jam Selesai" type="text" id="timepicker2" class="form-control timepicker">
                            </div>
                        </div>
                        
            		</div>
                    <div class='col-md-3'>
                    <div class="form-group">
                            <div class="input-group">
                            <label>Pilih Hari <span style="color:red">*</span></label>
                                <select class="select2" id="HariID" multiple placeholder="klik disini">
                                    <option value="0">Minggu</option>
                                    <option value="1">Senin</option>
                                    <option value="2">Selasa</option>
                                    <option value="3">Rabu</option>
                                    <option value="4">Kamis</option>
                                    <option value="5">Jumat</option>
                                    <option value="6">Sabtu</option>
                                </select>
                            </div>
                        </div>
                    </div>
            	</div>  
                <div class="row" style="margin-top: 20px;">
            		<div class="col-md-12">
                        <input type="hidden" id="sku" style="width:100%;"/>
            		</div>
            	</div> 
            	<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="text-align: right;">
                        Semua produk yang ada di bawah ini harus dibeli, untuk mendapatkan diskon.
                        <button class="btn btn-primary" id="prosesPO"><i class="fa fa-save"></i> Simpan</button>
                    </div>
            		<div class="col-md-12" style="margin-top: 20px;">
		            		<table class="table table-bordered" style="font-size:12px;">
		            			<thead>
			            			<tr style="font-weight: bold;">
			            				<td width="10%">SKU</td>
			            				<td >Nama Produk</td>
                                        <td width="5%">Stok</td>
			            				<td width="10%">Harga Beli</td>
			            				<td width="10%">Harga Jual</td>
			            				<td width="4%"></td>
			            			</tr>
		            			</thead>

		            			<tbody id="data-input">
                                    <tr>
                                        <td colspan="11" align="center"><b>--BELUM ADA DATA TERINPUT--</b></td>
                                    </tr>
		            			</tbody>
		            		</table>
            		</div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
