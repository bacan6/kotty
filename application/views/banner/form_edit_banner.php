<?php
	foreach($banner->result() as $row){
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="id_banner" value="<?php echo $row->id_banner; ?>"/>
	<div class="form-group">
		<input type="text" name="nama_banner" class="form-control" placeholder="Nama banner" id="nama_banner2" value="<?php echo $row->nm_banner; ?>"/>
	</div>
	<div class="form-group">
		<input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
		<input type="hidden" name="file_lama" class="form-control" value="<?php echo $row->gambar; ?>"/>
	</div>
  <div class="form-group">
    <select name="posisi" class="form-control">
      <option value="">-Pilih-</option>
      <option value="atas" <?php echo $row->posisi=="atas" ? "Selected" : ""; ?>>Atas</option>
      <option value="home" <?php echo $row->posisi=="home" ? "Selected" : ""; ?>>Home</option>
      <option value="kasir" <?php echo $row->posisi=="kasir" ? "Selected" : ""; ?>>Kasir</option>
    </select>
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
  <button type="submit" class="btn btn-primary edit-banner-sql">Add</button>
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
                url = "<?php echo base_url('banner/edit_banner_sql'); ?>";
                banner    = "<?php echo base_url('banner/data_banner'); ?>";

             $.ajax({
                  url: url,
                  type: 'post',
                  data: fdata,
                  contentType: false,
                  processData: false,
                  success: function(data){
                    $('#submit2').trigger("reset");
                    $('#data-banner').load(banner);
                    $('#edit-banner-modal').modal('hide');
                    $('.modal-backdrop').hide();

                    if(data > 0){
                        $.Notification.notify('success', 'top right', 'banner', 'banner Berhasil Ditambahkan');
                    } else {
                         $.Notification.notify('danger', 'top right', 'banner', 'banner Gagal Ditambahkan');
                    }
                }
             })
          
          });
</script>