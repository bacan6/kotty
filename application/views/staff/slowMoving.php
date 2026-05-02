<canvas id="bar-chart3" class="chart-holder" width="600" height="600"></canvas>

</table>

<script type="text/javascript">
	var trxStatus = document.getElementById("bar-chart3");

	var myChart = new Chart(trxStatus, {
			    	type: 'horizontalBar',
			    	data 	: { 
						        labels 	: <?php echo $produk; ?>,
						        datasets: [{
						        				label: 'Produk Kurang Laku',
						            			data: <?php echo $qty; ?>,
						            			backgroundColor: [dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors(),dynamicColors()],
						            			borderWidth: 1
						        		  }]
						    },
							    options: {
							        scales: {
							            xAxes: [{
							                ticks: {
							                    beginAtZero:true,
							                    userCallback: function(value, index, values) {
						                            return addCommas(value);
						                        }
							                }
							            }]
							        },
							        tooltips: {
						                enabled: true,  
						                callbacks: {
						                    label: function(tooltipItems, data) { 
						                        return addCommas(tooltipItems.xLabel);
						                    }
						                }
						            }
							    }
	});
</script>