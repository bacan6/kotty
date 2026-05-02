<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 10px;">
      <div class="col-md-6">
        <div class="page-title"> 
          <h3 class="title">Retur Penjualan</h3> 
        </div>
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
                    <label>Store</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-building"></i></span>
                      <select class="select2" id="store">
                      <?php
                                  foreach($toko as $tk){
                                     ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                <?php       }
                               ?>
                    </select>
                    </div>
                  </div>

                        <div class="form-group">
                          <label>Produk</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-inbox"></i></span>
                            <input type="hidden" id="sku" style="width: 100%;"/>
                          </div>
                        </div>    

                        <div class="form-group">
                          <label>No Invoice</label>
                          
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-book"></i></span>
                            <input type="text" placeholder="No Invoice" id="noInvoice" class="form-control">
                          </div>
                        </div>

				              	<div class="form-group">
				              		<button class="btn btn-info" id="viewReport">Submit</button>
				              	</div>
			              	</div>

                      <div class="col-md-9" id="dataReport">
                      </div>
              	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
