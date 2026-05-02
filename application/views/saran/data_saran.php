<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>ID Customer</td>
        <td>Saran</td>
        <td>Tanggal</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($saran->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><?php echo $row->id_customer; ?></td>
   		<td><?php echo $row->saran; ?></td>
      <td><?php echo $row->tgl_saran; ?></td>
   		<td>
        <a class="hapus_saran" id="<?php echo $row->id_saran; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<script type="text/javascript">

  $('#datatable').dataTable();

    $(document).on("click",".hapus_saran",function(){
    id = this.id;

    url = "<?php echo base_url('saran/hapus_saran'); ?>";
    saran    = "<?php echo base_url('saran/data_saran'); ?>";

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
      $.post(url,{id : id}, function(){
        $('#data-saran').load(saran);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>