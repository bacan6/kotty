<br>
<table class="table table-bordered" id="datatable" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td width="15%">Nama Supplier</td>
        <td>Alamat</td>
        <td width="12%">Kontak</td>
        <td width="12%">Email</td>
        <td width="12%">No Rekening</td>
        <td width="12%">Bank</td>
        <td width="10%">Atas Nama</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($supplier->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
   		<td><?php echo $row->supplier; ?></td>
   		<td><?php echo $row->alamat; ?></td>
      <td><?php echo $row->kontak; ?></td>
   		<td><?php echo $row->email; ?></td>
      <td><?php echo $row->no_rekening; ?></td>
      <td><?php echo $row->bank; ?></td>
      <td><?php echo $row->atas_nama; ?></td>
   		<td>
        <a href="#edit-supplier-modal" data-toggle="modal" class="edit_supplier" id="<?php echo $row->id_supplier; ?>"><i class="fa fa-pencil"></i></a> |
        <a class="hapus_supplier" id="<?php echo $row->id_supplier; ?>" ><i class="fa fa-trash"></i></a> 
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-supplier-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Supplier</h4>
      </div>
      <div class="modal-body" id="body-edit-supplier">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary edit-supplier-sql">Edit</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_supplier",function(){
    id = this.id;

    url = "<?php echo base_url('supplier/form_edit_supplier'); ?>";

    $('#body-edit-supplier').load(url,{id : id});
  });

  
    $(document).on("click",".hapus_supplier",function(){
    id = this.id;

    url = "<?php echo base_url('supplier/hapus_supplier'); ?>";
    supplier    = "<?php echo base_url('supplier/data_supplier'); ?>";

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
        $('#data-supplier').load(supplier);
      });
      swal("Deleted!", "Your imaginary file has been deleted.", "success");
    });
  });
</script>