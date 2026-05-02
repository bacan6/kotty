<?php
	foreach($viewCart as $row){
?>
<tr id="row<?php echo $row->ID; ?>">
	<td width="20%"><?php echo $row->no_online; ?></td>
	<td><?php echo $row->tanggal_online; ?></td>
	<td><?php echo $row->tanggal_invoice; ?></td>
	<td><?php echo $row->status; ?></td>
	<td style="text-align: center;"><a class="hapusCart" id="<?php echo $row->ID; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php } ?>

<script type="text/javascript">
	$('.hapusCart').on("click",function(){
		var id = this.id;
		var urlHapusCart = "<?php echo base_url('invoice_online/hapusCart'); ?>";

		$.ajax({
					method : "POST",
					url : urlHapusCart,
					data : {id : id},
					error : function(){
								alert("Terjadi Kesalahan");
							},
					success : function(){
								var dataUrl = "<?php echo base_url('invoice_online/viewInvoice'); ?>";
								$('#data-input').load(dataUrl);
							  }
		});
	});
</script>