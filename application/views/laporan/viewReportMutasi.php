<div style="padding: 20px;">
	<table class="table" id="dataTable">
		<thead>
			<tr style="font-weight: bold;">
				<td width="5%">No</td>
				<td>No Mutasi</td>
				<td>Tanggal</td>
				<td width="20%">Tujuan</td>
				<td width="20%">Penerima</td>
			</tr>
			</thead>
		</table>
	</div>

	<script type="text/javascript">
		var dateStart = "<?php echo $dateStart; ?>";
		var dateEnd = "<?php echo $dateEnd; ?>";
		var idStore = "<?php echo $idStore; ?>";

		$("#dataTable").DataTable({
	        ordering: false,
	        processing: false,
	        serverSide: true,
	        ajax: {
	            url: "<?php echo base_url('laporan/datatableMutasi'); ?>",
	            type:'POST',
	           	data: {dateStart : dateStart,dateEnd : dateEnd, idStore : idStore}
	        }
	    });
	</script>