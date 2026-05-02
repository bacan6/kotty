<div class="wraper container-fluid">
	<div class="page-title"> 
      <h3 class="title">Data Stok Kadaluarsa Pertoko</h3> 
    </div>

	<div class="row">
		<div class="col-md-12">
			<div class="portlet"><!-- /primary heading -->
		        
		        <div id="portlet2" class="panel-collapse collapse in">
		            <div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<form action="<?php echo base_url('data_stok_toko_exp/index'); ?>" method="get">
									<div class="form-inline pull-right">
										<div class="form-group" style="width: 300px;">
											<select class="select2" name="idToko" required>
												<option>--Pilih Toko--</option>
												<?php
													foreach($distributor->result() as $dt){
												?>
												<option value="<?php echo $dt->id_store; ?>"><?php echo $dt->store; ?></option>
												<?php } ?>
											</select>
										</div>

										<div class="form-group">
											<input type="submit" class="btn btn-primary" value="Submit"/> 
										</div> 
									</div>
								</form>
							</div>
						</div>	
                    <!--
						<div class="row" style="margin-top: 20px;">
		            		<div class="col-md-6">
		            			<button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-filter"></i> Filter</button>
		            		</div>

		            		<div class="col-md-6" style="text-align: right;" id="buttonExport">
		            			<a href="<?php echo base_url('data_stok_toko/exportExcel?idToko='.$_GET['idToko']); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        		<a href="<?php echo base_url('data_stok_toko/exportPdf?idToko='.$_GET['idToko']); ?>" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export Pdf</a>
		            		</div>
		            	</div>
                    -->
						<div class="row" style="margin-top: 20px;">
							<div class="col-md-12" style="padding: 30px;" id="content">
								<center>
									<h4>Data Stok Kadaluarsa Pertoko</h4>
									<h5><?php echo $nama_distributor; ?></h5>
								</center>

								<table class="table table-bordered" style="font-size: 12px;" id="tableStok">
									<thead>
										<tr style="font-weight: bold;">
											<td width="5%">No</td>
											<td>Tanggal</td>
                                            <td>SKU</td>
											<td>Nama Produk</td>
											<td>Departemen</td>
											<td>HPP</td>
                                            <td>Harga</td>
											<td>Qty</td>
                                            <td>HPPxQty</td>
                                            <td>HargaxQty</td>
										</tr>
									</thead>
								</table>
                                <!--<h3>Total nilai inventori: <u>Rp <?php echo number_format($nilai_toko[0]['nilai'],'0',',','.');?></u></h3>-->
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
                <h4 class="modal-title" id="myModalLabel">Filter Data Stok Pertoko</h4>
            </div>

            <div class="modal-body">                                   
                <div class="form-group">
                    <label>Departemen</label>
                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori" name="kategori">
                        <option value="">--Pilih Departemen--</option>
                        <?php 
                            foreach($show_kategori as $kt){
                        ?>
                        <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group" id="sub_kategori">
                </div>

                <div class="form-group" id="sub_kategori_2">
                	
                </div>

                <div class="form-group">
                	<label>Tempat</label>
                	<select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="stand">
                        <option value="">--Pilih Tempat--</option>
                        <?php 
                            foreach($stand as $st){
                        ?>
                        <option value="<?php echo $st->id_stand; ?>"><?php echo $st->stand; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Supplier</label>
                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="idSupplier" name="idSupplier">
                        <option value="">--Pilih Supplier--</option>
                        <?php 
                            foreach($show_supplier as $sp){
                        ?>
                        <option value="<?php echo $sp->id_supplier; ?>"><?php echo $sp->supplier; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-inline" style="margin-top: 20px;">
                    <div class="form-group" style="width:150px;">
                        <label>Stok</label>
                        <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="stok">
                            <option value="">--Pilih--</option>
                            <option value="=">=</option>
                            <option value=">">></option>
                            <option value=">=">>=</option>
                            <option value="<"><</option>
                            <option value="<="><=</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label></label>
                        <input type="text" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="stokValue"/>
                    </div>

                </div>

                <div class="form-inline" style="margin-top: 20px;">
                    <div class="form-group" style="width:150px;">
                        <label>Harga Beli</label>
                        <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="priceSign">
                            <option value="">--Pilih--</option>
                            <option value="=">=</option>
                            <option value=">">></option>
                            <option value=">=">>=</option>
                            <option value="<"><</option>
                            <option value="<="><=</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label></label>
                        <input type="text" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="priceSignValue"/>
                    </div>
                </div>

                <div class="form-inline" style="margin-top: 20px;">
                    <div class="form-group" style="width:150px;">
                        <label>Harga Jual</label>
                        <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="salePriceSign">
                            <option value="">--Pilih--</option>
                            <option value="=">=</option>
                            <option value=">">></option>
                            <option value=">=">>=</option>
                            <option value="<"><</option>
                            <option value="<="><=</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label></label>
                        <input type="text" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="salePriceValue"/>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="filterDataStok">Filter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

