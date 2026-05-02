<table class="table table-bordered" style="font-size:12px;" id="datatableFilter">  
  <thead>
    <tr style="font-weight: bold;">
      <td width="5%" style="text-align: center;vertical-align: middle;">No</td>
      <td style="text-align: center;vertical-align: middle;" width="18%">No PO</td>
      <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal PO</td>
      <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal Kirim</td>
      <td style="text-align: center;vertical-align: middle;">Supplier</td>
      <td style="text-align: center;vertical-align: middle;" width="15%">PIC</td>
      <td style="text-align: center;vertical-align: middle;" width="8%">Status</td>
    </tr>

  </thead>
</table>

<script type="text/javascript">
  var tanggalPO = "<?php echo $tanggalPO; ?>";
  var tanggalKirim = "<?php echo $tanggalKirim; ?>";
  var supplier = "<?php echo $supplier; ?>";
  var status = "<?php echo $status; ?>";


  $("#datatableFilter").DataTable({
    ordering: false,
    processing: false,
    serverSide: true,
    ajax: {
             url: "<?php echo base_url('bahan_masuk/datatablesPOFilter'); ?>",
             type:'POST',
             data : {tanggalPO : tanggalPO, tanggalKirim : tanggalKirim, supplier : supplier, status : status}
    }
  });
</script>