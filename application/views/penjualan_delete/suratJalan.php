<div class="wraper container-fluid">
    <div class="panel panel-default" >
        <div class="panel-body" id="print-area">
            <div class="hidden-print">
                <div class="pull-right">
                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
                </div>
            </div>

            <div class="row">
                <div class= "col-md-12" id="dataContent">

					<div style="margin-top: 10px;">
						<table width="100%">
							<tr style="border-bottom: solid 1px #ddd;">
								<td width="40%">
									<h3 style="font-size: 18px;"><?php echo $header->store; ?></h3>
									<p><?php echo $header->alamat; ?><br>
									Telpon <?php echo $header->kontak; ?></p>
								</td>
								<td width="50%" style="text-align: right;font-weight: bold;font-size: 18px;vertical-align: bottom">
									SURAT JALAN
								</td>
							</tr>
						</table>

						<BR>
						Kepada Yth.

						<table width="100%">
							<tr>
								<td width="50%">
									<table width="100%">
										<tr>
											<td width="15%">Nama</td>
											<td width="1%">:</td>
											<td><?php echo $invoiceInfo->nama; ?></td>
										</tr>

										<tr>
											<td width="15%">No Telp</td>
											<td width="1%">:</td>
											<td><?php echo $invoiceInfo->kontak; ?></td>
										</tr>

										<tr>
											<td width="15%" style="vertical-align: top;">Alamat</td>
											<td width="1%" style="vertical-align: top;">:</td>
											<td><?php echo $invoiceInfo->alamat; ?></td>
										</tr>
									</table>
								</td>

								<td style="vertical-align: top;">
									<table width="100%">
										<tr>
											<td width="20%">No Invoice</td>
											<td width="1%">:</td>
											<td><?php echo $invoiceInfo->no_invoice; ?></td>
										</tr>

										<tr>
											<td width="20%">Tanggal</td>
											<td width="1%">:</td>
											<td><?php echo date_format(date_create($invoiceInfo->tanggal),'d F Y H:i'); ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>

						<br>
						<table width="100%" style="border-bottom: solid 1px black;">
							<tr style="font-weight: bold;border:solid 1px black;">
								<td width="5%" style="text-align: center;border:solid 1px black;">No</td>
								<td style="border:solid 1px black;">Nama Barang</td>
								<td width="10%" style="text-align: center;border:solid 1px black;">QTY</td>
								<td width="30%" style="text-align: center;border:solid 1px black;">Keterangan</td>
							</tr>

							<?php
								$i = 1;
								foreach($invoiceItem->result() as $row){
							?>
							<tr style="border-left: solid 1px black;border-right: solid 1px black;">
								<td style="text-align: center;border-right: solid 1px black;"><?php echo $i; ?></td>
								<td style="border-right: solid 1px black;"><?php echo $row->nama_produk; ?></td>
								<td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
								<td></td>
							</tr>
							<?php $i++; } ?>
						</table>
						<i>BARANG SUDAH DITERIMA DALAM KEADAAN BAIK DAN CUKUP oleh :</i> <br>
						<i>(Tanda tangan dan cap(stempel) perusahaan)</i>
						<br>
						<br>
						<table width="100%">
							<tr style="height: 100px;">
								<td width="33%" style="text-align: center;vertical-align: top;">
									Penerima / Pembeli	
								</td>

								<td width="33%" style="text-align: center;vertical-align: top;">
									Bagian Pengiriman	
								</td>

								<td width="33%" style="text-align: center;vertical-align: top;">
									Petugas Gudang
								</td>
							</tr>
						</table>
					</div>


				</div>
            </div>
        </div>
    </div>

</div>
