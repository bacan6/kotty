<div class="input-group m-t-10" style="width: 200px;">
    <input type="text" id="date" class="form-control datepicker" placeholder="Pilih Bulan" readonly>
    <span class="input-group-btn">
        <button type="button" class="btn btn-effect-ripple btn-primary" id="viewByMonth"><i class="fa fa-search"></i></button>
    </span>
</div>

<script type="text/javascript">
	jQuery('.datepicker').datepicker({
        format: "yyyy-mm",
        autoclose : true,
        viewMode: "months", 
        minViewMode: "months"
    });

    $('#viewByMonth').on("click",function(){
    	var tanggal = $('#date').val();

    	var urlData = "<?php echo base_url('dashboard/dataPenjualanPerbulan'); ?>";

    	$.ajax({
    				method 	: "POST",
    				url : urlData,
    				dataType : 'json',
    				data : {tanggal : tanggal},
    				success : function(response){
    							$.each(response, function(x,obj){
    								var totalSales = obj.totalSales;
    								var transaction = obj.transaction;
    								var basketSize = obj.basketSize;
    								var totalItem = obj.totalItemTerjual;
                                    var totalMargin = obj.totalMargin;
                                    var totalInv = obj.totalInv;
                                            
                                    $('#margin').text(addCommas(totalMargin));
                                    $('#totalInv').text(addCommas(totalInv));
    								$('#sales').text(addCommas(totalSales));
    								$('#transaksi').text(addCommas(transaction));
    								$('#basketSize').text(addCommas(basketSize));
    								$('#totalItem').text(addCommas(totalItem));
    							});
    						  }
    	});

    	var urlSalesPerHour = "<?php echo base_url('dashboard/salesPerHourMonth'); ?>";
    	var urlSalesPerkategori = "<?php echo base_url('dashboard/perkategoriSalesMonth'); ?>";
    	var urlSalesPerkasir = "<?php echo base_url('dashboard/salesPerKasirMonth'); ?>";

    	$('#salesPerHour').load(urlSalesPerHour,{tanggal : tanggal});
    	$('#salesPerkategori').load(urlSalesPerkategori,{tanggal : tanggal});
    	$('#salesPerKasir').load(urlSalesPerkasir,{tanggal : tanggal});
    });
</script>