<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    }); 
    
    // Select2
    jQuery(".select2").select2({
        width: '100%'
    });

    $('#type_bayar').change(function(){
        type = $('#type_bayar').val();

        sub_account = "<?php echo base_url('laporan/sub_account'); ?>";
                
        $('#sub-account').load(sub_account,{id : type});        
    });  

    $('#viewReport').on("click",function(){
    	var dateStart 		= $('#dateStart').val();
    	var dateEnd 	 	= $('#dateEnd').val();
        var id_toko 	 	= $('#id_toko').val();
    	

    	var url = "<?php echo base_url('laporan/akumulasiPenjualanProdukReport'); ?>";

    	$.ajax({
    				method 		: "POST",
    				data 		: {dateStart : dateStart, dateEnd : dateEnd,id_toko:id_toko},
    				url 		: url,
    				beforeSend 	: function(){
    								var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";
									$('#dataReport').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
    							  },
    				success 	: function(data){
    								$('#dataReport').html(data);
    							  }
    	});
    });
</script>