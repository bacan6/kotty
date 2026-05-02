<div class="row">
	<div class="col-md-12">
		<select class="select2" id="topSubkategoriSelect">
			<?php foreach($subkategoriOptions as $opt){ ?>
				<option value="<?php echo $opt['id_subkategori']; ?>" <?php if($opt['id_subkategori']==$defaultSubkategoriId){ echo "selected"; } ?>>
					<?php echo $opt['subkategori']; ?>
				</option>
			<?php } ?>
		</select>
	</div>
</div>

<div id="topProdukBySubkategoriChartContainer">
	<?php echo $chartHtml; ?>
</div>

<script type="text/javascript">
	var tanggal = <?php echo json_encode($tanggal); ?>;
	var urlTopProductsBySubkategoriProducts = "<?php echo base_url('dashboard/topProdukBySubkategoriProducts'); ?>";

	jQuery('#topSubkategoriSelect').select2({ width: '270px' });
	jQuery('#topSubkategoriSelect').on('change', function(){
		var id_subkategori = jQuery(this).val();
		jQuery('#topProdukBySubkategoriChartContainer').load(urlTopProductsBySubkategoriProducts,{
			tanggal : tanggal,
			id_subkategori : id_subkategori
		});
	});
</script>

