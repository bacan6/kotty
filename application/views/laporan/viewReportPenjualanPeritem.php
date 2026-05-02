<table class="table table-bordered">
	<thead>
		<tr style="font-weight: bold;">
			<td>No</td>
			<td>PIC</td>
			<td>No Retur</td>
			<td>No Invoice</td>
			<td>Tanggal</td>
			<td>SKU</td>
			<td>Nama Produk</td>
			<td>Harga</td>
			<td>Qty</td>
			<td>Total</td>
		</tr>
	</thead>

	<tbody>
		<?php
			$i = 1;
			$subtotal = 0;
			$diskon = 0;
			foreach($viewReport as $row){
		?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo $row->first_name; ?></td>
			<td><?php echo $row->no_retur; ?></td>
			<td><?php echo $row->no_invoice; ?></td>
			<td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
			<td><?php echo $row->id_produk; ?></td>
			<td><?php echo $row->nama_produk; ?></td>
			<td>
				<?php echo number_format($row->harga,'0',',','.'); ?>
				<?php
					if(!empty($row->diskon)){
						echo "<br>";
						echo number_format($row->diskon,'0',',','.');
					}
				?>		
			</td>
			<td><?php echo $row->qty; ?></td>
			<td><?php echo number_format($row->harga*$row->qty,'0',',','.'); ?></td>
		</tr>
		<?php 
			$subtotal = $subtotal+$row->harga;
			$diskon = $diskon+$row->diskon;
			$i++; 
			} 
		?>

		<tr style="font-weight: bold;">
			<td colspan="8" align="center">SUBTOTAL</td>
			<td><?php echo number_format($subtotal,'0',',','.'); ?></td>
		</tr>

		<tr style="font-weight: bold;">
			<td colspan="8" align="center">DISKON</td>
			<td><?php echo number_format($diskon,'0',',','.'); ?></td>
		</tr>

		<tr style="font-weight: bold;">
			<td colspan="8" align="center">TOTAL</td>
			<td><?php echo number_format($subtotal-$diskon,'0',',','.'); ?></td>
		</tr>
	</tbody>
</table>