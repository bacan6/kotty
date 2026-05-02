<div class="row">
	<div class="col-md-6 col-xs-6 col-sm-6">
		<table width="100%">
			<tr>
				<td width="25%" style="font-weight: bold;">No Invoice</td>
				<td width="2%">:</td>
				<td><?php echo $invoiceDetail->no_invoice; ?></td>
			</tr>

			<tr>
				<td style="font-weight: bold;">Kasir</td>
				<td>:</td>
				<td><?php echo $invoiceDetail->nama_user; ?></td>
			</tr>

			<tr>
				<td style="font-weight: bold;">Toko</td>
				<td>:</td>
				<td><?php echo $invoiceDetail->store; ?></td>
			</tr>

			<tr>
				<td style="font-weight: bold;">Customer</td>
				<td>:</td>
				<td><?php echo $invoiceDetail->nama; ?></td>
			</tr>
		</table>
	</div>

	<div class="col-md-6 col-xs-6 col-sm-6">
		<table>
			<tr>
				<td width="25%" style="font-weight: bold;">Tipe Bayar</td>
				<td width="2%">:</td>
				<td><?php echo $invoiceDetail->payment_type." ".$invoiceDetail->account; ?></td>
			</tr>

			<tr>
				<td style="font-weight: bold;">Tanggal</td>
				<td>:</td>
				<td><?php echo date_format(date_create($invoiceDetail->tanggal),'d M Y H:i'); ?></td>
			</tr>

			<tr>
				<td style="font-weight: bold;">Keterangan</td>
				<td>:</td>
				<td><?php echo $invoiceDetail->keterangan; ?></td>
			</tr>
		</table>
	</div>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12">
		<table width="100%">
			<tr style="font-weight: bold;border-bottom: solid 1px black;border-top: solid 1px black;">
				<td>Produk</td>
				<td align="right">Diskon</td>
				<td align="right">Total</td>
			</tr>
			<?php
				$totalItem = 0;
				$totalDiskonPeritem = 0;
				foreach($invoiceItem as $row){
			?>
			<tr>
				<td>
					<?php 
					echo $row->nama_produk." (".$row->id_produk.") <br>";
					echo $row->qty." x ".number_format($row->harga_jual,'0',',','.');
					?>
				</td>
				<td align="right">
					<?php echo number_format($row->diskon,'0',',','.'); ?>
				</td>
				<td align="right">
					<?php 
						$total = ($row->harga_jual*$row->qty)-$row->diskon;
						echo number_format($total,'0',',','.')
					?>
				</td>
			</tr>
			<?php 
				$totalItem = $totalItem+$row->qty; 
				$totalDiskonPeritem = $totalDiskonPeritem+$row->diskon;
			} 
				
			?>
			<tr style="border-bottom: solid 1px black;">
				<td colspan="3"></td>
			</tr>

			<tr style="font-weight: bold;">
				<td colspan="2">Total Item</td>
				<td align="right"><?php echo number_format($totalItem,'0',',','.'); ?></td>
			</tr>
			<tr style="font-weight: bold;">
				<td colspan="2">Total Belanja</td>
				<td align="right">
					<?php
						echo number_format($invoiceDetail->total,'0',',','.');
					?>
				</td>
			</tr>
			<?php 
				if($totalDiskonPeritem > 0) {
			?>
			<tr style="font-weight: bold;">
				<td colspan="2">Total Diskon Peritem</td>
				<td align="right"><?php echo number_format($totalDiskonPeritem,'0',',','.'); ?></td>
			</tr>
			<?php } ?>

			<?php
				if($invoiceDetail->diskon_free > 0){
			?>
			<tr style="font-weight: bold;">
				<td colspan="2">Diskon</td>
				<td align="right"><?php echo number_format($invoiceDetail->diskon_free,'0',',','.'); ?></td>
			</tr>
			<?php } ?>

			<?php
				if($invoiceDetail->diskon > 0){
			?>
			<tr style="font-weight: bold;">
				<td colspan="2">Diskon Member</td>
				<td align="right"><?php echo number_format($invoiceDetail->diskon,'0',',','.'); ?></td>
			</tr>
			<?php } ?>

			<?php
				if($invoiceDetail->ongkir > 0){
			?>
			<tr style="font-weight: bold;">
				<td colspan="2">Ongkir</td>
				<td align="right"><?php echo number_format($invoiceDetail->ongkir,'0',',','.'); ?></td>
			</tr>
			<?php } ?>
			

			<tr>
				<td colspan="2"></td>
				<td style="border-bottom: solid 1px black;"></td>
			</tr>

			<?php 
				$subtotal = ($totalDiskonPeritem+$invoiceDetail->diskon_free+$invoiceDetail->diskon)+$invoiceDetail->ongkir;

				if($subtotal > 0){
			?>
			<tr style="font-weight: bold;">
				<td colspan="2">Subtotal</td>
				<td align="right">
					<?php
						echo number_format($invoiceDetail->total-$subtotal,'0',',','.');
					?>
				</td>
			</tr>
			<?php } ?>
			
			
			<?php
				if($invoiceDetail->surcharge > 0){
			?>
			<tr style="font-weight: bold;">
				<td colspan="2">Surcharge</td>
				<td align="right"><?php echo number_format($invoiceDetail->surcharge,'0',',','.'); ?></td>
			</tr>
			<?php } ?>
			<tr style="font-weight: bold;border-top: solid 1px black;">
				<td colspan="2">TOTAL</td>
				<td align="right">
					<?php
						$total = $invoiceDetail->total-$subtotal+$invoiceDetail->surcharge;
						echo number_format($total,'0',',','.');
					?>
				</td>
			</tr>
		</table>
	</div>
</div>