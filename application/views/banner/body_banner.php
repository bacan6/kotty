<link rel="stylesheet" href="<?php echo base_url(); ?>datepicker/css/bootstrap-datetimepicker.min.css">
<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-tag"></i> Data Banner</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-banner" data-toggle="modal" class="btn btn-info add-Banner"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-banner">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

<div class="modal fade" id="add-banner" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Banner</h4>
      </div>
      <form method="post" action="" enctype="multipart/form-data" id="submit">
      <div class="modal-body">
        
        <div class="form-group">
        	<input type="text" name="nama_banner" class="form-control" placeholder="Nama Banner" id="nama_banner"/>
        </div>
        <div class="form-group">
            <select name="posisi" class="form-control">
              <option value="">-Pilih-</option>
              <option value="atas">Atas</option>
              <option value="home">Home</option>
              <option value="kasir">Kasir</option>
            </select>
          </div>
        <div class="form-group">
          <input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add-banner-sql">Add</button>
      </div>
    </form>
    </div>
  </div>
</div>