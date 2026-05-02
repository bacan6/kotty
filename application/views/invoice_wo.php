<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" style="padding:30px;">
            	<div class="row" align="right">
            		<a class="btn btn-default" onclick="printContent('area-print')"><i class="fa fa-print"></i> Print</a>
            	</div>
                <div class="row">
	                <div class="col-md-12" id="area-print">
	                	<table width="100%">
	                        <?php
	                            foreach($header->result() as $hd){
	                        ?>
	                        <tr>
	                            <td style="text-align: center;">
	                                <h4><?php echo $hd->nama_perusahaan; ?></h4> 
	                                <h5>FORM STOCK TRANSFER</h5>
	                            </td>
	                        </tr>
	                            <?php } ?>
	                    </table>

	                    <?php
	                    	foreach($info_wo->result() as $in){
	                    ?>
	                    <table width="100%" style="margin-top: 20px;">
	                    	<tr>
	                    		<td width="50%">
	                    			<table class="table table-hover" style="font-size: 12px;">
	                    				<tr>
	                    					<td width="20%" style="font-weight: bold;">No WO</td>
	                    					<td width="1%">:</td>
	                    					<td><?php echo $in->no_order; ?></td>
	                    				</tr>

	                    				<tr>
	                    					<td width="20%" style="font-weight: bold;">Ref</td>
	                    					<td width="1%">:</td>
	                    					<td>
	                    						<?php 
	                    							$date = date_create($in->tanggal_order); 
	                    							echo date_format($date,'Hdm');
	                    						?>
	                    					</td>
	                    				</tr>

	                    				<tr>
	                    					<td style="font-weight:bold;">Created By</td>
	                    					<td>:</td>
	                    					<td><?php echo $in->nama_user; ?></td>
	                    				</tr>
	                    			</table>
	                    		</td>

	                    		<td width="50%">
	                    			<table class="table" style="font-size: 12px;">
	                    				<tr>
	                    					<td width="20%" style="font-weight: bold;">Date</td>
	                    					<td width="1%">:</td>
	                    					<td>
	                    						<?php 
	                    							echo date_format($date,'d M Y'); 
	                    						?>
	                    					</td>
	                    				</tr>

	                    				<tr>
	                    					<td style="font-weight:bold;">Time</td>
	                    					<td>:</td>
	                    					<td>
	                    						<?php
	                    							echo date_format($date,'H:i:s'); 
	                    						?>
	                    					</td>
	                    				</tr>

	                    				<tr>
	                    					<td style="font-weight:bold;"></td>
	                    					<td>.</td>
	                    					<td>
	                    						
	                    					</td>
	                    				</tr>
	                    			</table>
	                    		</td>
	                    	</tr>
	                    </table>
	                    <?php } ?>

	              		<div class="row" style="margin-top: 10px;">
	              			<div class="col-md-4 col-sm-4 col-xs-4">
	                    			<table class="table table-bordered table-striped" style="font-size: 12px;">
	                    				<tr style="font-weight: bold;background: #2A303A;color:white;">
	                    					<td width="3%">No</td>
	                    					<td width="67%">Nama Produk</td>
	                    					<td align="right" width="30%">Order</td>
	                    				</tr>

	                    				<?php
	                    					$i=1;
	                    					$total = 0;
	                    					foreach($order_item->result() as $pr){
	                    				?>
	                    				<tr>
	                    					<td><?php echo $i; ?></td>
	                    					<td><?php echo $pr->nama_produk; ?></td>
	                    					<td align="right"><?php echo number_format($pr->jumlah_produksi,'0',',','.'); ?></td>
	                    				</tr>
	                    				<?php $total = $total+$pr->jumlah_produksi; $i++; } ?>

	                    				<tr style="font-weight: bold;background: #2A303A;color:white;">
	                    					<td colspan="2" align="center">TOTAL</td>
	                    					<td align="right"><?php echo number_format($total,'0',',','.'); ?></td>
	                    				</tr>
	                    			</table>	
	                    	</div>

	                    	<div class="col-md-8 col-sm-8 col-xs-8">
	                    			<table class="table table-bordered table-striped" style="font-size: 12px;">
	                    				<tr style="font-weight: bold;background: #2A303A;color:white;">
	                    					<td width="3%">No</td>
	                    					<td width="77%">Material</td>
	                    					<td align="right" width="10%">QTY</td>
	                    					<td width="10%">UoM</td>
	                    				</tr>

	                    				<?php
	                    					$x = 1;
	                    					foreach($material_row->result() as $mt){
	                    				?>
	                    				<tr>
	                    					<td><?php echo $x; ?></td>
	                    					<td><?php echo $mt->nama_bahan; ?></td>
	                    					<td align="right"><?php echo number_format($mt->qty,'4','.',','); ?></td>
	                    					<td><?php echo $mt->satuan; ?></td>
	                    				</tr>
	                    				<?php $x++; } ?>
	                    			</table>

	                    			<br>
	                    			<br>
	                    			<i>Adjusment</i>
	                    			<table class="table table-bordered table-striped" style="font-size:12px;">
	                    				<?php
	                    					foreach($no_adjust->result() as $no){
	                    				?>
	                    				<tr style="font-weight: bold;background: #2A303A;color:white;">
	                    					<td><?php echo $no->no_adjust; ?></td>
	                    				</tr>
	                    				<tr>
	                    					<td>
	                    						<table class="table">
	                    							<tr style="font-weight: bold;">
	                    								<td width="5%">No</td>
	                    								<td>Material</td>
	                    								<td width="15%" align="right">QTY</td>
	                    								<td width="10%" align="center">UoM</td>
	                    							</tr>

	                    							<?php
	                    								$no_adjust = $no->no_adjust;

	                    								$data_item_tambahan = $this->model1->data_item_tambahan($no_adjust);
	                    								$a = 1;
	                    								foreach($data_item_tambahan->result() as $im){
	                    							?>
	                    							<tr>
	                    								<td><?php echo $a; ?></td>
	                    								<td><?php echo $im->nama_bahan; ?></td>
	                    								<td align="right"><?php echo number_format($im->qty,'2','.',','); ?></td>
	                    								<td align="center"><?php echo $im->satuan; ?></td>
	                    							</tr>
	                    							<?php $a++; } ?>
	                    						</table>
	                    					</td>
	                    				</tr>
	                    				<?php } ?>
	                    			</table>

	                    	</div>
	                   	</div>

	                   	<div class="row">
	                   		<div class="col-md-12">
	                   			<center>
			                        <table style="margin-top: 20px;" width="70%">
			                            <tr>
			                                <td width="33%">
			                                    <table class="table table-bordered" style="font-size: 12px;">
			                                        <tr style="font-weight: bold;background: #2A303A;color:white;">
			                                            <td style="text-align: center;">Created By</td>
			                                        </tr>
			                                        <tr height="100px">
			                                            <td>
			                                                
			                                            </td>
			                                        </tr>  
			                                        <tr height="30px">
			                                            <td style="text-align: center;font-weight: bold;">
			                                              <?php echo $in->nama_user; ?>
			                                            </td>
			                                        </tr> 
			                                    </table>
			                                </td>

			                                <td width="33%">
			                                    <table class="table table-bordered" style="font-size: 12px;">
			                                        <tr style="font-weight: bold;background: #2A303A;color:white;">
			                                            <td style="text-align: center;">Prepared By</td>
			                                        </tr>
			                                        <tr height="100px">
			                                            <td>
			                                                
			                                            </td>
			                                        </tr>
			                                        <tr height="30px">
			                                            <td style="text-align: center;font-weight: bold;">
			                                              	.
			                                            </td>
			                                        </tr>
			                                    </table>
			                                </td>

			                                <td width="33%">
			                                    <table class="table table-bordered" style="font-size: 12px;">
			                                        <tr style="font-weight: bold;background: #2A303A;color:white;">
			                                            <td style="text-align: center;">Received By</td>
			                                        </tr>
			                                        <tr height="100px">
			                                            <td>
			                                                
			                                            </td>
			                                        </tr>
			                                        <tr height="30px">
			                                            <td style="text-align: center;font-weight: bold;">
			                                              	.
			                                            </td>
			                                        </tr>
			                                    </table>
			                                </td>
			                            </tr>
			                        </table>
			                        </center>
	                   		</div>
	                   	</div>

	                </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
