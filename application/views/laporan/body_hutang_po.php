<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-12">
                  <a href="#myModal" data-toggle="modal" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</a>
                  <a class="btn btn-default" onclick="printContent('print-area')"><i class="fa fa-print"></i> Print</a>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12" id="print-area">
                  <center>
                      <p style="font-size: 14px;">
                      <?php 
                        echo $header->nama_perusahaan."<br>"; 
                        echo $header->alamat."<br>"; 
                      ?>
                      </p>

                      <h4>Hutang Pembelian</h4>
                  </center>
                  <table class="table" width="100%" style="font-size:11px;border:"> 
                    <thead>
                      <tr style="font-weight: bold;border-bottom: solid 1px black;">
                        <td width="5%" align="center">No</td>
                        <td width="10%">No PO</td>
                        <td width="15%">Supplier</td>
                        <td width="10%">Tanggal PO</td>
                        <td width="10%">Jatuh Tempo</td>
                        <td>Penginput</td>
                        <td align="right">Total</td>
                        <td align="right">Terbayar</td>
                        <td align="right">Sisa Hutang</td>
                        <td>Keterangan</td>
                      </tr>
                    </thead>

                    <tbody id="content">

                      
                    </tbody>
                  </table> 
                </div>
              </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Filter Hutang</h4>
            </div>

            <div class="modal-body">                                   
              <div class="form-group">
                  <label>Supplier</label>
                  <select class="select2" id="supplier">
                      <option value="">--Pilih--</option>
                      <?php
                        foreach($supplier as $dt){
                      ?>
                      <option value="<?php echo $dt->id_supplier; ?>"><?php echo $dt->supplier; ?></option>
                      <?php } ?>
                  </select>
              </div>

              <div class="form-group">
                  <label>Tanggal PO</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control datepicker" placeholder="Tanggal PO" id="tanggalPO" readonly>
                  </div>
              </div>

              <div class="form-group">
                  <label>Jatuh Tempo</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" class="form-control datepicker" placeholder="Jatuh Tempo" id="jatuhTempo" readonly>
                  </div>
              </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="filterHutang">Filter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





