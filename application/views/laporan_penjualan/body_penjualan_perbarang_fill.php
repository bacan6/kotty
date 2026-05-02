<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Laporan Penjualan Perbarang</h3> 
  </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                   <div class="col-md-12">
                   <form action="<?php echo base_url('laporan/penjualan_perbarang'); ?>" method="post">
                      <div class="form-inline">
                        <div class="form-group">
                          <input type="text" placeholder="Date Start" name="date_start" id="datepicker" readonly="" class="form-control" required>
                        </div>

                        <div class="form-group">
                          <input type="text" placeholder="Date End" name="date_end" id="datepicker2" readonly="" class="form-control" required>
                        </div>

                        <div class="form-group" style="width: 200px;">
                          <select class="select2" id="sku" name="id_produk" required>
                                  <?php
                                    foreach($get_produk->result() as $dt){
                                  ?>
                                  <option value="<?php echo $dt->id_produk; ?>"><?php echo $dt->nama_produk; ?></option>
                                  <?php } ?>
                              </select>
                        </div>

                        <div class="form-group">
                          <input type="submit" class="btn btn-info" value="Submit"/>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

            <div class="row" style="margin-top: 20PX;">
              <div class="col-md-12">
                <table class="table table-bordered table-striped" style="font-size: 11px;">
                  <tr style="background: #2A303A;color:white;font-weight: bold;">
                    <td width="5%">No</td>
                    <td>No Invoice</td>
                    <td>Tanggal</td>
                    <td>Harga Satuan</td>
                    <td>Qty</td>
                    <td>Total</td>
                  </tr>

                  <?php
                    $i = 1;
                    $total = 0;
                    foreach($penjualan_perbarang as $row){
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row->no_invoice; ?></td>
                    <td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
                    <td><?php echo number_format($row->harga,'0',',','.'); ?></td>
                    <td><?php echo $row->qty; ?></td>
                    <td><?php echo number_format($row->harga*$row->qty,'0',',','.'); ?></td>
                  </tr>
                  <?php $total = $total+($row->harga*$row->qty); $i++; } ?>

                  <tr style="background: #2A303A;color:white;font-weight: bold;">
                    <td colspan="5" align="center">TOTAL</td>
                    <td><?php echo number_format($total,'0',',','.'); ?></td>
                  </tr>
                </table>
              </div>
            </div>

            </div>


        </div>
    </div> <!-- /Portlet -->  
</div>
