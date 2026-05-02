<?php
	foreach($viewCart as $row){
?>
<tr>
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><?php echo number_format($row->hargaBeli,'0',',','.'); ?></td>
	<td><input type="number" class="form-control qty" id="<?php echo $row->id_produk; ?>" value="<?php echo $row->qty; ?>"/></td>
	<td><?php echo $row->satuan; ?></td>
	<td><?php echo number_format($row->total,'0',',','.'); ?></td>
	<td align="center"><a class="hapus" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>

<script type="text/javascript">
	$('.qty').on("change",function(){
		var idProduk = this.id;
		var qty = $(this).val();

		var urlUpdateQty = "<?php echo base_url('konversiProduk/updateQtyCart'); ?>";

		$.ajax({
					method :"POST",
					url : urlUpdateQty,
					data : {idProduk : idProduk, qty : qty},
					success :function(response){
								if(response==0){
									$.Notification.notify('error','top right', 'Stok Tidak Mencukupi', 'Stok saat ini = 0');
									$('#dataCart').load(urlViewCart);
								}
							 }
		});
	});

	$('.hapus').on("click",function(){
		var idProduk = this.id;

		$.ajax({
					method : "POST",
					url : "<?php echo base_url('konversiProduk/hapusCart'); ?>",
					data : {idProduk : idProduk},
					success : function(){
								$('#dataCart').load(urlViewCart);	
							  }
		});
	});
</script>

