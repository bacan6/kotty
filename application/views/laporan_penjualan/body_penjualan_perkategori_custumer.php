<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Penjualan Perkategori Customer</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                  <div class="col-md-12">

                      <div class="form-inline pull-right">
                        <div class="form-group">
                          <input type="text" placeholder="Date Start" id="dateStart" name="date_start" readonly="" class="form-control datepicker" required>
                        </div>

                        <div class="form-group">
                          <input type="text" placeholder="Date End" id="dateEnd" name="date_end" readonly="" class="form-control datepicker" required>
                        </div>

                        <div class="form-group" style="width: 200px;">
                          <select class="select2" id="id_kategori">
                            <option>--Pilih Kategori Customer--</option>

                            <?php
                              foreach($kategori as $kt){
                            ?>
                            <option value="<?php echo $kt->id_group; ?>"><?php echo $kt->group_customer; ?></option>
                            <?php } ?>

                          </select>
                        </div>

                        <div class="form-group" style="width: 200px;">
                          <select class="select2" id="id_toko">
                            <option value="">--Pilih Toko--</option>

                            <?php
                              foreach($toko as $tk){
                                  if ($isAdmin!=1){
                                      if ($idStore==$tk->id_store){ ?>
                            ?>
                            <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                            <?php       }
                                  }else{
                              ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                              <?php }
                              }?>
                          </select>
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

