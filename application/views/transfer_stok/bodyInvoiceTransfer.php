<div class="wraper container-fluid">
    <div class="row">
      <div class="col-md-12" style="text-align: right;">
        <a class="btn btn-primary" onclick="printContent('area-print')"><i class="fa fa-print"></i> Print</a>
      </div>
    </div>

    <div class="portlet" style="margin-top: 10px;"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" id="area-print">
              <div class="row">
                <div class="col-md-12" style="text-align: center;font-weight: bold;">
                  <?php echo $header->nama_perusahaan; ?> <br>
                  Form Transfer Barang
                </div>
              </div>

              <div class="row" style="margin-top: 10px;">
                <div class="col-md-6 col-xs-6 col-sm-6">
                  <table style="font-size: 12px;">
                    <tr>
                      <td width="25%" style="font-weight: bold;">No Transfer</td>
                      <td width="1%">:</td  >
                      <td><?php echo $infoTransfer->noTransfer; ?></td>
                    </tr>

                    <tr>
                      <td width="25%" style="font-weight: bold;">Tanggal Kirim</td>
                      <td width="1%">:</td>
                      <td><?php echo date_format(date_create($infoTransfer->tanggal),'d M Y H:i'); ?></td>
                    </tr>

                    <tr>
                      <td width="25%" style="font-weight: bold;">Toko Asal</td>
                      <td width="1%">:</td>
                      <td>
                        <?php
                          echo $this->model1->namaStore($infoTransfer->transferFrom);
                        ?>
                      </td>
                    </tr>
                    <tr>
                        <td width="35%" style="font-weight: bold;">PIC Pengirim</td>
                        <td width="1%">:</td>
                        <td>
                          <?php
                          echo $infoTransfer->pengirim;
                        ?>
                        </td>
                      </tr>
                  </table>
                </div>

                <div class="col-md-6 col-xs-6 col-sm-6">
                  <table style="font-size: 12px;">
                    <tr>
                        <td width="35%" style="font-weight: bold;">Tujuan Transfer</td>
                        <td width="1%">:</td>
                        <td>
                          <?php
                          echo $this->model1->namaStore($infoTransfer->transferTo);
                        ?>
                        </td>
                      </tr>
                      <tr>
                        <td width="35%" style="font-weight: bold;">Tanggal Terima</td>
                        <td width="1%">:</td>
                        <td>
                          <?php
                          echo $infoTransfer->tanggal_terima;
                        ?>
                        </td>
                      </tr>
                      <tr>
                        <td width="35%" style="font-weight: bold;">PIC Penerima</td>
                        <td width="1%">:</td>
                        <td>
                          <?php
                          echo $infoTransfer->penerima;
                        ?>
                        </td>
                      </tr>

                      <tr>
                        <td width="30%" style="font-weight: bold;">Keterangan</td>
                        <td width="1%">:</td>
                        <td>
                          <?php
                          echo $infoTransfer->keterangan;
                        ?>
                        </td>
                      </tr>
                    </table>
                </div>
              </div>

              <div class="row" style="margin-top: 5px;">
                <div class="col-md-12">
                  <table width="100%" style="font-size: 12px;border:solid 1px black;">
                    <tr style="font-weight: bold;border-bottom: solid 1px black;">
                      <td width="5%" style="text-align: center;border-right: solid 1px black;">No</td>
                      <td width="15%" style="border-right: solid 1px black;padding-left: 1px;">SKU</td>
                      <td style="border-right: solid 1px black;">Nama Produk</td>
                      <td width="15%" align="center" style="border-right: solid 1px black;">Qty dikirim</td>
                      <td width="15%" align="center" style="border-right: solid 1px black;">Qty diterima</td>
                      <td width="15%" align="center" style="border-right: solid 1px black;">Nominal Dikirim (Rp)</td>
                      <td width="15%" align="center">Nominal Diterima (Rp)</td>
                    </tr>

                    <?php
                      $i = 1;$jumlah=0;$sum=0;$sum_rec=0;$jumlah_rec=0;
                      foreach($itemTransfer as $row){
                          $jumlah += $row->qty*$row->hpp;
                          $jumlah_rec += $row->qty_rec*$row->hpp;
                          $sum += $row->qty;
                          $sum_rec += $row->qty_rec;
                    ?>
                    <tr>
                      <td style="text-align: center;border-right: solid 1px black;"><?php echo $i; ?></td>
                      <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                      <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                      <td align="center" style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->qty; ?></td>
                      <td align="center" style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->qty_rec; ?></td>
                        <td align="right" style="border-right: solid 1px black;padding-right: 5px;"><?php echo number_format($row->hpp*$row->qty,0,',','.'); ?></td>
                        <td align="right" style="padding-right: 5px;"><?php echo number_format($row->hpp*$row->qty_rec,0,',','.'); ?></td>
                    </tr>
                    <?php $i++; } ?>
                      <tr>
                        <td colspan="3" align="right" style="border-top:1px solid black;border-right: solid 1px black;padding-right: 10px;">T O T A L
                          </td>
                          <td align=center style="border-top:1px solid black;border-right: solid 1px black;padding-right: 1px;"><?php echo number_format($sum,0,',','.'); ?></td>
                          <td align=center style="border-top:1px solid black;border-right: solid 1px black;padding-right: 1px;"><?php echo number_format($sum_rec,0,',','.'); ?></td>
                          <td align=right style="border-top:1px solid black;border-right: solid 1px black;padding-right: 5px;">Rp <?php echo number_format($jumlah,0,',','.'); ?></td>
                          <td align=right style="border-top:1px solid black;border-right: solid 1px black;padding-right: 5px;">Rp <?php echo number_format($jumlah_rec,0,',','.'); ?></td>
                      </tr>
                     
                  </table>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <table width="100%" style="font-size: 12px;">
                    <tr>
                      <td align="center" width="50%">
                        <table width="40%">
                          <tr style="border-bottom: solid 1px black;">
                            <td style="height: 30px;text-align: center;font-weight: bold;">Yang Membuat,</td>
                          </tr>
                        </table>
                      </td>

                     <td align="center" width="50%">
                        <table width="40%">
                          <tr style="border-bottom: solid 1px black;">
                            <td style="height: 30px;text-align: center;font-weight: bold;">Yang Menerima,</td>
                          </tr>
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

