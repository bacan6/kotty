<table class="table table-bordered">
	<thead>
		<tr style="font-weight: bold;">
			<td>No</td>
			<td>Tanggal</td>
			<td>Jam Setor</td>
			<td>Nama Kasir</td>
			<td>No Setoran</td>
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
			<td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
			<td><?php echo $row->jam_setor; ?></td>
			<td><?php echo $row->first_name; ?></td>
			<td><a href="<?php echo base_url('penjualan/invoice_setorankasir?no_setor='.$row->no_setor);?>" target="_blank"><?php echo $row->no_setor; ?></a></td>
			
		</tr>
		<?php 
			$i++; 
			} 
		?>
	</tbody>
</table>