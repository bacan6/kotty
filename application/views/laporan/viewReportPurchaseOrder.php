<center>
	<p style="font-size: 14px;	">
	<?php
		echo $ap_receipt->nama_perusahaan."<br>";
		echo $ap_receipt->alamat;

	?>
	</p>
	<p>Laporan Pembelian <br>
		Periode <?php echo $dateStart." - ".$dateEnd; ?>
	</p>
</center>

<table width="100%">
	<?php 
		foreach($viewReport as $dt){
	?>

	<tr style="border-top: solid 2px #12A89D;">
		<td colspan="8">
			<table width="100%">
				<tr style="font-weight: bold;color:#12A89D;">
					<td style="vertical-align: top;" width="25%">No PO : <?php echo $dt->no_po; ?></td>
					<td style="vertical-align: top;" width="25%">Tanggal : <?php echo date_format(date_create($dt->tanggal_po),'d M Y'); ?></td>	
					<td style="vertical-align: top;" width="25%">
						Supplier : <?php echo $dt->supplier;  ?> 
					</td>	
					<td style="vertical-align: top;">
						<?php
							switch ($dt->status) {
								case '0':
									$status = "Menunggu Approve";
									break;

								case '1':
									$status = "Diterima";
									break;

								case '2':
									$status = "Ditolak";
									break;

								case '3':
									$status = "Selesai";
									break;
								
								default:
									$status = "";
									break;
							}
						?>
						Status : <?php echo $status; ?><br>
						Keterangan : <?php echo $dt->keterangan; ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr style="color: black;font-weight: bold;border-top: solid 1px black;border-bottom: solid 1px black;">
		<td width="15%">SKU</td>
		<td width="24%">Nama Produk</td>
		<td align="right" width="10%">Dipesan</td>
		<td align="right" width="10%">Diterima</td>
		<td align="right" width="10%">Retur</td>
		<td align="center" width="10%">Unit</td>
		<td align="right">Harga Satuan</td>
		<td align="right">Subtotal</td>
	</tr>

	<?php
		$dataPembelian = $this->modelLaporan->purchaseItem($dt->no_po);

		$total = 0;
		foreach($dataPembelian as $row){
	?>
	<tr>
		<td style="vertical-align: top;"><?php echo $row->id_produk; ?></td>
		<td style="vertical-align: top;"><?php echo $row->nama_produk; ?></td>
		<td align="right" style="vertical-align: top;"><?php echo number_format($row->qty,'0',',','.'); ?></td>
		<td align="right" style="vertical-align: top;">
			<?php
            	$delivered_qty  = $this->modelLaporan->delivered_qty($dt->no_po,$row->id_produk);

            	echo number_format($delivered_qty,'0',',','.');
			?>
		</td>
		<td align="right" style="vertical-align: top;">
			<?php
				$retur = $this->modelLaporan->returItem($dt->no_po,$row->id_produk);

				echo number_format($retur,'0',',','.');
			?>
		</td>
		<td align="center" style="vertical-align: top;"><?php echo $row->satuan;?></td>
		<td align="right" style="vertical-align: top;"><?php echo number_format($row->harga,'0',',','.'); ?></td>
		<td align="right" style="vertical-align: top;"><?php echo number_format($row->harga*($delivered_qty-$retur),'0',',','.'); ?></td>
	</tr>

	<?php $total = $total+($row->harga*($delivered_qty-$retur)); } //data pembelian end ?>

	<tr style="border-top: solid 2px #ccc;margin-top: 20px;">
		<td>Penginput :</td>	
		<td><?php echo $dt->first_name; ?></td>
		<td colspan="5" style="text-align:right;font-weight: bold;vertical-align: top;">Total</td>
		<td align="right" style="vertical-align: top;"><?php echo number_format($total,'0',',','.'); ?></td>
	</tr>

	<tr>
		<td colspan="7" style="text-align:right;font-weight: bold;vertical-align: top;">Terbayar</td>
		<td align="right" style="vertical-align: top;">
			<?php 
				$hutangTerbayar = $this->modelLaporan->hutangTerbayar($dt->no_po);
				echo number_format($hutangTerbayar,'0',',','.'); 
			?>
		</td>
	</tr>

	<tr style="height: 50px;">
		<td colspan="7" style="text-align:right;font-weight: bold;vertical-align: top;">Sisa Pembayaran</td>
		<td align="right" style="vertical-align: top;"><?php echo number_format($total-$hutangTerbayar,'0',',','.'); ?></td>
	</tr>

	<?php } //data po end ?>
</table>