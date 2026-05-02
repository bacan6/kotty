<?php
	foreach($viewCart as $row){
?>
<tr id="row<?php echo $row->id; ?>">
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><input type="text" class="form-control qty" id="produkTag<?php echo $row->id; ?>" data-id="<?php echo $row->id_produk; ?>" data-ai="<?php echo $row->id; ?>" value="<?php echo $row->qty; ?>"/></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>

<script type="text/javascript">
	$('.qty').on("change",function(){
		var qty = $(this).val();
		var idProduk = $(this).data('id');
		var id = $(this).data('ai');

		var urlUpdateCart = "<?php echo base_url('transferStok/updateCart'); ?>";

		$.ajax({
					method 	: "POST",
					url  	: urlUpdateCart,
					data 	: {idProduk : idProduk, qty : qty, idStore : idStore},
					success : function(response){
								if(response==0){
									$.Notification.notify('error','top right', 'Stok Tidak Mencukupi', 'Stok Yang Diinput Melebihi Stok Toko');
									//$('#dataCart').load(urlDataCart, {idStore : idStore});
									$('#produkTag'+id).val(1);
									$.post(urlUpdateCart,{idProduk : idProduk, qty : 1, idStore : idStore});
								} 
							  }
		});
	});

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapusCart = "<?php echo base_url('transferStok/hapusCart'); ?>";

		$.post(urlHapusCart,{idProduk : idProduk, idStore : idStore}, function(){
			$('#dataCart').load(urlDataCart,{idStore : idStore});
		});
	});
</script>