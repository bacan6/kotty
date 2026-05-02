<div class="wraper container-fluid">

	<div class="page-title"> 
	    <h3 class="title">Penjualan Berdasarkan Customer</h3> 
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
				              		<input type="hidden" id="customer-form" style="width: 200px;"/>
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


