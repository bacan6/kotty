<div class="wraper container-fluid">
    <div class="panel panel-default" >
        <div class="panel-body" id="print-area">
            <div class="hidden-print">
                <div class="pull-right">
                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
                </div>
            </div>

            <div class="row">
            	<div class="col-md-12">
            		<table width="100%">
						<tr style="border-bottom: solid 1px #ddd;vertical-align: bottom;">
							<td width="50%">
								<h3 style="font-size: 18px;"><?php echo $header->store; ?></h3>
								<p><?php echo $header->alamat; ?><br>
									Telpon <?php echo $header->kontak; ?></p>
							</td>

							<td width="50%" style="text-align: right;vertical-align: bottom">
								<p style="font-weight: bold;font-size: 18px;"><?php echo $invoiceInfo->ekspedisi."<br>".$invoiceInfo->no_invoice; ?></p>
							</td>
						</tr>
					</table>

					<br>
					<table width="100%" border="1">
						<tr>
							<td width="50%">
								Kepada :
								<P style="font-weight: bold;font-size: 12px;"><?php echo $invoiceInfo->nama_penerima; ?></P>
								<?php echo $invoiceInfo->alamat; ?> <br>
								<?php echo $invoiceInfo->nama_provinsi." - ".$invoiceInfo->nama_kabupaten." - ".$invoiceInfo->kecamatan; ?> <br>
								Telp. <?php echo $invoiceInfo->kontak_pengiriman; ?>
							</td>

							<td align="right">
								<img src="<?php echo base_url('qr/'.$invoiceInfo->no_invoice.'.png'); ?>"/>
							</td>

							<tr>
								<td>
									<b>Barang :</b>
									<table width="100%">
										<tr style="font-weight: bold;">
											<td>Kode</td>
											<td>Nama Barang</td>
											<td>Qty</td>
										</tr>

										<?php
											foreach($invoiceItem->result() as $itm){
										?>
										<tr>
											<td><?php echo $itm->id_produk; ?></td>
											<td><?php echo $itm->nama_produk; ?></td>
											<td><?php echo $itm->qty; ?></td>
										</tr>
										<?php } ?>
									</table>
								</td>

								<td align="right" style="vertical-align: middle;">
									Tanggal :<?php echo date_format(date_create($invoiceInfo->tanggal),'d F Y');?> <br>
									Jam :<?php echo date_format(date_create($invoiceInfo->tanggal),'H:i');?>
								</td>
							</tr>
						</tr>
					</table>
            	</div>
            </div>
        </div>
    </div>

</div>
