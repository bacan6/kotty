<script type="text/javascript">
	$(document).ready(function(){
		jQuery('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
	});

	$('#submitLaporan').on("click",function(){
		var dateStart 	= $('#dateStart').val();
		var dateEnd   	= $('#dateEnd').val();

		var url = "<?php echo base_url(); ?>";

		var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";

		var url = "<?php echo base_url('laporan/akumulasiPenjualanReport'); ?>";

		$.ajax({
					method 			: "POST",
					url 			: url,
					data 			: {dateStart : dateStart, dateEnd : dateEnd},
					beforeSend 		: function(){
										$('#dataLaporan').html("<tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr>");
								      },
					success 		: function(data){
										var urlDate = "<?php echo base_url('laporan/dateReport'); ?>";
										$('#dataLaporan').html(data);
										$('#titleReport').load(urlDate,{dateStart : dateStart, dateEnd : dateEnd});
									  }
		});
	});
</script>