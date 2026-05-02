<div class="row">
	<div class="col-md-12">
		<select class="select2" id="topKategoriSelect">
			<?php foreach($kategoriOptions as $opt){ ?>
				<option value="<?php echo $opt['id_kategori']; ?>" <?php if($opt['id_kategori']==$defaultKategoriId){ echo "selected"; } ?>>
					<?php echo $opt['kategori']; ?>
				</option>
			<?php } ?>
		</select>
	</div>
</div>

<div id="topProdukByKategoriChartContainer">
	<?php echo $chartHtml; ?>
</div>

<script type="text/javascript">
	var tanggal = <?php echo json_encode($tanggal); ?>;
	var urlTopProductsByKategoriProducts = "<?php echo base_url('dashboard/topProdukByKategoriProducts'); ?>";

	jQuery('#topKategoriSelect').select2({ width: '270px' });
	jQuery('#topKategoriSelect').on('change', function(){
		var id_kategori = jQuery(this).val();
		jQuery('#topProdukByKategoriChartContainer').load(urlTopProductsByKategoriProducts,{
			tanggal : tanggal,
			id_kategori : id_kategori
		});
	});
</script>

