<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <div class="portlet-widgets">
                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                <span class="divider"></span>
               	<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
               <?php
                $id = $_GET['no_tagihan'];
                $status_transaksi = $this->modelFinance->status_transaksi($id);

                if($status_transaksi !=2 ){ 
               ?>
               <div class="row">
                <div class="col-md-12" align="right">
                  <a href="<?php echo base_url('finance/tutup_transaksi?no_tagihan='.$_GET['no_tagihan']); ?>" onclick="return confirm('Tutup Transaksi ?')" class="btn btn-success">Tutup Transaksi</a>
                </div>
               </div>
               <?php } ?>

               <div class="row">
               		<div class="col-md-12">
               			<table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <h4><?php echo $hd->nama_perusahaan; ?></h4> 
                                    <h5>INVOICE PENAGIHAN</h5>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
               		</div> 
               </div>

               <div class="row" style="margin-top: 12px;">
               		<div class="col-md-6 col-xs-6 col-sm-6">
               			<table style="font-size: 12px;" width="100%">
               				<tr>
               					<td width="25%" style="font-weight: bold;">No Penagihan</td>
               					<td width="1%">:</td>
               					<td><?php echo $info_hutang->no_tagihan; ?></td>
               				</tr>

               				<tr>
               					<td width="25%" style="font-weight: bold;">Dibuat Oleh</td>
               					<td width="1%">:</td>
               					<td><?php echo $info_hutang->first_name; ?></td>
               				</tr>

                      <tr>
                          <td width="25%" style="font-weight: bold;">Status Hutang</td>
                          <td width="1%">:</td>
                          <td>
                            <?php
                              $status = $info_hutang->status_hutang;

                              if($status=='0'){
                                  echo "<span class='label label-danger'>Belum Terbayar</span>";
                              } elseif($status=='1'){
                                  echo "<span class='label label-info'>Terbayar</span>";
                              } elseif($status=='2'){
                                  echo "<span class='label label-success'>Transaksi Selesai</span>    ";
                              }
                            ?>
                          </td>
                        </tr>
               			</table>
               		</div>

               		<div class="col-md-6 col-xs-6 col-sm-6">
               			
	               			<table style="font-size: 12px;" width="100%">
	               				<tr>
	               					<td width="25%" style="font-weight: bold;">Supplier</td>
	               					<td width="1%">:</td>
	               					<td><?php echo $info_hutang->supplier; ?></td>
	               				</tr>

	               				<tr>
	               					<td width="25%" style="font-weight: bold;">Jatuh Tempo</td>
	               					<td width="1%">:</td>
	               					<td>
                            <span id="tanggalJatuhTempo">
	               			       <?php
                              $jatuh_tempo = date_create($info_hutang->jatuh_tempo);

                              echo date_format($jatuh_tempo,'d F Y');
                             ?>
                           </span>

                             <a href="#editJatuhTempo" id="jatuhTempo" data-no_po="<?php echo $this->input->get("no_tagihan"); ?>" data-toggle="modal"><i class="fa fa-pencil"></i></a>
	               					</td>
	               				</tr>

                        <tr>
                          <td width="25%" style="font-weight: bold;">Keterangan</td>
                          <td width="1%">:</td>
                          <td>
                            <?php echo $info_hutang->keterangan; ?>
                          </td>
                        </tr>
	               				
	               			</table>
               			
               		</div>
               </div>

               <div class="row" style="margin-top: 20px;">
               		<div class="col-md-12">
               			<table class="table table-bordered" style="font-size:11px;">
                      <thead>
                 				<tr style="font-weight: bold;">
                          <td width="5%" align="center">No</td>
                 					<td width="10%">SKU</td>
                 					<td>Nama Produk</td>
                          <td align="center">QTY</td>
                          <td align="center">Barang Diterima</td>
                          <td align="center">Sisa PO</td>
                 					<td align="center">Retur</td>
                 					<td align="center">Satuan</td>
                 					<td align="right">Harga Satuan</td>
                 					<td align="right">Subtotal</td>
                 				</tr>
                      </thead>

                      <tbody id="dataTagihan">
                        
                      </tbody>
               			</table>
               		</div>
               </div>

               <div class="row" style="margin-top: 20px;">
                  <div class="col-md-6">
                    <P style='font-weight: bold;'><u>Pembayaran Hutang :</u></P>
                    <div class="form-group">
                      Jumlah Pembayaran <br>
                      <input type="text" class='form-control' id="jumlahPembayaran" <?php if($status_transaksi==2){echo "disabled"; } ?>>
                    </div> 

                    <div class="form-group">
                      Tipe Pembayaran <br>
                      <select class="form-control" id="tipeBayar" <?php if($status_transaksi==2){echo "disabled"; } ?>>
                        <option value="">--Tipe Pembayaran--</option>

                        <?php
                          foreach($paymentType as $ty){
                        ?>
                        <option value="<?php echo $ty->id; ?>"><?php echo $ty->paymentType; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                    <div class="form-group">
                      Keterangan <br>
                      <textarea class="form-control" id="keterangan" <?php if($status_transaksi==2){echo "disabled"; } ?>></textarea>
                    </div> 

                    <div class="form-group" style="text-align: right;">
                      <button id="simpanPembayaran" class="btn btn-primary">Submit</button>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <P style='font-weight: bold;'><u>Informasi Supplier :</u></P>
                    <table width="100%">
                      <tr>
                        <td width="25%">Supplier</td>
                        <td width="1%">:</td>
                        <td><?php echo $supplier->supplier; ?></td>
                      </tr>

                      <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td><?php echo $supplier->alamat; ?></td>
                      </tr>

                      <tr>
                        <td>Kontak</td>
                        <td>:</td>
                        <td><?php echo $supplier->kontak; ?></td>
                      </tr>

                      <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td><?php echo $supplier->email; ?></td>
                      </tr>

                      <tr>
                        <td style="vertical-align: top;">No Rekening</td>
                        <td style="vertical-align: top;">:</td>
                        <td>
                          <?php echo $supplier->bank; ?> <br>
                          <?php echo $supplier->no_rekening; ?> <br>
                          <?php echo $supplier->atas_nama; ?>
                        </td>
                      </tr>
                    </table>
                  </div>
               </div>

               <div class="row" style="margin-top: 20px;">
                <div class="col-md-6">
                  <P style='font-weight: bold;'><u>Riwayat Pembayaran :</u></P>
                  <table class="table" style="font-size:12px;">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th width="25%">No Pembayaran</th>
                        <th>PIC</th>
                        <th>Tipe Bayar</th>
                        <th style="text-align: right;">Pembayaran</th>
                      </tr>
                    </thead>

                    <tbody id="riwayatPembayaran">
                    </tbody>                    
                  </table>
                </div>

                <div class="col-md-6">
                  <P style='font-weight: bold;'><u>Riwayat Penerimaan Barang :</u></P>
                  <table class="table" style="font-size:12px;">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th width="25%">No Receive</th>
                        <th>Tanggal Terima</th>
                        <th>Diterima Di</th>
                      </tr>
                    </thead>

                    <tbody id="invoiceReceive">
                    </tbody>                    
                  </table>
                </div>
               </div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<div class="modal fade bs-example-modal-sm" id="editJatuhTempo" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="mySmallModalLabel">Jatuh Tempo</h4>
      </div>

      <div class="modal-body">

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

