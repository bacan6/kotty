<?php
	foreach($kupon->result() as $row){
?>
<form method="post" action="" enctype="multipart/form-data" id="submit2">
<div class="modal-body">
  <input type="hidden" name="id_kupon" value="<?php echo $row->id_kupon; ?>"/>
	<div class="form-group">
		<input type="text" name="nama_kupon" class="form-control" placeholder="Nama kupon" id="nama_kupon2" value="<?php echo $row->nm_kupon; ?>"/>
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
		<input type="text" name="max_tukar" class="form-control" value="<?php echo $row->max_tukar; ?>" placeholder="Max Tukar" id="max_tukar2"/>
	</div>
	<div class="form-group">
		<input type="text" name="point" class="form-control" placeholder="Point" value="<?php echo $row->jml_point; ?>" id="point2"/>
	</div>
	<div class="form-group" id="select-produk">
		<input type="hidden" id="sku" style="width:100%;" name="produk"/>
    </div>
	<!-- <div class="form-group">
		<input type="text" name="potongan" class="form-control" placeholder="Potongan" value="<?php echo $row->potongan; ?>" id="potongan2"/>
	</div> -->
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
  <button type="submit" class="btn btn-primary edit-kupon-sql">Add</button>
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
                url = "<?php echo base_url('kupon/edit_kupon_sql'); ?>";
                kupon    = "<?php echo base_url('kupon/data_kupon'); ?>";

             $.ajax({
                  url: url,
                  type: 'post',
                  data: fdata,
                  contentType: false,
                  processData: false,
                  success: function(data){
                    $('#submit2').trigger("reset");
                    $('#data-kupon').load(kupon);
                    $('#edit-kupon-modal').modal('hide');
                    $('.modal-backdrop').hide();

                    if(data > 0){
                        $.Notification.notify('success', 'top right', 'kupon', 'kupon Berhasil Ditambahkan');
                    } else {
                         $.Notification.notify('danger', 'top right', 'kupon', 'kupon Gagal Ditambahkan');
                    }
                }
             })
          
          });
		
		
		jQuery(".select2").select2({
            width: '100%'
        });
        $('#sku').select2({
                placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('kupon/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 100,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
            minimumInputLength: 3,
        });
</script>