<?php
	$total = 0;

	$num = $viewCartPO->num_rows();

	if($num > 0 ){

	foreach($viewCartPO->result() as $row){
?>
<tr id="row<?php echo $row->id; ?>">
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td align="right"><?php echo $row->harga; ?></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $total = $total+($row->qty*$row->harga);} ?>

<tr>
	<td colspan="2" align="center"><b>TOTAL</b></td>
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
	var urlTotalCart = "<?php echo base_url('label_harga/totalCart'); ?>";
	$('#totalCart').load(urlTotalCart);

	$('.qty').on("keyup",function(){
		var idProduk = this.id;
		var qty = $(this).val();
		var id = $(this).data('id');

		var urlUpdateQty = "<?php echo base_url('label_harga/updateQtyCart'); ?>";

		$.post(urlUpdateQty,{idProduk : idProduk, qty : qty},function(response){
			$('#totalItem'+id).text(response);
			totalCart();
		});
	});

	$('.harga').on("change",function(){
		var idProduk = this.id;
		var harga = $(this).val();
		var id = $(this).data('id');

		var urlUpdateHarga = "<?php echo base_url('label_harga/updateHargaCart'); ?>";

		$.post(urlUpdateHarga,{idProduk : idProduk, harga : harga},function(response){
			$('#totalItem'+id).text(response);
			totalCart();
		});
	});

	function totalCart(){
		$('#totalCart').load(urlTotalCart);
	}

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapusCart = "<?php echo base_url('label_harga/hapusCart'); ?>";

		$.post(urlHapusCart,{idProduk : idProduk}, function(){
			$('#data-input').load(urlCartPO);
		});
	});
</script>