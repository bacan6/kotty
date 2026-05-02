<div class="wraper container-fluid">
	<div class="page-title"> 
      <h3 class="title">Penjualan Berdasarkan Kriteria</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              	<div class="row" style="border-bottom:solid 1px #eee;">
              		<div class="col-md-4">
		              	<form class="form-horizontal" role="form" style="font-size: 12px;">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Periode</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="start" id="datepicker">
                                </div>

                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="end" id="datepicker2">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Customer Group</label>
                                <div class="col-sm-9">
                                    <select class="select2" name="customer_group" id="customer_group">
                                    	<option value="all">--Customer Group--</option>
                                    	<?php
                                    		foreach($group as $gr){
                                    	?>
                                    	<option value="<?php echo $gr->id_group; ?>"><?php echo $gr->group_customer; ?></option>
                                    	<?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Customer</label>
                                <div class="col-sm-9"">
                                    <select class="select2" name="id_customer" id="customer_list">
                                    	<option value="">--Pilih Customer--</option>

                                    	<?php
                                    		foreach($customer as $cs){
                                    	?>
                                    	<option value="<?php echo $cs->id_customer; ?>"><?php echo $cs->nama; ?></option>
                                    	<?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Ekspedisi</label>
                                <div class="col-sm-9">
                                    <select class="select2" name="id_ekspedisi">
                                    	<option value="">--Ekspedisi--</option>

                                    	<?php
                                    		foreach($ekspedisi as $ex){
                                    	?>
                                    	<option value="<?php echo $ex->id_ekspedisi; ?>"><?php echo $ex->ekspedisi; ?></option>
                                    	<?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Tipe Bayar</label>
                                <div class="col-sm-9">
                                    <select class="select2" name="tipe_bayar">
                                    	<option value="">--Tipe Bayar--</option>
                                    	<option value="0">Cash</option>
                                    	<option value="1">Credit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                              	<label for="inputEmail3" class="col-sm-3 control-label"></label>
                                <div class="col-sm-9" style="text-align: right;">
                                    <input type="submit" class="btn btn-primary" value="Submit">
                                </div>
                            </div>
                        </form>
	              	</div>
              	</div>

              	<div class="row">
              		<div class="col-md-12">
              		</div>
              	</div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

