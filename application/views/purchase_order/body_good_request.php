<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->    
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
	                <div class="col-md-12" style="text-align: right;">
	                    <!--<a onclick="printContent('area-print')" class="btn btn-default"><i class="fa fa-print"></i> Print </a>-->
	                    <?php
	                    	$no_po 		= $_GET['no_po']; 
                        	$status_po 	= $this->model1->status_po($no_po);
                        	
                        	if($status_po < 1){
                        ?>
	                    <a onclick="return confirm('Are You Sure ?')" href="<?php echo base_url('purchase_request/change_po_status?status=1'.'&no_po='.$_GET['no_po']); ?>" class="btn btn-success"><i class="fa fa-check"></i> Accept </a>
	                    <a onclick="return confirm('Are You Sure ?')" href="<?php echo base_url('purchase_request/change_po_status?status=2'.'&no_po='.$_GET['no_po']); ?>" class="btn btn-danger"><i class="ion-close"></i> Decline </a>
	                	<?php } ?>

                        <?php
                            if($status_po==1){
                        ?>
                            <a class='btn btn-primary' onclick="return confirm('Are You Sure Close This Transaction ?')" href="<?php echo base_url('bahan_masuk/change_po_status?status=3'.'&no_po='.$_GET['no_po']); ?>"><i class='fa fa-power-off'></i> Tutup Transaksi </a>
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
                                    <h5>Goods Request Note</h5>
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
                                            <td><?php echo $_GET['no_po']; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;width: 15%;">Status</td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                            	<?php
                                            		$no_po 		= $_GET['no_po']; 
                                            		$status_po 	= $this->model1->status_po($no_po);

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

                        <div class="col-md-12">
                            <input type="hidden" id="sku" style="width:100%;"/>
                        </div>

                        <table style="margin-top: 20px;" width="100%">
                            <tr>
                                <td width="50%">
                                    <table class="table table-bordered" style="font-size: 12px;">
                                        <tr style="font-weight: bold;">
                                            <td>Nama Perusahaan</td>
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
                                        <tr style="font-weight: bold;">
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

                        <table class="table table-bordered" style="margin-top: 20px;font-size:12px;">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td width="5%" align="center">No</td>
                                    <td width="8%">SKU</td>
                                    <td width="20%">Nama Produk</td>
                                    <td width="8%">Last Receive</td>
                                    <td width="8%">Qty (Last Receive)</td>
                                    <td width="8%">Sales &raquo; Receive</td>
                                    <td width="5%" align="center">Stok</td>
                                    <td width="8%" style="text-align: center;">Qty Diajukan</td>
                                    <td width="5%" align="center">Harga</td>
                                    <td width="5%" align="center">Total</td>
                                    <td width="10%" style="text-align: center;">Qty Disetujui</td>
                                </tr>
                            </thead>

                            <tbody id="detailOrder">
                                
                            </tbody>
                        
                        </table>

                        <table class="table table-bordered" style="font-size: 12px;margin-top: 20px;">
                            <tr style="font-weight: bold;">
                                <td style="text-align: left;">Alamat Pengiriman</td>
                            </tr>

                            <tr>
                                <td>
                                    <ul>
                                        <li>Alamat Pengiriman : <?php echo $noteInfo->alamat_pengiriman; ?></li>
                                        <li>Tanggal Pengiriman : <?php
                                                    $date_send = date_create($noteInfo->tanggal_kirim);

                                                    echo date_format($date_send,'d M Y');
                                                ?></li>
                                    </ul>
                                </td>
                            </tr>
                        </table>
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
