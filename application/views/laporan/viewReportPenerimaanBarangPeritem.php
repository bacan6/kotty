<div style="padding: 20px;">
	<table class="table" id="dataTable">
		<thead>
			<tr style="font-weight: bold;">
				<td width="5%">No</td>
				<td>No Penerimaan</td>
				<td>Tanggal Terima</td>
				<td>Tempat Penerimaan</td>
				<td>Supplier</td>
				<td>SKU</td>
				<td>Nama Produk</td>
				<td>Qty</td>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="7" align="center"><b>Total Barang Diterima</b></td>
				<td id="total" style="font-weight: bold;"></td>
			</tr>
		</tfoot>
		</table>
	</div>

	<script type="text/javascript">
		var dateStart = "<?php echo $dateStart; ?>";
		var dateEnd = "<?php echo $dateEnd; ?>";
		var tempatPenerimaan = "<?php echo $tempatPenerimaan; ?>";
		var supplier = "<?php echo $supplier; ?>";
		var idProduk = "<?php echo $idProduk; ?>";

		$("#dataTable").DataTable({
	        ordering: false,
	        processing: false,
	        serverSide: true,
	        ajax: {
	            url: "<?php echo base_url('laporan/datatablePenerimaanBarangPeritem'); ?>",
	            type:'POST',
	           	data: {dateStart : dateStart,dateEnd : dateEnd, tempatPenerimaan : tempatPenerimaan, supplier : supplier, idProduk : idProduk}
	        }
	    });

	    var urlQTY = "<?php echo base_url('laporan/qtyPeritemPenerimaan'); ?>";

	    $('#total').load(urlQTY,{dateStart : dateStart,dateEnd : dateEnd, tempatPenerimaan : tempatPenerimaan, supplier : supplier, idProduk : idProduk});
	</script>