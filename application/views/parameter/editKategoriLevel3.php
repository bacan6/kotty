<div class="wraper container-fluid">
    <div class="portlet" col-md-6><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                Edit Sub Kategori
            </h3>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12 table-responsive">
            			<form action="<?php echo base_url('parameter/kategoriLevel3EditSQL'); ?>" method="post">
            				<div class="form-group" style="width:400px;">
            					<label>ID SubKategori</label>
            					<input type="hidden" name="id_kategori" value="<?php echo $_GET['id']; ?>"/>
            					<input type="text" class="form-control" name="idBaru" value="<?php echo $_GET['id']; ?>"/>
            				</div>
							<div class="form-group" style="width:400px;">
            					<label>Kategori</label>
            					<input type="text" class="form-control" name="kategori" value="<?php echo $namaKategori; ?>"/>
            				</div>

            				<div class="form-group" style="width:400px;text-align: right;">
            					<input type="submit" class="btn btn-primary" value="Submit"/>
            				</div>
            			</form>
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

