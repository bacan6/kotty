<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Hutang Terbayar</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <div class="row">
                  <div class="col-md-3" style="border-right: solid 1px #ccc;">
                    <div class="form-group">
                        <label>Date Start</label>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input type="text" class="form-control datepicker" placeholder="Date Start" id="dateStart" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Date End</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control datepicker" placeholder="Date End" id="dateEnd" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Supplier</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                            <select class="select2" id="supplier">
                              <option value="">--Semua--</option>
                              <?php
                                foreach($supplier as $sp){
                              ?>
                              <option value="<?php echo $sp->id_supplier; ?>"><?php echo $sp->supplier; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                      </div>

                    <div class="form-group">
                        <label>Tipe Pembayaran</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <select class="form-control" id="tipeBayar">
                                <option value="">--Semua--</option>

                                <?php
                                    foreach($tipeBayar as $dt){
                                ?>
                                <option value="<?php echo $dt->id; ?>"><?php echo $dt->paymentType; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>No PO</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                            <input type="text" class="form-control" placeholder="No PO" id="noPO">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>No Payment</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-bolt"></i></span>
                            <input type="text" class="form-control" placeholder="No Payment" id="noPayment">
                        </div>
                    </div>

                    <div class="form-group" style="text-align: right;">
                        <a class="btn btn-primary" id="submitLaporan">Submit</a>
                    </div>
                  </div>

                  <div class="col-md-9">
                    <table class="table table-bordered" style="font-size:11px;">
                        <thead>
                            <tr style="font-weight: bold;">
                                <td width="3%">No</td>
                                <td width="15%">No Payment</td>
                                <td width="12%">No PO</td>
                                <td width="12%">Supplier</td>
                                <td>PIC</td>
                                <td>Tanggal Pembayaran</td>
                                <td>Tipe Pembayaran</td>
                                <td>Keterangan</td>
                                <td align="right">Terbayar</td>
                            </tr>
                        </thead>

                        <tbody id="content">
                            <tr>
                                <td colspan="9">Belum ada data</td>
                            </tr>
                        </tbody>
                    </table>
                  </div>
              </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
