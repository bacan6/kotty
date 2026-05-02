<?php error_reporting(0);ini_set('display_errors','0');
	$total = 0;

	$num = $viewCartPO->num_rows();

	if($num > 0 ){

	foreach($viewCartPO->result() as $row){
		// $lastPurchased = $this->modelPurchaseOrder->lastPurchased($row->id_produk,$idStore);
		if ($row->last_sales>0){
			$lastSales = $this->modelPurchaseOrder->lastSales($row->id_produk,$idStore,$row->last_sales);
		}else $lastSales = 0;
		
		
?>
<tr id="row<?php echo $row->id; ?>">
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><?php echo $row->last_receives; ?></td>
	<td><?php echo $row->last_sales; ?></td>
	<td><?php echo $lastSales; ?></td>
	<td><?php echo $row->stok; ?></td>
	<!-- <td><?php echo $row->min; ?></td>
	<td><?php echo $row->max; ?></td> -->
	<td><input type="text" value="<?php echo $row->qty; ?>" class="form-control qty" id="<?php echo $row->id_produk; ?>" size=3 data-id="<?php echo $row->id; ?>" style="width:65px"/></td>
	<td><input type="text" value="<?php echo $row->bonus; ?>" class="form-control bonus" id="bonus<?php echo $row->id_produk; ?>" size=3 data-id="<?php echo $row->id; ?>" style="width:65px"/></td>
	<td><?php echo $row->isi; ?></td>
	<td align="right"><input type="text" class="form-control harga" id="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" value="<?php echo $row->harga; ?>" size=14 style="width:80px"/></td>
	<td align="right" id="totalItem<?php echo $row->id; ?>"><?php echo number_format($row->qty*$row->harga,'0',',','.'); ?></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $total = $total+($row->qty*$row->harga);} ?>

<tr>
	<td colspan="10" align="center"><b>TOTAL</b></td>
	<td align="right" id="totalCart" style="font-weight: bold;"></td>
	<td></td>
</tr>

<?php } else {
?>
	<tr>
		<td colspan="12" align="center">--Belum Ada Data Terinput--</td>
	</tr>
<?php	
} ?>

<script type="text/javascript">
	var urlTotalCart = "<?php echo base_url('purchase_order/totalCart'); ?>";
	$('#totalCart').load(urlTotalCart);

	$('.qty').on("keyup",function(){
		var idProduk = this.id;
		var qty = $(this).val();
		var id = $(this).data('id');

		var urlUpdateQty = "<?php echo base_url('purchase_order/updateQtyCart'); ?>";

		$.post(urlUpdateQty,{id : id, qty : qty},function(response){
			$('#totalItem'+id).text(response);
			totalCart();
		});
	});
	$('.bonus').on("keyup",function(){
		var idProduk = this.id;
		var bonus = $(this).val();
		var id = $(this).data('id');

		var urlUpdateQty = "<?php echo base_url('purchase_order/updateBonusCart'); ?>";

		$.post(urlUpdateQty,{id : id, bonus : bonus},function(response){
			$('#totalItem'+id).text(response);
			totalCart();
		});
	});

	$('.harga').on("change",function(){
		var idProduk = this.id;
		var harga = $(this).val();
		var id = $(this).data('id');

		var urlUpdateHarga = "<?php echo base_url('purchase_order/updateHargaCart'); ?>";

		$.post(urlUpdateHarga,{id : id, harga : harga},function(response){
			$('#totalItem'+id).text(response);
			totalCart();
		});
	});

	function totalCart(){
		$('#totalCart').load(urlTotalCart);
	}

	$('.hapusCart').on("click",function(){
		var id = this.id;
		var id_toko = $('#id_toko').val();

		var urlHapusCart = "<?php echo base_url('purchase_order/hapusCart'); ?>";

		$.post(urlHapusCart,{id : id}, function(){
			$('#data-input').load(urlCartPO,{id_toko:id_toko});
		});
	});
</script>