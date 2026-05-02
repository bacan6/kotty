<select class="form-control" id="subkategori_3" name="subkategori2">
	<option value="">--Semua--</option>
	<?php
	foreach($show_sub->result() as $row){
	?>
	<option value="<?php echo $row->id; ?>"><?php echo $row->kategori_3; ?></option>
	<?php } ?>
</select>

	