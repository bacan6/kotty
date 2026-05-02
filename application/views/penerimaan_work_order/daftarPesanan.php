<?php
	error_reporting(0);
	$i = 1;
	foreach($daftarPesananOrder as $row){
?>
	<tr>
	   <td><?php echo $i; ?></td>
	   <td><?php echo $row->id_produk; ?></td>
	   <td><?php echo $row->nama_produk; ?></td>
	   <td align="center"><?php echo $row->qty; ?></td>
	   <td align="center">
	   		<?php
	   			$orderDiterima = $this->modelWorkOrder->orderDiterimaPeritem($row->id_produk,$noWO);
	   			echo $orderDiterima;
	   		?>
	   </td>
	   <td align="center">
	   		<?php echo $orderDiterima-$row->qty; ?>
	   </td>
	</tr>
<?php $i++; } ?>