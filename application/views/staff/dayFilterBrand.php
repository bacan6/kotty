<div class="input-group m-t-10" style="width: 200px;">
    <input type="text" id="date" class="form-control datepicker" placeholder="Pilih Tanggal" readonly>
    <span class="input-group-btn">
        <button type="button" class="btn btn-effect-ripple btn-primary" id="viewByDay"><i class="fa fa-search"></i></button>
    </span>
</div>

<script type="text/javascript">
	jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    });

    $('#viewByDay').on("click",function(){
    	var tanggal = $('#date').val();

    	var urlData = "<?php echo base_url('dashboard_brand/dataPenjualan'); ?>";


    	var urlSalesPerHour = "<?php echo base_url('dashboard_brand/salesPerHour'); ?>";
    	var urlSalesPerkategori = "<?php echo base_url('dashboard_brand/perkategoriSales'); ?>";
    	var urlSalesPerkasir = "<?php echo base_url('dashboard_brand/salesPerKasir'); ?>";
		var urlFastMoving = "<?php echo base_url('dashboard_brand/fastMoving'); ?>";
        var urlSlowMoving = "<?php echo base_url('dashboard_brand/slowMoving'); ?>";
		var urlBrand1 = "<?php echo base_url('dashboard_brand/salesPerBrand1'); ?>";
        var urlBrand2 = "<?php echo base_url('dashboard_brand/salesPerBrand2'); ?>";

    	//$('#salesPerHour').load(urlSalesPerHour,{tanggal : tanggal});
    	$('#salesPerkategori').load(urlSalesPerkategori,{tanggal : tanggal});
    	//$('#salesPerKasir').load(urlSalesPerkasir,{tanggal : tanggal});
		$('#fastMoving').load(urlFastMoving,{tanggal : tanggal});
        $('#slowMoving').load(urlSlowMoving,{tanggal : tanggal});
		$('#salesPerBrand1').load(urlBrand1,{tanggal : tanggal});
        $('#salesPerBrand2').load(urlBrand2,{tanggal : tanggal});
    });
</script>