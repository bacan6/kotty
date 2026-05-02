	<?php
	$totalQty = 0;
	foreach($dataCart as $row){
?>
	<tr>
		<td><?php echo $row->id_produk; ?></td>
		<td><?php echo $row->nama_produk; ?></td>
		<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
		<td align="right">
			<input type="number" class="form-control jumlahBeli" min="0" id="qty<?php echo $row->id; ?>" data-id="<?php echo $row->id_produk; ?>" data-id_cart="<?php echo $row->id; ?>" value="<?php echo $row->qty; ?>"/>
		</td>
		<td align="right" id="totalHarga<?php echo $row->id; ?>"><?php echo number_format($row->harga*$row->qty,'0',',','.'); ?></td>
		<td align="right">
			<input type="text" class="form-control changeDiskon" id="diskon<?php echo $row->id; ?>" data-id_produk="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" value="<?php echo $row->diskon; ?>"/>
		</td>
		<td align="right" id="grandTotal<?php echo $row->id; ?>"><?php echo number_format(($row->harga*$row->qty)-$row->diskon,'0',',','.'); ?></td>
		<td><a class="hapusCart" id="<?php echo $row->id_produk; ?>"><i class="fa fa-trash"></i></a></td>
	</tr>
<?php $totalQty = $totalQty+$row->qty; } ?>
<tr>
	<td colspan="8" align="right" style="font-weight: bold;">Total Qty = <?php echo $totalQty; ?></td>
</tr>

<script type="text/javascript">
	$('.jumlahBeli').on("change",function(){
        var idProduk = $(this).data('id');
        var qty 	  = $(this).val();
        var id = $(this).data('id_cart');

        var urlCekStok = "<?php echo base_url('penjualan/cekStokPerStore'); ?>";

        $.post(urlCekStok,{sku : idProduk, qty : qty, id: id},function(response){
        	if(response=="StokEnough"){
        		if(qty < 1){
        			$.Notification.notify('error','top right', 'Error', 'Qty hanya bisa di isi dengan nilai >= 1');
        			$('#qty'+id).val();
        		} else {
        			updateQtyCart(idProduk,qty,id);
                	viewPricePanel();
                }
        	} else {
                alert("Stok Tidak Mencukupi, Hubungi Warehouse");
        		$('#qty'+id).val(response);
        	}
        });         
    });


	$('.changeDiskon').on("change",function(){
		var idProduk = $(this).data('id_produk');
		var diskon 	 = $(this).val();
		var id = $(this).data('id');

		var updateDiskon = "<?php echo base_url('penjualan/updateDiskon'); ?>";

			
		$.ajax({
        			method : "POST",
        			url : updateDiskon,
        			dataType : 'json',
        			data : {idProduk : idProduk, diskon : diskon, id : id},
        			success : function(response){
        						$.each(response, function(x,obj){
					                var harga = obj.harga;
					        		var diskon = obj.diskon;
					        		var qty = obj.qty;

					        		var totalHarga = harga*qty;
					        		var grandTotal = (harga*qty)-diskon;

									$('#grandTotal'+id).text(formatAngka(grandTotal));
					                $('#diskon'+id).val(diskon);
					            });

					         	viewPricePanel();
        					  }
        });

	});

	$('.hapusCart').on("click",function(){
		var idProduk = this.id;

		var urlHapus 	 = "<?php echo base_url('penjualan/hapusCart'); ?>";

		$.post(urlHapus,{idProduk : idProduk},function(){
			viewCart();
			viewPricePanel();
		});
	});

    function updateQtyCart(idProduk,qty,id){
        var urlUpdate = "<?php echo base_url('penjualan/updateQtyCart'); ?>";

        $.ajax({
        			method : "POST",
        			url : urlUpdate,
        			dataType : 'json',
        			data : {qty : qty, idProduk : idProduk, id : id},
        			success : function(response){
        						$.each(response, function(x,obj){
					                var harga = obj.harga;
					        		var diskon = obj.diskon;

					        		var totalHarga = harga*qty;
					        		var grandTotal = (harga*qty)-diskon;

					        		$('#totalHarga'+id).text(formatAngka(totalHarga));
									$('#grandTotal'+id).text(formatAngka(grandTotal));
					                $('#diskon'+id).val(diskon);                       
					            });

					         	viewPricePanel();
        					  }
        });
    }

    function viewCart(){
		var dataUrl = "<?php echo base_url('penjualan/viewCart'); ?>";
		$('#data-input').load(dataUrl);
	}

</script>