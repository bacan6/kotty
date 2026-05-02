<?php
	$i = 1;
	$diskonPeritem = 0;
	foreach($invoiceItem->result() as $row){
?>
	<tr>
		<td align="center" style="vertical-align: top;"><?php echo $i; ?></td>
		<td style="vertical-align: top;"><?php echo $row->id_produk; ?></td>
		<td style="vertical-align: top;"><?php echo $row->nama_produk; ?></td>
		<td style="text-align: right;vertical-align: top;"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
		<td style="text-align: center;vertical-align: top;"><?php echo number_format($row->qty,'0',',','.'); ?></td>
		<td style="text-align: right;vertical-align: top;">
			<?php echo number_format($row->qty*$row->harga_jual,'0',',','.'); ?>
										
			<?php
				if($row->diskon > 0){
					echo "<br>";
					echo "<i>(-".number_format($row->diskon,'0',',','.').")</i>";
				}
			?>
		</td>
	</tr>
<?php 
	$diskonPeritem = $diskonPeritem+$row->diskon;
	$i++; }

	$grandTotal = ($invoiceInfo->ongkir+$invoiceInfo->total)-($invoiceInfo->diskon+$invoiceInfo->diskon_free+$invoiceInfo->poin_value+$diskonPeritem); 
?>

<tr style="border-top: solid 1px black;">
	<td colspan="5" align="right"><b>Subtotal</b></td>
	<td style="text-align: right;"><?php echo number_format($invoiceInfo->total-$diskonPeritem,'0',',','.'); ?></td>
</tr>

<?php
	if(!empty($invoiceInfo->ongkir)){
?>
<tr>
	<td colspan="5" align="right"><b>Ongkir</b></td>
	<td style="text-align: right;"><?php echo number_format($invoiceInfo->ongkir,'0',',','.'); ?></td>
</tr>
<?php
	}
?>

<?php
	if(!empty($invoiceInfo->diskon)){
?>
<tr>
	<td colspan="5" align="right"><b>Diskon Member</b></td>
	<td style="text-align: right;"><?php echo number_format($invoiceInfo->diskon,'0',',','.'); ?></td>
</tr>
<?php
	}
?>

<?php
	if(!empty($invoiceInfo->diskon_free)){
?>
<tr>
	<td colspan="5" align="right"><b>Diskon</b></td>
	<td style="text-align: right;"><?php echo number_format($invoiceInfo->diskon_free,'0',',','.'); ?></td>
</tr>
<?php
	}
?>

<?php
	if(!empty($invoiceInfo->poin_value)){
?>
	<tr>
		<td colspan="5" align="right"><b>Poin Reimburs</b></td>
		<td style="text-align: right;"><?php echo number_format($invoiceInfo->poin_value,'0',',','.'); ?></td>
	</tr>
<?php
	}
?>
<tr>
	<td colspan="5" align="right"><b>Grand Total</b></td>
	<td style="text-align: right;"><?php echo number_format(($invoiceInfo->ongkir+$invoiceInfo->total)-($invoiceInfo->diskon+$invoiceInfo->diskon_free+$invoiceInfo->poin_value+$diskonPeritem),'0',',','.'); ?></td>
</tr>

<tr>
	<td colspan="5" align="right"><b>Terbayar</b></td>
	<td align="right" style="font-weight: bold;"><?php echo number_format($totalTerbayar,'0',',','.'); ?></td>
</tr>

<tr>
	<td colspan="5" align="right"><b>Sisa Pembayaran</b></td>
	<td align="right" style="font-weight: bold;"><?php echo number_format($grandTotal-$totalTerbayar,'0',',','.'); ?></td>
</tr>