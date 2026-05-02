<?php
	$i = 1;
	$total = 0;
	foreach($riwayatPembayaran as $row){
?>
<tr>
	<td><?php echo $i; ?></td>
	<td><a href="<?php echo base_url('finance/invoicePembayaran?no_payment='.$row->no_payment); ?>"><?php echo $row->no_payment; ?></a></td>
	<td><?php echo $row->nama_user; ?></td>
	<td><?php echo $row->paymentType; ?></td>
	<td align="right"><?php echo number_format($row->pembayaran,'2',',','.'); ?></td>
</tr>
<?php $i++; $total = $total+$row->pembayaran; } ?>

<tr>
	<td align="center" colspan="4" style="font-weight: bold;">TOTAL</td>
	<td style="font-weight: bold;text-align: right;"><?php echo number_format($total,'2',',','.'); ?></td>
</tr>