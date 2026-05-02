<?php
	$total = 0;

	$num = $viewCartPO->num_rows();

	if($num > 0 ){

	foreach($viewCartPO->result() as $row){
?>
<tr id="row<?php echo $row->id; ?>">
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
	<td align="right" id="stokBefore<?php echo $row->id; ?>"><?php echo number_format($row->stok_before,'0',',','.'); ?></td>
	<td><input type="text" value="<?php echo $row->stok_after; ?>" class="form-control stok_after" id="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>"/></td>
	<td align="right" id="totalItem<?php echo $row->id; ?>"><?php echo number_format($row->stok_after*$row->harga,'0',',','.'); ?></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $total = $total+($row->stok_after*$row->harga);} ?>

<tr>
	<td colspan="5" align="center"><b>TOTAL</b></td>
	<td align="right" id="totalCart" style="font-weight: bold;"></td>
	<td></td>
</tr>

<?php } else {
?>
	<tr>
		<td colspan="7" align="center">--Belum Ada Data Terinput--</td>
	</tr>
<?php	
} ?>

<script type="text/javascript">
	var urlTotalCart = "<?php echo base_url('so_peritem/totalCart'); ?>";
	$('#totalCart').load(urlTotalCart);

	$('.stok_after').on("keyup",function(){
		var idProduk = this.id;
		var stok_after = $(this).val();
		var id = $(this).data('id');

		var urlUpdateQty = "<?php echo base_url('so_peritem/updateQtyCart'); ?>";

		$.post(urlUpdateQty,{idProduk : idProduk, qty : stok_after},function(response){
			$('#totalItem'+id).text(response);
			totalCart();
		});
	});

	$('.min').on("change",function(){
		var idProduk = this.id;
		var min = $(this).val();
		var id = $(this).data('id');

		var urlUpdateMin = "<?php echo base_url('so_peritem/updateMinCart'); ?>";

		$.post(urlUpdateMin,{idProduk : idProduk, min : min},function(response){
			
		});
	});

	$('.max').on("change",function(){
		var idProduk = this.id;
		var max = $(this).val();
		var id = $(this).data('id');

		var urlUpdateMax = "<?php echo base_url('so_peritem/updateMaxCart'); ?>";

		$.post(urlUpdateMax,{idProduk : idProduk, max : max},function(response){
			
		});
	});

	function totalCart(){
		$('#totalCart').load(urlTotalCart);
	}

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapusCart = "<?php echo base_url('so_peritem/hapusCart'); ?>";

		$.post(urlHapusCart,{idProduk : idProduk}, function(){
			$('#data-input').load(urlCartPO);
		});
	});
</script>