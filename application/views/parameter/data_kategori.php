<table class="table table-bordered" style="font-size:12px;margin-top: 20px;">
    <tr style="font-weight: bold;">
        <td width="5%" style="text-align: center;">No</td>
        <td>Kategori</td>
        <td width="5%"></td>
   	</tr>

    <?php
      $i=1;
      foreach($kategori->result() as $row){
    ?>
    <tr>
      <td style="text-align: center;"><?php echo $i; ?></td>
      <td><?php echo $row->kategori; ?></td>
      <td>
          <a class="edit-kategori" href="#modal-edit-kategori" data-toggle="modal" id="<?php echo $row->id_kategori; ?>"><i class="fa fa-pencil"></i></a> | 
          <a class="hapus_kategori" id="<?php echo $row->id_kategori; ?>"><i class="fa fa-trash"></i></a></td>
    </tr>
    <?php $i++; } ?>
</table>

<!-- Modal -->
<div class="modal fade" id="modal-edit-kategori" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Kategori Bahan</h4>
      </div>
      <div class="modal-body" id="form-kategori">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary edit-kategori-submit">Edit</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('.hapus_kategori').on("click",function(){
    id = this.id;

    url = "<?php echo base_url('parameter/hapus_kategori'); ?>";
    kategori_url    = "<?php echo base_url('parameter/data_kategori'); ?>";
    
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
        
          $.post(url,{id : id},function(){
            $('#kategori-bahan').load(kategori_url);
          });
      });
  });
  
  $('.edit-kategori').on("click",function(){
    url = "<?php echo base_url('parameter/form_kategori'); ?>";
    id = this.id;
    $('#form-kategori').load(url,{id : id});
  });

  $('.edit-kategori-submit').on("click",function(){
    kategori    = $('#kategori_edit').val();
    id_kategori = $('#id_kategori').val();

    url = "<?php echo base_url('parameter/edit_kategori_sql'); ?>";
    kategori_url    = "<?php echo base_url('parameter/data_kategori'); ?>";

    $.post(url,{id : id_kategori, kategori : kategori}, function(){
      $('#kategori-bahan').load(kategori_url);
      $('#modal-edit-kategori').modal('hide');
      $('.modal-backdrop').remove();
    }); 
  });
</script>