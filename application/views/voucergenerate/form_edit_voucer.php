<?php
	foreach($voucer->result() as $row){
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="id" value="<?php echo $row->id_generate; ?>"/>
	<div class="form-group">
		<input type="text" name="nama_voucer" class="form-control" placeholder="Nama voucer" id="nama_voucer2" value="<?php echo $row->nm_voucher; ?>"/>
	</div>
	<div class="form-group">
		<input type="text" name="berlaku_mulai" class="form-control datetimepicker" value="<?php echo $row->berlaku_mulai; ?>" placeholder="Tanggal Berlaku" id="tgl_mulai2"/>
	</div>
	<div class="form-group">
		<input type="text" name="berlaku_selesai" class="form-control datetimepicker" value="<?php echo $row->berlaku_selesai; ?>" placeholder="Tanggal Selesai" id="tgl_selesai2"/>
	</div>
	<div class="form-group">
		<input type="text" name="nilai" class="form-control" placeholder="Nilai" value="<?php echo $row->nilai; ?>" id="jml2"/>
	</div>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="submit" class="btn btn-primary edit-voucer-sql">Add</button>
</div>
</form>


<?php } ?>

<script type="text/javascript">
 $('.datetimepicker').datetimepicker({
 	 format: 'YYYY-MM-DD HH:mm:ss',
          widgetPositioning: {
              horizontal: 'right',
              vertical: 'bottom'
          }
          
        });
	 $('#submit2').submit(function(e){
              e.preventDefault();
                var fdata = new FormData(this);
                url = "<?php echo base_url('Voucergenerate/edit_voucer_sql'); ?>";
                voucer    = "<?php echo base_url('Voucergenerate/data_voucer'); ?>";

             $.ajax({
                  url: url,
                  type: 'post',
                  data: fdata,
                  contentType: false,
                  processData: false,
                  success: function(data){
                    $('#submit2').trigger("reset");
                    $('#data-voucer').load(voucer);
                    $('#edit-voucer-modal').modal('hide');
                    $('.modal-backdrop').hide();

                    if(data > 0){
                        $.Notification.notify('success', 'top right', 'voucer', 'voucer Berhasil Ditambahkan');
                    } else {
                         $.Notification.notify('danger', 'top right', 'voucer', 'voucer Gagal Ditambahkan');
                    }
                }
             })
          
          });
		
		
		jQuery(".select2").select2({
            width: '100%'
        });
  
</script>