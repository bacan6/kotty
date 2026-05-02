<?php
	foreach($viewProduk as $row){
?>
<tr id="row<?php echo $row->id; ?>">	
	<td width="15%"><?php echo $row->idProduk; ?></td>
	<td width="30%"><?php echo $row->nama_produk; ?></td>
	<td><?php echo number_format($row->hargaBeli,'0',',','.'); ?></td>
	<td width="15%">
		<input type="number" class="form-control qty" id="produkTag<?php echo $row->id; ?>" data-row="<?php echo $row->id; ?>" data-id="<?php echo $row->idProduk; ?>" data-max_stok="<?php echo $row->stok; ?>" value="<?php echo $row->qty; ?>" style="text-align: right;">
		
	</td>
	<td width="5%" style="text-align: center;"><a class='hapus-form' id="<?php echo $row->idProduk; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>

<script type="text/javascript">
	var urlUpdate = "<?php echo base_url('bahan_keluar/updateCart'); ?>";

	$('.hapus-form').on("click",function(){
        var id = this.id;
        
       	var urlDelete = "<?php echo base_url('bahan_keluar/deleteCart'); ?>";

        $.post(urlDelete,{id :id},function(){
        	$('#data-input').load(dataUrl);
        });
    });

    $('.qty').on("keyup",function(){
    	var id = $(this).data('id');
    	var qty = $(this).val();
    	var maxStok = $(this).data('max_stok');

        var row = $(this).data('row');

    	if(qty > maxStok){
    		$.Notification.notify('error','top right', 'Melebihi Stok', 'Qty yang anda masukan melebihi stok saat ini');
            $('#produkTag'+row).val(1);
            $.post(urlUpdate,{id : id,qty : 1});
    	} else {
	    	$.post(urlUpdate,{id : id,qty : qty});
    	}
    });
</script>