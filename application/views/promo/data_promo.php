<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Gambar</td>
        <td>Nama Promo</td>
        <td>Nama Brand</td>
        <td>Status</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($promo->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
      <td><a href="<?php echo base_url('/'); ?>uploads/files/promo/<?php echo $row->gambar; ?>" target="_blank"><img class="img-thumbnail" src="<?php echo base_url('/'); ?>uploads/files/promo/<?php echo $row->gambar; ?>" style="width: 100px;"></td>
   		<td><?php echo $row->nm_promo; ?></td>
      <td><?php echo $row->brand; ?></td>
      <td><?php echo $row->status; ?></td>
   		<td>
        <a href="#edit-promo-modal" data-toggle="modal" class="edit_promo" id="<?php echo $row->id_promo; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_promo" id="<?php echo $row->id_promo; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-promo-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit promo</h4>
      </div>
        <div id="body-edit-promo"></div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_promo",function(){
    id = this.id;

    url = "<?php echo base_url('promo/form_edit_promo'); ?>";

    $('#body-edit-promo').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_promo",function(){
    id = this.id;

    url = "<?php echo base_url('promo/hapus_promo'); ?>";
    promo    = "<?php echo base_url('promo/data_promo'); ?>";

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
        $('#data-promo').load(promo);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>