<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    });	

	$('#customer-form').select2({
        placeholder: "Pilih Data Customer",
        ajax: {
                    url         : '<?php echo base_url('penjualan/ajax_customer'); ?>',
                    dataType    : 'json',
                    quietMillis : 100,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
        minimumInputLength: 3,
    });

    $('#submitReport').on("click",function(){
    	var dateStart 		= $('#date_start').val();
    	var dateEnd 		= $('#date_end').val();
    	var idCustomer 		= $('#customer-form').val();

    	var url = "<?php echo base_url('laporan/penjualanPercustomerReport'); ?>";
    	var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";

    	$.ajax({
    				method 			: "POST",
    				data 			: {dateStart : dateStart, dateEnd : dateEnd, idCustomer : idCustomer},
    				url 			: url,
    				beforeSend 		: function(){
    									$('#dataReport').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
    								  },
    				success 		: function(data){
    									$('#dataReport').html(data);
    								  }
    	});
    });
</script>