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

		url = "<?php echo base_url('promo_supplier/get_subkategori_2'); ?>";

		$('#sub_kategori_2').load(url,{id : id});

		var kategori = $('#kategori').val();
		var brand = $('#brand').val();
		var subkategori     = $('#subkategori2').val();
		var subkategori2    = $('#subkategori_3').val();
		$.ajax({
					method      : "POST",
					url         : '<?php echo base_url('promo_supplier/ajax_produk_supplier'); ?>',
					data        : {brand : brand, kategori : kategori, subkategori : subkategori, subkategori2 : subkategori2},
					success     : function(noInv){
									
									$('#data-input').load(urlCartPO);
									}
		});
	});
</script>