<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Pembayaran Piutang </h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-6">
                    <?php
                        if($statusPiutang==0){
                    ?>
                    <button id="<?php echo $_GET['no_invoice']; ?>" class="btn btn-inverse lunasiPiutang"><i class="fa fa-check"></i> Lunas</button>
                    <?php } ?>
                </div>

                <div class="col-md-6" align="right">
                    <a target="_blank" href="<?php echo base_url('data_piutang/printPembayaranPiutang?noInvoice='.$_GET['no_invoice']); ?>" class="btn btn-success"><i class="fa fa-print"></i> Print</a>
                </div>
              </div>

              <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="font-weight: bold;">
                                <td width="5%" style="text-align: center;">No</td>
                                <td style="width: 13%">Kode Barang</td>
                                <td style="">Nama Barang</td>
                                <td style="width: 20%;text-align: right;">Harga Satuan</td>
                                <td width="10%" style="text-align: center;">QTY</td>
                                <td width="20%" style="text-align: center;text-align: right;">Total</td>
                            </tr>
                        </thead>

                        <tbody id="dataPembayaran">
                            
                        </tbody>
                    </table>
                </div>
              </div>

		      <div class="row" style="margin-top: 20px;">
                <div class="col-md-5">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Nominal</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Nominal" id="nominal" <?php if($statusPiutang==1){echo "disabled";} ?>/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Tipe Bayar</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="type_bayar" <?php if($statusPiutang==1){echo "disabled";} ?>>
                                    <?php
                                        foreach($paymentType as $pt){
                                            if($pt->id !=5){
                                    ?>
                                    <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"></label>
                            <div class="col-sm-9" id="sub-account">
                                <input type="hidden" id="subAccount" <?php if($statusPiutang==1){echo "disabled";} ?>/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="keterangan" <?php if($statusPiutang==1){echo "disabled";} ?>></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label"></label>
                            <div class="col-sm-9" style="text-align: right;">
                                <button type="button" class="btn btn-primary btn-rounded m-b-5" id="simpanPembayaran" <?php if($statusPiutang==1){echo "disabled";} ?>><i class="fa fa-save"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-7">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="font-weight: bold;">
                                <td>No</td>
                                <td>No Pembayaran</td>
                                <td>Tanggal</td>
                                <td>Penerima Pembayaran</td>
                                <td>Tipe Bayar</td>
                                <td align="right">Nominal</td>
                                <td>Keterangan</td>
                            </tr>
                        </thead>

                        <tbody id="riwayatPembayaran">
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

