<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-trash"></i> Waste</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
            			<a href="<?php echo base_url('waste/daftar_waste'); ?>"><i class="fa fa-book"></i> Daftar Waste</a>
            		</div>
            	</div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <input type="hidden" id="sku" style="width: 100%;" />
                    </div>
                </div> 
                
                
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-trash"></i></span>
                                    <select class="select2" id="idWaste">
                                        <option value="">--Jenis Waste--</option>
                                        <?php
                                            foreach($keterangan_waste->result() as $ws){
                                        ?>
                                        <option value="<?php echo $ws->id_keterangan; ?>"><?php echo $ws->keterangan; ?></option>
                                        <?php } ?>
                                </select>
                                </div>      
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                    <select class="select2" id="status">
                                        <option value="Belum Lunas">Belum Lunas</option>
                                        <option value="Lunas">Lunas</option>
                                </select>
                                </div>      
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                <select class="select2" id="id_brand">
                                    <option value="">--Brand--</option>
                                    <?php
                                        foreach($brand->result() as $ws){
                                    ?>
                                    <option value="<?php echo $ws->id_brand; ?>"><?php echo $ws->brand; ?></option>
                                    <?php } ?>
                            </select>
                            </div>      
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                <select class="select2" id="id_supplier">
                                    <option value="">--Supplier--</option>
                                    <?php
                                        foreach($supplier->result() as $ws){
                                    ?>
                                    <option value="<?php echo $ws->id_supplier; ?>"><?php echo $ws->supplier; ?></option>
                                    <?php } ?>
                            </select>
                            </div>      
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <textarea id="keterangan" class="form-control" placeholder="Keterangan"></textarea>
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
                                        <td width="15%">Jumlah Waste</td>
                                        <td width="15%">Satuan</td>
                                        <td width="5%"></td>
                                    </tr>
                                </thead>

                                <tbody id="data-input">
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">
                                            <input type="submit" class="btn btn-primary" value="Submit" id="waste-click">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                    </div>
                </div>        
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
