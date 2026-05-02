<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Gambar</td>
        <td>Nama Voucer</td>
        <td>Tgl Berlaku</td>
        <td>Potongan</td>
        <td>Min. Transaksi</td>
        <td>Status</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($voucer->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><a href="<?php echo base_url('/'); ?>uploads/files/voucer/<?php echo $row->gambar; ?>" target="_blank"><img class="img-thumbnail" src="<?php echo base_url('/'); ?>uploads/files/voucer/<?php echo $row->gambar; ?>" style="width: 100px;"></td>
   		<td><?php echo $row->nm_voucer; ?></td>
      <td><?php echo date("d F Y H:i",strtotime($row->tgl_berlaku)); ?> - <?php echo date("d F Y H:i",strtotime($row->tgl_expired)); ?></td>
      <td><?php echo $row->potongan; ?></td>
      <td><?php echo $row->min_transaksi; ?></td>
      <td><?php echo $row->status; ?></td>
   		<td>
        <a href="#edit-voucer-modal" data-toggle="modal" class="edit_voucer" id="<?php echo $row->id_voucer; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_voucer" id="<?php echo $row->id_voucer; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-voucer-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit voucer</h4>
      </div>
        <div id="body-edit-voucer"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_voucer",function(){
    id = this.id;

    url = "<?php echo base_url('voucer/form_edit_voucer'); ?>";

    $('#body-edit-voucer').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_voucer",function(){
    id = this.id;

    url = "<?php echo base_url('voucer/hapus_voucer'); ?>";
    voucer    = "<?php echo base_url('voucer/data_voucer'); ?>";

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
        $('#data-voucer').load(voucer);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>