<center>
	<p style="font-size: 14px;	">
	<?php
		echo $ap_receipt->nama_perusahaan."<br>";
		echo $ap_receipt->alamat;

	?>
	</p>
	<p>Laporan Retur Pembelian <br>
		Periode <?php echo $dateStart." - ".$dateEnd; ?>
	</p>
</center>

<table width="100%">
	<?php 
		$totalKeseluruhan = 0;
		foreach($viewReport as $dt){
	?>

	<tr style="border-top: solid 2px #12A89D;">
		<td colspan="7">
			<table width="100%">
				<tr style="font-weight: bold;color:#12A89D;">
					<td style="vertical-align: top;" width="25%">No PO : <?php echo $dt->no_po; ?></td>
					<td style="vertical-align: top;" width="25%">
						Supplier : <?php echo $dt->supplier;  ?> 
					</td>	
				</tr>
			</table>
		</td>
	</tr>

	<tr style="color: black;font-weight: bold;border-top: solid 1px black;border-bottom: solid 1px black;">
		<td width="15%">Tanggal</td>
		<td width="15%">SKU</td>
		<td width="30%">Nama Produk</td>
		<td align="right" width="12%">Retur</td>
		<td align="center" width="10%">Unit</td>
		<td align="right">Harga Satuan</td>
		<td align="right">Subtotal</td>
	</tr>

	<?php
		$dataRetur = $this->modelLaporan->dataRetur($dt->no_po);

		$total = 0;
		foreach($dataRetur as $row){
	?>
	<tr>
		<td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
		<td><?php echo $row->id_produk; ?></td>
		<td><?php echo $row->nama_produk; ?></td>
		<td align="right"><?php echo number_format($row->qty,'0',',','.'); ?></td>
		<td align="center"><?php echo $row->satuan;?></td>
		<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
	</tr>


	<?php $total = $total+($row->total); } //data pembelian end ?>

	<tr style="border-top: solid 2px #ccc;margin-top: 20px;height: 50px;">
		<td style="vertical-align: top;">Penginput :</td>	
		<td style="vertical-align: top;"><?php echo $dt->first_name; ?></td>
		<td colspan="4" style="text-align:right;font-weight: bold;vertical-align: top;">TOTAL</td>
		<td align="right" style="vertical-align: top;"><?php echo number_format($total,'0',',','.'); ?></td>
	</tr>

	<?php $totalKeseluruhan = $totalKeseluruhan+$total; } //data po end ?>

	<tr style="font-size: 18px;border-top: solid 1px black;">
		<td style="text-align: center;font-weight: bold;" colspan="6">TOTAL KESELURUHAN</td>
		<td style="font-weight: bold;text-align: right;"><?php echo number_format($totalKeseluruhan,'0',',','.'); ?></td>
	</tr>
</table>
