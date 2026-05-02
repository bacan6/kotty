No PO : <?php echo $no_po; ?>
<table class="table table-striped" style="font-size:10px;">
	<tr>
		<th width="5%">No</th>
		<th>Item</th>
		<th width="10%">Satuan</th>
		<th width="10%">Qty</th>
		<th style="text-align: right;width: 15%;">Delivered Qty</th>
		<th style="text-align: right;">Harga</th>
	</tr>

	<?php
		$i=1;
		$total = 0;
		foreach($purchase_item->result() as $row){
			$id = $row->sku;
	?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $row->nama_bahan; ?></td>
		<td><?php echo $row->satuan; ?></td>
		<td><?php echo $row->qty; ?></td>
		<td align="right">
			<?php
				$qty_received = $this->model1->qty_received($id,$no_po);

				echo $qty_received;
			?>
		</td>
		<td align="right"><?php echo number_format(($row->harga*$row->qty),'0',',','.'); ?></td>
	</tr>
	<?php $total = $total+($row->harga*$row->qty); $i++; } ?>

	<tr>
		<td colspan="5" align="center"><b>TOTAL</b></td>
		<td style="text-align: right;"><b><?php echo number_format($total,'0',',','.'); ?></b></td>
	</tr>
</table>