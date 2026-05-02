<?php
	$numRows = $viewCart->num_rows();
	$total = 0;

	if($numRows < 1){
?>
<tr>
	<td colspan="5">Belum ada data terinput</td>
</tr>
<?php
	} else {
	
	foreach($viewCart->result() as $row){
?>
<tr>
	<td><?php echo $row->nama_bahan; ?></td>
	<td><?php echo $row->satuan; ?></td>
	<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
	<td><input type="number" class="form-control qty" id="<?php echo $row->sku; ?>" value="<?php echo $row->qty; ?>"/></td>
	<td align="right"><?php echo number_format($row->totalHarga,'0',',','.'); ?></td>
	<td><a class="hapusCart" id="<?php echo $row->sku; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $total = $total+$row->totalHarga; } } ?>

<tr>
	<td colspan="4" align="center"><b>TOTAL</b></td>
	<td align="right"><b><?php echo number_format($total,'0',',','.'); ?></b></td>
	<td></td>
</tr>

<script type="text/javascript">
	$('.hapusCart').on("click",function(){
		var sku = this.id;
		var urlHapus = "<?php echo base_url('workOrder/hapusCart'); ?>";

		$.post(urlHapus,{sku : sku},function(){
			$('#daftarBahanBaku').load(urlDaftarBahanBaku);
		});
	});

	$('.qty').change(function(){
		var qty = $(this).val();
		var sku = this.id;

		var urlUpdate = "<?php echo base_url('workOrder/updateCart'); ?>";

		$.post(urlUpdate,{qty : qty, sku : sku},function(response){
			if(response==0){
				$.Notification.notify('error','top right', 'Stok Tidak Mencukupi', 'Melebihi stok saat ini');
				$('#daftarBahanBaku').load(urlDaftarBahanBaku);
			} else {
				$('#daftarBahanBaku').load(urlDaftarBahanBaku);
			}
		});
	});
</script>