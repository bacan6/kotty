<table width="100%">
	<tr style="font-weight: bold;">
		<td width="20%">Nama Kasir</td>
		<td width="1%">:</td>
		<td><?php echo $nama_kasir; ?></td>
	</tr>

	<tr style="font-weight: bold;">
		<td width="20%">Tanggal</td>
		<td width="1%">:</td>
		<td><?php echo date_format(date_create($tanggal),'d M Y'); ?></td>
	</tr>

	<tr style="font-weight: bold;">
		<td width="20%">Jam Closing</td>
		<td width="1%">:</td>
		<td><?php echo $jamClosing; ?></td>
	</tr>

	<tr style="font-weight: bold;">
		<td width="20%">No Closing</td>
		<td width="1%">:</td>
		<td><?php echo $noClosing; ?></td>
	</tr>
</table>
<br>
<table width="100%">
	<tr style="font-weight: bold;border-bottom: solid 1px #ccc;border-top: solid 1px #ccc;height: 40px;">
		<td width="15%">Tipe Bayar</td>
		<td align="right" width="10%">Total</td>
		<td align="right" width="10%">Diskon</td>
		<td align="right" width="10%">Retur</td>
		<td align="right" width="10%">Grand Total</td>
		<td align="right" width="10%">Nilai Closing</td>
		<td align="right" width="10%">Selisih</td>
		<td align="right" width="25%">Tebus Murah</td>
	</tr>
	<tr style="height: 40px;">
		<?php
			$cash_value = $this->model_penjualan->cash_value($id,$tanggal);
			$tDiskon = 0;
			$tTebusMurah = 0;
			$tRetur = 0;
			$tGrand = 0;
			$tClosing = 0;
			$tSelisih = 0;
			$tTotal = 0;

			foreach($cash_value as $cs){
				$diskon = $this->model_penjualan->diskon_cash_value($id,$tanggal);
				$tebusmurah = $this->model_penjualan->tebusmurahCashValue($id,$tanggal);
				$retur_value = $this->model_penjualan->retur_value($id,$tanggal);
				$nilaiClosingCash = $this->model_penjualan->nilaiClosingCash($id,$tanggal);

			$tDiskon += $diskon;
			$tTebusMurah += $tebusmurah->harga-$tebusmurah->diskon;
			$tRetur += $retur_value;
			$tGrand += $cs->total-($diskon);
			$tClosing += $nilaiClosingCash;
			$tSelisih += $nilaiClosingCash-($cs->total-($diskon));
			$tTotal += $cs->total; 
				
		?>

		<td style="font-weight: bold;">Cash</td>
		<td align="right"><?php echo number_format($cs->total,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
		<td align="right">
			<?php
				

				echo number_format($retur_value,'0',',','.')
			?>
		</td>
		<td width="15%" align="right"><?php echo number_format($cs->total-($diskon),'0',',','.'); ?></td>
		<td width="15%" align="right">
			<?php
				

				echo number_format($nilaiClosingCash,'0',',','.');
			?>
		</td>
		<td align="right">
			<?php 
				echo number_format($nilaiClosingCash-($cs->total-($diskon)));
			?>
		</td>
		<td align="right" style="padding:4px" width="15%"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
		<?php } ?>
	</tr>

	<tr style="height: 40px;">
		<td colspan="8" style="font-weight: bold;">Debit</td>
	</tr>

	<?php
		foreach($list_debit as $dbt){
			$id_account = $dbt->id_payment_account;

			$debit_value = $this->model_penjualan->debit_value($id_account,$id,$tanggal);
			
			$debitValueClosing = $this->model_penjualan->debitValueClosing($id_account,$id,$tanggal);

			foreach($debit_value as $dv){
				$diskon = $this->model_penjualan->diskon_debit_value($id_account,$id,$tanggal);
				$tebusmurah = $this->model_penjualan->tebusmurahDebitValue($id_account,$id,$tanggal);

			$tDiskon += $diskon;
			$tTebusMurah += $tebusmurah->harga-$tebusmurah->diskon;
			//$tRetur += $retur_value;
			$tGrand += $dv->total-$diskon;
			$tClosing += $debitValueClosing;
			$tSelisih += $debitValueClosing-($dv->total-$diskon);
			$tTotal += $dv->total;
	?>

	<tr style="height: 40px;">
		<td style="padding-left: 10px;">Debit <?php echo $dbt->account;?></td>
		<td align="right"><?php echo number_format($dv->total,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($diskon); ?></td>
		<td align="right">-</td>
		<td align="right"><?php echo number_format($dv->total-$diskon,'0',',','.'); ?></td>
		<td align="right" >
			<?php echo number_format($debitValueClosing,'0',',','.'); ?>
		</td>
		<td align="right">
			<?php 
				echo number_format($debitValueClosing-($dv->total-$diskon),'0',',','.')
			?>
		</td>
		<td align="right" style="padding:4px"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
	</tr>

	<?php 
		}
			} 
	?>

	<tr style="height: 40px;">
		<td colspan="8" style="font-weight: bold;">Kredit</td>
	</tr>

	<?php
		foreach($list_kredit as $krd){
	
		$id_account_kr = $krd->id_payment_account;

		$kredit_value = $this->model_penjualan->kredit_value($id_account_kr,$id,$tanggal);

		$kreditValueClosing = $this->model_penjualan->kreditValueClosing($id_account_kr,$id,$tanggal);
		
		$tebusmurah = $this->model_penjualan->tebusmurahKreditValue($id_account_kr,$id,$tanggal);
		foreach($kredit_value as $krv){
			$tDiskon += $krv->diskon;
			$tTebusMurah += $tebusmurah->harga-$tebusmurah->diskon;
			//$tRetur += $retur_value;
			$tGrand += $krv->total-$krv->diskon;
			$tClosing += $kreditValueClosing;
			$tSelisih += $kreditValueClosing-($krv->total-$krv->diskon);
			$tTotal += $krv->total;
	?>
	<tr style="height: 40px;">
		<td style="padding-left: 10px;">Kredit <?php echo $krd->account;?></td>
		<td align="right"><?php echo number_format($krv->total); ?></td>
		<td align="right"><?php echo number_format($krv->diskon); ?></td>
		<td align="right" align="right">-</td>
		<td align="right"><?php echo number_format($krv->total-$krv->diskon,'0',',','.'); ?></td>
		<td align="right" >
			<?php echo number_format($kreditValueClosing,'0',',','.'); ?>
		</td>
		<td align="right">
			<?php 
				echo number_format($kreditValueClosing-($krv->total-$krv->diskon),'0',',','.'); 
			?>
		</td>
		<td align="right" style="padding:4px"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
	</tr>
	<?php 
		} 
			} 
	?>


	<?php
		$transfer_value = $this->model_penjualan->transfer_value($id,$tanggal);
			$tebusmurah = $this->model_penjualan->tebusmurahTransferValue($id,$tanggal);
		foreach($transfer_value as $trs){
			$nilaiClosingTransfer = $this->model_penjualan->nilaiClosingTransfer($id,$tanggal);
			$tDiskon += $trs->diskon;
			$tTebusMurah += $tebusmurah->harga-$tebusmurah->diskon;
			//$tRetur += $retur_value;
			$tGrand += $trs->total-$trs->diskon;
			$tClosing += $nilaiClosingTransfer;
			$tSelisih += $nilaiClosingTransfer-($trs->total-$trs->diskon);
			$tTotal += $trs->total;
	?>
	<tr style="height: 40px;">
		<td style="font-weight: bold;">Transfer</td>
		<td align="right"><?php echo number_format($trs->total,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($trs->diskon,'0',',','.'); ?></td>
		<td align="right">-</td>
		<td align="right"><?php echo number_format($trs->total-$trs->diskon,'0',',','.'); ?></td>
		<td align="right">
			<?php
				

				echo number_format($nilaiClosingTransfer,'0',',','.');
			?>
		</td>
		<td align="right">
			<?php
				echo number_format($nilaiClosingTransfer-($trs->total-$trs->diskon),'0',',','.');
			?>
		</td>
		<td align="right" style="padding:4px"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<td><strong>T O T A L</strong></td>
		<td align="right"><?php echo number_format($tTotal,0)?></td>
		<td align="right"><?php echo number_format($tDiskon,0)?></td>
		<td align="right"><?php echo number_format($tRetur,0)?></td>
		<td align="right"><?php echo number_format($tGrand,0)?></td>
		<td align="right"><?php echo number_format($tClosing,0)?></td>
		<td align="right"><?php echo number_format($tSelisih,0)?></td>
		<td align="right"><?php echo number_format($tTebusMurah,0)?></td>
	</tr>
</table>	

<br>
<br>
<table width="100%">
	<tr style="font-weight: bold;height: 100px;">
		<td width="33.3%" align="center" style="vertical-align: bottom;text-decoration: underline;">Finance</td>
		<td width="33.3%" align="center" style="vertical-align: bottom;text-decoration: underline;">Head Store</td>
		<td width="33.3%" align="center" style="vertical-align: bottom;text-decoration: underline;">Kasir</td>
	</tr>
</table>

<br>
NB:<i>Nilai Retur tidak dihitung lagi ke Grand Total, karena perlakuan di sistem saat retur sudah otomatis mengurangi omset.</i>
<!-- definisikan id dan tanggal untuk di kirim ke table closing id-->
<input type="hidden" id="idUser" value="<?php echo $id; ?>"/>
<input type="hidden" id="tanggal" value="<?php echo $tanggal; ?>"/>