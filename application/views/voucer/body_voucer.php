<link rel="stylesheet" href="<?php echo base_url(); ?>datepicker/css/bootstrap-datetimepicker.min.css">
<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title"><i class="fa fa-ticket"></i> Data Voucer</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="#add-voucer" data-toggle="modal" class="btn btn-info add-voucer"><i class="fa fa-plus"></i> Add New</a>
            		</div>
            	</div>

            	<div class="row">
            		<div class="col-md-12 table-responsive" id="data-voucer">
            			
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->
</div>

<div class="modal fade" id="add-voucer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Voucer</h4>
      </div>
      <form method="post" action="" enctype="multipart/form-data" id="submit">
      <div class="modal-body">
        
        <div class="form-group">
        	<input type="text" name="nama_voucer" class="form-control" placeholder="Nama Voucer" id="nama_voucer"/>
        </div>
        <div class="form-group">
          <input type="text" name="tgl_berlaku" class="form-control datetimepicker" placeholder="Tanggal Berlaku" id="tgl_berlaku"/>
        </div>
        <div class="form-group">
          <input type="text" name="tgl_expired" class="form-control datetimepicker" placeholder="Tanggal Expired" id="tgl_expired"/>
        </div>
        <div class="form-group">
          <input type="text" name="jml" class="form-control" placeholder="Jumlah" id="jml"/>
        </div>
        <div class="form-group">
          <input type="text" name="potongan" class="form-control" placeholder="Potongan" id="potongan"/>
        </div>
        <div class="form-group">
          <input type="text" name="min_transaksi" class="form-control" placeholder="Min Transaksi" id="min_transaksi"/>
        </div>
        <div class="form-group">
          <textarea class="form-control" name="syarat" placeholder="Syarat" id="syarat"></textarea>
        </div>
        <div class="form-group">
          <input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary add-voucer-sql">Add</button>
      </div>
    </form>
    </div>
  </div>
</div>