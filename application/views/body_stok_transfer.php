<div class="wraper container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="portlet"><!-- /primary heading -->
		        <div class="portlet-heading">
		            <h3 class="portlet-title text-dark text-uppercase">
		                Stok Transfer
		            </h3>
		            
		            <div class="portlet-widgets">
		                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
		                <span class="divider"></span>
		                <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		        
		        <div id="portlet2" class="panel-collapse collapse in">
		            <div class="portlet-body">
		                <div class="row" style="margin-top: 20px;">
		                    <div class="col-md-12">
		          				<select class="select2" id="sku_transfer">
		          					<option value="">--Item List--</option>
		          					<?php
		          						foreach($produk_non_produksi->result() as $row){
		          					?>
		          					<option value="<?php echo $row->id_produk; ?>"><?php echo $row->nama_produk; ?></option>
		          					<?php } ?>
		          				</select>
		                   </div>
		                </div>

		                <div class="row" style="margin-top: 30px;">
		                	<div class="col-md-12">
		                		<table class="table table-bordered table-striped" style="font-size:10px;">
		                			<thead>
			                			<tr style="background: #2A303A;color:white;font-weight: bold;">
			                				<td width="5%" align="center">No</td>
			                				<td width="10%">SKU</td>
			                				<td>Nama Item</td>
			                				<td width="10%" align="right">Stok Transfer</td>
			                			</tr>
		                			</thead>

		                			<tbody id="stok_transfer_temp">

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

<div class="modal fade bs-example-modal-sm" tabindex="-1" id="edit_transfer_temp" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content" id="edit_transfer_form">
     	<div class="input-group m-t-10">
           <input type="text" id="example-input2-group2" name="example-input2-group2" class="form-control" placeholder="Qty">
           <span class="input-group-btn">
           		<button type="button" class="btn btn-effect-ripple btn-primary">Submit</button>
           </span>
        </div>
    </div>
  </div>
</div>