<?php
	foreach($waste->result() as $row){
?>
<input id="nama_keterangan" type="text" class="form-control" value="<?php echo $row->keterangan; ?>"/>
<input id="id_keterangan" type="hidden" value="<?php echo $row->id_keterangan; ?>"/>
<?php } ?>

<script type="text/javascript">
	$('#simpan-waste').on("click",function(){
		id 			= $('#id_keterangan').val();
		keterangan 	= $('#nama_keterangan').val();
		muat = "<?php echo base_url('parameter/data_waste'); ?>";

		url = "<?php echo base_url('parameter/update_waste_sql'); ?>";
	
		$.post(url,{id : id, keterangan : keterangan}, function(){
			$('#kategori-waste-modal').modal('hide');
			$('#kategori-waste').load(muat);
			$('.modal-backdrop').remove();
		});
	});
</script>