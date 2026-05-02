<?php error_reporting(0);ini_set('display_errors',0);?>
<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-truck"></i> Exclusive PO</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
                        <a href="<?php echo base_url('purchase_order/importPurchaseItem'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Import Purchase Item</a>
            			<a href="<?php echo base_url('purchase_order/daftar_po'); ?>"><i class="fa fa-book"></i> Daftar PO</a>
            		</div>
            	</div>

                
            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-book"></i></span>
                                <select class="select2" id="brand" multiple placeholder="Pilih Brand">
                                    <?php
                                        foreach($brand->result() as $br){
                                    ?>
                                    <option value="<?php echo $br->id_brand; ?>" ><?php echo $br->brand; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                <select class="select2" id="supplier" required>
                                    <option value="">--Pilih Supplier--</option>
                                    <?php
                                        foreach($supplier->result() as $sp){
                                            $sel = ($_SESSION['id_supplier']==$sp->id_supplier)?'selected':'';
                                    ?>
                                    <option value="<?php echo $sp->id_supplier; ?>" <?php echo $sel?>><?php echo $sp->supplier; ?></option>
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" placeholder="Tanggal Kirim" id="tanggalKirim" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control datepicker" placeholder="Jatuh Tempo" id="jatuhTempo" readonly>
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

                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-rocket"></i></span>
                                <textarea id="alamatPengiriman" class="form-control" placeholder="Alamat Pengiriman"></textarea>
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

            		<div class="col-md-12" style="margin-top: 20px;">
                        <div align=right style="margin-bottom:10px">
                            Diskon1 (%)<input type="number" id="setDiskon1" style="width:80px"> 
                            <a class="btn btn-info btn-xs" id="btn-diskon1">Set All</a>
                            Diskon2 (%)<input type="number" id="setDiskon2" style="width:80px"> 
                            <a class="btn btn-info btn-xs" id="btn-diskon2">Set All</a>
                            Diskon3 (Rp/item)<input type="number" id="setDiskon3" style="width:110px"> 
                            <a class="btn btn-info btn-xs" id="btn-diskon3">Set All</a>
                            <a class="btn btn-success btn-xs" onclick="editHarga3();">Hitung Semua</a>
                            <a class="btn btn-danger btn-xs" id="kosongkanCart">Kosongkan Cart</a>
                        </div>
		            		<table class="table table-bordered" style="font-size:12px;">
                        
		            			<thead>
			            			<tr style="font-weight: bold;">
			            				<td width="15%">SKU</td>
			            				<td width="15%">Nama Produk</td>
                                        <td width="8%">Jumlah Beli</td>
                                        <td align="right" width="10%">Harga Satuan</td>
                                        <td align="right" width="10%">Harga Jual</td>
                                        <td align="right" width="5%">Margin</td>
                                        <td width="7%" align=center>Qty Bonus</td>
                                        <td width="7%" align=center>Diskon1<br>(%)</td>
                                        <td width="7%" align=center>Diskon2<br>(%)</td>
                                        <td width="7%" align=center>Diskon3<br>(Rp/item)</td>
			            				<td align="right" width="10%">Sub Total</td>
			            				<td></td>
			            			</tr>
		            			</thead>

		            			<tbody id="data-input">
                                    <tr>
                                        <td colspan="7" align="center"><b>--BELUM ADA DATA TERINPUT--</b></td>
                                    </tr>
		            			</tbody>
                                <tbody>
                                <tr>
	                                    		<td colspan="3" align=right><b>Diterima Oleh</b> <label id="diterimaAlert" style="color: red;"></label></td>
	                                    		<td colspan="3">
                                                    <input type="hidden" id="noPo" value="<?php echo $_GET['no_po']; ?>"/>
                                                    <input type="hidden" id="idSupplier" value="<?php echo $noteInfo->id_supplier; ?>"/>
	                                    			<input type="text" class="form-control" id="diterimaOleh" required>
	                                    		</td>
	                                    	</tr>

	                                    	<tr>
	                                    		<td colspan="3" align=right><b>Diperiksa Oleh</b> <label id="diperiksaAlert" style="color: red;"></label></td>
	                                    		<td colspan="3"><input type="text" class="form-control" id="diperiksaOleh" required></td>
	                                    	</tr>
                                            <tr>
                                                <td colspan="3" align=right><b>Diskon Global</b> <label id="lbdiskon" style="color: red;"></label></td>
                                                <td colspan="3"><input type="text" class="form-control" id="diskon" onchange="javascript:editHarga2(this.value);"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align=right><b>Diskon Produk</b> <label id="lbdiskon" style="color: red;"></label></td>
                                                <td colspan="3"><b><span id='stDiskon'></span></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align=right><b>PPn 11%</b> <label id="lbppn" style="color: red;"></label></td>
                                                <td colspan="3"><select name='PPN' id='PPN' onChange="javascript:editHarga3();"><option value='0'>No</option><option value='1'>Yes</option></select></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align=right><b>Harga Akhir</b> <label id="total" style="color: red;"></label></td>
                                                <td colspan="3"><b><span id='stTotal'><?php echo $tHarga?></span></b></td>
                                            </tr>
                                            
	                                    	<tr>
	                                    		<td colspan="3" align=right><b>Tanggal Kedatangan</b></td>
	                                    		<td colspan="3"><input type="text" class="form-control datepicker" id="tanggalTerima" value="<?php echo date('Y-m-d'); ?>" readonly ></td>
	                                    	</tr>

                                            <tr>
                                                <td colspan="3" align=right><b>Diterima Di</b> <label id="diterimaDiAlert" style="color: red;"></label></td>
                                                <td colspan="3">
                                                    <select class="select2" id="diterimaDi">
                                                        <option value="0">Gudang</option>
                                                        <?php
                                                            foreach($store as $st){
                                                        ?>
                                                        <option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            
                                </tbody>
		            		</table>
                            <table width="100%">
	                                    	<tr>
	                                    		<td colspan="4" align="center"><button type="submit" class="btn btn-primary" id="submitPenerimaan">Simpan</button></td>
	                                    	</tr>
	                                    </table>
            		</div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
