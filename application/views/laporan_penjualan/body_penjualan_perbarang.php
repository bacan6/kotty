
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Laporan Penjualan Perbarang</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              	<div class="row">
              		<div class="col-md-12">

			              	<div class="form-inline">
			              		<div class="form-group">
			              			<input type="text" placeholder="Date Start" id="dateStart" readonly="" class="form-control datepicker" required>
			              		</div>

			              		<div class="form-group">
				              		<input type="text" placeholder="Date End" id="dateEnd" readonly="" class="form-control datepicker" required>
				              	</div>

				              	<div class="form-group" style="width: 200px;">
				              		<input type="hidden" id="produk-ajax" style="width: 100%;">
				              	</div>

                        <div class="form-group" style="width: 200px;">
                          <select class="select2" id="toko">
                            <?php
                              foreach($store as $st){
                                  if ($isAdmin!=1){
                                      if ($idStore==$st->id_store){ ?>
                               <option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>         <?php  
                                      }
                                  }else{
                                ?>
                            <option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>      <?php
                                  }
                            
                            }  ?>
                          </select>
                        </div>

				              	<div class="form-group">
				              		<button class="btn btn-info" id="viewReport">Submit</button>
				              	</div>
			              	</div>

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

