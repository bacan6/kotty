<?php
	foreach($riwayatPenerimaan as $row){
?>
<tr>
	<td><?php echo $row->sku; ?></td>
	<td><?php echo $row->nama_bahan; ?></td>
	<td><?php echo date_format(date_create($row->tanggal),'d/m/Y'); ?></td>
	<td><?php echo $row->qty; ?></td>
</tr>
<?php } ?>