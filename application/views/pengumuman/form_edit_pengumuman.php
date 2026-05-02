<?php
	foreach($pengumuman->result() as $row){
?>
<div class="form-group">
	<textarea class="form-control" placeholder="Isi Pengumuman" id="konten"><?php echo $row->Isi; ?></textarea>
</div>

<?php } ?>

<script type="text/javascript">

	$(document).on("click",".edit-pengumuman-sql",function(){
		nama 		= $('#konten').val();

		id 		= $('#id_supplier_edit').val();
		pengumuman    = "<?php echo base_url('pengumuman/data_pengumuman'); ?>";

		url 	= "<?php echo base_url('pengumuman/edit_pengumuman_sql'); ?>";

		$.ajax({
					method : "POST",
					url : url,
					data : {konten : nama},
					success : function(data){

			                $.Notification.notify('success', 'top right', 'Pengumuman', 'Pengumuman Berhasil Diedit');
			                $('#edit-pengumuman-modal').modal('hide');
							$('#data-pengumuman').load(pengumuman);
							$('.modal-backdrop').hide();
			            
					}
		});
	});
</script>