<div class="wraper container-fluid">
    <div class="row">
        <!-- <div class="col-md-12" style="text-align: right;">
            <a class="btn btn-default" id="hari">Hari</a> <a class="btn btn-default" id="bulan">Bulan</a> <a class="btn btn-default" id="tahun">Tahun</a>
        </div>

        <div class="col-md-12" style="margin-top: 10px;">
            <table width="100%">
                <tr>
                    <td align="right" id="filter"></td>
                </tr>
            </table>
        </div> -->
            
        <div class="col-md-12" align="right" style="margin-top: 10px;">
             <form method="GET" action=?>
                          <div class="input-group">
                              <select class="select2" name="id_toko" onchange="this.form.submit();">
                                  <?php 
                                  if ($isAdmin==1){?>
                                <option value="">--Semua--</option>
                                  <?php }?>
                                <?php
                                  foreach($toko as $tk){
                                      if ($isAdmin!=1){
                                      if ($idStore==$tk->id_store){ ?>
                                ?><option value="<?php echo $tk->id_store; ?>" <?php if($tk->id_store==$_SESSION['id_toko']) {echo "selected";}?>><?php echo $tk->store; ?></option>
                                <?php       }
                                  }else{
                              ?>
                                <option value="<?php echo $tk->id_store; ?>" <?php if($tk->id_store==$_SESSION['id_toko']) {echo "selected";}?>><?php echo $tk->store; ?></option>
                              <?php }
                                  }  ?>
                              </select>
                              <input type="submit" class="btn btn-success" value="Go">
                            </div>
                        </div>
            </form>
    </div>

    <div class="row" style="margin-top: 15px;">
        <!-- <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="prepo_a">
            <div class="widget-panel widget-style-1 bg-pink">
                <i class="fa fa-quote-right"></i> 
                <h2 class="m-0 counter text-white" id="prepo"></h2>
                <div class="text-white">Pre-PO Belum SO</div>
            </div>
            </a>
        </div> -->

        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="waitingmd_a">
            <div class="widget-panel widget-style-1 bg-warning">
                <i class="fa fa-calculator"></i> 
                <h2 class="m-0 counter text-white" id="waitingmd"></h2>
                <div class="text-white">Menunggu Approval MD</div>
            </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="waitingsupplier_a">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-send"></i> 
                <h2 class="m-0 counter text-white" id="waitingsupplier"></h2>
                <div class="text-white">Menunggu Konfirmasi Supplier</div>
            </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="waitingdelivery_a">
            <div class="widget-panel widget-style-1 bg-success">
                <i class="fa fa-bus"></i> 
                <h2 class="m-0 counter text-white" id="waitingdelivery"></h2>
                <div class="text-white">PO Belum Diantar</div>
            </div>
            </a>
        </div>
        
        <!-- <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="waitingreceive_a">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-truck"></i> 
                <h2 class="m-0 counter text-white" id="waitingreceive"></h2>
                <div class="text-white">Sudah diantar, Belum Receive</div>
            </div>
            </a>
        </div> -->
        
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="transferblm_a">
            <div class="widget-panel widget-style-1 bg-danger">
                <i class="fa fa-phone"></i> 
                <h2 class="m-0 counter text-white" id="transferblm"></h2>
                <div class="text-white">Transfer Stok Belum Diterima</div>
            </div>
            </a>
        </div>
        
    </div>
   
    <div class="row" style="margin-top: 15px;">
        <h3 class="portlet-title text-dark" style="font-size:28px;margin-left:20px;font-weight:bold">Things Today</h3>
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="todaypo_a">
            <div class="widget-panel widget-style-1 bg-warning">
                <i class="fa fa-pencil"></i> 
                <h2 class="m-0 counter text-white" id="todaypo"></h2>
                <div class="text-white">PO Hari ini</div>
            </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="todayreceive_a">
            <div class="widget-panel widget-style-1 bg-success">
                <i class="fa fa-check"></i> 
                <h2 class="m-0 counter text-white" id="todayreceive"></h2>
                <div class="text-white">Receive Hari ini</div>
            </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="todaytransfer_a">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-truck"></i> 
                <h2 class="m-0 counter text-white" id="todaytransfer"></h2>
                <div class="text-white">Transfer Stok Dikirim Hari ini</div>
            </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="todaytransferrec_a">
            <div class="widget-panel widget-style-1 bg-info">
                <i class="fa fa-truck"></i> 
                <h2 class="m-0 counter text-white" id="todaytransferrec"></h2>
                <div class="text-white">Transfer Stok Diterima Hari ini</div>
            </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="todayretur_a">
            <div class="widget-panel widget-style-1 bg-danger">
                <i class="fa fa-level-down"></i> 
                <h2 class="m-0 counter text-white" id="todayretur"></h2>
                <div class="text-white">Retur PO Hari ini</div>
            </div>
            </a>
        </div>
        <!-- <div class="col-md-3 col-sm-6">
            <div class="widget-panel widget-style-1 bg-warning">
                <i class="fa fa-cubes"></i> 
                <h2 class="m-0 counter text-white" id="omseth1"></h2>
                <div class="text-white">Budget Belanja</div>
            </div>
        </div> -->
        <div class="col-md-3 col-sm-6">
            <a href="#detailModal" data-toggle="modal" id="belanja_a">
            <div class="widget-panel widget-style-1 bg-success">
                <i class="fa fa-cubes"></i> 
                <h2 class="m-0 counter text-white" id="belanja"></h2>
                <div class="text-white">Belanja Hari ini</div>
            </div>
            </a>
        </div>
    </div>
    
</div>
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">...</h4>
      </div>
      <div class="modal-body" id="myModalBody">
        Loading...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>