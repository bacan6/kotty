<?php
	$total = 0;

	$num = $viewCartPO->num_rows();

	if($num > 0 ){

	foreach($viewCartPO->result() as $row){
?>
<tr id="row<?php echo $row->id; ?>">
<td><input type="text" value="<?php echo $row->minimal_belanja; ?>" class="form-control nominal" id="nominal<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id_produk; ?>" size=6/></td>	
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><?php echo $row->stok; ?></td>
	<td><?php echo number_format($row->hpp,'0',',','.'); ?></td>
	<td><?php echo number_format($row->harga_jual,'0',',','.'); ?>
		<input type="hidden" id="harga_jual<?php echo $row->id; ?>" value="<?php echo $row->harga_jual;?>"</td>
	
	<td><input type="text" value="<?php echo $row->paid_item; ?>" class="form-control paid" id="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" size=3/></td>
	<td><input type="text" value="<?php echo $row->free_item; ?>" class="form-control free" id="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" size=5/></td>
	<td align="right"><input type="text" class="form-control quota" id="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" value="<?php echo $row->quota; ?>" size=14 style="width:80px"/></td>
	<td align="right" id="totalItem<?php echo $row->id; ?>"><?php echo number_format($row->free_item*$row->harga,'0',',','.'); ?></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>


<?php } else {
?>
	<tr>
		<td colspan="9" align="center">--Belum Ada Data Terinput--</td>
	</tr>
<?php	
} ?>

<script type="text/javascript">
	
	$(".nominal").on("change",function() {
			var nominal = $(this).val();
			var id = $(this).data('id');
			var urlUpdateNominal = "<?php echo base_url('promo_buy1get3/updateNominalCart'); ?>";

			$.post(urlUpdateNominal,{idProduk : id, nominal : nominal},function(response){
				//$('#totalItem'+id).text(response);
			});
			

		});
	$('.paid').on("keyup",function(){
		var idProduk = this.id;
		var qty = $(this).val();
		var id = $(this).data('id');
		var harga_jual = $('#harga_jual'+id).val();

		var urlUpdateQty = "<?php echo base_url('promo_buy1get3/updateQtyPaidCart'); ?>";

		$.post(urlUpdateQty,{idProduk : idProduk, qty : qty},function(response){
			$('#totalItem'+id).text(response);
		});
	});
	$('.free').on("keyup",function(){
		var idProduk = this.id;
		var qty = $(this).val();
		var id = $(this).data('id');

		var urlUpdateQty = "<?php echo base_url('promo_buy1get3/updateQtyFreeCart'); ?>";

		$.post(urlUpdateQty,{idProduk : idProduk, qty : qty},function(response){
			$('#totalItem'+id).text(response);
		});
	});

	$('.quota').on("change",function(){
		var idProduk = this.id;
		var quota = $(this).val();
		var id = $(this).data('id');

		var urlUpdateHarga = "<?php echo base_url('promo_buy1get3/updateQuotaCart'); ?>";

		$.post(urlUpdateHarga,{idProduk : idProduk, quota : quota},function(response){
			//$('#totalItem'+id).text(response);
		});
	});

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapusCart = "<?php echo base_url('promo_buy1get3/hapusCart'); ?>";

		$.post(urlHapusCart,{idProduk : idProduk}, function(){
			$('#data-input').load(urlCartPO);
		});
	});

	$("#btn-quota").on("click",function(){
		var setKuota = $('#setQuota').val();
		$(".quota").each(function( index, element ) {
			$(element).val(setKuota);

			var idProduk = element.id;
			var quota = $(element).val();
			var id = $(element).data('id');

			var urlUpdateHarga = "<?php echo base_url('promo_buy1get3/updateQuotaCart'); ?>";

			$.post(urlUpdateHarga,{idProduk : idProduk, quota : quota},function(response){
				//$('#totalItem'+id).text(response);
			});
		});
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

	$("#btn-paid").on("click",function(){
		var setQty = $('#setPaid').val();
		$(".paid").each(function( index, element ) {
			$(element).val(setQty);

			var idProduk = element.id;
			var qty = $(element).val();
			var id = $(element).data('id');

			var urlUpdateQty = "<?php echo base_url('promo_buy1get3/updateQtyPaidCart'); ?>";

			$.post(urlUpdateQty,{idProduk : idProduk, qty : qty},function(response){
				$('#totalItem'+id).text(response);
			});
		});
	});
	$("#btn-free").on("click",function(){
		var setQty = $('#setFree').val();
		$(".free").each(function( index, element ) {
			$(element).val(setQty);

			var idProduk = element.id;
			var qty = $(element).val();
			var id = $(element).data('id');

			var urlUpdateQty = "<?php echo base_url('promo_buy1get3/updateQtyFreeCart'); ?>";

			$.post(urlUpdateQty,{idProduk : idProduk, qty : qty},function(response){
				$('#totalItem'+id).text(response);
			});
		});
	});
	$("#btn-nominal").on("click",function(){
		var setNominal = $('#setNominal').val();
		$(".nominal").each(function( index, element ) {
			$(element).val(setNominal);
			var id = $(element).data('id');
			var urlUpdateNominal = "<?php echo base_url('promo_buy1get3/updateNominalCart'); ?>";

			$.post(urlUpdateNominal,{idProduk : id, nominal : setNominal},function(response){
				//$('#totalItem'+id).text(response);
			});
			

		});
	});
</script>