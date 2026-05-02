<div class="row">
	<div class="col-md-12">
			<?php
				foreach($data_request->result() as $row){
			?>
			<table width="100%">
				<tr>
					<td width="23%">No Request</td>
					<td width="1%">:</td>
					<td><?php echo $row->purchase_no; ?></td>
				</tr>

				<tr>
					<td>User Request</td>
					<td width="1%">:</td>
					<td><?php echo $row->nama_user; ?></td>
				</tr>

				<tr>
					<td>Nama Item</td>
					<td width="1%">:</td>
					<td><?php echo $row->nama_bahan; ?></td>
				</tr>

				<tr>
					<td>Jumlah Request</td>
					<td width="1%">:</td>
					<td><?php echo $row->qty." ".$row->satuan; ?></td>
				</tr>
			</table>
			<?php } ?>
	</div>
</div>

<div class="row" style="margin-top: 10px;">
	<div class="col-md-12">
		<table class="table table-bordered tabke-striped" style="font-size: 10px;">
			<tr style="background: #2A303A;color:white;font-weight: bold;">
				<td width="3%">No</td>
				<td>Supplier</td>
				<td width="30%" align="right">Harga</td>
				<td width="20%">Remark</td>
				<td width="3%"></td>
			</tr>

			<?php
				$i = 1;
				foreach($item_request->result() as $dt){
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $dt->supplier; ?></td>
				<td align="right"><?php echo number_format($dt->harga,'0',',','.'); ?></td>
				<td><?php echo $dt->remark; ?></td>
				<td align="center">
					<?php 
						if($dt->isChoose==1){
							echo "<span class='badge bg-info'><i class='fa fa-check'></i></span>";
						} else {
							echo "-";
						}
					?>
				</td>
			</tr>
			<?php $i++; } ?>
		</table>
	</div>
</div>