<?php
	foreach($get_kategori->result() as $row){
?>
<div class="form-group">
	<input id="kategori_edit" type="text" class="form-control" value="<?php echo $row->kategori; ?>"/>
	<input id="id_kategori" type="hidden" value="<?php echo $row->id_kategori; ?>"/>
</div>
<?php } ?>