<label>Subkategori</label>
<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="subkategori_3" name="subkategori2">
	<option value="">--Pilih Subkategori--</option>
	<?php
	foreach($show_sub->result() as $row){
	?>
	<option value="<?php echo $row->id; ?>"><?php echo $row->kategori_3; ?></option>
	<?php } ?>
</select>
<script>
$('#generate').click(function(){
                setNewSKU();
            });
</script>

	