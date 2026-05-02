<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-car"></i> Data Supplier</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-supplier" data-toggle="modal" class="btn btn-info add-supplier"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-supplier">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

<div class="modal fade" id="add-supplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Supplier</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        	<input type="text" class="form-control" placeholder="Nama Supplier" id="nama_supplier"/>
        </div>

        <div class="form-group">
        	<input type="text" class="form-control" placeholder="Kontak" id="kontak_supplier"/>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="Email" id="email_supplier"/>
        </div>

        <div class="form-group">
        	<textarea class="form-control" placeholder="Alamat" id="alamat_supplier"></textarea>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="No Rekening" id="no_rekening"/>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="Bank" id="bank"/>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="Atas Nama" id="atas_nama"/>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="Term of Pay" id="termOfPay"/>
        </div>
        <div class="form-group">
          <select class="form-control" id="jenisBayar">
            <option value="Cash">Cash</option>
            <option value="Kredit">Kredit</option>
          </select>
        </div>
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Jenis Retur" id="jenisRetur"/>
        </div>
        <div class="form-group">
          <select class="form-control" id="jenisPKP">
            <option value="PKP">PKP</option>
            <option value="NON-PKP">NON-PKP</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary add-supplier-sql">Add</button>
      </div>
    </div>
  </div>
</div>