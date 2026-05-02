<?php
	$i = 1;
	foreach($riwayatPembayaran as $row){
?>
<tr>
	<td><?php echo $i; ?></td>
	<td><?php echo $row->no_seri; ?></td>
	<td><?php echo date_format(date_create($row->tanggal),'d/m/y'); ?></td>
	<td><?php echo $row->nama_user; ?></td>
	<td><?php echo $row->payment_type." ".$row->account; ?></td>
	<td align="right"><?php echo number_format($row->nominal,'0',',','.'); ?></td>
	<td><?php echo $row->keterangan; ?></td>
</tr>
<?php $i++; } ?>