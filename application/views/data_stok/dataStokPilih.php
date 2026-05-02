<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Laporan Data Stok</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
		      <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <?php if ($idStore==0){ ?>
                    	<div class="col-md-3" style="text-align: center;">
                    		<a href="<?php echo base_url('data_stok'); ?>">
	                    		<img src="<?php echo base_url('assets/Box-Open-icon.png'); ?>" height="40px"/><br>
                                Data Stok Gudang
                    		</a>
                    	</div>
                        <?php } ?>
                    	<div class="col-md-3" style="text-align: center;">
                    		<a href="<?php echo base_url('data_stok_toko'); ?>">
	                    		<img src="<?php echo base_url('assets/store.png'); ?>" height="40px"/><br>
	                    		Data Stok Toko
                    		</a>
                    	</div>

						<div class="col-md-3" style="text-align: center;">
                    		<a href="<?php echo base_url('data_stok/stokPerkategori'); ?>">
	                    		<img src="<?php echo base_url('assets/store.png'); ?>" height="40px"/><br>
	                    		Data Stok Per Departemen
                    		</a>
                    	</div>

						<div class="col-md-3" style="text-align: center;">
                    		<a href="<?php echo base_url('data_stok_toko_exp'); ?>">
	                    		<img src="<?php echo base_url('assets/store.png'); ?>" height="40px"/><br>
	                    		Data Produk Kadaluarsa
                    		</a>
                    	</div>

                    	<div class="col-md-3" style="text-align: center;">
                    		<a href="<?php echo base_url('dataStokBahanBaku'); ?>">
	                    		<img src="<?php echo base_url('assets/return_book1600.png'); ?>" height="40px"/><br>
	                    		Data Stok Bahan Baku
                    		</a>
                    	</div>
                        <?php if ($idStore==0){ ?>
                    	<div class="col-md-3" style="text-align: center;">
                    		<a href="<?php echo base_url('akumulasiStok'); ?>">
	                    		<img src="<?php echo base_url('assets/1-59-512.png'); ?>" height="40px"/><br>
	                    		Akumulasi Stok
	                    	</a>
                    	</div>
                        <?php } ?>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

