<?php
	$numRows = $viewCart->num_rows();

	if($numRows < 1){
?>

<tr>
	<td colspan="4">Belum ada data</td>
</tr>

<?php
	} else {

	foreach($viewCart->result() as $row){
?>
<tr>
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><input type="number" value="<?php echo $row->qty; ?>" class="form-control qty" id="<?php echo $row->id_produk; ?>"/></td>
	<td><a class="hapus" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>	
</tr>

<?php } } ?>

<script type="text/javascript">
	$('.hapus').on("click",function(){
		var idProduk = this.id;
		var urlHapus = "<?php echo base_url('workOrder/hapusCartConvert'); ?>";

		$.post(urlHapus,{idProduk : idProduk},function(){
			$('#convertItem').load(urlCartProdukConvert);
		});
	});

	$('.qty').on("change",function(){
		var idProduk = this.id;
		var qty = $(this).val();
		var urlUpdateConvert = "<?php echo base_url('workOrder/updateCartConvert'); ?>";

		$.post(urlUpdateConvert,{idProduk : idProduk, qty : qty},function(){
			$('#convertItem').load(urlCartProdukConvert);
		});
	});
</script>