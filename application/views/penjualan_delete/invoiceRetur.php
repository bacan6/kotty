<?php
	$i = 1;
	foreach($invoiceRetur as $row){
?>
<tr>
	<td><?php echo $i; ?></td>
	<td><?php echo $row->no_retur; ?></td>
	<td><?php echo date_format(date_create($row->tanggal),'d F Y'); ?></td>
	<td><?php echo $row->keterangan; ?></td>
	<td><a target="_blank" href="<?php echo base_url('penjualan/printInvoiceRetur?noRetur='.$row->no_retur); ?>"><i class="fa fa-print"></i></a></td>
</tr>

<tr>
	<td colspan="5">
		<table width="100%">
			<tr style="font-weight: bold;">
				<td width="15%">SKU</td>
				<td>Nama Produk</td>
				<td width="20%">Harga Satuan</td>
				<td width="10%">Qty</td>
				<td width="20%">Total</td>
			</tr>

			<?php
				$returItem = $this->model_penjualan->returItemSale($row->no_retur);
				foreach($returItem as $dt){
			?>
			<tr>
				<td><?php echo $dt->id_produk; ?></td>
				<td><?php echo $dt->nama_produk; ?></td>
				<td><?php echo number_format($dt->harga,'0',',','.'); ?></td>
				<td><?php echo $dt->qty; ?></td>
				<td><?php echo number_format($dt->harga*$dt->qty,'0',',','.'); ?></td>
			</tr>
			<?php } ?>
		</table>
	</td>
</tr>
<?php $i++; } ?>
