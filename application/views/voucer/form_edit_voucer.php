<?php
	foreach($voucer->result() as $row){
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="id_voucer" value="<?php echo $row->id_voucer; ?>"/>
	<div class="form-group">
		<input type="text" name="nama_voucer" class="form-control" placeholder="Nama voucer" id="nama_voucer2" value="<?php echo $row->nm_voucer; ?>"/>
	</div>
	<div class="form-group">
		<input type="text" name="tgl_berlaku" class="form-control datetimepicker" value="<?php echo $row->tgl_berlaku; ?>" placeholder="Tanggal Berlaku" id="tgl_berlaku2"/>
	</div>
	<div class="form-group">
		<input type="text" name="tgl_expired" class="form-control datetimepicker" value="<?php echo $row->tgl_expired; ?>" placeholder="Tanggal Expired" id="tgl_expired2"/>
	</div>
	<div class="form-group">
		<input type="text" name="jml" class="form-control" placeholder="Jumlah" value="<?php echo $row->jml; ?>" id="jml2"/>
		<input type="hidden" name="sisa" class="form-control" placeholder="Jumlah" value="<?php echo $row->sisa; ?>"/>
	</div>
	<div class="form-group">
		<input type="text" name="potongan" class="form-control" placeholder="Potongan" value="<?php echo $row->potongan; ?>" id="potongan2"/>
	</div>
  <div class="form-group">
          <input type="text" name="min_transaksi" value="<?php echo $row->min_transaksi; ?>" class="form-control" placeholder="Min Transaksi" id="min_transaksi2"/>
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
                url = "<?php echo base_url('voucer/edit_voucer_sql'); ?>";
                voucer    = "<?php echo base_url('voucer/data_voucer'); ?>";

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