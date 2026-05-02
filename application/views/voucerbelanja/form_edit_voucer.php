<?php
	foreach($voucer->result() as $row){
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="kode" value="<?php echo $row->kode; ?>"/>
	<div class="form-group">
		<input type="text" name="nama_voucer" class="form-control" placeholder="Nama voucer" id="nama_voucer2" value="<?php echo $row->nama; ?>"/>
	</div>
	<div class="form-group">
		<input type="text" name="tgl_mulai" class="form-control datetimepicker" value="<?php echo $row->tgl_mulai; ?>" placeholder="Tanggal Berlaku" id="tgl_mulai2"/>
	</div>
	<div class="form-group">
		<input type="text" name="tgl_selesai" class="form-control datetimepicker" value="<?php echo $row->tgl_selesai; ?>" placeholder="Tanggal Selesai" id="tgl_selesai2"/>
	</div>
	<div class="form-group">
		<input type="text" name="quota" class="form-control" placeholder="Quota" value="<?php echo $row->quota; ?>" id="jml2"/>
		<input type="hidden" name="sisa" class="form-control" placeholder="Sisa" value="<?php echo $row->sisa; ?>"/>
	</div>
	<div class="form-group">
		<input type="text" name="nominal" class="form-control" placeholder="nominal" value="<?php echo $row->nominal; ?>" id="nominal2"/>
	</div>
  <div class="form-group">
          <input type="text" name="poin" value="<?php echo $row->poin; ?>" class="form-control" placeholder="Poin" id="poin2"/>
        </div>
	<div class="form-group">
		<textarea class="form-control" name="syarat" placeholder="Syarat" id="syarat2"><?php echo $row->syarat; ?></textarea>
	</div>
	<div class="form-group">
		<input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
		<input type="hidden" name="file_lama" class="form-control" value="<?php echo $row->gambar; ?>"/>
	</div>
	<div class="form-group">
		<div class="form-check">
			  <input class="form-check-input" type="radio" name="status" value="A" <?php echo $row->status=="A" ? "Checked" : ""; ?>>
			  <label class="form-check-label" for="flexRadioDefault1">
			    Aktif
			  </label>
			</div>
			<div class="form-check">
			  <input class="form-check-input" type="radio" name="status" value="NA" <?php echo $row->status=="NA" ? "Checked" : ""; ?>>
			  <label class="form-check-label" for="flexRadioDefault2">
			    Non Aktif
			  </label>
			</div>
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
                var files = $('#gambar')[0].files;
                url = "<?php echo base_url('voucerbelanja/edit_voucer_sql'); ?>";
                voucer    = "<?php echo base_url('voucerbelanja/data_voucer'); ?>";

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