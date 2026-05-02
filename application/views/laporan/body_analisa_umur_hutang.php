<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Analisa Umur Hutang</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <div class="row">
                  <div class="col-md-12">
                      <a href="#myModal" data-toggle="modal" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</a>
                      <a class="btn btn-default" onclick="printContent('print-area')"><i class="fa fa-print"></i> Print</a>
                  </div>
              </div>

              <div class="row" style="margin-top: 10px;" id="print-area">
              	<div class="col-md-12">
                  <p style="text-align: center;font-size: 18px;font-weight: bold;">Analisa Umur Hutang</p>
              		<table class="table table-bordered" style="font-size:11px;">
                    <thead>
                			<tr style="font-weight: bold;color: black;">
                				<td rowspan="2" style="vertical-align: middle;text-align: center;" width="12%">No PO</td>
                				<td rowspan="2" style="vertical-align: middle;text-align: center;" width="15%">Supplier</td>
                				<td rowspan="2" style="vertical-align: middle;text-align: center;">Tgl Jatuh Tempo</td>
                				<td colspan="6" align="center" style="vertical-align: middle;">Jatuh Tempo</td>
                			</tr>

                			<tr style="font-weight: bold;color: black;">
                				<td style="text-align: center;" width="10%">Hari Ini</td>
                				<td style="text-align: center;" width="10%"> <= 7 Hari </td>
                				<td style="text-align: center;" width="10%"> <= 14 Hari</td>
                				<td style="text-align: center;" width="10%"> <= 25 Hari</td>
                				<td style="text-align: center;" width="10%"> > 25 Hari</td>
                				<td style="text-align: center;" width="10%"> > Tempo</td>
                			</tr>
                    </thead>

                    <tbody id="content">
                			
                    </tbody>
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
                <h4 class="modal-title" id="myModalLabel">Filter Hutang</h4>
            </div>

            <div class="modal-body">                                   
              <div class="form-group">
                  <label>Supplier</label>
                  <select class="select2" id="supplier">
                      <option value="">--Pilih--</option>
                      <?php
                        foreach($supplier as $dt){
                      ?>
                      <option value="<?php echo $dt->id_supplier; ?>"><?php echo $dt->supplier; ?></option>
                      <?php } ?>
                  </select>
              </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="filterHutang">Filter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->