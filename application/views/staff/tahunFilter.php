<div class="input-group m-t-10" style="width: 200px;">
    <input type="text" id="date" class="form-control datepicker" placeholder="Pilih Tahun" readonly>
    <span class="input-group-btn">
        <button type="button" class="btn btn-effect-ripple btn-primary" id="viewByYear"><i class="fa fa-search"></i></button>
    </span>
</div>


<script type="text/javascript">
	jQuery('.datepicker').datepicker({
        format: "yyyy",
        autoclose : true,
        viewMode: "years", 
        minViewMode: "years"
    });

    $('#viewByYear').on("click",function(){
    	var tanggal = $('#date').val();

    	var urlData = "<?php echo base_url('dashboard/dataPenjualanPertahun'); ?>";

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
                                    
                                    
                                    $('#totalMargin').text(addCommas(totalMargin));
                                    $('#totalInv').text(addCommas(totalInv));
    								$('#sales').text(addCommas(totalSales));
    								$('#transaksi').text(addCommas(transaction));
    								$('#basketSize').text(addCommas(basketSize));
    								$('#totalItem').text(addCommas(totalItem));
    							});
    						  }
    	});

    	var urlSalesPerHour = "<?php echo base_url('dashboard/salesPerHourYear'); ?>";
    	var urlSalesPerkategori = "<?php echo base_url('dashboard/perkategoriSalesYear'); ?>";
    	var urlSalesPerkasir = "<?php echo base_url('dashboard/salesPerKasirYear'); ?>";
    	var urlSalesPerSubkategori = "<?php echo base_url('dashboard/subkategoriSalesYear'); ?>";
    	var urlTopProdukByKategoriWidget = "<?php echo base_url('dashboard/topProdukByKategoriWidget'); ?>";
    	var urlTopProdukBySubkategoriWidget = "<?php echo base_url('dashboard/topProdukBySubkategoriWidget'); ?>";
		var urlFastMoving = "<?php echo base_url('dashboard/fastMovingYear'); ?>";
        var urlSlowMoving = "<?php echo base_url('dashboard/slowMovingYear'); ?>";
		var urlBrand1 = "<?php echo base_url('dashboard/salesPerBrand1Year'); ?>";
        var urlBrand2 = "<?php echo base_url('dashboard/salesPerBrand2Year'); ?>";

    	$('#salesPerHour').load(urlSalesPerHour,{tanggal : tanggal});
    	$('#salesPerkategori').load(urlSalesPerkategori,{tanggal : tanggal});
    	$('#salesPerKasir').load(urlSalesPerkasir,{tanggal : tanggal});
    	$('#salesPerSubkategori').load(urlSalesPerSubkategori,{tanggal : tanggal});
    	$('#topProdukByKategoriWidget').load(urlTopProdukByKategoriWidget,{tanggal : tanggal});
    	$('#topProdukBySubkategoriWidget').load(urlTopProdukBySubkategoriWidget,{tanggal : tanggal});
		$('#fastMoving').load(urlFastMoving,{tanggal : tanggal});
        $('#slowMoving').load(urlSlowMoving,{tanggal : tanggal});
		$('#salesPerBrand1').load(urlBrand1,{tanggal : tanggal});
        $('#salesPerBrand2').load(urlBrand2,{tanggal : tanggal});
    });
</script>