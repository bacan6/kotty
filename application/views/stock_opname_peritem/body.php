<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Stock Opname per Item<br><small><?php echo $store?></small></h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
						<a href="<?php echo base_url('so_peritem/importSOItem'); ?>" class="btn btn-success"><i class="fa fa-edit"></i> Import Data Stock (PDT)</a>
            			<a href="<?php echo base_url('so_peritem/daftar_po'); ?>" class="btn btn-primary"><i class="fa fa-book"></i> Daftar Stock Opname</a>
                        <a href="<?php echo base_url('so_peritem/laporanSO'); ?>" class="btn btn-success"><i class="fa fa-eye"></i> Hasil Keseluruhan</a>
						<a href="<?php echo base_url('so_peritem/laporanSOnull'); ?>" class="btn btn-danger"><i class="fa fa-eye"></i> Belum SO</a>
                        <a href="<?php echo base_url('so_peritem/rekapBrand'); ?>" class="btn btn-info"><i class="fa fa-list"></i> Rekap per Brand</a>
            		</div>
            	</div>

            	<div class="row" style="margin-top: 20px;">
					<div class="col-md-12" style="margin-bottom:20px">
                        <label>Keterangan</label>
                        <input type="text" id="keterangan" placeholder="Keterangan: Kode Rak - Lorong - DLL" style="width:100%;" class="form-control" />
            		</div>
                    <div class="col-md-12" style="margin-bottom:20px">
                        <label>Brand</label>
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                              <select class="select2" id="brand">
                                <option value="">--Pilih Brand--</option>
                                <?php
                                //var_dump($brand);
                                  foreach($brand->result() as $b){
                                    $sel = $id_brand == $b->id_brand? 'selected':'';
                                ?>
                                <option value="<?php echo $b->id_brand; ?>" <?php echo $sel?>><?php echo $b->brand; ?></option>
                                <?php }  ?>
                              </select>
                            </div>
            		</div>
                    <!-- <div class="col-md-12" style="margin-bottom:20px">
                        <label>Kategori</label>
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-tags"></i></span>
                              <select class="select2" id="kategori" data-sel="<?php echo isset($id_kategori) && $id_kategori !== '' ? htmlspecialchars($id_kategori, ENT_QUOTES, 'UTF-8') : ''; ?>">
                                <option value="">--Pilih Kategori--</option>
                              </select>
                            </div>
            		</div> -->
            		<!-- <div class="col-md-12">
                        <input type="hidden" id="sku" style="width:100%;"/>
            		</div> -->
            	</div> 
                
            	 

            	<div class="row" style="margin-top: 20px;">
                    <div class="col-md-12 col-lg-5" style="margin-bottom:10px;">
                        <label for="jumpToSku">Lompat ke SKU</label>
                        <input type="text" id="jumpToSku" class="form-control" placeholder="Ketik id_produk, Enter" autocomplete="off"/>
                    </div>
                    <div class="col-md-12 col-lg-7" style="text-align: right;padding-top:24px;">
                        <a href="<?php echo base_url('so_peritem/kosongkanCart'); ?>" class="btn btn-danger"><i class="fa fa-trash"></i> Kosongkan Keranjang SO</a>
                        <a href="<?php echo base_url('so_peritem/exportExcelCartSo'); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        <button class="btn btn-primary" id="prosesPO" disabled><i class="fa fa-save"></i> Simpan</button>
						<a href="#approvalSO" data-toggle="modal" class="btn btn-info" onclick="$('#chartID').val('prosesPO');$('#userApprover').focus();"><i class="fa fa-cog"></i> </a>
                    </div>

            		<div class="col-md-12" style="margin-top: 20px;">
		            		<table class="table table-bordered" style="font-size:12px;">
		            			<thead>
			            			<tr style="font-weight: bold;">
			            				<td width="15%">SKU</td>
			            				<td width="30%">Nama Produk</td>
                                        <td align="right" width="10%">HPP</td>
										<td width="5%">Stok<br>Sistem</td>
										<td width="10%">Stok<br>Toko</td>
										<td align="right" width="15%">Total Harga</td>
			            				<td></td>
			            			</tr>
		            			</thead>

		            			<tbody id="data-input">
                                    <tr>
                                        <td colspan="2" align="center"><b>--BELUM ADA DATA TERINPUT--</b></td>
                                    </tr>
		            			</tbody>
		            		</table>
            		</div>
            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
<div id="approvalSO" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check"></i> Approval SO</h4>
            </div>                             
            <div class="modal-body">

                <div class="row" id="approvalContent" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                            <input type="hidden" id="chartID">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="userApprover" required>
                                    <label id="labelpwd" style="color:red;"></label>
                                </div>
                            </div>

                        </form>                            
                    </div>
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" onsubmit="$('#verifyApproval').click();return false;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                    <input type="password" class="form-control" id="passApprover" required>
                                </div>
                            </div>

                        </form>                            
                    </div>

                </div>     
            </div>        
            <div class="modal-footer">
                <button class="btn btn-success" id="verifyApproval"><i class="fa fa-check"></i> Setujui</button>
            </div>                            
        </div>

    </div>
</div>