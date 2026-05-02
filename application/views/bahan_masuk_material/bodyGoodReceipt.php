<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->    
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
	                <div class="col-md-12" style="text-align: right;">
	                    <!--<a onclick="printContent('area-print')" class="btn btn-default"><i class="fa fa-print"></i> Print </a>-->
	                    <?php
	                    	$no_po 		= $_GET['noPO']; 
                        	$status_po 	= $this->model1->status_po($no_po);
                        	
                        	if($status_po < 1){
                        ?>
	                    <a onclick="return confirm('Are You Sure ?')" href="<?php echo base_url('bahanMasukMaterial/changePOStatus?status=1'.'&no_po='.$_GET['noPO']); ?>" class="btn btn-success"><i class="fa fa-check"></i> Accept </a>
	                    <a onclick="return confirm('Are You Sure ?')" href="<?php echo base_url('bahanMasukMaterial/changePOStatus?status=2'.'&no_po='.$_GET['noPO']); ?>" class="btn btn-danger"><i class="ion-close"></i> Decline </a>
	                	<?php } ?>

                        <?php
                            if($status_po==1){
                        ?>
                            <a class='btn btn-primary' onclick="return confirm('Are You Sure Close This Transaction ?')" href="<?php echo base_url('bahanMasukMaterial/changePOStatus?status=3'.'&no_po='.$_GET['noPO']); ?>"><i class='fa fa-power-off'></i> Tutup Transaksi </a>
                        <?php   
                            }
                        ?>
	                </div>
                </div>

                <div class="row">
                	<div class="col-md-12">
                		<table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <h4><?php echo $hd->nama_perusahaan; ?></h4> 
                                    <h5>Goods Received Note</h5>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="margin-top: 30px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 15%;">Date</td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                                <?php
                                                    $date_po = date_create($noteInfo->tanggal_po);

                                                    echo date_format($date_po,'d M Y');
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;width: 15%;">No PO</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $noteInfo->no_po; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;width: 15%;">Status</td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                            	<?php
                                            		$status_po 	= $noteInfo->status;

                                            		if($status_po==0){
			                                            echo "<span class='label label-primary'>Menunggu Approve</span>";
			                                        } elseif($status_po=='1') {
			                                            echo "<span class='label label-success'>Diterima</span>";
			                                        } elseif($status_po=='2') {
			                                            echo "<span class='label label-danger'>Ditolak</span>";
			                                        } elseif($status_po=='3'){
                                                         echo "<span class='label label-info'>Transaksi Selesai</span>";
                                                    }
                                            	?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    
                                </td>
                            </tr>
                        </table>

                        <table style="margin-top: 20px;" width="100%">
                            <tr>
                                <td width="50%">
                                    <table class="table table-bordered" style="font-size: 12px;">
                                        <tr style="font-weight: bold;background: #2A303A;color:white;">
                                            <td>Accepted & Approved By</td>
                                        </tr>
                                        <?php
                                            foreach($header->result() as $hd){
                                        ?>
                                        <tr height="100px">
                                            <td>
                                                <b><?php echo $hd->nama_perusahaan; ?></b> <br>
                                                <?php echo $hd->alamat; ?> <br>
                                                <?php echo $hd->kontak; ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </td>

                                <td width="50%">
                                    <table class="table table-bordered" style="font-size: 12px;">
                                        <tr style="font-weight: bold;background: #2A303A;color:white;">
                                            <td>Vendor / Supplier</td>
                                        </tr>

                                        <tr height="100px">
                                            <td>
                                                <b><?php echo $noteInfo->supplier; ?></b> <br>
                                                <?php echo $noteInfo->alamat; ?> <br>
                                                <?php echo $noteInfo->kontak; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <table class="table table-bordered table-striped" style="margin-top: 20px;font-size:12px;">
                            <thead>
                                <tr style="background: #2A303A;color:white;font-weight: bold;">
                                    <td width="5%" align="center">No</td>
                                    <td width="25%">Nama Material</td>
                                    <td width="8%" style="text-align: center;">Jumlah Order</td>
                                    <td width="10%" style="text-align: center;">Order Diterima</td>
                                    <td width="8%" style="text-align: center;">Sisa</td>
                                    <td width="8%" style="text-align: center;">Satuan</td>
                                    <!--<td width="14%" style="text-align: right;">Unit Price</td>
                                    <td width="14%" style="text-align: right;">Ext Price</td>-->
                                </tr>
                            </thead>

                            <tbody id="detailOrder">
                                
                            </tbody>
                        
                        </table>

                        <table class="table table-bordered" style="font-size: 12px;margin-top: 20px;">
                            <tr style="font-weight: bold;background: #2A303A;color:white;">
                                <td style="text-align: left;">Special Term</td>
                            </tr>

                            <tr>
                                <td>
                                    <ul>
                                        <li>Deliver To &nbsp &nbsp &nbsp &nbsp: <?php echo $noteInfo->alamat_pengiriman; ?></li>
                                        <li>Delivery Time &nbsp: <?php
                                                    $date_send = date_create($noteInfo->tanggal_kirim);

                                                    echo date_format($date_send,'d M Y');
                                                ?></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>

                        <?php 
                            if($status_po=='1'){
                        ?>
                        <table class="table table-bordered" style="font-size: 12px;margin-top: 20px;">
                            <tr style="font-weight: bold;background: #2A303A;color:white;">
                                <td style="text-align: left;">Good Receive</td>
                                <td style="text-align: left;">Riwayat Penerimaan</td>
                            </tr>

                            <tr>
                                <td>
	                                    <table class="table">
	                                    	<tr style="font-weight: bold;">
                                                <td width="15%">SKU</td>
	                                    		<td>Nama Produk</td>
                                                <td width="25%">Produk Diterima</td>
	                                    		<td width="8%">Satuan</td>
	                                    		<!--<td width="15%">Unit Price</td>-->
	                                    	</tr>

	                                    	<?php
                                                $y = 1;
				                                foreach($purchase_item->result() as $dt){
				                            ?>
	                                    	<tr>
                                                <td style="vertical-align: middle;"><?php echo $dt->sku; ?></td>
	                                    		<td style="vertical-align: middle;"><?php echo $dt->nama_bahan; ?></td>
	                                    		<td>
	                                    			<input type="number" name='qty' id="qtyProduk<?php echo $y; ?>" data-urut="<?php echo $y; ?>" data-id="<?php echo $dt->sku; ?>" data-price="<?php echo $dt->harga; ?>" data-max="<?php echo $dt->qty; ?>" min="0" class="form-control qtyAjax" value="0"/>
	                                    		</td>
                                                <td><?php echo $dt->satuan; ?></td>
	                                    	</tr>
	                                    	<?php $y++; } ?>

	                                    	<tr>
	                                    		<td colspan="2"><b>Diterima Oleh</b> <label id="diterimaAlert" style="color: red;"></label></td>
	                                    		<td colspan="2">
                                                    <input type="hidden" id="noPo" value="<?php echo $_GET['noPO']; ?>"/>
	                                    			<input type="text" class="form-control" id="diterimaOleh" required>
	                                    		</td>
	                                    	</tr>

	                                    	<tr>
	                                    		<td colspan="2"><b>Diperiksa Oleh</b> <label id="diperiksaAlert" style="color: red;"></label></td>
	                                    		<td colspan="2"><input type="text" class="form-control" id="diperiksaOleh" required></td>
	                                    	</tr>

	                                    	<tr>
	                                    		<td colspan="2"><b>Tanggal Kedatangan</b></td>
	                                    		<td colspan="2"><input type="text" class="form-control" id="tanggalTerima" value="<?php echo date('Y-m-d'); ?>" readonly>
                                                <input type="hidden" id="idSupplier" value="<?php echo $noteInfo->id_supplier;  ?>">
                                                </td>
	                                    	</tr>
                                        </table>
                                        
                                        <table class="table" width="100%" id="payment">
                                            
                                        </table>

                                        <table width="100%">
	                                    	<tr>
	                                    		<td colspan="4" align="right"><button type="submit" class="btn btn-primary" id="submitPenerimaan">Submit</button></td>
	                                    	</tr>
	                                    </table>

                                </td>

                                <td width="40%">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>SKU</th>
                                                <th>Nama Produk</th>
                                                <th>Tanggal</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>

                                        <tbody id="riwayatPenerimaan">
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <?php } ?>

                        <table class="table table-bordered" style="font-size: 12px;margin-top: 20px;">
                            <tr style="font-weight: bold;background: #2A303A;color:white;">
                                <td style="text-align: left;">Invoice Receive</td>
                            </tr>

                            <tr>
                                <td>
                                	<table class="table" style="font-size:12px;">
                                        <thead>
                                    		<tr>
                                    			<th width="5%">No</th>
                                    			<th width="25%">No Receive</th>
                                    			<th>Tanggal Terima</th>
                                    			<th>Diterima Oleh</th>
                                    			<th>Diperiksa Oleh</th>
                                    		</tr>
                                        </thead>

                                        <tbody id="invoiceReceive">
                                        </tbody>
                                        
                                	</table>
                                </td>
                            </tr>
                        </table>
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
