
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Top Brand berdasarkan Kategori</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              	<div class="row">
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
                          <label>Toko</label>
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                              <select class="select2" id="toko">
                                  <option value="">--Semua--</option>
                                <?php
                                  foreach($toko as $tk){

                              ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                              <?php }
                                    ?>
                              </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                          <label>Kategori</label>
                          
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              <select class="form-control" id="kategori">
                            <option value="">--Semua--</option>
                            <?php
                              foreach($kategori as $kt){
                            ?>
                            <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                            <?php } ?>
                          </select>
                            </div>
                        </div>

                        <!--id=subkategori2-->
                        <div class="form-group" id="subkategori">
                          <input type="hidden" id="subkategori2" value=""/>
                        </div>

                        <!--id=subkategori_3-->
                        <div class="form-group" id="sub_kategori_2">
                          <input type="hidden" id="subkategori_3" value=""/>
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

