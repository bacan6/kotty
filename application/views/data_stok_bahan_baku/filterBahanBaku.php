<table class="table" style="font-size: 12px;" id="dataStok">
    <thead>
        <tr style="font-weight: bold;">
            <td width="5%">No</td>
            <td width="15%">Kode Bahan</td>
            <td>Nama Bahan Baku</td>
            <td width="15%">Kategori</td>
           	<td width="10%">Stok Akhir</td>
            <td width="10%">Harga Beli</td>
        </tr>
    </thead>
</table>

<script type="text/javascript">
	var idKategori 	= "<?php echo $idKategori; ?>";
	var stokSign = "<?php echo $stokSign; ?>";
	var stokValue = "<?php echo $stokValue; ?>";
	var priceSign = "<?php echo $priceSign; ?>";
	var priceSignValue = "<?php echo $priceSignValue; ?>";

	$("#dataStok").DataTable({
        ordering: false,
        processing: false,
        serverSide: true,
        ajax: {
          url: "<?php echo base_url('dataStokBahanBaku/datatablesBahanBakuFilter'); ?>",
          type:'POST',
          data: {idKategori : idKategori, stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue} 
        }
    });
</script>