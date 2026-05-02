<td colspan="4">
	<table class="table table-bordered">
		<tr>
			<td width="60%">Gudang</td>
			<td style="text-align: right;"><?php echo number_format($stokGudang,'0',',','.'); ?></td>
		</tr>

		<?php
			foreach($stokStore as $row){
		?>
		<tr>
			<td><?php echo $row->store; ?></td>
			<td style="text-align: right;"><?php echo number_format($row->stok,'0',',','.'); ?></td>
		</tr>
		<?php } ?>

		<tr>
			<td colspan="2" style="text-align: right;"><a class="btn btn-primary removeUp" id="<?php echo $id; ?>"><i class="fa fa-caret-square-o-up"></i></a></td>
		</tr>
	</table>
</td>

<script type="text/javascript">
	$('.removeUp').on("click",function(){
		var id = this.id;

		$('#viewAllStok'+id).empty();
	})
</script>
