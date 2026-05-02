<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-cube"></i> Produk</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" style="padding:30px;">
                <div class="row">
                
                    <div class="col-md-12" style="text-align: right;">
                        <?php if ($isAdmin==1){?>
                        <a href="<?php echo base_url('produk/add_produk'); ?>" class="btn btn-success btn-rounded m-b-5"><i class="fa fa-plus"></i> Tambah Produk</a>
                        <a href="<?php echo base_url('produk/exportExcelProduk'); ?>" class="btn btn-primary btn-rounded m-b-5"><i class="fa fa-plus"></i> Export Produk</a>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-rounded m-b-5 dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Update Massal <span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo base_url('produk/massUpdate/kategori'); ?>">Kategori</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/hargaBeli'); ?>">Harga Beli</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/hargaJual'); ?>">Harga Jual</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/minMax'); ?>">Stok MinMax</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/supplier'); ?>">Supplier</a></li>
                                <li><a href="<?php echo base_url('produk/massUpdate/nama_produk'); ?>">Nama Produk</a></li>
                            </ul>
                        </div>
                        <?php }?>
                    </div>
                    <div class="col-md-12">
                        <form action="<?php echo base_url('produk');?>">
                        <div class="col-md-4">
                            <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                <select class="select2" name="toko" onchange="this.form.submit();" required>
                                    <option value="">--Pilih Toko--</option>
                                    <?php
                                    $sel = '';
                                        foreach($store->result() as $tk){
                                            if(isset($_SESSION["toko"])){
                                                $sel = $tk->id_store==$_SESSION['toko']? 
                                            'selected':'';
                                            }
                                        if ($isAdmin==1){ ?>
                                        <option value="<?php echo $tk->id_store; ?>" <?php echo $sel?>><?php echo $tk->store; ?></option>
                                       <?php }else{
                                            if ($id_toko==$tk->id_store){ 
                                                ?>
                                                    <option value="<?php echo $tk->id_store; ?>" <?php echo $sel?>><?php echo $tk->store; ?></option>
                                                <?php
                                            }
                                        }  
                                    ?>
                                    
                                    <?php } ?>
                                </select>
                            </div>		
            			</div>
                        <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <select class="select2" onchange="this.form.submit();" name="id_brand">
                                    <option value="">--Pilih Brand--</option>
                                    <?php
                                    $id = (!isset($_GET['id_brand']))?'':$_GET['id_brand'];
                                        foreach($brand->result() as $br){
                                    $sel = $br->id_brand==$id? 
                                            'selected':'';
                                    ?>
                                    <option value="<?php echo $br->id_brand; ?>" <?php echo $sel?>><?php echo $br->brand; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <select class="select2" onchange="this.form.submit();" name="id_kategori">
                                    <option value="">--Pilih Departemen--</option>
                                    <?php
                                    $id = (!isset($_GET['id_kategori']))?'':$_GET['id_kategori'];
                                        foreach($ap_kategori->result() as $sp){
                                    $sel = $sp->id_kategori==$id? 
                                            'selected':'';
                                    ?>
                                    <option value="<?php echo $sp->id_kategori; ?>" <?php echo $sel?>><?php echo $sp->kategori; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <div class="input-group m-b-5">
                                <span class="input-group-addon"><i class="fa fa-list"></i></span>
                                <select class="select2" name="sub_kategori" onchange="this.form.submit();">
                                    <option value="">--Pilih Kategori--</option>
                                    <?php
                                    $id = (!isset($_GET['sub_kategori']))?'':$_GET['sub_kategori'];
                                    if (isset($ap_kategori_1)){
                                        foreach($ap_kategori_1->result() as $sp){
                                    $sel = $sp->id==$id? 
                                            'selected':'';
                                    ?>
                                    <option value="<?php echo $sp->id; ?>" <?php echo $sel?>><?php echo $sp->kategori_level_1; ?></option>
                                    <?php } 
                                }?>
                                </select>
                            </div>
                            <a href="<?php echo base_url('produk'); ?>?sub_kategori=&id_kategori=" class="btn btn-danger m-b-5"><i class="fa fa-refresh"></i> Reset Filter</a>
                        </div>
                        
                    </form>
                    </div>
                    
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="produkDatatables">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td width="5%">No</td>
                                    <td>SKU</td>
                                    <td>Nama Produk</td>
                                    <td>Satuan</td>
                                    <td>HPP</td>
                                    <td>Harga</td>
                                    <td>Margin</td>
                                    <td>Departemen</td>
                                    <td>Status</td>
						
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

