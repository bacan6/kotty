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
</table>
<br>
<table width="100%">
	<tr style="font-weight: bold;border-bottom: solid 1px #ccc;border-top: solid 1px #ccc;height: 40px;">
		<td>Tipe Bayar</td>
		<td align="right">Total</td>
		<td align="right">Diskon</td>
		<td align="right">Retur</td>
		<td align="right">Tebus Murah</td>
		<td align="right">Grand Total</td>
		<td></td>
	</tr>
	<tr style="height: 40px;">
		<?php
			$cash_value = $this->model_penjualan->cash_value($id,$tanggal);

			foreach($cash_value as $cs){
				$diskon = $this->model_penjualan->diskon_cash_value($id,$tanggal);
				$tebusmurah = $this->model_penjualan->tebusmurahCashValue($id,$tanggal);
				$retur_value = $this->model_penjualan->retur_value($id,$tanggal);
		?>

		<td width="20%" style="font-weight: bold;">Cash</td>
		<td width="20%" align="right"><?php echo number_format($cs->total-$tebusmurah->harga+$diskon+$retur_value,'0',',','.'); ?></td>
		<td width="15%" align="right"><?php echo number_format($diskon-$tebusmurah->diskon,'0',',','.'); ?></td>
		<td width="15%" align="right">
			<?php
				

				echo number_format($retur_value,'0',',','.')
			?>
		</td>
		<td width="15%" align="right"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
		<td width="20%" align="right"><?php echo number_format($cs->total,'0',',','.'); ?></td>
		<td width="20%" align="center" style="padding-left: 10px;">
			<input type="text" style="border:none;border-bottom:solid 1px #ccc;" id="value" data-payment_type="1" data-account_type="0"/>
		</td>
		<?php } ?>
	</tr>

	<tr style="height: 40px;">
		<td colspan="5" style="font-weight: bold;">Debit</td>
	</tr>

	<?php
		foreach($list_debit as $dbt){
			$id_account = $dbt->id_payment_account;

			$debit_value = $this->model_penjualan->debit_value($id_account,$id,$tanggal);
			
			foreach($debit_value as $dv){
				$diskon = $this->model_penjualan->diskon_debit_value($id_account,$id,$tanggal);
				$tebusmurah = $this->model_penjualan->tebusmurahDebitValue($id_account,$id,$tanggal);
	?>

	<tr style="height: 40px;">
		<td style="padding-left: 10px;">Debit <?php echo $dbt->account;?></td>
		<td align="right"><?php echo number_format($dv->total-$tebusmurah->harga+$diskon,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($diskon-$tebusmurah->diskon); ?></td>
		<td align="right">-</td>
		<td align="right"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($dv->total,'0',',','.'); ?></td>
		<td align="center" style="padding-left: 10px;">
			<input type="text" style="border:none;border-bottom:solid 1px #ccc;" id="value" data-payment_type="2" data-account_type="<?php echo $id_account; ?>"/>
		</td>
	</tr>

	<?php 
		}
			} 
	?>

	<tr style="height: 40px;">
		<td colspan="5" style="font-weight: bold;">Kredit</td>
	</tr>

	<?php
		foreach($list_kredit as $krd){
	
		$id_account_kr = $krd->id_payment_account;

		$kredit_value = $this->model_penjualan->kredit_value($id_account_kr,$id,$tanggal);
		$tebusmurah = $this->model_penjualan->tebusmurahKreditValue($id_account_kr,$id,$tanggal);

		foreach($kredit_value as $krv){

	?>
	<tr style="height: 40px;">
		<td style="padding-left: 10px;">Kredit <?php echo $krd->account;?></td>
		<td align="right"><?php echo number_format($krv->total-$tebusmurah->harga+$krv->diskon); ?></td>
		<td align="right"><?php echo number_format($krv->diskon-$tebusmurah->diskon); ?></td>
		<td align="right" align="right">-</td>
		<td align="right"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($krv->total,'0',',','.'); ?></td>
		<td align="center" style="padding-left: 10px;">
			<input type="text" style="border:none;border-bottom:solid 1px #ccc;" id="value"  data-payment_type="3" data-account_type="<?php echo $id_account_kr; ?>"/>
		</td>
	</tr>
	<?php 
		} 
			} 
	?>


	<?php
		$transfer_value = $this->model_penjualan->transfer_value($id,$tanggal);
		$tebusmurah = $this->model_penjualan->tebusmurahTransferValue($id,$tanggal);

		foreach($transfer_value as $trs){
	?>
	<tr style="height: 40px;">
		<td style="font-weight: bold;">Transfer</td>
		<td align="right"><?php echo number_format($trs->total-$tebusmurah->harga+$trs->diskon,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($trs->diskon+$tebusmurah->diskon,'0',',','.'); ?></td>
		<td align="right">-</td>
		<td align="right"><?php echo number_format($tebusmurah->harga-$tebusmurah->diskon,'0',',','.'); ?></td>
		<td align="right"><?php echo number_format($trs->total,'0',',','.'); ?></td>
		<td align="center" style="padding-left: 10px;">
			<input type="text" style="border:none;border-bottom:solid 1px #ccc;" id="value"  data-payment_type="4" data-account_type=""/>
		</td>
	</tr>
	<?php } ?>

	<tr>
		<td colspan="6" style="text-align: right;"><a href="<?php echo base_url('kasir/adjusment'); ?>" class="btn btn-info">Adjustment</a></td>
	</tr>
</table>
<br>
NB:<i>Nilai Retur dan diskon tidak dihitung lagi ke Grand Total, karena perlakuan di sistem saat retur sudah otomatis mengurangi omset.</i>
<!-- definisikan id dan tanggal untuk di kirim ke table closing id-->
<input type="hidden" id="idUser" value="<?php echo $id; ?>"/>
<input type="hidden" id="tanggal" value="<?php echo $tanggal; ?>"/>