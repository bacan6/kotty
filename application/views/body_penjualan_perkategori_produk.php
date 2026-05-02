<div class="wraper container-fluid">

	<div class="page-title"> 
	    <h3 class="title">Penjualan Berdasarkan Departemen</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              	<div class="row">
              		<div class="col-md-12">
		              
			              	<div class="form-inline pull-right">
			              		<div class="form-group">
			              			<input type="text" placeholder="Date Start" id="date_start" readonly="" class="form-control datepicker" required>
			              		</div>
			              		<div class="form-group">
				              		<input type="text" placeholder="Date End" id="date_end" readonly="" class="form-control datepicker" required>
				              	</div>

                        <div class="form-group">
                          <select class="form-control" id="kategori">
                            <option value="">--Pilih Departemen--</option>
                            <?php 
                              $show_kategori = $this->db->get("ap_kategori");
                              foreach($show_kategori->result() as $kt){
                            ?>
                            <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                            <?php } ?>
                          </select>
                        </div>

                        <div class="form-group" id="sub_kategori">
                        </div>

                        <div class="form-group" id="sub_kategori_2">
                        </div>
				              	<div class="form-group">
				              		<button id="submitReport" class="btn btn-info">Submit</button>
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

