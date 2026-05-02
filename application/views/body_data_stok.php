<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                Data Stok
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
            	<div class="row">
                    <div class="col-md-12" align="right">
                        <form action="<?php echo base_url('data_stok'); ?>" method="get">
                            <div class="input-group" style="width: 30%;">
                                <input type="text" id="example-input1-group2" name="query" class="form-control" placeholder="Search">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-effect-ripple btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-12">
                        <a href="<?php echo base_url('data_stok/export_excel'); ?>" class="btn btn-success">Export</a> | <a href="<?php echo base_url('data_stok/data_stok_fg'); ?>" class="btn btn-primary">Data Stok Finish Goods</a>
                        <br>
                        <br>
            			<table class="table table-bordered table-striped" style="font-size: 12px;">
            				<tr style="background: #2A303A;color:white;font-weight: bold;">
            					<td width="5%">No</td>
            					<td width="10%">SKU</td>
            					<td>Nama Bahan</td>
            					<td width="15%">Kategori</td>
                                <td width="10%" align="right">Last Stok</td>
                                <td width="8%" align="right">Satuan</td>
            					<td width="10%" align="right">Harga</td>
            				</tr>

            			</table>
            		</div>
            	</div>             
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

