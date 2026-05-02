<div class="wraper container-fluid">
  	<div class="row">
  		<div class="col-md-12" style="text-align: right;">
  			<a class="btn btn-success" onclick="printContent('area-print')"><i class="fa fa-print"></i> Print</a>
  		</div>
  	</div>

    <div class="portlet" style="margin-top: 10px;"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" id="area-print">
            	<div class="row" style="border-bottom: solid 1px black;">
            		<div class="col-md-6 col-xs-6 col-sm-6">
            			<h4 style="font-weight: bold;">WORK ORDER</h4>
            			<h5 style="font-weight: bold;"><?php echo $infoStore->nama_perusahaan; ?></h5>
            			<h5><?php echo $infoStore->alamat; ?></h5>
            			<h5><?php echo $infoStore->kontak; ?></h5>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 10px;">
            		<div class="col-md-12">
            			<table width="100%">
            				<tr>
            					<td width="25%">No Work Order</td>
            					<td width="1%">:</td>
            					<td><?php echo $infoWO->no_order; ?></td>
            				</tr>

            				<tr>
            					<td>Tanggal Work Order</td>
            					<td>:</td>
            					<td><?php echo date_format(date_create($infoWO->tanggalWO),'d F Y'); ?></td>
            				</tr>

            				<tr>
            					<td>Tanggal Penyelesaian Order</td>
            					<td>:</td>
            					<td><?php echo date_format(date_create($infoWO->tanggalPenyelesaian),'d F Y'); ?></td>
            				</tr>

            				<tr>
            					<td>Vendor / Supplier</td>
            					<td>:</td>
            					<td><?php echo $infoWO->supplier; ?></td>
            				</tr>

            				<tr>
            					<td>Pemohon</td>
            					<td>:</td>
            					<td><?php echo $infoWO->pemohon; ?></td>
            				</tr>
            			</table>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 10px;">
            		<div class="col-md-6 col-xs-6 col-sm-6">
            			<label>Daftar bahan baku yang dikeluarkan : </label>
            			<table width="100%" border="1">
            				<thead>
	            				<tr style="font-weight: bold;">
	            					<td width="5%">No</td>
	            					<td>Bahan Baku</td>
	            					<td width="8%" align="center">Qty</td>
	            					<td width="10%">Satuan</td>
	            				</tr>
            				</thead>

            				<tbody>
            					<?php
            						$i = 1;
            						foreach($bahanBakuOrder as $row){
            					?>
	            				<tr>
	            					<td><?php echo $i; ?></td>
	            					<td><?php echo $row->nama_bahan; ?></td>
	            					<td align="center"><?php echo number_format($row->qty,'0',',','.'); ?></td>
	            					<td><?php echo $row->satuan; ?></td>
	            				</tr>
	            				<?php $i++; } ?>
            				</tbody>
            			</table>
            		</div>

            		<div class="col-md-6 col-xs-6 col-sm-6">
            			<label>Daftar pesanan : </label>
            			<table width="100%" border="1">	
            				<thead>
	            				<tr style="font-weight: bold;">
	            					<td width="5%">No</td>
	            					<td width="25%">SKU</td>
	            					<td>Nama Produk</td>
	            					<td width="10%" align="center">Qty</td>
	            				</tr>
            				</thead>

            				<tbody>
            					<?php
            						$y = 1;
            						foreach($daftarPesananOrder as $dt){
            					?>
	            				<tr>	
	            					<td><?php echo $y; ?></td>
	            					<td><?php echo $dt->id_produk; ?></td>
	            					<td><?php echo $dt->nama_produk; ?></td>
	            					<td align="center"><?php echo $dt->qty; ?></td>
	            				</tr>
	            				<?php $y++; } ?>
            				</tbody>
            			</table>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 20px;border-top: solid 1px #ccc;padding-top: 20px;">
            		<div class="col-md-6 col-xs-6 col-sm-6">
            			<label>Daftar Biaya :</label>
            			<table width="100%" border="1">
            				<thead>
            					<tr style="font-weight: bold;">
            						<td>Jenis Biaya</td>
            						<td width="30%" style="text-align: right;">Biaya</td>
            					</tr>
            				</thead>

            				<tbody>
		            			<?php
		            				$total = 0;
		            				foreach($payment as $key => $value){
		            					if(!empty($value)){
		            			?>
		            			<tr>
		            				<td><?php echo $key; ?></td>
		            				<td align="right"><?php echo number_format($value,'0',',','.'); ?></td>
		            			</tr>
		            			<?php $total=$total+$value;} } ?>

		            		<tfoot>
		            			<tr style="font-weight: bold;">
		            				<td align="center">TOTAL</td>
		            				<td style="text-align: right;"><?php echo number_format($total,'0',',','.'); ?></td>
		            			</tr>
		            		</tfoot>
	            			</tbody>
            			</table>
            		</div>

            		<div class="col-md-6 col-xs-6 col-sm-6">
            			<label><i>Keterangan :</i></label>
            			<?php echo $infoWO->keterangan; ?>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12" style="text-align: center;">
            			<center>
            			<table width="70%">
            				<tr>
            					<td width="50%" align="center">
            						<table width="80%">
            							<tr>
            								<td width="100%" style="border-bottom: solid 1px black;height:80px;" align="center">
            									Dibuat Oleh
            								</td>
            							</tr>
            						</table>
            					</td>

            					<td width="50%" align="center">
            						<table width="80%">
            							<tr>
            								<td width="100%" style="border-bottom: solid 1px black;height:80px;" align="center">
            									Pemohon
            								</td>
            							</tr>
            						</table>
            					</td>
            				</tr>
            			</table>
            			</center>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

