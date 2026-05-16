<br>
<table class="table table-bordered" id="datatable3" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Kode</td>
        <td>Tgl Berlaku</td>
        <td>Nilai</td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($voucer->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
   		<td><?php echo $row->id_voucher; ?></td>
      <td><?php echo date("d F Y H:i",strtotime($row->berlaku_mulai)); ?> - <?php echo date("d F Y H:i",strtotime($row->berlaku_selesai)); ?></td>
      <td><?php echo (isset($row->nilai_tipe) && $row->nilai_tipe === 'percent') ? htmlspecialchars($row->nilai).'%' : number_format($row->nilai); ?></td>
   		
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<script type="text/javascript">

  $('#datatable3').dataTable();

</script>