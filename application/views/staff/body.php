<div class="wraper container-fluid">
    <div class="row">
        <div class="col-md-12" style="text-align: right;">
            <a class="btn btn-default" id="hari">Hari</a> <a class="btn btn-default" id="bulan">Bulan</a> <a class="btn btn-default" id="tahun">Tahun</a>
        </div>

        <div class="col-md-12" style="margin-top: 10px;">
            <table width="100%">
                <tr>
                    <td align="right" id="filter"></td>
                </tr>
            </table>
        </div>
        <?php //if($idUser>1){ ?>
            <form method="GET" action=?>
        <div class="col-md-12" align="right" style="margin-top: 10px;">
            
                          <div class="input-group">
                              <select class="select2" name="id_toko" onchange="this.form.submit();">
                                  <?php 
                                  if ($isAdmin==1){?>
                                <option value="">--Semua--</option>
                                  <?php }?>
                                <?php
                                  foreach($toko as $tk){
                                      ?>
                                <option value="<?php echo $tk->id_store; ?>" <?php if($tk->id_store==$_SESSION['id_toko']) {echo "selected";}?>><?php echo $tk->store; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>
            </form>
            <?php //} ?>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-pink">
                <i class="fa fa-dollar"></i> 
                <h2 class="m-0 counter text-white" id="sales"></h2>
                <div class="text-white">Total Penjualan</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-shopping-cart"></i> 
                <h2 class="m-0 counter text-white" id="basketSize"></h2>
                <div class="text-white">Basket Size</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-warning">
                <i class="fa fa-send-o"></i> 
                <h2 class="m-0 counter text-white" id="transaksi"></h2>
                <div class="text-white">Transaksi</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-success">
                <i class="fa fa-barcode"></i> 
                <h2 class="m-0 counter text-white" id="totalItem"></h2>
                <div class="text-white">Total Item</div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-area-chart"></i> 
                <h2 class="m-0 counter text-white" id="margin"></h2>
                <div class="text-white">Margin</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-cubes"></i> 
                <h2 class="m-0 counter text-white" id="totalInv"></h2>
                <div class="text-white">Inventori</div>
            </div>
        </div>
        
    </div>
  
    <div class="row">
        <div class="col-lg-12">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Perjam
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerHour">

                        </div>
                    </div>
                </div> <!-- /Portlet -->

                </div>
            </div> <!-- /Portlet -->            
 
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Perkasir
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerKasir">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->            
        </div>
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Perkategori
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerkategori">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->            
        </div>
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Brand 1-10
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerBrand1">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->            
        </div>
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Brand 11-20
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerBrand2">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->            
        </div>

        
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Per Subkategori (Top 20)
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="salesPerSubkategori">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->
        </div>
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        10 Produk Paling Laku
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="fastMoving">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->            
        </div>
        
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Top 20 Produk Per Kategori
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="topProdukByKategoriWidget">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->
        </div>
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Top 20 Produk Per Subkategori
                    </h3>

                    <div class="portlet-widgets">
                        <a href="javascript:;" data-toggle="reload"><i class="ion-refresh"></i></a>
                        <span class="divider"></span>
                        <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
                        <span class="divider"></span>
                        <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="topProdukBySubkategoriWidget">

                        </div>
                    </div>
                </div>
            </div> <!-- /Portlet -->
        </div>

        
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Lini Masa
                    </h3>

                    <div class="portlet-widgets">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-success" id="hariLiniMasa">Hari</a> <a class="btn btn-success" id="bulanLiniMasa">Bulan</a> <a class="btn btn-success" id="tahunLiniMasa">Tahun</a>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 5px;">
                            <div class="col-md-12" style="text-align: right;" id="dateRange">
                            </div>
                        </div>
                        
                    </div>
                    <div class="clearfix"></div>
                    <div id="portlet2" class="panel-collapse collapse in">
                        <div class="portlet-body" id="graph">

                        </div>
                    </div>
                </div> <!-- /Portlet -->

                </div>
            </div> <!-- /Portlet --> 
    </div>
</div>