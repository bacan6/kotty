<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Akumulasi Stok</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
          <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                  <form action="<?php echo base_url('akumulasiStok/index'); ?>" method="get">
                    <div class="col-md-12">
                      <div class="input-group m-t-10 pull-right" style="width: 300px;">
                        <input type="text" name="q" class="form-control" placeholder="Pencarian">
                        <span class="input-group-btn">
                          <button type="submit" class="btn btn-effect-ripple btn-primary">Submit</button>
                        </span>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="row" style="margin-top: 20px;">
                  <div class="col-md-12">
                    <table class="table table-bordered">
                      <tr style="font-weight: bold;">
                        <td width="1%">No</td>
                        <td width="10%">SKU</td>
                        <td width="50%">Produk</td>
                        <td style="text-align: right;">Stok</td>
                        <td style="text-align: right;">(Stok) x (HPP)</td>
                      </tr>

                      <?php
                        $uri = $this->uri->segment(3);

                        if(!empty($uri)){
                          $i = $uri+1;
                        } else {
                          $i = 1;
                        }

                        if($dataStok->num_rows() > 0){

                        foreach($dataStok->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->id_produk; ?></td>
                        <td><?php echo $row->nama_produk; ?></td>
                        <td style="text-align: right;">
                          <a class="collapseStok" id="<?php echo str_replace(" ", "-",$row->id_produk); ?>">
                            <?php echo number_format($row->stok+$row->stokGudang,'0',',','.'); ?>    
                          </a>
                        </td>
                        <td style="text-align: right;">
                          <a class="collapseStok" id="<?php echo str_replace(" ", "-",$row->id_produk); ?>">
                            <?php echo number_format(($row->stok+$row->stokGudang)*$row->hpp,'0',',','.'); ?>    
                          </a>
                        </td>
                      </tr>

                      <tr id="viewAllStok<?php echo str_replace(" ", "-",$row->id_produk); ?>">
                      </tr>
                      <?php $i++; } } else { ?>

                      <tr>
                        <td colspan="3">Not Found</td>
                      </tr>

                      <?php } ?>

                      <?php
                        if(empty($_GET['q'])){
                      ?>
                      <tr>
                        <td colspan="4" style="text-align: center;"><?php echo $paging; ?></td>
                      </tr>
                      <?php } ?>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

