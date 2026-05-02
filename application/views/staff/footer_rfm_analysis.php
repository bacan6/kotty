        <!-- Footer Start -->
        <footer class="footer">
            <?php echo $footer; ?>
        </footer>
        <!-- Footer Ends -->
    </section>
    <!-- Main Content Ends -->

    <script src="<?php echo base_url('assets'); ?>/js/jquery.js"></script>
    <script src="<?php echo base_url('assets'); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/js/modernizr.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/js/pace.min.js"></script>
    <script src="<?php echo base_url('assets'); ?>/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/js/jquery.app.js"></script>
    <script src="<?php echo base_url('assets'); ?>/assets/select2/select2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url('assets'); ?>/assets/timepicker/bootstrap-datepicker.js"></script>

    <script type="text/javascript">
        jQuery('.datepicker').datepicker({ format: "yyyy-mm-dd", autoclose: true });

        var urlGetRfmData = "<?php echo base_url('rfm_analysis/get_rfm_data'); ?>";
        var rfmData = null;

        function formatNumber(num) {
            if (num === null || num === undefined) return '0';
            return Number(num).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        }

        function getSegmentBadge(segment) {
            var colors = {
                'Champions': 'success',
                'Loyal Customers': 'primary',
                'Potential Loyalist': 'info',
                'At Risk': 'warning',
                "Can't Lose Them": 'danger',
                'Hibernating': 'default',
                'About to Sleep': 'warning',
                'Others': 'default'
            };
            var c = colors[segment] || 'default';
            return '<span class="label label-' + c + '">' + (segment || '') + '</span>';
        }

        $(document).ready(function() {
            $(".select2").select2({ width: '100%' });
            loadRfmData();
        });

        $('#btnFilter').on('click', function() { loadRfmData(); });

        $('#btnExport').on('click', function() {
            if (!rfmData || !rfmData.data || rfmData.data.length === 0) {
                alert('Data belum dimuat atau kosong.');
                return;
            }
            var csv = 'No,Customer,Recency (hari),Frequency,Monetary,R,F,M,Total Score,Segment\n';
            rfmData.data.forEach(function(row, i) {
                csv += (i + 1) + ',"' + (row.name || '').replace(/"/g, '""') + '",';
                csv += row.recency_days + ',' + row.frequency + ',' + row.monetary + ',';
                csv += row.r_score + ',' + row.f_score + ',' + row.m_score + ',' + row.total_rfm_score + ',"' + (row.segment_name || '') + '"\n';
            });
            var blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "rfm_analysis_" + new Date().toISOString().slice(0, 10) + ".csv";
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        function loadRfmData() {
            $('#rfmTableBody').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
            $('#rfm-segment-stats').html('');

            var payload = {
                id_toko: $('#id_toko').val(),
                tanggal_mulai: $('#tanggal_mulai').val(),
                tanggal_selesai: $('#tanggal_selesai').val(),
                id_group: $('#id_group').val(),
                segment_name: $('#segment_name').val(),
                recency_min: $('#recency_min').val(),
                recency_max: $('#recency_max').val(),
                frequency_min: $('#frequency_min').val(),
                frequency_max: $('#frequency_max').val(),
                monetary_min: $('#monetary_min').val(),
                monetary_max: $('#monetary_max').val()
            };

            $.ajax({
                method: "POST",
                url: urlGetRfmData,
                data: payload,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        rfmData = response;
                        updateSegmentStats(response.segment_summary);
                        updateRfmTable(response.data);
                    } else {
                        $('#rfmTableBody').html('<tr><td colspan="10" class="text-center text-danger">Gagal memuat data</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#rfmTableBody').html('<tr><td colspan="10" class="text-center text-danger">Error loading data</td></tr>');
                }
            });
        }

        function updateSegmentStats(summary) {
            if (!summary || typeof summary !== 'object') return;
            var order = ['Champions', 'Loyal Customers', 'Potential Loyalist', 'At Risk', "Can't Lose Them", 'Hibernating', 'About to Sleep', 'Others'];
            var html = '';
            order.forEach(function(seg) {
                var cnt = summary[seg] || 0;
                html += '<div class="col-md-2 col-sm-4"><div class="widget-panel widget-style-1 bg-default"><h2 class="m-0">' + cnt + '</h2><div>' + seg + '</div></div></div>';
            });
            $('#rfm-segment-stats').html(html);
        }

        function updateRfmTable(data) {
            var html = '';
            if (!data || data.length === 0) {
                html = '<tr><td colspan="10" class="text-center">Tidak ada data</td></tr>';
            } else {
                data.forEach(function(row, i) {
                    html += '<tr>';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td>' + (row.name || '-') + '</td>';
                    html += '<td class="text-right">' + formatNumber(row.recency_days) + '</td>';
                    html += '<td class="text-right">' + formatNumber(row.frequency) + '</td>';
                    html += '<td class="text-right">' + formatNumber(row.monetary) + '</td>';
                    html += '<td class="text-center">' + row.r_score + '</td>';
                    html += '<td class="text-center">' + row.f_score + '</td>';
                    html += '<td class="text-center">' + row.m_score + '</td>';
                    html += '<td class="text-center">' + row.total_rfm_score + '</td>';
                    html += '<td class="text-center">' + getSegmentBadge(row.segment_name) + '</td>';
                    html += '</tr>';
                });
            }
            $('#rfmTableBody').html(html);
        }
    </script>
</body>
</html>
