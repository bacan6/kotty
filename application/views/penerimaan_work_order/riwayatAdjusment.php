<?php
	foreach($riwayatAdjusment as $row){
?>
<tr>
	<td><a href="<?php echo base_url('penerimaanWorkOrder/invoiceAdjusment?noAdj='.$row->no_adjusment); ?>"><?php echo $row->no_adjusment; ?></a></td>
	<td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
</tr>

<tr>
	<td colspan="2">
		<table width="100%">
			<tr style="font-weight: bold;border-bottom: solid 1px #ccc;">
				<td width="60%">Nama Bahan</td>
				<td width="20%">Qty</td>
				<td>Satuan</td>
			</tr>

			<?php
				$adjusmentItem = $this->modelWorkOrder->adjusmentItem($row->no_adjusment);

				foreach($adjusmentItem as $dt){
			?>
			<tr>
				<td><?php echo $dt->nama_bahan; ?></td>
				<td><?php echo $dt->qty; ?></td>
				<td><?php echo $dt->satuan; ?></td>
			</tr>
			<?php } ?>
		</table>
	</td>
</tr>
<?php } ?>