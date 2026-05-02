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
                  INVOICE PEMBAYARAN HUTANG
                </div>
              </div>

              <div class="row" style="margin-top: 10px;">
                <div class="col-md-6 col-xs-6 col-sm-6">
                  <table style="font-size: 12px;">
                    <tr>
                      <td width="25%" style="font-weight: bold;">No Pembayaran</td>
                      <td width="1%">:</td  >
                      <td> <?php echo $infoPembayaran->no_payment; ?></td>
                    </tr>

                    <tr>
                      <td width="25%" style="font-weight: bold;">No PO</td>
                      <td width="1%">:</td  >
                      <td> <?php echo $infoPembayaran->no_po; ?></td>
                    </tr>
                    <tr>
                      <td width="25%" style="font-weight: bold;">Tanggal</td>
                      <td width="1%">:</td  >
                      <td> <?php echo $infoPembayaran->tanggal_pembayaran; ?></td>
                    </tr>
                  </table>
                </div>

                <div class="col-md-6 col-xs-6 col-sm-6">
                  	<table style="font-size: 12px;">
                    	<tr>
	                      <td width="25%" style="font-weight: bold;">PIC</td>
	                      <td width="1%">:</td  >
	                      <td><?php echo $infoPembayaran->nama_user; ?></td>
	                    </tr>

	                    <tr>
	                      <td width="25%" style="font-weight: bold;">Keterangan</td>
	                      <td width="1%">:</td  >
	                      <td><?php echo $infoPembayaran->keterangan; ?></td>
	                    </tr>
                    </table>
                </div>
              </div>

              <div class="row" style="margin-top: 5px;">
                <div class="col-md-12">
                  <table width="100%" style="font-size: 12px;border:solid 1px black;">
                    <tr style="font-weight: bold;border-bottom: solid 1px black;">
                      <td width="50%" style="border-right: solid 1px black;padding-left: 1px;">Tipe Bayar</td>
                      <td style="border-right: solid 1px black;">Jumlah Bayar</td>
                    </tr>

                   	<tr>
                   		<td style="border-right: solid 1px black;"><?php echo $infoPembayaran->paymentType; ?></td>
                   		<td><?php echo number_format($infoPembayaran->pembayaran,'2',',','.'); ?></td>
                   	</tr>

                  </table>

                  <table width="100%" style="font-size: 12px;">
                    <tr>
                      <td align="center" width="33%">
                        <table width="60%">
                          <tr style="border-bottom: solid 1px black;">
                            <td style="height: 30px;text-align: center;font-weight: bold;">Mengetahui</td>
                          </tr>
                        </table>
                      </td>

                     <td align="center" width="33%">
                        <table width="60%">
                          <tr style="border-bottom: solid 1px black;">
                            <td style="height: 30px;text-align: center;font-weight: bold;">Admin Finance</td>
                          </tr>
                        </table>
                      </td>
                      <td align="center" width="33%">
                        <table width="60%">
                          <tr style="border-bottom: solid 1px black;">
                            <td style="height: 30px;text-align: center;font-weight: bold;">Yang Menerima</td>
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

