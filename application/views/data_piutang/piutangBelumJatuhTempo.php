<table class="table table-bordered table-striped" id="datatable" style="font-size: 11px;">
    <thead>
        <tr>
            <td width="4%" style="font-weight: bold;">No</td>
            <td style="font-weight: bold;">No Invoice</td>
            <td style="font-weight: bold;">Customer</td>
            <td style="font-weight: bold;">Jatuh Tempo</td>
            <td style="font-weight: bold;">Tanggal Order</td>
            <td style="font-weight: bold;" align="right">Grand Total</td>
            <td style="font-weight: bold;" align="right">Terbayar</td>
            <td style="font-weight: bold;"align="right">Sisa Piutang</td>
        </tr>
    </thead>

    <tbody>
    	<?php
    		$i = 1;
    		foreach($dataPiutang as $row){
    			$diskonPeritem = $this->modelPiutang->diskonPeritem($row->no_invoice);
    			$grandTotal = ($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$diskonPeritem);
    	?>
    	<tr>
    		<td><?php echo $i; ?></td>
    		<td><a href="<?php echo base_url('data_piutang/bayar_piutang?no_invoice='.$row->no_invoice); ?>"><?php echo $row->no_invoice; ?></a></td>
    		<td><?php echo $row->nama; ?></td>
    		<td><?php echo date_format(date_create($row->jatuh_tempo),'d M Y'); ?></td>
    		<td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
    		<td align="right">
    			<?php echo number_format($grandTotal,'0',',','.'); ?>
    		</td>
    		<td align="right">
    			<?php
                    //ambil data piutang terbayar
                    $piutang_terbayar = $this->modelPiutang->piutangTerbayar($row->no_invoice);

                    echo number_format($piutang_terbayar,'0',',','.')
                ?>
    		</td>
    		<td align="right">
    			<?php echo number_format($grandTotal-$piutang_terbayar,'0',',','.'); ?>
    		</td>
    	</tr>
    	<?php $i++; } ?>
    </tbody>
</table>

<script type="text/javascript">
	$(document).ready(function(){
		$('#datatable').dataTable();
	});
</script>
