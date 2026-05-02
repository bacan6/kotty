<div class="wraper container-fluid">
	<div class="page-title"> 
      <h3 class="title">Setting Poin</h3> 
    </div>

	<div class="row">
		<div class="col-md-12">
			<div class="portlet"><!-- /primary heading -->
		        <div id="portlet2" class="panel-collapse collapse in">
		            <div class="portlet-body">
			            	<div class="row">
			            		<div class="col-md-12">
			            			<?php 
			            				echo $this->session->userdata("message");
			            			?>
			            		</div>
			            	</div>

			            	<div class="row">
			            		<form action="<?php echo base_url('promotion/submit_setting_poin'); ?>" method="post">
				            		<?php
				            			foreach($get_poin->result() as $row){
				            		?>
				            		<div class="col-md-6" style="border-right:solid 1px #ddd;">
				            			<h4>Parameter Mendapatkan Poin</h4>
						            	<div class="form-group">
						            		<label>Poin</label>
						            		<input type="text" class="form-control" name="poin_pembelian" value="<?php echo $row->poin_pembelian; ?>"/>
						            	</div>

						            	<div class="form-group">
						            		<label>Nilai Pembelian</label>
						            		<input type="text" class="form-control" name="nilai_pembelian" value="<?php echo $row->nilai_pembelian; ?>"/>
						            	</div>
					            	</div>

					            	<div class="col-md-6">
					            		<h4>Parameter Penukaran Poin</h4>
						            	<div class="form-group">
						            		<label>Poin</label>
						            		<input type="text" class="form-control" name="poin_pengeluaran" value="<?php echo $row->poin_pengeluaran; ?>"/>
						            	</div>

						            	<div class="form-group">
						            		<label>Nilai Reedeem</label>
						            		<input type="text" class="form-control" name="nilai_pengeluaran" value="<?php echo $row->nilai_pengeluaran; ?>"/>
						            	</div>
					            	</div>

					            	<div class="col-md-12" style="text-align: right;">
					            		<input type="submit" class="btn btn-primary" value="Submit">
					            	</div>
					            	<?php } ?>
				            	</form>
			            	</div>
		            	</div>
		            </div>
		        </div>
		    </div> <!-- /Portlet -->    
		</div>
	</div>
</div>