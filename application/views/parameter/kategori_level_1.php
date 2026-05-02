<div class="wraper container-fluid">
    <div class="portlet" col-md-6><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                Kategori Level 1
            </h3>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12 table-responsive">
            			<?php
            				if(empty($_GET['id'])){
            			?>

            			<form action="<?php echo base_url('parameter/kategori_level_1_submit'); ?>" method="post">
            				<div class="form-group" style="width:400px;">
            					<label>Kategori</label>
            					<input type="text" class="form-control" name="kategori"/>
            				</div>

            				<div class="form-group" style="width:400px;text-align: right;">
            					<input type="submit" class="btn btn-primary" value="Submit"/>
            				</div>
            			</form>
            			<?php } else { ?>

            			<form action="<?php echo base_url('parameter/kategori_level_1_edit'); ?>" method="post">
            				
            				<?php 
            					$id_kategori = $_GET['id'];

            					$data_kategori = $this->model1->data_kategori($id_kategori);
            				?>

            				<div class="form-group" style="width:400px;">
            					<label>Kategori</label>
            					<input type="hidden" name="id_kategori" value="<?php echo $_GET['id']; ?>"/>
            					<input type="text" class="form-control" name="kategori" value="<?php echo $data_kategori; ?>"/>
            				</div>

            				<div class="form-group" style="width:400px;text-align: right;">
            					<input type="submit" class="btn btn-primary" value="Submit"/>
            				</div>
            			</form>

            			<?php } ?>
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

