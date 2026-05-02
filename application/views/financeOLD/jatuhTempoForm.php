<div class="input-group">
    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    <input type="text" class="form-control datepicker" placeholder="Jatuh Tempo" value="<?php echo $currentDeadline; ?>" readonly>
</div>

<div class="form-group" style="text-align: right;margin-top: 5px;">
	<a class="btn btn-primary" id="simpan"><i class="fa fa-save"></i> Save</a>
</div>

<script type="text/javascript">
	jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    });

    $('#simpan').on("click",function(){
    	var noPO = "<?php echo $noPO; ?>";
    	var tanggal = $('.datepicker').val();

    	var urlUpdateTempo = "<?php echo base_url('finance/updateTanggalJatuhTempo'); ?>";

    	$.ajax({
    				method : "POST",
    				url : urlUpdateTempo,
    				data : {noPO : noPO, tanggal : tanggal},
    				error : function(){
    							alert("Error");
    						},
    				success : function(){
    							$('#editJatuhTempo').modal('hide');
    							$('#tanggalJatuhTempo').text(tanggal);
    						  }
    	});
    });
</script>