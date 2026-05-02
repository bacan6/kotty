<canvas id="subkategori-chart" class="chart-holder" width="600" height="600"></canvas>

<script type="text/javascript">
	var trxStatus = document.getElementById("subkategori-chart");
	var len = <?php echo (isset($len) ? (int)$len : 0); ?>;

	var backgroundColors = [];
	for (var i = 0; i < len; i++) {
		backgroundColors.push(dynamicColors());
	}

	var myChart = new Chart(trxStatus, {
		type: 'horizontalBar',
		data: {
			labels: <?php echo $subkategori; ?>,
			datasets: [{
				label: 'Penjualan Per Subkategori',
				data: <?php echo $sales; ?>,
				backgroundColor: backgroundColors,
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				xAxes: [{
					ticks: {
						beginAtZero: true,
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

