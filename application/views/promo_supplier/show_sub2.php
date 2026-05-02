<select class="form-control" id="subkategori_3" name="subkategori2">
	<option value="">--Semua--</option>
	<?php
	foreach($show_sub->result() as $row){
	?>
	<option value="<?php echo $row->id; ?>"><?php echo $row->kategori_3; ?></option>
	<?php } ?>
</select>
<script type="text/javascript">
	$('#subkategori_3').change(function(){
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
	