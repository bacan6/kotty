<table class="table table-bordered" id="penerimaanWO">
    <thead>
	   <tr style="font-weight: bold;">
		    <td width="5%">No</td>
		    <td>No WO</td>
		    <td>Tanggal WO</td>
		    <td>Tanggal Penyelesaian</td>
		    <td>Vendor</td>
		    <td>Pemohon</td>
		    <td>Status</td>
		    <td width="10%"></td>
	   </tr>
    </thead>
</table>

<script type="text/javascript">
	var tanggalWO = "<?php echo $tanggalWO; ?>";
	var tanggalPenyelesaian = "<?php echo $tanggalPenyelesaian; ?>";
	var vendor = "<?php echo $vendor; ?>";
	var status = "<?php echo $status; ?>";

	$("#penerimaanWO").DataTable({
        ordering: false,
        processing: false,
        serverSide: true,
        ajax: {
                url: "<?php echo base_url('penerimaanWorkOrder/datatablesPenerimaanWOFilter'); ?>",
                type:'POST',
                data : {tanggalWO : tanggalWO, tanggalPenyelesaian : tanggalPenyelesaian, vendor : vendor, status : status}
        }
    });
</script>