<?php
	$i=1;
	foreach($item_transfer_temp->result() as $row){
?>
<tr>
	<td align="center"><?php echo $i; ?></td>
	<td><?php echo $row->sku; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td align="right"><a href="#edit_transfer_temp" data-toggle="modal"><?php echo $row->qty; ?></a></td>
</tr>
<?php $i++; } ?>


