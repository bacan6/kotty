<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Penerimaan Work Order</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12">
            			<button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-filter"></i> Filter</button>
            		</div>

            		<div class="col-md-12" style="padding: 30px;" id="dataWO">
            			<table class="table table-bordered" id="penerimaanWO">
            				<thead>
	            				<tr style="font-weight: bold;">
	            					<td width="5%">No</td>
	            					<td>No WO</td>
	            					<td>Tanggal WO</td>
	            					<td>Tanggal Penyelesaian</td>
	            					<td>Vendor</td>
	            					<td>Pemohon</td>
	            					<td>Status</td>
	            					<td width="5%"></td>
	            				</tr>
            				</thead>
            			</table>
            		</div>
            	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Filter Bahan Masuk</h4>
            </div>

            <div class="modal-body">                                   
                <div class="form-group">
                    <label>Tanggal wo</label>
                    <input type="text" class="datepicker" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tanggalWO"> 
                </div>

                <div class="form-group">
                    <label>Tanggal Penyelesaian</label>
                    <input type="text" class="datepicker" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tanggalPenyelesaian"> 
                </div>

                <div class="form-group">
                    <label>Vendor</label>
                    <select class="select2" id="supplier">
                        <option value="">--Pilih vendor--</option>
                        <?php 
                            foreach($supplier as $dt){
                        ?>
                            <option value="<?php echo $dt->id_supplier?>"><?php echo $dt->supplier; ?></option>
                        <?php        
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="select2" id="status">
                        <option value="">--Pilih Status--</option>
                        <option value="0">Dalam Proses</option>
                        <option value="1">Diterima</option>
                        <option value="2">Selesai</option>
                        <option value="2">Batal</option>
                    </select>
                </div>

                <div class="form-group">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="filterDatatables">Filter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


