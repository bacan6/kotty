<tr id="row<?php echo $no; ?>">
	<td><input type="number" name="qty[]" class="form-control"/></td>
	<td><input type="number" name="discount[]" class="form-control"></td>
	<td><input type="text" class="form-control datepicker" name="date_start[]" readonly/></td>
	<td><input type="text" class="form-control datepicker" name="date_end[]" readonly/></td>
	<td><a class="btn btn-danger hapus-baris" id="<?php echo $no; ?>"><i class="fa fa-trash"></i></a></td>
</tr>

<script type="text/javascript">
	jQuery('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose :true
                });

	$('.hapus-baris').on("click",function(){
		id = this.id;

		$('#row'+id).remove();
	});
</script>
