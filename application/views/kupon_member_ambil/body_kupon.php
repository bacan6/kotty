<link rel="stylesheet" href="<?php echo base_url(); ?>datepicker/css/bootstrap-datetimepicker.min.css">
<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-ticket"></i> Pengambilan Produk Redeem</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <form method="get" action="<?php echo base_url('kupon_member_ambil')?>">
              <div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<label>ID Customer</label>
                  <input type="text" class="form-control" id="id_customer" name="id_customer" value="<?php echo $id_customer; ?>">
                  <input type=submit value="cari" class="btn btn-primary">
            		</div>
            	</div>
              </form>
            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-kupon">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

