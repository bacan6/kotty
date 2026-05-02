<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Gambar</td>
        <td>Nama Banner</td>
        <td>Posisi</td>
        <td>Status</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($banner->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><a href="<?php echo base_url('/'); ?>uploads/files/banner/<?php echo $row->gambar; ?>" target="_blank"><img class="img-thumbnail" src="<?php echo base_url('/'); ?>uploads/files/banner/<?php echo $row->gambar; ?>" style="width: 100px;"></td>
   		<td><?php echo $row->nm_banner; ?></td>
      <td><?php echo $row->posisi; ?></td>
      <td><?php echo $row->status; ?></td>
   		<td>
        <a href="#edit-banner-modal" data-toggle="modal" class="edit_banner" id="<?php echo $row->id_banner; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_banner" id="<?php echo $row->id_banner; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-banner-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit banner</h4>
      </div>
        <div id="body-edit-banner"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_banner",function(){
    id = this.id;

    url = "<?php echo base_url('banner/form_edit_banner'); ?>";

    $('#body-edit-banner').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_banner",function(){
    id = this.id;

    url = "<?php echo base_url('banner/hapus_banner'); ?>";
    banner    = "<?php echo base_url('banner/data_banner'); ?>";

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
        $('#data-banner').load(banner);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>