<div class="form-inline">
	<div class="form-group">
		<input type="text" class="form-control datepicker" id="dateStart" placeholder="Year Start" readonly/>
	</div>

	<div class="form-group">
		<input type="text" class="form-control datepicker" id="dateEnd" placeholder="Year End" readonly/>
	</div>

	<div class="form-group">
		<a class="btn btn-primary" id="submit"><i class="fa fa-search"></i></a>
	</div>
</div>

<script type="text/javascript">
	jQuery('.datepicker').datepicker({
        format: "yyyy",
        autoclose : true,
        viewMode: "years", 
        minViewMode: "years"
    });

    $('#submit').on("click",function(){
    	var dateStart = $('#dateStart').val();
    	var dateEnd = $('#dateEnd').val();
    	var type = "year";

    	var urlLiniMasa = "<?php echo base_url('dashboard/liniMasa'); ?>";

    	$.ajax({
    				method : "POST",
    				url : urlLiniMasa,
    				data : {dateStart : dateStart, dateEnd : dateEnd,type : type},
    				beforeSend : function(){
    								var loading = "<?php echo base_url('dashboard/loading'); ?>";
    								$('#graph').load(loading);
    							  },
    				success : function(response){
    							$('#graph').html(response);
    						  }
    	});
    });
</script>