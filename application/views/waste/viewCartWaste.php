<?php
	foreach($viewCart as $row){
?>
<tr id="row<?php echo $row->id; ?>">
	<td width="20%"><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><input type="number" class="form-control qty" id="qty<?php echo $row->id; ?>" data-id_produk="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" value="<?php echo $row->qty; ?>"/></td>
	<td><?php echo $row->satuan; ?></td>
	<td style="text-align: center;"><a class="hapusCart" id="<?php echo $row->id; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>

<script type="text/javascript">
	$('.qty').change(function(){
		var qty = $(this).val();
		var idProduk = $(this).data('id_produk');
		var id = $(this).data('id');

		var urlUpdateQty = "<?php echo base_url('waste/updateQtyCart'); ?>";

		$.ajax({
					method : "POST",
					url : urlUpdateQty,
					data : {qty : qty, idProduk : idProduk},
					success : function(response){
								if(response == 0 ){
									$.Notification.notify('error','top right', 'Tidak ada stok', 'Stok Saat Ini 0');  
									var qtyOnCart = "<?php echo base_url('waste/qtyOnCart'); ?>";
									$.post(qtyOnCart,{id : id},function(result){
										$('#qty'+id).val(result)
									});
								} 
							  }	
		});
	});	

	$('.hapusCart').on("click",function(){
		var id = this.id;
		var urlHapusCart = "<?php echo base_url('waste/hapusCart'); ?>";

		$.ajax({
					method : "POST",
					url : urlHapusCart,
					data : {id : id},
					error : function(){
								alert("Terjadi Kesalahan");
							},
					success : function(){
								var dataUrl = "<?php echo base_url('waste/viewCartWaste'); ?>";
								$('#data-input').load(dataUrl);
							  }
		});
	});
</script>