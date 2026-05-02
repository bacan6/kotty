<table class="table table-bordered" style="font-size:12px;margin-top: 20px;">
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Kategori Waste</td>
        <td width="5%"></td>
   	</tr>

   	<?php
   		$i=1;
   		foreach($waste->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
   		<td><?php echo $row->keterangan; ?></td>
   		<td>
   			<a href="#kategori-waste-modal" data-toggle="modal" class="edit_waste" id="<?php echo $row->id_keterangan; ?>"><i class="fa fa-pencil"></i></a> |
   			<a class="hapus_waste" id="<?php echo $row->id_keterangan; ?>"><i class="fa fa-trash"></i></a>
   		</td>
   	</tr>
   	<?php $i++; } ?>
</table>

<script type="text/javascript">
	$('.hapus_waste').on("click",function(){
		id = this.id;
		url = "<?php echo base_url('parameter/hapus_waste'); ?>";
		muat = "<?php echo base_url('parameter/data_waste'); ?>";

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
			$('#kategori-waste').load(muat);
		  });
		});
	});

	$('.edit_waste').on("click",function(){
		id = this.id;
		url = "<?php echo base_url('parameter/form_edit_waste'); ?>";
		$('#change-kategori-waste').load(url,{id : id});
	});
</script>

<!-- Modal -->
<div class="modal fade" id="kategori-waste-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Kategori Waste</h4>
      </div>
      <div class="modal-body">
        <div class="form-group" id="change-kategori-waste">
        	
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="simpan-waste">Save changes</button>
      </div>
    </div>
  </div>
</div>