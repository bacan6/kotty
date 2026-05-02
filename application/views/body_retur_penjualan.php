<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Retur Penjualan</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<?php
            		foreach($ket_invoice->result() as $dt){
            	?>
            	<div class="row">
            		<div class="col-md-6">
            			<table class="table" style="font-size: 12px;">
            				<tr>
            					<td width="30%" style="font-weight: bold;">No Invoice</td>
            					<td width="1%">:</td>
            					<td><?php echo $dt->no_invoice; ?></td>
            				</tr>

            				<tr>
            					<td width="15%" style="font-weight: bold;">Nama Customer</td>
            					<td width="1%">:</td>
            					<td><?php echo $dt->nama; ?></td>
            				</tr>

            				<tr>
            					<td width="15%" style="font-weight: bold;">Kategori Customer</td>
            					<td width="1%">:</td>
            					<td><?php echo $dt->group_customer; ?></td>
            				</tr>
            				<tr>
            					<td width="30%" style="font-weight: bold;">Keterangan</td>
            					<td width="1%">:</td>
            					<td><?php echo $dt->keterangan; ?></td>
            				</tr>	
            			</table>
            		</div>

            		<div class="col-md-6">
            			<table class="table" style="font-size: 12px;">
            				<tr>
            					<td width="30%" style="font-weight: bold;">Tanggal Order</td>
            					<td width="1%">:</td>
            					<td><?php echo date_format(date_create($dt->tanggal),'d M Y H:i'); ?></td>
            				</tr>
            				<tr>
            					<td width="30%" style="font-weight: bold;">Alamat Pengiriman</td>
            					<td width="1%">:</td>
            					<td><?php echo $dt->alamat."<br>".$dt->nama_provinsi."-".$dt->nama_kabupaten."-".$dt->kecamatan; ?></td>
            				</tr>
            			</table>
            		</div>
            	</div>
            	<?php } ?>

            	<div class="row">
            		<div class="col-md-12">
            			<form action="<?php echo base_url('data_transaksi/retur_sql'); ?>" method="post">
	            			<table class="table table-bordered table-striped" style="font-size: 10px;">
	            				<tr style="background: #2A303A;color:white;font-weight: bold;">
	            					<td width="5%" align="center">No</td>
	            					<td width="20%">SKU</td>
	            					<td>Item</td>
	            					<td width="13%" align="center">Qty</td>
	            					<td width="13%" align="right">Unit Price</td>
	            					<td width="13%" align="right">Ext Price</td>
	            					<td>Remark</td>
	            				</tr>

	            				<?php
	            					$i = 1;
	            					foreach($invoice_item->result() as $row){
	            				?>
	            				<tr>
	            					<td align="center"><?php echo $i; ?></td>
	            					<td><?php echo $row->id_produk; ?></td>
	            					<td><?php echo $row->nama_produk; ?></td>
	            					<td align="center">
	            						<input type="hidden" name="id_produk[]" value="<?php echo $row->id_produk; ?>"/>
                                        <input type="hidden" name="harga[]" value="<?php echo $row->harga_jual; ?>"/>
	            						<input type="number" name="qty[]" value="<?php echo $row->qty; ?>" max="<?php echo $row->qty; ?>" class="form-control"/>
	            					</td>
	            					<td align="right"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
	            					<td align="right"><?php echo number_format($row->harga_jual*$row->qty,'0',',','.'); ?></td>
	            					<td><input type="text" name="remark[]" class="form-control"/></td>	
	            				</tr>
	            				<?php $i++; } ?>

	            				<tr>
	            					<td colspan="7" align="right">
                                        <input type="hidden" name="no_invoice" value="<?php echo $_GET['no_invoice']; ?>"/>
	            						<input type="submit" class="btn btn-primary" value="Retur"/>
	            					</td>
	            				</tr>
	            			</table>
            			</form>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
