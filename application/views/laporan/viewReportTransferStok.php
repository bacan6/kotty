<div style="padding: 20px;">
	<table class="table" id="dataTable">
		<thead>
			<tr style="font-weight: bold;">
				<td width="5%">No</td>
				<td>No Transfer</td>
				<td>Dikirim</td>
				<td width="20%">Transfer Dari</td>
				<td width="20%">Tujuan Transfer</td>
				<td>Status</td>
				<td>Diterima</td>
				<td>Penerima</td>
			</tr>
			</thead>
		</table>
	</div>

	<script type="text/javascript">
		var dateStart = "<?php echo $dateStart; ?>";
		var dateEnd = "<?php echo $dateEnd; ?>";
		var transferFrom = "<?php echo $transferFrom; ?>";
		var transferTo = "<?php echo $transferTo; ?>";

		$("#dataTable").DataTable({
	        ordering: false,
	        processing: false,
	        serverSide: true,
	        ajax: {
	            url: "<?php echo base_url('laporan/datatableTransferStok'); ?>",
	            type:'POST',
	           	data: {dateStart : dateStart,dateEnd : dateEnd, transferFrom : transferFrom, transferTo : transferTo}
	        }
	    });
	</script>