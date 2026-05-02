<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Akumulasi Pembelian per Brand</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-inline pull-right">
                        <div class="form-group">
                          <input type="text" placeholder="Date Start" id="dateStart" readonly="" class="form-control datepicker" required>
                        </div>

                        <div class="form-group">
                          <input type="text" placeholder="Date End" id="dateEnd" readonly="" class="form-control datepicker" required>
                        </div>
                        <div class="form-group" style="min-width:200px">
                          <div class="input-group">
                          <select class="select2" id="toko" style="min-width:200px">
                                <option value="">--Semua--</option>
                                <option value="0">Gudang</option>
                                <?php
                                  foreach($toko as $tk){
                                ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                <?php } ?>
                              </select>
                                  </div>
                        </div>
                        <div class="form-group">
                          <button class="btn btn-info" id="viewReport">Submit</button>
                        </div>
                      </div>
                  </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                  <div class="col-md-12" id="dataReport">
                  </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

