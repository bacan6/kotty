<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 10px;">
      <div class="col-md-6">
        <div class="page-title"> 
          <h3 class="title">Laporan Transfer Stok</h3> 
        </div>
      </div>

      <div class="col-md-6" style="text-align: right;">
        <a href="<?php echo base_url('laporan/transferStokPeritem'); ?>" class="btn btn-default btn-rounded"><i class="fa fa-book"></i> Laporan Transfer Stok Peritem</a>
      </div>
    </div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                
              	<div class="row" style="margin-top: 10px;">
              		<div class="col-md-3" style="border-right: solid 1px #ddd;">
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
                          <label>Transfer Dari</label>
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <select class="select2" id="transferFrom">
                                <option value="">--Semua--</option>
                                <?php
                                  foreach($toko as $tk){
                                ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>

                        <div class="form-group">
                          <label>Tujuan Transfer</label>
                      
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-building"></i></span>
                              <select class="select2" id="transferTo">
                                <option value="">--Semua--</option>
                                <?php
                                  foreach($toko as $kt){
                                ?>
                                <option value="<?php echo $kt->id_store; ?>"><?php echo $kt->store; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>

				              	<div class="form-group">
				              		<button class="btn btn-info" id="viewReport">Submit</button>
				              	</div>
			              	</div>

                      <div class="col-md-9" id="dataReport">
                      </div>
              	</div>

              	<div class="row" style="margin-top: 30px;">
              		<div class="col-md-12" id="dataReport">
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


