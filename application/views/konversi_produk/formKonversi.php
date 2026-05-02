<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 20px;">
    	<div class="col-md-12" style="text-align: right;">
    		<a class="btn btn-primary" onclick="printContent('print-area')"><i class="fa fa-print"></i> Print</a>
    	</div>
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12" id='print-area'>
           				<h4 style="text-align: center;">Form Konversi</h4>
           				<h4 style="text-align: center;">Produk Ke Bahan Baku</h4>

           				<table width="100%">
           					<tr>
           						<td width="15%">No Konversi</td>
           						<td width="1%">:</td>
           						<td><?php echo $infoKonversi->no_convert; ?></td>
           					</tr>

           					<tr>
           						<td width="15%">Tanggal</td>
           						<td width="1%">:</td>
           						<td><?php echo date_format(date_create($infoKonversi->tanggal),'d F Y'); ?></td>
           					</tr>

           					<tr>
           						<td width="15%">User</td>
           						<td width="1%">:</td>
           						<td><?php echo $infoKonversi->nama_user; ?></td>
           					</tr>
           				</table>
           				<br>
           				<table border="1" width="100%">
           					<tr style="font-weight: bold;">
           						<td width="5%">No</td>
           						<td width="15%">SKU</td>
           						<td>Nama Bahan</td>
           						<td width="10%">Qty</td>
           						<td width="10%">Satuan</td>
           					</tr>

           					<?php
           						$i = 1;
           						foreach($itemKonversi as $row){
           					?>
           					<tr>
           						<td><?php echo $i; ?></td>
           						<td><?php echo $row->sku; ?></td>
           						<td><?php echo $row->nama_bahan; ?></td>
           						<td><?php echo $row->qty; ?></td>
           						<td><?php echo $row->satuan; ?></td>		
           					</tr>
           					<?php $i++; } ?>
           				</table>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>

