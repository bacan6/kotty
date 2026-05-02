<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-cubes"></i> Bahan Baku</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="form-inline" style="text-align: right;">
            		<div class="form-group">
            			<a href="<?php echo base_url('bahan_baku/addNewBahanBaku'); ?>" class="btn btn-info"><i class="fa fa-plus"></i> Add New</a>  
            		</div>
            	</div>

            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-12" style="padding: 30px;">
            			<table class="table table-bordered" id='tableStok'>
                    <thead>
                      <tr style="font-weight: bold;">
                        <td width="5%">No</td>
                        <td width="10%">Kode Bahan</td>
                        <td>Nama Bahan</td>
                        <td>Satuan</td>
                        <td>Kategori</td>
                        <td>Harga</td>
                        <td width="8%">Status</td>
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
