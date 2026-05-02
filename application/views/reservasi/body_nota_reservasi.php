<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Data Reservasi</h3> 
	</div>

	<div class="row">
	    <div class="portlet"><!-- /primary heading -->
	        <div id="portlet2" class="panel-collapse collapse in">
	            <div class="portlet-body">
	            	<div class="row">
	            		<div class="pull-right">
		                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
		                </div>
	            	</div>
	            	<div class="row" id="print-area">
	            		<?php
	            			foreach($detail_invoice as $dl){
	            		?>
	            		<div class="col-md-12">
	            			<img src="<?php echo base_url('assets/Batik-Salma-Cirebon.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:80px;"/>
	                    <?php
	                        foreach($receipt->result() as $cf){
	                    ?>
	                    <h5 align="center"><?php echo $cf->nama_perusahaan; ?></h5>
	                    <h5 align="center"><?php echo $cf->alamat; ?></h5>
	                    <h5 align="center"><?php echo $cf->kontak; ?></h5>
	                    
	                    <center>
	                    <table width="30%">
	                        <tr style="border-top:dashed 1px #000;">
	                            <td width="160px"></td>
	                            <td width="20px"></td>
	                            <td width="80px" align="right"></td>
	                            <td align="right" width="80px"></td>
	                        </tr>

	                        <?php
	                        	foreach($reservasi_item as $row){
	                        ?>
	                       	<tr>
	                       		<td><?php echo $row->nama_produk; ?></td>
	                       		<td align="right"><?php echo $row->qty; ?></td>
	                       		<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
	                       		<td align="right"><?php echo number_format($row->harga*$row->qty,'0',',','.'); ?></td>
	                       	</tr>

	                       	<?php
	                       		if($row->diskon > 0){
	                       	?>
	                       	<tr>
	                       		<td colspan="3" align="right">Diskon</td>
	                       		<td align="right">(<?php echo number_format($row->diskon,'0',',','.'); ?>)</td>
	                       	</tr>
	                       	<?php } } ?>
	                        

	                        <tr style="border-top:dashed 1px #000;">
	                            <td colspan="3" align="CENTER">Subtotal</td>
	                            <td align="right"><?php echo number_format($dl->total_reservasi-$dl->diskon_produk,'0',',','.	'); ?></td>
	                        </tr>

	                        <tr>
	                            <td colspan="3" align="CENTER">Diskon</td>
	                            <td align="right"><?php echo number_format($dl->diskon_promosi,'0',',','.	'); ?></td>
	                        </tr>

	                        <tr>
	                            <td colspan="3" align="CENTER">Ongkir</td>
	                            <td align="right"><?php echo number_format($dl->ongkir,'0',',','.	'); ?></td>
	                        </tr>

	                        <tr style="border-top:dashed 1px #000;font-weight:bold;">
	                            <td colspan="3" align="CENTER">Grand Total</td>
	                            <td align="right"><?php echo number_format(($dl->total_reservasi+$dl->ongkir)-($dl->diskon_produk+$dl->diskon_promosi),'0',',','.	'); ?></td>
	                        </tr>

	                        <tr style="border-top:dashed 1px #000;font-weight:bold;">
	                            <td colspan="3" align="CENTER">Down Payment</td>
	                            <td align="right"><?php echo number_format($dl->down_payment,'0',',','.	'); ?></td>
	                        </tr>

	                        <tr style="border-top:dashed 1px #000;font-weight:bold;">
	                            <td colspan="3" align="CENTER">Sisa Pembayaran	</td>
	                            <td align="right"><?php echo number_format((($dl->total_reservasi+$dl->ongkir)-($dl->diskon_produk+$dl->diskon_promosi))-$dl->down_payment,'0',',','.	'); ?></td>
	                        </tr>

	                        <tr style="border-top:dashed 1px #000;"">
	                            <td colspan="4" align="CENTER">
	                            	No Reservasi 	: <?php echo $dl->no_reservasi; ?> <br>
	                            	Atas Nama 		: <?php echo $dl->atas_nama; ?> <br>
	                            	Tangggal Rsv 	: <?php echo date_format(date_create($dl->tanggal_reservasi),'d m Y'); ?> <br>
	                            	Keterangan 		: <?php echo $dl->keterangan; ?>
	                            </td>
	                        </tr>

	                        <tr>
	                            <td colspan="4" align="center"><?php echo $cf->footer; ?></td>
	                        </tr>
	                    </table>
	                    <?php } ?>
	            		</div>
	            		<?php } ?>
	            	</div>
	            </div>
	        </div>
	    </div> <!-- /Portlet -->
	</div>

	<div class="row">
		<div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Pembayaran Reservasi</h3></div>
                <div class="panel-body">
                	<form action="<?php echo base_url('penjualan/bayar_hutang_sql'); ?>" method="post">
	                	<div class="form-group">
		                	<div class="input-group">
		                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
		                        <input type="text" class="form-control" placeholder="Nominal" name="nominal">
		                    	<input type="hidden" name="no_invoice" value="<?php echo $_GET['no_reservasi']; ?>">
		                    </div>
	                   	</div>

	                   	<div class="form-group">
		                	<div class="input-group">
		                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
		                        <select class="select2" name="payment_type">
		                        	<?php
		                        		foreach($payment_type as $py){
		                        	?>
		                        	<option value="<?php echo $py->id; ?>"><?php echo $py->payment_type; ?></option>
		                        	<?php } ?>
		                        </select>
		                    </div>
	                   	</div>	
	              	
		                <div class="form-group">
		                    <div class="input-group">
		                        <span class="input-group-addon"><i class="fa fa-list"></i></span>
		                        <textarea class="form-control" placeholder="Keterangan" name="keterangan"></textarea>
		                    </div>
		                </div>

		                <div class="form-group" style="text-align: right;">
		                	<input type="submit" class="btn btn-primary" value="Submit"> 
		                	
		                </div>
	            	</form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    	<div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Riwayat Pembayaran</h3> <p style="text-align: right;"><a href="#" onclick="printContent('print-area2')" class="btn btn-inverse"><i class="fa fa-print"></i></a></p></div>
                <div class="panel-body" id="print-area2">
                	<img src="<?php echo base_url('assets/Batik-Salma-Cirebon.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:80px;"/>
	                    <?php
	                        foreach($receipt->result() as $cf){
	                    ?>
	                    <h5 align="center"><?php echo $cf->nama_perusahaan; ?></h5>
	                    <h5 align="center"><?php echo $cf->alamat; ?></h5>
	                    <h5 align="center"><?php echo $cf->kontak; ?></h5>
	                    <?php } ?>
	         		<table class="table">
	         			<tr style="font-weight: bold;">
	         				<td width="10%">Tanggal</td>
	         				<td width="20%">Tipe Bayar</td>
	         				<td width="20%">PIC</td>
	         				<td width="20%">Keterangan</td>
	         				<td align="right">Nominal Pembayaran</td>
	         			</tr>

	         			<?php
	         				$total = 0;
	         				foreach($riwayat_pembayaran as $rp){
	         			?>
	         			<tr>
	         				<td><?php echo date_format(date_create($rp->tanggal),"d-M-Y"); ?></td>
	         				<td><?php echo $rp->payment_type; ?></td>
	         				<td><?php echo $rp->nama_user; ?></td>
	         				<td><?php echo $rp->keterangan; ?></td>
	         				<td align="right"><?php echo number_format($rp->nominal,'0',',','.'); ?></td>
	         			</tr>
	         			<?php $total = $total+$rp->nominal; } ?>

	         			<tr>
	         				<td colspan="4" align="right">Total Terbayar</td>
	         				<td align="right"><?php echo number_format($total,'0',',','.'); ?></td>
	         			</tr>

	         			<tr>
	                        <td colspan="4" align="right">Grand Total</td>
	                        <td align="right"><?php echo number_format(($dl->total_reservasi+$dl->ongkir)-($dl->diskon_produk+$dl->diskon_promosi),'0',',','.	'); ?></td>
	                     </tr>

	                     <tr>
	                     	<td colspan="4" align="right">Down Payment</td>
	                        <td align="right"><?php echo number_format($dl->down_payment,'0',',','.	'); ?></td>
	                     </tr>

	                     <tr style="border-top:dashed 1px #000;font-weight:bold;">
	                        <td colspan="4" align="right">Sisa Pembayaran</td>
	                        <td align="right"><?php echo number_format(((($dl->total_reservasi+$dl->ongkir)-($dl->diskon_produk+$dl->diskon_promosi))-$dl->down_payment)-$total,'0',',','.	'); ?></td>
	                     </tr>
	         		</table>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->
	</div>

	<div class="row">
		<div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Pengambilan Barang</h3></div>
                <div class="panel-body">
                	<form action="<?php echo base_url('penjualan/ambil_barang_sql'); ?>" method="post">
                		<input type="hidden" name="no_reservasi" value="<?php echo $_GET['no_reservasi']; ?>"/>
	                	<table class="table">
	                		<?php
	                			foreach($reservasi_item as $ri){
	                		?>
	                		<tr>
	                			<td width="60%"><?php echo $ri->nama_produk; ?></td>
	                			<td>

	                				<?php
	                					$stok_toko = $this->model1->get_stok_lama_produk_store($ri->id_produk,$this->session->userdata("id_store"));
	                				?>

	                				<input type="number" class="form-control" name="qty[]" max="<?php echo $stok_toko; ?>"/>
	                				<input type="hidden" name="sku[]" value="<?php echo $ri->id_produk; ?>"/>
	                			</td>
	                		</tr>
	                		<?php } ?>

	                		<tr>
	                			<td colspan="2" align="right"><input type="submit" class="btn btn-primary" value="Submit"/></td>
	                		</tr>
	                	</table>
	            	</form>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->

    	<div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Riwayat Pengambilan Barang</h3><p style="text-align: right;"><a href="#" onclick="printContent('print-area3')" class="btn btn-inverse"><i class="fa fa-print"></i></a></p></div>
                <div class="panel-body" id="print-area3">
                	<img src="<?php echo base_url('assets/Batik-Salma-Cirebon.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:80px;"/>
	                <?php
	                   	foreach($receipt->result() as $cf){
	                ?>
	                    <h5 align="center"><?php echo $cf->nama_perusahaan; ?></h5>
	                    <h5 align="center"><?php echo $cf->alamat; ?></h5>
	                    <h5 align="center"><?php echo $cf->kontak; ?></h5>
	                <?php } ?>
	         		
	         		<table class="table">
	         			<tr>
	         				<td width="5%">No</td>
	         				<td>Nama Produk</td>
	         				<td width="10%">Qty</td>
	         				<td width="20%">Tanggal</td>
	         				<td width="20%">PIC</td>
	         			</tr>	

	         			<?php
	         				$y = 1;
	         				foreach($riwayat_pengambilan as $pn){
	         			?>
	         			<tr>
	         				<td><?php echo $y; ?></td>
	         				<td><?php echo $pn->nama_produk; ?></td>
	         				<td><?php echo $pn->qty; ?></td>
	         				<td><?php echo date_format(date_create($pn->tanggal),'d-M-Y'); ?></td>
	         				<td><?php echo $pn->nama_user; ?></td>
	         			</tr>
	         			<?php $y++; } ?>
	         		</table>
                </div><!-- panel-body -->
            </div> <!-- panel -->
        </div> <!-- col-->
	</div>
</div>
