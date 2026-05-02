<link rel="stylesheet" href="<?php echo base_url(); ?>datepicker/css/bootstrap-datetimepicker.min.css">
<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-tag"></i> Data Promo</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-promo" data-toggle="modal" class="btn btn-info add-promo"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-promo">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

<div class="modal fade" id="add-promo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Promo</h4>
      </div>
      <form method="post" action="" enctype="multipart/form-data" id="submit">
      <div class="modal-body">
        
        <div class="form-group">
        	<input type="text" name="nama_promo" class="form-control" placeholder="Nama promo" id="nama_promo"/>
        </div>
         <div class="form-group">
          <input type="hidden" id="brand" name="brand" style="width:100%;"/>
         </div>
        <div class="form-group">
          <input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add-promo-sql">Add</button>
      </div>
    </form>
    </div>
  </div>
</div>