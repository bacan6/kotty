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
	                    <a onclick="return confirm('Are You Sure ?')" href="<?php echo base_url('bahan_masuk/change_po_status?status=1'.'&no_po='.$_GET['no_po']); ?>" class="btn btn-success"><i class="fa fa-check"></i> Accept </a>
	                    <a onclick="return confirm('Are You Sure ?')" href="<?php echo base_url('bahan_masuk/change_po_status?status=2'.'&no_po='.$_GET['no_po']); ?>" class="btn btn-danger"><i class="ion-close"></i> Decline </a>
	                	<?php } ?>

                        <?php
                            if($status_po==1){
                        ?>
                            <a class='btn btn-primary' onclick="return confirm('Are You Sure Close This Transaction ?')" href="<?php echo base_url('bahan_masuk/change_po_status?status=3'.'&no_po='.$_GET['no_po']); ?>"><i class='fa fa-power-off'></i> Tutup Transaksi </a>
                        <?php   
                            }
                        ?>
                        <?php
                            if($status_po==3){
                        ?>
                            <a class='btn btn-primary' onclick="return confirm('Are You Sure Open This Transaction ?')" href="<?php echo base_url('bahan_masuk/change_po_status?status=1'.'&no_po='.$_GET['no_po']); ?>"><i class='fa fa-power-off'></i> Buka Transaksi </a>
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
			                                            echo "<span class='label label-danger'>Waiting for MD</span>";
			                                        } elseif($status_po=='1') {
			                                            echo "<span class='label label-success'>Approved</span>";
			                                        } elseif($status_po=='2') {
			                                            echo "<span class='label label-danger'>Declined</span>";
			                                        } elseif($status_po=='3'){
                                                         echo "<span class='label label-info'>Order Received</span>";
                                                    }
                                            	?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:  bold;width: 15%;">Keterangan</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $noteInfo->keterangan; ?></td>
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
                                        <tr style="font-weight: bold;">
                                            <td>Nama Perusahaan</td>
                                        </tr>
                                        <?php
                                            foreach($header->result() as $hd){
                                        ?>
                                        <tr height="100px">
                                            <td>
                                                <b><?php echo $noteInfo->store; ?></b> <br>
                                                <?php echo $noteInfo->alamatpenerima; ?> <br>
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
                                    <td width="15%">SKU</td>
                                    <td width="25%">Nama Produk</td>
                                    <td width="8%" style="text-align: center;">Qty Order</td>
                                    <td width="8%" style="text-align: center;">Qty Approved (MD)</td>
                                    <td width="8%" style="text-align: center;">Qty Final (Supplier)</td>
                                    <td width="8%" style="text-align: center;">Qty Bonus</td>
                                    <td width="10%" style="text-align: center;">Qty Diterima</td>
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

                        <?php 
                            if($status_po=='1'){
                        ?>
                        <style>input[type=text]{text-align:right}</style>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-12">
                                <input type="hidden" id="sku" style="width:100%;text-align:left!important;"/>
                            </div>
                        </div>
                        <div align=right style="margin-top:20px">
                            Diskon1 (%)<input type="number" id="setDiskon1" style="width:80px"> 
                            <a class="btn btn-info btn-xs" id="btn-diskon1">Set All</a>
                            Diskon2 (%)<input type="number" id="setDiskon2" style="width:80px"> 
                            <a class="btn btn-info btn-xs" id="btn-diskon2">Set All</a>
                            Diskon3 (Rp/item)<input type="number" id="setDiskon3" style="width:110px"> 
                            <a class="btn btn-info btn-xs" id="btn-diskon3">Set All</a>
                        </div>
                        <table class="table table-bordered" style="font-size: 12px;margin-top: 5px;">
                            <tr style="font-weight: bold;">
                                <td style="text-align: left;">Good Receive</td>
                            </tr>

                            <tr>
                                <td>
	                                    <table class="table">
                                            
	                                    	<tr style="font-weight: bold;">
                                                <td width="10%">SKU</td>
	                                    		<td>Nama Produk</td>
                                                <td width="6%">Qty<br>Diorder</td>
                                                <td width="6%">Qty<br>Diterima</td>
	                                    		<td width="5%">Harga<br>Beli</td>
                                                <td width="5%">Harga<br>Jual</td>
                                                <td width="5%">Margin</td>
	                                    		<td width="7%" align=center>Qty Bonus</td>
                                                <td width="7%" align=center>Diskon1<br>(%)</td>
                                                <td width="7%" align=center>Diskon2<br>(%)</td>
                                                <td width="7%" align=center>Diskon3<br>(Rp)</td>
                                                <td width="10%">Sub Total</td>
	                                    	</tr>
                                            <tbody id="addAble">
	                                    	<?php
                                                $y = 1;$tHarga=0;$margin = 0;
				                                foreach($purchase_item->result() as $dt){
                                                    $margin = ($dt->hargajual - $dt->harga)/$dt->hargajual * 100;
                                                    $bgcolor = $dt->qty_pdt > $dt->qty? 'bgcolor=yellow':'';
				                            ?>
	                                    	<tr <?php echo $bgcolor?>>
                                                <td style="vertical-align: middle;"><?php echo $dt->id_produk; ?></td>
	                                    		<td style="vertical-align: middle;"><?php echo $dt->nama_produk; ?></td>
                                                <td style="vertical-align: middle;" align='center'><?php echo $dt->qty; ?></td>
	                                    		<td>
	                                    			<input type="number" name='qty' id="qtyProduk<?php echo $y; ?>" size=5 data-urut="<?php echo $y; ?>" data-id="<?php echo $dt->id_produk; ?>" data-price="<?php echo $dt->harga; ?>" data-max="<?php //echo $dt->qty; ?>" min="0" class="qtyAjax" value="<?php echo $dt->qty_pdt; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);" style="width:60px"/>
	                                    		</td>
                                                <td><input class="harga" id="hrgProduk<?php echo $y; ?>" type="text" size=7 value="<?php echo $dt->harga; ?>" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
                                                <td><input id="hrgProdukJual<?php echo $y; ?>" type="text" size=7 value="<?php echo $dt->hargajual; ?>" data-urut="<?php echo $y; ?>" onChange="javascript:hitungMargin(<?php echo $y; ?>);"></td>
                                                <td>
                                                    <input id="margin<?php echo $y; ?>" type="text" size=3 value="<?php echo number_format($margin,2) ?>" data-urut="<?php echo $y; ?>" onChange="javascript:hargaJual(<?php echo $y; ?>);">
                                                </td>
                                                <td>
	                                    			<input type="text" name='bonus' id="bonus<?php echo $y; ?>" size=3 data-urut="<?php echo $y; ?>" data-id="<?php echo $dt->id_produk; ?>" data-price="<?php echo $dt->harga; ?>" data-max="<?php echo $dt->qty; ?>" min="0" class="bonus" value="0" />
	                                    		</td>
                                                <td><input class="diskon1" id="diskon1<?php echo $y; ?>" type="text" size=5 value="0" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
                                                <td><input class="diskon2" id="diskon2<?php echo $y; ?>" type="text" size=5 value="0" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
                                                <td><input class="diskon3" id="diskon3<?php echo $y; ?>" type="text" size=5 value="0" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
                                                <td align=right><span id="subTotal<?php echo $y?>"><?php if($dt->qty_pdt>0) echo number_format($dt->harga*$dt->qty_pdt,0);?></span></td>
	                                    	</tr>
	                                    	<?php $y++;
                                            $tHarga +=$dt->harga;  } ?>

                                            </tbody>

	                                    	<tr>
	                                    		<td colspan="3" align=right><b>Diterima Oleh</b> <label id="diterimaAlert" style="color: red;"></label></td>
	                                    		<td colspan="3">
                                                    <input type="hidden" id="noPo" value="<?php echo $_GET['no_po']; ?>"/>
                                                    <input type="hidden" id="idSupplier" value="<?php echo $noteInfo->id_supplier; ?>"/>
	                                    			<input type="text" class="form-control" id="diterimaOleh" required>
	                                    		</td>
	                                    	</tr>

	                                    	<tr>
	                                    		<td colspan="3" align=right><b>Diperiksa Oleh</b> <label id="diperiksaAlert" style="color: red;"></label></td>
	                                    		<td colspan="3"><input type="text" class="form-control" id="diperiksaOleh" required></td>
	                                    	</tr>
                                            <tr>
                                                <td colspan="3" align=right><b>Diskon Global</b> <label id="lbdiskon" style="color: red;"></label></td>
                                                <td colspan="3"><input type="text" class="form-control" id="diskon" onchange="javascript:editHarga2(this.value);"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align=right><b>Diskon Produk</b> <label id="lbdiskon" style="color: red;"></label></td>
                                                <td colspan="3"><b><span id='stDiskon'></span></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align=right><b>PPn 11%</b> <label id="lbppn" style="color: red;"></label></td>
                                                <td colspan="3"><select name='PPN' id='PPN' onChange="javascript:editHarga();"><option value='0'>No</option><option value='1'>Yes</option></select></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" align=right><b>Harga Akhir</b> <label id="total" style="color: red;"></label></td>
                                                <td colspan="3"><b><span id='stTotal'><?php echo $tHarga?></span></b></td>
                                            </tr>
                                            
	                                    	<tr>
	                                    		<td colspan="3" align=right><b>Tanggal Kedatangan</b></td>
	                                    		<td colspan="3"><input type="text" class="form-control datepicker" id="tanggalTerima" value="<?php echo date('Y-m-d'); ?>" readonly ></td>
	                                    	</tr>

                                            <tr>
                                                <td colspan="3" align=right><b>Diterima Di</b> <label id="diterimaDiAlert" style="color: red;"></label></td>
                                                <td colspan="3">
                                                    <select class="select2" id="diterimaDi" required>
                                                        <option value="0">Gudang</option>
                                                        <?php
                                                            foreach($store as $st){
                                                        ?>
                                                        <option value="<?php echo $st->id_store; ?>" selected><?php echo $st->store; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
	                                    		<td colspan="3" align=right><b>Status Penerimaan Barang</b></td>
	                                    		<td colspan="3">
                                                    <select class="select2" id="status_receive" required>
                                                        <option value="Done">Done</option>
                                                        <option value="Problem Faktur">Problem Faktur</option>
                                                    </select>
                                                </td>
	                                    	</tr>
                                            <tr>
	                                    		<td colspan="3" align=right><b>Keterangan Problem</b></td>
	                                    		<td colspan="3"><input type="text" class="form-control" id="keterangan_receive" value="" ></td>
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
                                </tr>
                                <tr>
                                <td style="text-align: left;">Riwayat Penerimaan</td>
                                </tr><tr>
                                <td width="100%">
                                    <table class="table table-bordered">
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
                            <tr style="font-weight: bold;">
                                <td style="text-align: left;">Invoice Penerimaan</td>
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
                                                <th>Diterima Di</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
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
