<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>
<link href="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-dollar"></i> Setting Promo </h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
                        <a href="<?php echo base_url('promo_supplier/importPromo'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Import Data</a>
            			<a href="<?php echo base_url('promo_supplier/daftar_po'); ?>"><i class="fa fa-book"></i> Daftar Promo</a>
            		</div>
            	</div>

                
            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                <select class="select2" id="toko" name="toko[]" multiple="multiple">
                                    <?php
                                        foreach($store->result() as $tk){
                                    ?>
                                    <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                <select class="select2" id="brand" required>
                                    <option value="">--Pilih Brand--</option>
                                    <?php
                                        foreach($brand->result() as $sp){
                                            $sel = ($_SESSION['id_brand']==$sp->id_brand)?'selected':'';
                                    ?>
                                    <option value="<?php echo $sp->id_brand; ?>" <?php echo $sel?>><?php echo $sp->brand; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="form-group">
                          <label>Kategori</label>
                          
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              <select class="form-control" id="kategori">
                            <option value="">--Semua--</option>
                            <?php
                              foreach($kategori as $kt){
                            ?>
                            <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                            <?php } ?>
                          </select>
                            </div>
                        </div>

                        <!--id=subkategori2-->
                        <div class="form-group" id="subkategori">
                          <input type="hidden" id="subkategori2" value=""/>
                        </div>

                        <!--id=subkategori_3-->
                        <div class="form-group" id="sub_kategori_2">
                          <input type="hidden" id="subkategori_3" value=""/>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                <select class="select2" id="tipe" required>
                                    <option value="0">All Customer</option>
                                    <option value="1">K-Member</option>
                                    <option value="4">Twenties</option>
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
                                <input type='checkbox' id='setJam' value='1' checked required> Hanya Jam Tertentu? 
                            </div>
                            <div class="input-group">
                                <label>Jam Mulai</label>
                                <input placeholder="Jam Mulai" type="text" id="timepicker1" class="form-control timepicker" required>
                            </div>
                            <div class="input-group">
                            <label>Jam Selesai</label>
                                <input placeholder="Jam Selesai" type="text" id="timepicker2" class="form-control timepicker" required>
                            </div>
                        </div>
            		</div>
                    <div class='col-md-3'>
                    <div class="form-group">
                            <div class="input-group">
                                <input type='checkbox' id='setHari' value='1' checked required> Hanya Hari Tertentu? 
                            </div>
                            <div class="input-group">
                            <label>Pilih Hari</label>
                                <select class="select2" id="HariID" multiple placeholder="klik disini">
                                    <option value="0" selected>Minggu</option>
                                    <option value="1" selected>Senin</option>
                                    <option value="2" selected>Selasa</option>
                                    <option value="3" selected>Rabu</option>
                                    <option value="4" selected>Kamis</option>
                                    <option value="5" selected>Jumat</option>
                                    <option value="6" selected>Sabtu</option>
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
                        <button class="btn btn-primary" id="prosesPO"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                    <div class="col-md-12" style="text-align: right;margin-top:30px">
                        Qty
                            <input type='text' placeholder='0' style='width:60px' id='setQty'>
                        <a class='btn btn-info btn-xs' id='btn-qty'>Set</a>
                        Quota (Pcs)
                            <input type='text' placeholder='0' style='width:60px' id='setQuota'>
                        <a class='btn btn-info btn-xs' id='btn-quota'>Set</a>
                        Quota (Rp)
                            <input type='text' placeholder='0' style='width:60px' id='setQuotarp'>
                        <a class='btn btn-info btn-xs' id='btn-quotarp'>Set</a>  
                        Diskon Toko 
                            <input type='text' placeholder='0' style='width:90px' id='setToko'> 
                        <a class='btn btn-info btn-xs' id='btn-toko'>Set </a> 
                        Diskon Supplier
                            <input type='text' placeholder='0' style='width:90px' id='setSupplier'>
                        <a class='btn btn-info btn-xs' id='btn-supplier'>Set</a> 
                    </div>
            		<div class="col-md-12" style="margin-top: 20px;">
		            		<table class="table table-bordered" style="font-size:12px;">
		            			<thead>
			            			<tr style="font-weight: bold;">
			            				<td width="10%">SKU</td>
			            				<td width="20%">Nama Produk</td>
                                        <td width="5%">Stok</td>
			            				<td width="10%">Harga Beli</td>
			            				<td width="10%">Harga Jual</td>
                                        <td align="right" width="5%">Quantity</td>
                                        <td align="right" width="7%">Quota (Pcs)</td>
                                        <td align="right" width="7%">Quota (Rp)</td>
			            				<td align="right" width="10%">Disc. Toko</td>
                                        <td align="right" width="10%">Disc. Supplier</td>
                                        <td align="right" width="20%">Harga Setelah Diskon</td>
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
