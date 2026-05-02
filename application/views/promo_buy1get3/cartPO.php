<?php
	$total = 0;

	$num = $viewCartPO->num_rows();

	if($num > 0 ){

	foreach($viewCartPO->result() as $row){
?>
<tr id="row<?php echo $row->id; ?>">
	<td>
		<select class="form-control updateGroup" data-id="<?php echo $row->id_produk; ?>" style="width:70px;">
			<option value="A" <?php if($row->group_series=='A') echo 'selected'; ?>>A</option>
			<option value="B" <?php if($row->group_series=='B') echo 'selected'; ?>>B</option>
			<option value="C" <?php if($row->group_series=='C') echo 'selected'; ?>>C</option>
			<option value="D" <?php if($row->group_series=='D') echo 'selected'; ?>>D</option>
			<option value="E" <?php if($row->group_series=='E') echo 'selected'; ?>>E</option>
		</select>
	</td>
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><?php echo $row->stok; ?></td>
	<td><?php echo number_format($row->hpp,'0',',','.'); ?></td>
	<td><?php echo number_format($row->harga_jual,'0',',','.'); ?>
		<input type="hidden" id="harga_jual<?php echo $row->id; ?>" value="<?php echo $row->harga_jual;?>"></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>


<?php } else {
?>
	<tr>
		<td colspan="7" align="center">--Belum Ada Data Terinput--</td>
	</tr>
<?php	
} ?>

<script type="text/javascript">
	
	

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapusCart = "<?php echo base_url('promo_buy1get3/hapusCart'); ?>";

		$.post(urlHapusCart,{idProduk : idProduk}, function(){
			$('#data-input').load(urlCartPO);
		});
	});

	$('.updateGroup').on("change",function(){
		var idProduk = $(this).data('id');
		var group_series = $(this).val();
		var urlUpdateGroup = "<?php echo base_url('promo_buy1get3/updateGroupCart'); ?>";
		$.post(urlUpdateGroup,{idProduk : idProduk, group_series : group_series});
	});

	
	$("#btn-toko").on("click",function(){
		var setToko = $('#setToko').val();
		$(".harga").each(function( index, element ) {
			$(element).val(setToko);

			var idProduk = element.id;
			var harga = $(element).val();
			var id = $(element).data('id');
			var qty = $("#"+idProduk).val();
			var harga_jual = $('#harga_jual'+id).val();

			var urlUpdateHarga = "<?php echo base_url('promo_buy1get3/updateHargaCart'); ?>";

			$.post(urlUpdateHarga,{idProduk : idProduk, harga : harga, harga_jual : harga_jual, qty : qty},function(response){
				$('#totalItem'+id).text(response);
			});
		});
	});

	
</script>