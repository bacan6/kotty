<?php
	$total = 0;
	foreach($viewCartPO->result() as $row){
?>
<tr>
	<td><?php echo $row->nama_bahan; ?></td>
	<td><input type="number" value="<?php echo $row->qty; ?>" class="form-control qty" id="<?php echo $row->sku; ?>"/></td>
	<td><?php echo $row->satuan; ?></td>
	<td align="right"><input type="number" class="form-control harga" id="<?php echo $row->sku; ?>" value="<?php echo $row->harga; ?>"/></td>
	<td align="right"><?php echo number_format($row->qty*$row->harga,'0',',','.'); ?></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->sku; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $total = $total+($row->qty*$row->harga);} ?>

<tr>
	<td colspan="4" align="center"><b>TOTAL</b></td>
	<td align="right"><b><?php echo number_format($total,'0',',','.'); ?></b></td>
	<td></td>
</tr>

<script type="text/javascript">
	$('.qty').on("change",function(){
		var idProduk = this.id;
		var qty = $(this).val();

		var urlUpdateQty = "<?php echo base_url('purchaseOrderMaterial/updateQtyCart'); ?>";

		$.post(urlUpdateQty,{idProduk : idProduk, qty : qty},function(){
			$('#data-input').load(urlCartPO);
		});
	});

	$('.harga').on("change",function(){
		var idProduk = this.id;
		var harga = $(this).val();

		var urlUpdateHarga = "<?php echo base_url('purchaseOrderMaterial/updateHargaCart'); ?>";

		$.post(urlUpdateHarga,{idProduk : idProduk, harga : harga},function(){
			$('#data-input').load(urlCartPO);
		});
	});

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapusCart = "<?php echo base_url('purchaseOrderMaterial/hapusCart'); ?>";

		$.post(urlHapusCart,{idProduk : idProduk}, function(){
			$('#data-input').load(urlCartPO);
		});
	});
</script>