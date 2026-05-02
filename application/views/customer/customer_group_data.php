<?php
	$i = 1;
	foreach($group_customer->result() as $row){
?>
<tr>
	<td align="center"><?php echo $i; ?></td>
	<td><?php echo $row->group_customer; ?></td>
	<td align="center"><a class="hapusGroup" id="<?php echo $row->id_group; ?>"><i class="fa fa-trash"></i></a> | <a href="#editGroup" data-toggle="modal" class="modalEdit" id="<?php echo $row->id_group; ?>"><i class="fa fa-pencil"></i></a></td>
</tr>
<?php $i++; } ?>

<script type="text/javascript">
	$('.hapusGroup').on("click",function(){
		var idGroup = this.id;
		var urlHapus = "<?php echo base_url('customer_group/hapusGroup'); ?>";

		swal({
  		  title: "Are you sure?",
  		  text: "You will not be able to recover this imaginary file!",
  		  type: "warning",
  		  showCancelButton: true,
  		  confirmButtonColor: "#DD6B55",
  		  confirmButtonText: "Yes, delete it!",
  		  closeOnConfirm: false
  		},
  		function(){
  		    swal("Deleted!", "Your imaginary file has been deleted.", "success");
  			$.post(urlHapus,{id : idGroup}, function(){
				$('#customer-group-data').load(group_data);
  	        });
  		});
	})

	$('.modalEdit').on("click",function(){
		var urlFormEdit = "<?php echo base_url('customer_group/formEditCustomerGroup'); ?>";
		var id = this.id;

		$.post(urlFormEdit,{id : id},function(data){
			$('#editPlace').html(data);
		});
	});

	$('#ubah-customer-group').on("click",function(){
		var groupValue = $('#groupValue').val();
		var id = $('#idGroup').val();
		var urlUbah = "<?php echo base_url('customer_group/ubahGroupSQL'); ?>";

		$.post(urlUbah,{groupValue : groupValue, id : id},function(){
			$('#customer-group-data').load(group_data);
			$('#editGroup').modal('hide');
		});
	});
</script>