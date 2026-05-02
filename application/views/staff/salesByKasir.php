<canvas id="salesByKasir" class="chart-holder" width="600" height="600"></canvas>

<script type="text/javascript">
	var trxStatus = document.getElementById("salesByKasir");

	var myChart = new Chart(trxStatus, {
			    	type 	: 'pie',
			    	data 	: { 
			        labels 	: <?php echo $kasir; ?>,

			        datasets: [{
			        				label: 'Penjualan Perkasir',
			            			data: <?php echo $sales; ?>,
			            			backgroundColor:  [dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors()],
			            			borderWidth: 1
			        		  }]
			    }
	});
</script>