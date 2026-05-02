<?php
	foreach($promo->result() as $row){
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="id_promo" value="<?php echo $row->id_promo; ?>"/>
	<div class="form-group">
		<input type="text" name="nama_promo" class="form-control" placeholder="Nama promo" id="nama_promo2" value="<?php echo $row->nm_promo; ?>"/>
	</div>
  <div class="form-group">
    <input type="hidden" id="brand" name="brand" value="<?php echo $row->id_brand; ?>" style="width:100%;"/>
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
  <button type="submit" class="btn btn-primary edit-promo-sql">Add</button>
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
                url = "<?php echo base_url('promo/edit_promo_sql'); ?>";
                promo    = "<?php echo base_url('promo/data_promo'); ?>";

             $.ajax({
                  url: url,
                  type: 'post',
                  data: fdata,
                  contentType: false,
                  processData: false,
                  success: function(data){
                    $('#submit2').trigger("reset");
                    $('#data-promo').load(promo);
                    $('#edit-promo-modal').modal('hide');
                    $('.modal-backdrop').hide();

                    if(data > 0){
                        $.Notification.notify('success', 'top right', 'promo', 'promo Berhasil Ditambahkan');
                    } else {
                         $.Notification.notify('danger', 'top right', 'promo', 'promo Gagal Ditambahkan');
                    }
                }
             })
          
          });
</script>