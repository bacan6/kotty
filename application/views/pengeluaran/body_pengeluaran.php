<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Jurnal Toko 1</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-jurnal" data-toggle="modal" class="btn btn-info add-bahan-baku"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row" style="margin-top:30px">
            		<div class="col-md-12 table-responsive" id="data-jurnal">
            
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<!-- Modal -->
<div class="modal fade" id="add-jurnal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Tambah Jurnal</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <select id="Kode" class="form-control">
                <option>PENJUALAN</option>
                <option>PENDAPATAN</option>
                <option>PEMBELIAN</option>
                <option>BIAYA PROMOSI</option>
                <option>LISTRIK DAN AIR</option>
                <option>ATK</option>
                <option>TRANSPORTASI</option>
                <option>PERBAIKAN</option>
                <option>SALDO AWAL</option>
                <option>PENYESUAIAN</option>
                <option>SETORAN KE PUSAT</option>
                <option>DLL</option>
            </select>
             
        </div>

        <div class="form-group">
            <textarea class="form-control" placeholder="Keterangan" id="keterangan"></textarea>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Debet" id="Debet">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Kredit" id="Kredit">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary add-jurnal">Add</button>
      </div>
    </div>
  </div>
</div>