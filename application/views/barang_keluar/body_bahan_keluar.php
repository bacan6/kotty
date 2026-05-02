<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-unlock"></i> Mutasi Barang</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->

        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
                        <a href="<?php echo base_url('bahan_keluar/daftar_pengeluaran_barang'); ?>"><i class="fa fa-book"></i> Daftar Mutasi Barang</a>  
            		</div>
            	</div>

            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-12">
                        <input type="hidden" id="sku" style="width: 100%;">
            		</div>
            	</div> 

            	<!--<form action="<?php echo base_url('bahan_keluar/proses_bahan_keluar'); ?>" method="post">-->
                	<div class="row" style="margin-top: 20px;">
                		<div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" placeholder="Nama Penerima" id="namaPenerima">
                                </div>
                                <label style="color:red;" id="namaPenerimaLabel"></label>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                                        <select class="select2" id="storeTujuan">
                                        <option value="">--Pilih Store Tujuan--</option>
                                        <?php
                                            foreach($store_tujuan as $st){
                                        ?>
                                        <option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <label style="color:red;" id="storeTujuanLabel"></label>
                            </div>
                		</div>

                		<div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-th-list"></i></span>
                                    <textarea class="form-control" placeholder="Keterangan" id="keterangan"></textarea>
                                </div>
                            </div>
                		</div>
                	</div>  

                	<div class="row" style="margin-top: 20px;">
                		<div class="col-md-12">
    		            		<table class="table table-bordered" style="font-size:12px;">
    		            			<thead>
    			            			<tr style="font-weight: bold;">
    			            				<td>SKU</td>
                                            <td>Nama Produk</td>
                                            <td>Harga Beli</td>
    			            				<td>Jumlah Keluar</td>
    			            				<td>Action</td>
    			            			</tr>
    		            			</thead>

    		            			<tbody id="data-input">
    		            			</tbody>

    		            			<tfoot>
    		            				<tr>
    		            					<td colspan="5" style="text-align: right;">
    		            						<button id="submitMutasi" class="btn btn-primary">Submit</button>
    		            					</td>
    		            				</tr>
    		            			</tfoot>
    		            		</table>
                		</div>
                	</div>      
            	<!--</form>-->      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

