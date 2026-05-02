<tr id="form-input<?php echo $no; ?>">
	<?php
		foreach($bahan_baku->result() as $row){
			$max = $this->model_penjualan->cekStokPerStore($row->sku,$idStore);
	?>
	<td width="15%"><?php echo $row->id_produk; ?></td>
	<td width="28%"><?php echo $row->nama_produk; ?></td>
	<td width="10%">
		<input type="number" min="0" max="<?php echo $max; ?>" class="form-control qty_beli<?php echo $no; ?>" name="qty[]" required style="text-align: right;"></td>
		<input type="hidden" name="sku[]" value="<?php echo $row->id_produk; ?>"/>
	<td width="3%"><a class='hapus-form' id="<?php echo $no; ?>"><i class="fa fa-trash"></i></a></td>
	<?php } ?>
</tr>

<script type="text/javascript">
	$('.hapus-form').on("click",function(){
        id = this.id;
        $('#form-input'+id).remove();
    });  

	function formatAngka(angka) {
		 if (typeof(angka) != 'string') angka = angka.toString();
		 var reg = new RegExp('([0-9]+)([0-9]{3})');
		 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
		 return angka;
	}



</script>