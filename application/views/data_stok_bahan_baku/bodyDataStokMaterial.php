<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Data Stok Bahan Baku</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-filter"></i> Filter</button>
                    </div>

                    <div class="col-md-6" style="text-align: right;" id="buttonExport">
                        <a href="<?php echo base_url('dataStokBahanBaku/exportExcelBahanBaku'); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
                        <a href="<?php echo base_url('dataStokBahanBaku/exportPdf'); ?>" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export Pdf</a>
                    </div>
                </div>

            	<div class="row" style="margin-top: 20px;">
            		<div class="col-md-12" style="padding: 20px;" id="content">
            			<table class="table table-bordered" style="font-size: 12px;" id="dataStok">
                            <thead>
                				<tr style="font-weight: bold;">
                                    <td width="5%">No</td>
                					<td width="15%">Kode Bahan</td>
                					<td>Nama Bahan Baku</td>
                					<td width="15%">Kategori</td>
                                    <td width="10%">Stok Akhir</td>
                					<td width="10%">Harga Beli</td>
                				</tr>
                            </thead>
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
                <h4 class="modal-title" id="myModalLabel">Filter Data Stok</h4>
            </div>

            <div class="modal-body">                                   
                <div class="form-group">
                    <label>Kategori</label>
                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori">
                        <option value="">--Pilih Kategori--</option>
                        <?php 
                            foreach($show_kategori as $kt){
                        ?>
                        <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
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
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="filterDataStok">Filter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



    