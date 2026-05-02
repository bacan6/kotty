<div class="wraper container-fluid">
	
	<div class="row">
		<div class="col-md-12">
			<div class="portlet"><!-- /primary heading -->
		        <div id="portlet2" class="panel-collapse collapse in">
		            <div class="portlet-body">
		            	<div class="row">
					        <div class="col-md-12">
					            <a href="#myModal" data-toggle="modal" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</a>
					            <a class="btn btn-default" onclick="printContent('print-area')"><i class="fa fa-print"></i> Print</a>
					        </div>
					    </div>

		                <div class="row" style="margin-top: 20px;" id="print-area">
		                    <div class="col-md-12">
		                    	<p style="text-align: center;font-size: 18px;"><?php echo $header->nama_perusahaan; ?></p>
		                    	<p style="text-align: center;font-size: 12px;">Hutang Jatuh Tempo </p>

		             			<table class="table" style="font-size: 11px;">
		             				<thead>
			             				<tr style="font-weight: bold;">
			             					<td width="3%">No</td>
			             					<td>No PO</td>
			             					<td>Tanggal PO</td>
			             					<td>Jatuh Tempo</td>
			             					<td>Supplier</td>
			             					<td align="right">Total</td>
			             					<td align="right">Terbayar</td>
			             					<td align="right">Saldo</td>
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
	</div>
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