<div class="wraper container-fluid">
	<div class="page-title"> 
      <h3 class="title">Daftar Produk</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
					<div class="col-md-12" style="text-align: right;">
						<a href="<?php echo base_url('promotion/diskon'); ?>">List Produk <span class="badge"><?php echo $jumlah_produk; ?></span></a> | <a href="<?php echo base_url('promotion/discount_produk'); ?>">Produk Promotion <span class="badge"><?php echo $jumlah_produk_diskon; ?></span></a> 
					</div> 
				</div>

				<div class="row" style="margin-top: 30px;">
					<div class="col-md-12" style="padding:30px;">
						<?php 
							echo $this->session->userdata("message");
						?>	
						<table class="table table-bordered" style="font-size:12px;" id="myTable">
							<thead>
								<tr style="font-weight: bold;">
									<td width="5%">No</td>
									<td width="10%">SKU</td>
									<td>Nama Produk</td>
									<td width="10%">Kategori</td>
									<td width="20%">HPP</td>
									<td width="20%">Harga Jual</td>
									<td width="10%"></td>
								</tr>
							</thead>
						</table>
						</div>
				</div>
			</div>
        </div>
    </div> <!-- /Portlet -->	
</div>


			