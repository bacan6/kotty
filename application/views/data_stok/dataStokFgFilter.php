<table class="table" style="font-size: 12px;" id="dataStokFilter">
    <thead>
        <tr style="font-weight: bold;">
            <td width="5%">No</td>
            <td width="10%">SKU</td>
            <td>Nama Produk</td>
            <td width="15%">Kategori</td>
                           <td width="10%">Last Stok</td>
            <td width="10%">Harga Beli</td>
        </tr>
    </thead>
</table>

<script type="text/javascript">
	var idKategori = "<?php echo $idKategori; ?>";
    var subkategori = "<?php echo $subkategori; ?>";
    var subSubKategori = "<?php echo $subSubKategori; ?>";
    var stokSign = "<?php echo $stokSign; ?>";
    var stokValue = "<?php echo $stokValue; ?>";
    var priceSign = "<?php echo $priceSign; ?>";
    var priceSignValue = "<?php echo $priceSignValue; ?>";


	$("#dataStokFilter").DataTable({
        ordering: false,
        processing: false,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('data_stok/datatablesStokFgFilter'); ?>",
            type:'POST',
           	data: {idKategori : idKategori, subkategori : subkategori, subSubKategori : subSubKategori,stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue}
        }
    });
</script>