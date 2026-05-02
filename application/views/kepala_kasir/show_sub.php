<label>Kategori</label>
<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="subkategori_2" name="subkategori">
	<option value="">--Pilih kategori--</option>
	<?php
	foreach($show_sub->result() as $row){
	?>
	<option value="<?php echo $row->id; ?>"><?php echo $row->kategori_level_1; ?></option>
	<?php } ?>
</select>

<script type="text/javascript">
	$('#subkategori_2').change(function(){
		id = $('#subkategori_2').val();

		url = "<?php echo base_url('kategoriDropdown/get_subkategori_2'); ?>";

		$('#sub_kategori_2').load(url,{id : id});
	});
</script>