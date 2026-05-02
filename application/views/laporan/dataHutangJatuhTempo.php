<?php
	$i=1;
	$total = 0;
	$terbayar = 0;
	$saldo = 0;
	foreach($hutang_jatuh_tempo->result() as $row){
?>
<tr>
	<td align="center"><?php echo $i; ?></td>
	<td width="10%"><?php echo $row->no_tagihan; ?></td>
	<td width="10%"><?php echo date_format(date_create($row->tanggal_po),'d/m/y'); ?></td>
	<td width="10%"><?php echo date_format(date_create($row->jatuh_tempo),'d/m/y'); ?></td>
	<td><?php echo $row->supplier; ?></td>
	<td align="right">
		<?php echo number_format($row->total_hutang-$row->total_retur,'0',',','.'); ?>
	</td>
	<td align="right"><?php echo number_format($row->total_terbayar,'0',',','.'); ?></td>
	<td align="right"><?php echo number_format( ($row->total_hutang-$row->diskon-$row->total_retur)-$row->total_terbayar,'0',',','.' ); ?></td>
</tr>
<?php 
	$total = $total+($row->total_hutang-$row->diskon-$row->total_retur);
	$terbayar = $terbayar+$row->total_terbayar;
	$saldo = $saldo+($row->total_hutang-$row->diskon-$row->total_retur-$row->total_terbayar);
	$i++; 
	} 
?>

<tr>
	<td colspan="5" style="text-align: center;font-weight: bold;">TOTAL</td>
	<td align="right" style="font-weight: bold;"><?php echo number_format($total,'0',',','.'); ?></td>
	<td align="right" style="font-weight: bold;"><?php echo number_format($terbayar,'0',',','.'); ?></td>
	<td align="right" style="font-weight: bold;"><?php echo number_format($saldo,'0',',','.'); ?></td>
</tr>