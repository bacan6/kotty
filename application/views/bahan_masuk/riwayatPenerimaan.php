<?php
	foreach($riwayatPenerimaan as $row){
?>
<tr>
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><?php echo date_format(date_create($row->tanggal),'d/m/Y'); ?></td>
	<td><?php echo $row->qty; ?></td>
</tr>
<?php } ?>