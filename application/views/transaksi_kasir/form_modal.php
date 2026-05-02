<table width="100%">
	<tr>
		<td width="20%">Nama</td>
		<td width="1%">:</td>
		<td><?php echo $nama_kasir; ?></td>
	</tr>

	<tr>
		<td>Modal</td>
		<td>:</td>
		<td>
			<input type="text" class="form-control" id="modal_kasir"/>
			<input type="hidden" id="id_kasir" value="<?php echo $id; ?>"/>
			<input type="hidden" id="tanggal" value="<?php echo $tanggal; ?>"/>
		</td>
	</tr>
</table>

