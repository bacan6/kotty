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

    	var urlData = "<?php echo base_url('dashboard_brand/dataPenjualanPerbulan'); ?>";


    	var urlSalesPerHour = "<?php echo base_url('dashboard_brand/salesPerHourMonth'); ?>";
    	var urlSalesPerkategori = "<?php echo base_url('dashboard_brand/perkategoriSalesMonth'); ?>";
    	var urlSalesPerkasir = "<?php echo base_url('dashboard_brand/salesPerKasirMonth'); ?>";
		var urlFastMoving = "<?php echo base_url('dashboard_brand/fastMovingMonth'); ?>";
        var urlSlowMoving = "<?php echo base_url('dashboard_brand/slowMovingMonth'); ?>";
		var urlBrand1 = "<?php echo base_url('dashboard_brand/salesPerBrand1Month'); ?>";
        var urlBrand2 = "<?php echo base_url('dashboard_brand/salesPerBrand2Month'); ?>";

    	//$('#salesPerHour').load(urlSalesPerHour,{tanggal : tanggal});
    	$('#salesPerkategori').load(urlSalesPerkategori,{tanggal : tanggal});
    	//$('#salesPerKasir').load(urlSalesPerkasir,{tanggal : tanggal});
		$('#fastMoving').load(urlFastMoving,{tanggal : tanggal});
        $('#slowMoving').load(urlSlowMoving,{tanggal : tanggal});
		$('#salesPerBrand1').load(urlBrand1,{tanggal : tanggal});
        $('#salesPerBrand2').load(urlBrand2,{tanggal : tanggal});
    });
</script>