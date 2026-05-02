<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Gambar</td>
        <td>Nama kupon</td>
        <td>Tgl Berlaku</td>
        <td>Jumlah</td>
        <td>Sisa</td>
        <td>Status</td>
        <td>Store</td>
        <td>Group</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($kupon->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><a href="<?php echo base_url('/'); ?>uploads/files/kupon/<?php echo $row->gambar; ?>" target="_blank"><img class="img-thumbnail" src="<?php echo base_url('/'); ?>uploads/files/kupon/<?php echo $row->gambar; ?>" style="width: 100px;"></td>
   		<td><?php echo $row->nm_kupon; ?><br><b>SKU: <?php echo $row->id_produk?></b><br><small>(<?php echo $row->nama_produk?>)</small></td>
      <td><?php echo date("d F Y H:i",strtotime($row->tgl_berlaku)); ?> - <?php echo date("d F Y H:i",strtotime($row->tgl_expired)); ?></td>
      <td><?php echo $row->jml; ?></td>
      <td><?php echo $row->sisa; ?></td>
      <td><?php echo $row->status; ?></td>
      <td><?php echo $row->store; ?></td>
      <td><?php echo $row->group_customer; ?></td>
   		<td>
        <a href="#edit-kupon-modal" data-toggle="modal" class="edit_kupon" id="<?php echo $row->id_kupon; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_kupon" id="<?php echo $row->id_kupon; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-kupon-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit kupon</h4>
      </div>
        <div id="body-edit-kupon"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_kupon",function(){
    id = this.id;

    url = "<?php echo base_url('kupon/form_edit_kupon'); ?>";

    $('#body-edit-kupon').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_kupon",function(){
    id = this.id;

    url = "<?php echo base_url('kupon/hapus_kupon'); ?>";
    kupon    = "<?php echo base_url('kupon/data_kupon'); ?>";

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
        $('#data-kupon').load(kupon);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>