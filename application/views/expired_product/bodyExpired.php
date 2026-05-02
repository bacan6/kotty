<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Produk Kadaluarsa</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
            			<a href="<?php echo base_url('expired_product/daftar_po'); ?>"><i class="fa fa-book"></i> Daftar Produk Kadaluarsa</a>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 20px;">
					<div class="col-md-12" style="margin-bottom:20px">
                        <input type="text" id="keterangan" placeholder="Keterangan Produk" style="width:100%;" class="form-control" />
            		</div>
            		<div class="col-md-12">
                        <input type="hidden" id="sku" style="width:100%;"/>
            		</div>
            	</div> 
                
            	 

            	<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="text-align: right;">
                        <button class="btn btn-primary" id="prosesPO"><i class="fa fa-save"></i> Simpan</button>
                    </div>

            		<div class="col-md-12" style="margin-top: 20px;">
		            		<table class="table table-bordered" style="font-size:12px;">
		            			<thead>
			            			<tr style="font-weight: bold;">
			            				<td width="15%">SKU</td>
			            				<td width="30%">Nama Produk</td>
                                        <td align="right" width="15%">Harga Jual</td>
										<td width="10%">QTY</td>
										<td width="15%">SubTotal</td>
			            				<td></td>
			            			</tr>
		            			</thead>

		            			<tbody id="data-input">
                                    <tr>
                                        <td colspan="4" align="center"><b>--BELUM ADA DATA TERINPUT--</b></td>
                                    </tr>
		            			</tbody>
		            		</table>
            		</div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
