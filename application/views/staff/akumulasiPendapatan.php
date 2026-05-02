<h3 align="center" style="color: black;">Grafik Penjualan <br>
Periode <br>
<?php echo $periode; ?></h3>


<br>
<canvas id="storeSales" class="chart-holder" width="600" height="200"></canvas>

<script type="text/javascript">
	var trxStatus = document.getElementById("storeSales");

	var myChart = new Chart(trxStatus, {
			    	type 	: 'bar',
			    	data 	: { 
							        labels 	: <?php echo $title; ?>,

							        datasets: [{
							        				label 			: 'Penjualan',
							            			data 			: <?php echo $value; ?>,
							            			backgroundColor:  'rgba(18, 168, 157, 0.2)',
							            			borderWidth		: 1
							        		  },{
							        		  		label 			: 'Potongan / Diskon',
							        		  		data  			: <?php echo $potongan; ?>,
							        		  		backgroundColor :'#de2b2b',
                        							borderColor		: "#de2b2b",
                        							borderWidth 	: 3,
							        		  		type 			: 'line',
							        		  		fill 			: false
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