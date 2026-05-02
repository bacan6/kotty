<canvas id="penjualanPerjam" class="chart-holder" width="600" height="200"></canvas>

<script type="text/javascript">
	var trxStatus = document.getElementById("penjualanPerjam");

	var myChart = new Chart(trxStatus, {
			    	type 	: 'line',
			    	data 	: { 
			        labels 	: <?php echo $tanggal; ?>,

			        datasets: [{
			        				label: 'Penjualan Perjam',
			            			data: <?php echo $sales; ?>,
			            			backgroundColor: '#008aff',
			            			borderColor : '#008aff',
			            			fill : false,
			        		  }]
			    },
							    options: {
							        scales: {
							            yAxes: [{
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
						                        return addCommas(tooltipItems.yLabel);
						                    }
						                }
						            }
							    }

	});
</script>