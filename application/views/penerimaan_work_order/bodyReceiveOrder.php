<div class="wraper container-fluid">
    <div class="row" style="padding-bottom: 20px;">
        <div class="col-md-12" style="text-align: right;">
            <?php
                if($infoWO->status==0){
                    echo "<a class='btn btn-warning changeStatus' id='diterima'><i class='fa fa-key'></i> Order Diterima</a>";
                } elseif($infoWO->status==1){ 
            ?>
                <a class="btn btn-success changeStatus" id="selesai"><i class="fa fa-check"></i> Selesai</a> 
                <a class="btn btn-danger changeStatus" id="batal"><i class="fa fa-ban"></i> Batal</a> 
            <?php } ?>
        </div>
    </div>
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12">
            			<h3 align="center"><u>Penerimaan Work Order</u></h3>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-6">
            			<table width="100%">
            				<tr>
            					<td width="25%">No WO</td>
            					<td width="1%">:</td>
            					<td><?php echo $infoWO->no_order; ?></td>
            				</tr>

            				<tr>
            					<td width="25%">Tanggal WO</td>
            					<td width="1%">:</td>
            					<td><?php echo date_format(date_create($infoWO->tanggalWO),'d F Y'); ?></td>
            				</tr>

            				<tr>
            					<td width="25%">Tanggal Penyelesaian</td>
            					<td width="1%">:</td>
            					<td><?php echo date_format(date_create($infoWO->tanggalPenyelesaian),'d F Y'); ?></td>
            				</tr>
            			</table>
            		</div>

            		<div class="col-md-6">
            			<table width="100%">
            				<tr>
            					<td width="25%">Vendor</td>
            					<td width="1%">:</td>
            					<td><?php echo $infoWO->supplier; ?></td>
            				</tr>

            				<tr>
            					<td width="25%">Pemohon</td>
            					<td width="1%">:</td>
            					<td><?php echo $infoWO->pemohon; ?></td>
            				</tr>

                            <tr>
                                <td width="25%">Status</td>
                                <td width="1%">:</td>
                                <td>
                                    <?php 
                                        $status = $infoWO->status;

                                        if($status==0){
                                            $button = '<span class="label label-primary">On Process</span>';
                                        } elseif($status==1){
                                            $button = '<span class="label label-info">Diterima</span>';
                                        } elseif($status==2){
                                            $button = '<span class="label label-success">Selesai</span>';
                                        } elseif($status==3){
                                            $button = '<span class="label label-danger">Batal</span>';
                                        }

                                        echo $button;
                                    ?>
                                </td>
                            </tr>
            			</table>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 10px;">
            		<div class="col-md-6">
            			<label>Daftar pesanan produk:</label>
            			<table class="table table-bordered">
            				<thead>
	            				<tr style="font-weight: bold;">
	            					<td>No</td>
	            					<td>SKU</td>
	            					<td>Nama Produk</td>
	            					<td align="center">Jumlah Order</td>
	            					<td>Jumlah Diterima</td>
	            					<td>Sisa</td>
	            				</tr>
            				</thead>

            				<tbody id="daftarPesanan">
	            				
            				</tbody>
            			</table>
            		</div>

            		<div class="col-md-6">
            			<label>Daftar bahan baku/material :</label>
            			<table class="table table-bordered">
            				<thead>
	            				<tr style="font-weight: bold;">
	            					<td width="5%">No</td>
	            					<td>Nama Bahan</td>
	            					<td>Jumlah Dikeluarkan</td>
	            					<td>Satuan</td>
	            				</tr>
            				</thead>

            				<tbody id="daftarMaterial">
            					
            				</tbody>
            			</table>
            		</div>
            	</div>

                <?php
                    if($infoWO->status==1){
                ?>
            	<div class="row" style="margin-top: 10px;border-top: solid 1px #ccc;padding-top: 10px;">
            		<div class="col-md-6">
            			<label>Penerimaan Produk :</label>
            			<table class="table table-bordered">
            				<thead>
            					<tr style="font-weight: bold;">
            						<td width="5%">No</td>
            						<td width="20%">SKU</td>
            						<td>Nama Produk</td>
            						<td width="25%">Jumlah Diterima</td>
            					</tr>
            				</thead>

            				<tbody>
            					<?php
            						$a = 1;
            						foreach($daftarPesananOrder as $bk){
            					?>
            					<tr>
            						<td><?php echo $a; ?></td>
            						<td><?php echo $bk->id_produk; ?></td>
            						<td><?php echo $bk->nama_produk; ?></td>
            						<td><input type="number" id="listProduk" data-id_produk="<?php echo $bk->id_produk; ?>" class="form-control qtyAjax"/></td>
            					</tr>
            					<?php $a++; } ?>

            					<tr>
            						<td colspan="2" style="font-weight: bold;vertical-align: middle;">Diterima Oleh </td>
            						<td colspan="2">
            							<input type="text" class="form-control" id="diterimaOleh"/>
            						</td>
            					</tr>

            					<tr>
            						<td colspan="2" style="font-weight: bold;vertical-align: middle;">Diperiksa Oleh </td>
            						<td colspan="2">
            							<input type="text" class="form-control" id="diperiksaOleh"/>
            							<input type="hidden" id="idSupplier" value="<?php echo $infoWO->id_supplier; ?>"/>
            							<input type="hidden" id="noWO" value="<?php echo $this->input->get("noWO"); ?>"/>
            						</td>
            					</tr>

            					<tr>
            						<td colspan="2" style="font-weight: bold;vertical-align: middle;">Diterima di </td>
            						<td colspan="2">
            							<select class="select2" id="diterimaDi">
                                            <option value="">--Pilih Tempat Penerimaan--</option>
                                            <option value="0">Gudang</option>
                                            <?php
                                                foreach($store as $st){
                                            ?>
                                            <option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>
                                            <?php } ?>
										</select>
            						</td>
            					</tr>

            					<tr>
            						<td colspan="4" align="right"><button class="btn btn-primary" id="submitProduk">Submit</button></td>
            					</tr>
            				</tbody>
            			</table>
            		</div>

            		<div class="col-md-6">
            			<label>Penyesuaian Bahan Baku :</label>
            			<table class="table table-bordered">
            				<thead>
            					<tr style="font-weight: bold;">
            						<td width="5%">No</td>
            						<td>Nama Bahan Baku</td>
            						<td width="20%" align="center">Adjusment</td>
            					</tr>
            				</thead>

            				<tbody>
            					<?php
            						$x = 1;
            						foreach($bahanBakuOrder as $dt){
            					?>
            					<tr>
            						<td><?php echo $x; ?></td>
            						<td><?php echo $dt->nama_bahan; ?></td>
            						<td>
                                        <input type="number" min="0" class="form-control qtyAjaxPesanan sku<?php echo $dt->sku; ?>" data-sku="<?php echo $dt->sku; ?>" id="adjustItem"/>       	
            						</td>
            					</tr>
            					<?php $x++; } ?>

            					<tr>
            						<td colspan="3">
            							<label>Keterangan</label>
            							<textarea class="form-control" id="keterangan"></textarea>
            						</td>
            					</tr>

            					<tr>
            						<td colspan="3" align="right"><button class="btn btn-primary" id="submitAdjusment">Submit</button></td>
            					</tr>
            				</tbody>
            			</table>
            		</div>
            	</div>
                <?php } ?>

            	<div class="row" style="margin-top: 10px;border-top: solid 1px #ccc;padding-top: 10px;">
            		<div class="col-md-6">
            			<label>Riwayat Penerimaan :</label>
            			<table class="table table-bordered">
            				<thead>
            					<tr style="font-weight: bold;">
            						<td>No Penerimaan</td>
            						<td>Penerima</td>
            						<td>Pemeriksa</td>
            						<td>Tgl Terima</td>
            						<td>Diterima Di</td>
            					</tr>
            				</thead>

            				<tbody id="riwayatPenerimaan">
            				</tbody>
            			</table>
            		</div>

            		<div class="col-md-6">
            			<label>Riwayat Adjustment :</label>
            			<table class="table table-bordered">
            				<thead>
            					<tr style="font-weight: bold;">
            						<td width="40%">No Adjusment</td>
            						<td>Tanggal Adjusment</td>
            					</tr>
            				</thead>

            				<tbody id="riwayatAdjusment">
            				</tbody>
            			</table>
            		</div>
            	</div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
