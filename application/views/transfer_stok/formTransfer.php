<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Transfer Stok</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
		      <div class="row">
                <div class="col-md-12">
                    Transfer Dari : <b><?php echo $namaStore; ?></b><br>
					<p align=right><a href="<?php echo base_url('transferStok/importTransferItem'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Import Transfer Item</a></p>
                </div>
              </div>

              <div class="row" style="margin-top: 20px;">
              	<div class="col-md-12">
              		<input type="hidden" id="produkAjax" style="width: 100%;" />
              	</div>
              </div>

              <div class="row" style="margin-top: 20px;">
              	<div class="col-md-6">
              		<div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home"></i></span>
                            <select class="select2" id="tokoTujuan">
                            	<?php
                            		foreach($store as $row){
                            	
                            		if($_GET['idStore'] != $row->id_store){
                            	?>
                            	<option value="<?php echo $row->id_store; ?>"><?php echo $row->store; ?></option>
                            	<?php } } ?>
                            </select>
                        </div>
                    </div>
              	</div>

              	<div class="col-md-6">
              		<div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-list"></i></span>
                            <textarea class="form-control" placeholder="Keterangan" id="keterangan"></textarea>
                        </div>
                    </div>
              	</div>
              </div>

              <div class="row">
              	<div class="col-md-12" style="text-align: right;">
              		<btn class="btn btn-primary" id="doTransfer"> <i class="fa fa-rocket"></i> Transfer </btn>
              	</div>
              </div>

              <div class="row" style="margin-top: 10px;">
              	<div class="col-md-12">
              		<table class="table table-bordered">
              			<thead>
              				<tr style="font-weight: bold;">
              					<td width="15%">SKU</td>
              					<td width="70%">Nama Produk</td>
              					<td>Qty Transfer</td>
              					<td width="5%"></td>
              				</tr>
              			</thead>

              			<tbody id="dataCart">
              			</tbody>
              		</table>
              	</div>
              </div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

