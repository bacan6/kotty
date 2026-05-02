<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>
<link href="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-gear"></i> Setting Promo Tebus Murah </h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
            			<a href="<?php echo base_url('promo_tebusmurah/daftar_po'); ?>"><i class="fa fa-book"></i> Daftar Promo Tebus Murah</a>
            		</div>
            	</div>

                
            	<div class="row" style="margin-top: 20px;">
                    
            		<div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                <select class="select2" id="toko" required>
                                    <option value="">--Pilih Toko--</option>
                                    <?php
                                        foreach($store->result() as $tk){
                                    ?>
                                    <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" placeholder="Tanggal Mulai" id="tanggalMulai" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control datepicker" placeholder="Tanggal Selesai" id="tanggalSelesai" readonly>
                                </div>
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
                                <input type='checkbox' id='setJam' value='1'> Hanya Jam Tertentu? 
                            </div>
                            <div class="input-group">
                                <label>Jam Mulai</label>
                                <input placeholder="Jam Mulai" type="text" id="timepicker1" class="form-control timepicker">
                            </div>
                            <div class="input-group">
                            <label>Jam Selesai</label>
                                <input placeholder="Jam Selesai" type="text" id="timepicker2" class="form-control timepicker">
                            </div>
                        </div>
            		</div>
                    <div class='col-md-3'>
                        <div class="form-group">
                            <div class="input-group">
                                <input type='checkbox' id='setHari' value='1'> Hanya Hari Tertentu? 
                            </div>
                            <div class="input-group">
                            <label>Pilih Hari</label>
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
                            <div class="input-group">
                                <input type='checkbox' id='tipe' value='1'> Quota akumulasi semua item terpilih? 
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
                        <button class="btn btn-primary" id="prosesPO"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                    <div class="col-md-12" style="text-align: right;margin-top:30px">
                        Minimal Belanja
                            <input type='text' placeholder='0' style='width:60px' id='setNominal'>
                        <a class='btn btn-info btn-xs' id='btn-nominal'>Set</a>
                        Maks. Qty
                            <input type='text' placeholder='0' style='width:60px' id='setQty'>
                        <a class='btn btn-info btn-xs' id='btn-qty'>Set</a>
                        Quota Promo
                            <input type='text' placeholder='0' style='width:60px' id='setQuota'> 
                        <a class='btn btn-info btn-xs' id='btn-quota'>Set</a> 
                        Diskon
                            <input type='text' placeholder='0' style='width:90px' id='setToko'> 
                        <a class='btn btn-info btn-xs' id='btn-toko'>Set </a>  
                    </div>
            		<div class="col-md-12" style="margin-top: 20px;">
		            		<table class="table table-bordered" style="font-size:12px;">
		            			<thead>
			            			<tr style="font-weight: bold;">
                                        <td align="right" width="12%">Belanja Minimal (Rp)</td>
			            				<td width="10%">SKU</td>
			            				<td width="20%">Nama Produk</td>
                                        <td width="5%">Stok</td>
			            				<!-- <td width="10%">Harga Beli</td> -->
			            				<td width="10%">Harga Jual</td>
                                        <td align="right" width="5%">Maks. Qty</td>
                                        <td align="right" width="7%">Quota Promo</td>
			            				<td align="right" width="10%">Discount</td>
                                        <td align="right" width="10%">Harga Setelah Diskon</td>
			            				<td></td>
			            			</tr>
		            			</thead>

		            			<tbody id="data-input">
                                    <tr>
                                        <td colspan="9" align="center"><b>--BELUM ADA DATA TERINPUT--</b></td>
                                    </tr>
		            			</tbody>
		            		</table>
            		</div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
