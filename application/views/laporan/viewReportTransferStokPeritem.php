<div style="padding: 20px;">
	<table class="table" id="dataTable">
		<thead>
			<tr style="font-weight: bold;">
				<td width="5%">No</td>
				<td width="18%">No Transfer</td>
				<td>Tanggal</td>
				<td>SKU</td>
				<td>Nama Produk</td>
				<td width="20%">Transfer Dari</td>
				<td width="20%">Tujuan Transfer</td>
				<td width="5%">Qty</td>
				<td width="5%">Received</td>
			</tr>
			</thead>

			<tfoot>
				<tr>
					<td colspan="7" align="center"><b>Total Keseluruhan QTY</b></td>
					<td id="totalQty" style="font-weight: bold;"></td>
				</tr>
			</tfoot>
		</table>
	</div>

	<script type="text/javascript">
		var dateStart = "<?php echo $dateStart; ?>";
		var dateEnd = "<?php echo $dateEnd; ?>";
		var transferFrom = "<?php echo $transferFrom; ?>";
		var transferTo = "<?php echo $transferTo; ?>";
		var idProduk = "<?php echo $idProduk; ?>";

		$("#dataTable").DataTable({
	        ordering: false,
	        processing: false,
	        serverSide: true,
	        ajax: {
	            url: "<?php echo base_url('laporan/datatableTransferStokPeritem'); ?>",
	            type:'POST',
	           	data: {dateStart : dateStart,dateEnd : dateEnd, transferFrom : transferFrom, transferTo : transferTo, idProduk : idProduk}
	        }
	    });

		var urlTotalQTY = "<?php echo base_url('laporan/totalQtyTransferStok'); ?>";
	    $('#totalQty').load(urlTotalQTY,{dateStart : dateStart,dateEnd : dateEnd, transferFrom : transferFrom, transferTo : transferTo, idProduk : idProduk});
	</script>