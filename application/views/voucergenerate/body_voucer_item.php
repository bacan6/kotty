<link rel="stylesheet" href="<?php echo base_url(); ?>datepicker/css/bootstrap-datetimepicker.min.css">
<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-ticket"></i> Data Voucer Item</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <a href="<?php echo base_url('Voucergenerate'); ?>" class="btn btn-success btn-rounded m-b-5"> &laquo; Kembali</a>
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
                  <a href="<?php echo base_url('Voucergenerate/export_html/'.$id); ?>" data-toggle="modal" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Html</a>
            			<a href="<?php echo base_url('Voucergenerate/export_excel/'.$id); ?>" data-toggle="modal" class="btn btn-warning"><i class="fa fa-file-excel-o"></i> Excel</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-voucer-item">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>
