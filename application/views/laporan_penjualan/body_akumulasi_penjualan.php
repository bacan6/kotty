<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Akumlasi Penjualan</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-inline" style="text-align: right;">
                        <div class="form-group">
                          <input type="text" placeholder="Date Start" id="dateStart" readonly="" class="form-control datepicker" required>
                        </div>

                        <div class="form-group">
                          <input type="text" placeholder="Date End" id="dateEnd"readonly="" class="form-control datepicker" required>
                        </div>
                        <div class="form-group">
                          <button type="submit" id="submitLaporan" class="btn btn-info">Submit</button>
                        </div>

                        <div class="form-group" id="exportExcelButton">
                        </div>

                        <div class="form-group" id="printButton">
                        </div>
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12" id="titleReport" style="text-align: center;">
                  </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                  <div class="col-md-12">

                    <table class="table" style="font-size:11px;">
                      <thead>
                        <tr style="font-weight: bold;">
                          <td width="4%">No</td>
                          <td width="12%">No Invoice</td>
                          <td>Toko</td>
                          <td>Tanggal</td>
                          <td>Tipe Bayar</td>
                          <td align="right">Subtotal</td>
                          <td align="right">Ongkir</td>
                          <td align="right">Diskon Channel</td>
                          <td align="right">Diskon</td>
                          <td align="right">Poin Reimburs</td>
                          <td align="right">Diskon Peritem</td>
                          <td align="right">Total</td>
                        </tr>
                      </thead>

                      <tbody id="dataLaporan">
                        <tr>
                          <td colspan="12" align="center" style="font-weight: bold;" id="loading">--BELUM ADA DATA UNTUK DI TAMPILKAN--</td>
                        </tr>
                      </tbody>
                     
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<div id="modalDetail" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Penjualan</h4>
            </div>
            <div class="modal-body" id="dataPenjualan">
                                                
            </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
