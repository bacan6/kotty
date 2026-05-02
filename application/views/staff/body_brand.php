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
  
    <div class="row">
        <div class="col-lg-12">
                      
 
        <div class="col-lg-6">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        Penjualan Top 20 Brand 
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
        

        
        <div class="col-lg-12">
            <div class="portlet"><!-- /primary heading -->
                <div class="portlet-heading">
                    <h3 class="portlet-title text-dark text-uppercase">
                        20 Produk Paling Laku
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

</div>