<div class="wraper container-fluid">
   <div class="row" style="margin-bottom: 10px;">
      <div class="col-md-6">
        <div class="page-title"> 
      <h3 class="title"><i class="fa fa-rocket"></i> Transfer Stok</h3> 
    </div>
      </div>

      <div class="col-md-6" style="text-align: right;">
        <a href="<?php echo base_url('laporan/transferStok'); ?>" class="btn btn-default btn-rounded"><i class="fa fa-book"></i> Laporan Transfer Stok</a>
      </div>
</div>
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
		      <div class="row">
                <div class="col-md-12">
                    Transfer Dari :
                </div>
              </div>

              <div class="row">
                <?php 
                    foreach($store as $st){
                        
                        if ($idStore>0){
                          if ($idStore==$st->id_store){
                ?>
                <div class="col-md-3">
                    <a href="<?php echo base_url('transferStok/formTransfer?idStore='.$st->id_store); ?>">
                        <div class="mini-stat clearfix" style="box-shadow: 1px 1px 1px #ccc;">
                            <span class="mini-stat-icon bg-info"><i class="fa fa-home"></i></span>
                            <div class="mini-stat-info text-right" style="font-size: 15px;vertical-align: middle;">
                                <?php echo $st->store; ?>
                            </div>
                        </div>
                    </a>
                </div>
                <?php      } 
                        }
                    }?>
              </div>
            </div>
            
            <!-- informasi transfer stok-->
            <div class="row">
                <div class="col-lg-12" style="padding-left:30px">
                <h4>Transfer Stok belum diproses:</h4>
                </div>
            </div>
            <div style="padding: 20px;">
            <table class="table" id="dataTable">
                <thead>
                    <tr style="font-weight: bold;">
                        <td width="5%">No</td>
                        <td>No Transfer</td>
                        <td>Dikirim</td>
                        <td width="20%">Transfer Dari</td>
                        <td width="20%">Tujuan Transfer</td>
                        <td>Proses</td>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no=0;
                        foreach($last_transfer->result_array() as $lt){ 
                            $no++;
                            ?>
                            <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $lt['noTransfer']; ?></td>
                            <td><?php echo date_format(date_create($lt['tanggal']),'d M Y H:i'); ?></td>
                            <td><?php echo $this->model1->namaStore($lt['transferFrom']); ?></td>
                            <td><?php echo $this->model1->namaStore($lt['transferTo']); ?></td>
                            <td><a href="<?php echo base_url('transferStok/formReceive?noTransfer='.$lt['noTransfer']); ?>"><span class="label label-primary">Menunggu Approve</span></a></td>
                            </tr>
                       <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

