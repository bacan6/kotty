<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-car"></i> Data Brand</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-brand" data-toggle="modal" class="btn btn-info add-brand"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-brand">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

<div class="modal fade" id="add-brand" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="" enctype="multipart/form-data" id="submit">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Brand</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<input type="text" class="form-control" name="nama_brand" placeholder="Nama Brand" id="nama_brand"/>
        </div>
        <div class="form-group">
          <input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add-brand-sql">Add</button>
      </div>
    </form>
    </div>
  </div>
</div>