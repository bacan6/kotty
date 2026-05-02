<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Nama Brand</td>
        <td>Gambar</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($brand->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
   		<td><?php echo $row->brand; ?></td>
      <td><a href="<?php echo base_url('/'); ?>uploads/files/brand/<?php echo $row->gambar; ?>" target="_blank"><img class="img-thumbnail" src="<?php echo base_url('/'); ?>uploads/files/brand/<?php echo $row->gambar; ?>" style="width: 100px;"></td>
   		<td>
        <a href="#edit-brand-modal" data-toggle="modal" class="edit_brand" id="<?php echo $row->id_brand; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_brand" id="<?php echo $row->id_brand; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-brand-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="" enctype="multipart/form-data" id="submit2">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Brand</h4>
      </div>
      <div class="modal-body" id="body-edit-brand">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary edit-brand-sql">Edit</button>
      </div>
    </form>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_brand",function(){
    id = this.id;

    url = "<?php echo base_url('brand/form_edit_brand'); ?>";

    $('#body-edit-brand').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_brand",function(){
    id = this.id;

    url = "<?php echo base_url('brand/hapus_brand'); ?>";
    brand    = "<?php echo base_url('brand/data_brand'); ?>";

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
        $('#data-brand').load(brand);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>