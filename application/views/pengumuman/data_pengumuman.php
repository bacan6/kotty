<br>
<table class="table" style="font-size:12px;margin-top: 20px;">
  <thead>
    <tr style="font-weight: bold;">
        <td width="75%">Pengumuman untuk Supplier</td>
        <td>Tanggal Edit</td>
        <td width="12%">Login Edit</td>
        <td width="5%"></td>
   	</tr>
  </thead>

  <tbody>

   	<?php
   		$i=1;
   		foreach($pengumuman->result() as $row){
   	?>
   	<tr>
   		<td><?php echo nl2br($row->Isi); ?></td>
   		<td><?php echo $row->TanggalEdit; ?></td>
      <td><?php echo $row->LoginEdit; ?></td>
   		<td>
        <a href="#edit-pengumuman-modal" data-toggle="modal" class="edit_pengumuman" id="<?php echo $row->ID; ?>"><i class="fa fa-pencil"></i></a>
      </td>
   	</tr>
   	<?php $i++; } ?>
  </tbody>
</table>

<div class="modal fade" id="edit-pengumuman-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Pengumuman</h4>
      </div>
      <div class="modal-body" id="body-edit-pengumuman">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary edit-pengumuman-sql">Edit</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  $('#datatable').dataTable();

  $(document).on("click",".edit_pengumuman",function(){
    id = this.id;

    url = "<?php echo base_url('pengumuman/form_edit_pengumuman'); ?>";

    $('#body-edit-pengumuman').load(url,{id : id});
  });
</script>