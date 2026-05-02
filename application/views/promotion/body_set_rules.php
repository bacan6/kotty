<div class="wraper container-fluid">
	<div class="page-title"> 
      <h3 class="title">Rules Discount</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
					<div class="col-md-12" style="text-align: right;">
						<a href="<?php echo base_url('promotion/diskon'); ?>">List Produk <span class="badge"><?php echo $jumlah_produk; ?></span></a> | <a href="<?php echo base_url('promotion/discount_produk'); ?>">Produk Promotion <span class="badge"><?php echo $jumlah_produk_diskon; ?></span></a> 
					</div> 
				</div>

				<div class="row" style="margin-right: 30px;">
					<div class="col-md-12">
						<table width="100%"> 
							<?php
								foreach($infoProduk as $pr){
							?>
							<tr>
								<td width="15%">ID Produk</td>
								<td width="1%">:</td>
								<td><?php echo $pr->id_produk; ?></td>
							</tr>

							<tr>
								<td>Nama Produk</td>
								<td>:</td>
								<td><?php echo $pr->nama_produk; ?></td>
							</tr>

							<tr>
								<td>Harga Beli</td>
								<td>:</td>
								<td><?php echo number_format($pr->harga_beli,'0',',','.'); ?></td>
							</tr>
							
							<tr>
								<td>Harga Jual</td>
								<td>:</td>
								<td><?php echo number_format($pr->harga_jual,'0',',','.'); ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>

				<div class="row" style="margin-top: 30px;">
					<div class="col-md-12" style="padding:30px;">
						<form action="<?php echo base_url('promotion/submit_promotion'); ?>" method="post">
							<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
							<input type="hidden" name="sku" value="<?php echo $_GET['sku']; ?>">
							<br>
							<br>

							<?php 
								echo $this->session->userdata("message");
							?>	
							<table class="table table-bordered" style="font-size:12px;">
								<thead>
									<tr style="font-weight: bold;">
										<td width="24%">Quantity</td>
										<td width="24%">Discount Perproduk</td>
										<td width="24%">Date Start</td>
										<td width="24%">Date End</td>
										<td width="4%"></td>
									</tr>
								</thead>

								<input type="hidden" id="sdf" value=0>
			            		<tbody id="data-input">
			      
			            			<?php
			            				$x = 1;
			            				foreach($data_diskon as $row){
			            			?>
			            			<tr id="row<?php echo $x; ?>">
			            				<td><input type="number" name="qty[]" class="form-control" value="<?php echo $row->qty; ?>"/></td>
										<td><input type="number" name="discount[]" class="form-control" value="<?php echo $row->discount; ?>"></td>
										<td><input type="text" class="form-control datepicker" name="date_start[]" value="<?php echo $row->date_start; ?>"/></td>
										<td><input type="text" class="form-control datepicker" name="date_end[]" value="<?php echo $row->date_end; ?>"/></td>
										<td><a href="<?php echo base_url('promotion/hapus_promo?id='.$row->id.'&sku='.$_GET['sku']); ?>" class="btn btn-danger hapus-baris"  id="<?php echo $x; ?>"><i class="fa fa-trash"></i></a></td>
			            			</tr>
			            			<?php $x++; } ?>
			            		</tbody>


								<tfoot>
									<tr>
										<td colspan="4" align="right" style="font-weight: bold;"></td>
										<td><a id="addForm" class="btn btn-primary">+</a></td>
									</tr>
								</tfoot>
							</table>
						</form>
					</div>
				</div>
			</div>
        </div>
    </div> <!-- /Portlet -->	
</div>


			