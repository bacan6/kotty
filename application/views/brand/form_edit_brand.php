<?php
	foreach($brand->result() as $row){
?>
<div class="form-group">
	<input type="text" class="form-control" name="nama_brand" placeholder="Nama Brand" id="nama_brand_edit" value="<?php echo $row->brand; ?>"/>
</div>
<div class="form-group">
  <input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
  <input type="hidden" class="form-control" name="file_lama" placeholder="Gambar" id="file_lama" value="<?php echo $row->gambar; ?>" />
</div>
<input type="hidden" name="id" id="id_brand_edit" value="<?php echo $row->id_brand; ?>"/>

<?php } ?>

<script type="text/javascript">

	$('#submit2').submit(function(e){
		e.preventDefault();
		var fdata = new FormData(this);
        var files = $('#gambar')[0].files;
        fdata.append('nama_brand', $('#nama_brand_edit').val());
        fdata.append('file_lama', $('#file_lama').val());
		brand    = "<?php echo base_url('brand/data_brand'); ?>";
		url 	= "<?php echo base_url('brand/edit_brand_sql'); ?>";

		$.ajax({
				method : "POST",
				url : url,
				data: fdata,
				contentType: false,
              	processData: false,
				success : function(data){

		                $.Notification.notify('success', 'top right', 'Brand', 'Brand Berhasil Diedit');
		                $('#edit-brand-modal').modal('hide');
						$('#data-brand').load(brand);
						$('.modal-backdrop').hide();
		            
				}
		});
	});
</script>