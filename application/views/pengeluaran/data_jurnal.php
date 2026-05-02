<table class="table table-bordered datatable" id="jurnalDatatable" style="font-size:12px;margin-top: 20px;">
    <thead>
    <tr style="font-weight: bold;">
        <th width="5%">No</th>
        <th width="15%">Jurnal</th>
        <th>Keterangan</th>
        <th width="12%">Debet</th>
        <th width="12%">Kredit</th>
        <th width="12%">Saldo</th>
        <th>-</th>
   	</tr>
</thead>
    <tbody>
   	<?php
   		$i=1;$dbt=0;$kdt=0;
   		foreach($jurnal->result() as $row){
            $dbt+=$row->D;
            $kdt+=$row->K;
            $saldo = $kdt - $dbt;
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
   		<td><?php echo $row->Kode; ?><br><small><?php echo $row->Tanggal; ?></small></td>
   		<td><?php echo $row->Keterangan; ?></td>
        <td><?php echo number_format($row->D,0,',','.'); ?></td>
        <td><?php echo number_format($row->K,0,',','.'); ?></td>
        <td><?php echo number_format($saldo,0,',','.'); ?></td>
   		<td style="text-align: center;"><a class="hapus_jurnal" id="<?php echo $row->JurnalID; ?>"><i class="fa fa-trash"></i></a></td>
   	</tr>
   	<?php $i++; } ?>
        </tbody>
</table>

<script type="text/javascript">
	$('.hapus_jurnal').on("click",function(){
        id 	= this.id;
        url = "<?php echo base_url('pengeluaran/hapus_jurnal'); ?>";
        uom2 = "<?php echo base_url('pengeluaran/data_jurnal'); ?>";

      swal({
  		  title: "Are you sure?",
  		  text: "You will not be able to recover this imaginary file!",
  		  type: "warning", 
  		  showCancelButton: true,
  		  confirmButtonColor: "#DD6B55",
  		  confirmButtonText: "Yes, delete it!",
  		  closeOnConfirm: false
  		},
  		function(){
  		    swal("Deleted!", "Your imaginary file has been deleted.", "success");
  			
  			$.post(url,{id : id}, function(){
  	        	$('#data-jurnal').load(uom2);
  	        });
  		});
    });
    $("#jurnalDatatable").DataTable({
                ordering: false});
</script>