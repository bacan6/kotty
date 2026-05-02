<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Penjualan Pertempat</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                    <form class="pull-right" action="<?php echo base_url('laporan/penjualan_pertempat'); ?>" method="get">
                      <div class="form-inline">
                        <div class="form-group">
                          <input type="text" placeholder="Date Start" name="date_start" readonly="" class="form-control datepicker" required>
                        </div>

                        <div class="form-group">
                          <input type="text" placeholder="Date End" name="date_end" readonly="" class="form-control datepicker" required>
                        </div>

                        <div class="form-group" style="width:250px;">
                          <select class="select2" name="id_stand">
                          	<?php
                              foreach($stand as $st){
                            ?>
                            <option value="<?php echo $st->id_stand; ?>"><?php echo $st->stand; ?></option>
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

                <div class="row" style="margin-top: 20px;">
                  <div class="col-md-12">

                    <?php
                      if(!empty($_GET['date_start'])){
                    ?>

                      <h3 style="text-align: center;">Laporan Penjualan Pertempat</h3>
                      <h3 style="text-align: center;"><?php echo $namaTempat; ?></h3>
                      <h4 style="text-align: center;">Periode</h4>
                      <h5 style="text-align: center;"><?php echo date_format(date_create($_GET['date_start']),'d F Y')." - ".date_format(date_create($_GET['date_end']),'d F Y'); ?></h5>
                    <?php
                      }
                    ?>

                    <table class="table table-striped" style="font-size:11px;">
                      <tr style="background: #2A303A;color:white;font-weight: bold;">
                        <td width="4%">No</td>
                        <td width="10%">SKU</td>
                        <td width="15%">Nama Produk</td>
                        <td align="right">Harga Jual</td>
                        <td align="right">Qty Terjual</td>
                        <td align="right">Total Terjual</td>
                        <td align="right">Diskon Peritem</td>
                        <td align="right">Grand Total</td>
                      </tr>

                      <?php
                        $count = $penjualanPertempat->num_rows();

                        if($count > 0){

                        $i=1;
                        foreach($penjualanPertempat->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->id_produk; ?></td>
                        <td><?php echo $row->nama_produk; ?></td>
                        <td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->qtyTerjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga*$row->qtyTerjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format(($row->harga*$row->qtyTerjual)-$row->diskon,'0',',','.'); ?></td>
                      </tr>
                      
                      <?php $i++; } ?>


                      <?php } else { ?>
                      <tr>
                        <td colspan="8" align="center">--Belum Ada Data Untuk Ditampilkan--</td>
                      </tr>
                      <?php } ?>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

