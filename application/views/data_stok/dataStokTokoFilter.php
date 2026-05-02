<center>
	<h4>Data Stok Pertoko</h4>
	<h5><?php echo $nama_distributor; ?></h5>
</center>

<table class="table table-striped" style="font-size: 10px;" id="tableStok">
	<thead>
		<tr style="background: #2A303A;color:white;font-weight: bold;">
			<td width="5%">No</td>
			<td>SKU</td>
			<td>Nama Produk</td>
			<td>Kategori</td>
			<td>Harga Beli</td>
			<td>Harga Jual</td>
            <td>Margin</td>
            <td>Stok</td>
            <td>HPPxStok</td>
		</tr>
	</thead>
</table>
<h3>Total nilai inventori: <br><small>
Harga Modal <u>Rp <?php echo number_format($nilai_toko[0]['nilai'],'0',',','.');?></u><br>
Harga Jual <u><?php echo number_format($nilai_toko[0]['nilaiJual'],'0',',','.');?></u></small></h3>
<script type="text/javascript">
	var idKategori = "<?php echo $idKategori; ?>";
    var subkategori = "<?php echo $subkategori; ?>";
    var subSubKategori = "<?php echo $subSubKategori; ?>";
    var stokSign = "<?php echo $stokSign; ?>";
    var stokValue = "<?php echo $stokValue; ?>";
    var priceSign = "<?php echo $priceSign; ?>";
    var priceSignValue = "<?php echo $priceSignValue; ?>";
    var idToko = "<?php echo $idToko; ?>";
    var idStand = "<?php echo $idStand; ?>";
    var salePriceSign = "<?php echo $salePriceSign; ?>";
    var salePriceValue = "<?php echo $salePriceValue; ?>";
    var idSupplier = "<?php echo $idSupplier; ?>";
    var idBrand = "<?php echo $idBrand; ?>";
    var stokMinim = "<?php echo $stokMinim; ?>";

	$("#tableStok").DataTable({
        ordering: false,
        processing: false,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url('data_stok_toko/datatablesStokTokoFilter'); ?>",
            type:'POST',
           	data: {idKategori : idKategori, subkategori : subkategori, subSubKategori : subSubKategori,stokSign : stokSign, stokValue : stokValue, priceSign : priceSign, priceSignValue : priceSignValue, idToko : idToko, idStand : idStand, salePriceSign : salePriceSign, salePriceValue : salePriceValue,idSupplier:idSupplier,idBrand:idBrand,stokMinim:stokMinim}
        }
    });
</script>