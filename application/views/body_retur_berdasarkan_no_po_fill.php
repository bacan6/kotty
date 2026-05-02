<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                RETUR BERDASARKAN NO PO
            </h3>
            
            <div class="portlet-widgets">
                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                <span class="divider"></span>
               	<a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
               <div class="row">
               	   <div class="col-md-12">
		               <form action="<?php echo base_url('laporan/retur_berdasarkan_no_po'); ?>" method="post">
			              	<div class="form-inline pull-right">
				              	<div class="form-group">
				              		<input type='text' class="form-control" name="no_po" placeholder="No PO"/>
				              	</div>

				              	<div class="form-group">
				              		<input type="submit" class="btn btn-info" value="Submit"/>
				              	</div>
			              	</div>
		              	</form>
	              	</div>
              	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
