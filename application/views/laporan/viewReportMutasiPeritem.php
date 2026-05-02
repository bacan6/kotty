<div style="padding: 20px;">
	<table class="table" id="dataTable">
		<thead>
			<tr style="font-weight: bold;">
				<td width="5%">No</td>
				<td>No Mutasi</td>
				<td>Tanggal</td>
				<td>SKU</td>
				<td>Nama Produk</td>
				<td width="20%">Tujuan</td>
				<td>Qty</td>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="6" style="text-align: center;"><b>TOTAL KESELURUHAN QTY</b></td>
				<td id="totalQTY" style="font-weight: bold;"></td>
			</tr>
		</tfoot>
	</table>
</div>

<script type="text/javascript">
	var dateStart = "<?php echo $dateStart; ?>";
	var dateEnd = "<?php echo $dateEnd; ?>";
	var idStore = "<?php echo $idStore; ?>";
	var idProduk = "<?php echo $idProduk; ?>";

	$("#dataTable").DataTable({
	    ordering: false,
	    processing: false,
	    serverSide: true,
	    ajax: {
	        url: "<?php echo base_url('laporan/datatableMutasiPeritem'); ?>",
	        type:'POST',
	        data: {dateStart : dateStart,dateEnd : dateEnd, idStore : idStore, idProduk : idProduk}
	    }
	});

	var urlTotalQTY = "<?php echo base_url('laporan/totalQTYMutasi'); ?>";
	$('#totalQTY').load(urlTotalQTY,{dateStart : dateStart, dateEnd : dateEnd, idStore : idStore, idProduk : idProduk});
</script>
