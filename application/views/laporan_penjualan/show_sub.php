<select class="form-control" id="subkategori2" name="subkategori">
	<option value="">--Semua--</option>
	<?php
	foreach($show_sub->result() as $row){
	?>
	<option value="<?php echo $row->id; ?>"><?php echo $row->kategori_level_1; ?></option>
	<?php } ?>
</select>

<script type="text/javascript">
	$('#subkategori2').change(function(){
		id = $(this).val();

		url = "<?php echo base_url('laporan/get_subkategori_2'); ?>";

		$('#sub_kategori_2').load(url,{id : id});
	});
</script>