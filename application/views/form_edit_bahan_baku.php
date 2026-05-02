<div class="input-group m-t-10">
   	<input type="number" step="any" id="nilai-qty" class="form-control" placeholder="Qty" value="<?php echo $get_qty; ?>">
    <span class="input-group-btn">
        <button type="button" class="btn btn-effect-ripple btn-primary" id="submit-qty">Submit</button>
    </span>
</div>

<script type="text/javascript">
	$('#submit-qty').on("click",function(){
		sku 		= "<?php echo $sku; ?>";
		id_produk 	= "<?php echo $id_produk; ?>";

		qty 		= $('#nilai-qty').val();

		url = "<?php echo base_url('komposisi_produk/edit_qty_sql'); ?>";
		komposisi = "<?php echo base_url('komposisi_produk/table_komposisi'); ?>";
		$.post(url,{sku : sku, id_produk : id_produk, qty : qty},function(){
			$('#komposisi_produk').load(komposisi,{id_produk : id_produk});
			$('.bs-example-modal-sm').modal('hide');
			$('.modal-backdrop').remove();
		});
	});
</script>