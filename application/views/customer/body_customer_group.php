<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Customer Group</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
                    <div class="col-md-12" style="text-align: right;">
                        <a href="#myModal" data-toggle="modal" class="btn btn-primary"> Tambah Group </a>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
            		<div class="col-md-12">
            			<table class="table table-bordered" style="font-size: 10px;">
            				<thead>
                                <tr style="font-weight: bold;">
                                    <td width="5%" align="center">No</td>
                					<td>Group</td>
                                    <td width="5%"></td>
                				</tr>
                            </thead>

                            <tbody id="customer-group-data">
                				
                            </tbody>
            			</table>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Group Customer</h4>
            </div>
                                            
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nama Group" id="nama_group">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="simpan-customer-group">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade bs-example-modal-sm" id="editGroup" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="mySmallModalLabel">Edit Customer Group</h4>
            </div>
            
            <div class="modal-body" id="editPlace">
                        
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="ubah-customer-group">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
