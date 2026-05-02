<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Kategori Bahan</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-kategori" data-toggle="modal" class="btn btn-info add-bahan-baku"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="kategori-bahan">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<!-- Modal -->
<div class="modal fade" id="add-kategori" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Kategori Bahan</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Kategori" id="kategori">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary add-kategori">Add</button>
      </div>
    </div>
  </div>
</div>