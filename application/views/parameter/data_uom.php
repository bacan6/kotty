<table class="table table-bordered" style="font-size:12px;margin-top: 20px;">
    <tr style="font-weight: bold;">
        <td width="5%">No</td>
        <td width="25%">Satuan</td>
        <td>Keterangan</td>
        <td width="5%"></td>
   	</tr>

   	<?php
   		$i=1;
   		foreach($satuan->result() as $row){
   	?>
   	<tr>
   		<td style="text-align: center;"><?php echo $i; ?></td>
   		<td><?php echo $row->satuan; ?></td>
   		<td><?php echo $row->keterangan; ?></td>
   		<td style="text-align: center;"><a class="hapus_uom" id="<?php echo $row->id_satuan; ?>"><i class="fa fa-trash"></i></a></td>
   	</tr>
   	<?php $i++; } ?>
</table>

<script type="text/javascript">
	$('.hapus_uom').on("click",function(){
        id 	= this.id;
        url = "<?php echo base_url('parameter/hapus_uom'); ?>";
        uom2 = "<?php echo base_url('parameter/data_uom'); ?>";

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
  			
  			$.post(url,{id : id}, function(){
  	        	$('#data-uom').load(uom2);
  	        });
  		});
    });
</script>