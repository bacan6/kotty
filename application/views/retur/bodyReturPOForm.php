<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Retur</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row" style="font-weight: bold;">
            		<div class="col-md-6">
                        <table width="100%">
                            <tr>
                                <td width="25%">NO PO</td>
                                <td width="1%">:</td>
                                <td>
                                    <?php echo $infoPO->no_po; ?>
                                </td>
                            </tr>

                            <tr>
                                <td width="25%">Tanggal</td>
                                <td width="1%">:</td>
                                <td>
                                    <?php echo date_format(date_create($infoPO->tanggal_po),'d M Y'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td width="25%">Jatuh Tempo</td>
                                <td>:</td>
                                <td>
                                    <?php echo date_format(date_create($infoPO->jatuh_tempo),'d M Y'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td width="25%">Tanggal Kirim</td>
                                <td>:</td>
                                <td>
                                    <?php echo date_format(date_create($infoPO->tanggal_kirim),'d M Y'); ?>
                                </td>
                            </tr>
                        </table>
            		</div>

                    <div class="col-md-6">
                        <table width="100%">
                            <tr>
                                <td width="25%">Supplier</td>
                                <td width="1%">:</td>
                                <td><?php echo $infoPO->supplier; ?></td>
                            </tr>

                            <tr>
                                <td width="25%">Alamat Pengiriman</td>
                                <td>:</td>
                                <td><?php echo $infoPO->alamat_pengiriman; ?></td>
                            </tr>

                            <tr>
                                <td width="25%">Keterangan</td>
                                <td>:</td>
                                <td><?php echo $infoPO->keterangan; ?></td>
                            </tr>
                        </table>
                    </div>
            	</div> 
                 

            	<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="text-align: right;">
                        <button class="btn btn-primary" id="prosesRetur"><i class="fa fa-save"></i> Retur</button>
                    </div>

            		<div class="col-md-12" style="margin-top: 20px;">
		            		<table class="table table-bordered" style="font-size:12px;">
		            			<thead>
			            			<tr style="font-weight: bold;">
			            				<td width="15%">SKU</td>
			            				<td width="25%">Nama Produk</td>
                                        <td width="10%">Jumlah Beli</td>
                                        <td width="10%">Diterima</td>
			            				<td width="10%">Retur</td>
			            				<td width="5%">Satuan</td>
                                        <td align="right" width="15%">Harga Satuan</td>
			            				<td align="right" width="15%">Total Harga</td>
			            			</tr>
		            			</thead>

		            			<tbody id="data-input">
                                    <?php
                                        $i = 1;
                                        foreach($purchase_item as $row){
                                    ?>
                                    <tr>
                                        <td><?php echo $row->id_produk; ?></td>
                                        <td><?php echo $row->nama_produk; ?></td>
                                        <td align="center"><?php echo $row->qty; ?></td>
                                        <td align="center">
                                            <?php
                                                echo number_format($this->modelRetur->barangDiterima($row->id_produk,$infoPO->no_po),'0',',','.');
                                            ?>
                                        </td>
                                        <td><input type="number" class="form-control retur" name="retur" id="row<?php echo $i; ?>" data-row="<?php echo $i; ?>" data-id="<?php echo $row->id_produk; ?>" data-no_po="<?php echo $row->no_po; ?>" data-harga="<?php echo $row->harga; ?>"/></td>
                                        <td><?php echo $row->satuan; ?></td>
                                        <td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
                                        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                                    </tr>
                                    <?php $i++; } ?>
		            			</tbody>
		            		</table>
            		</div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
