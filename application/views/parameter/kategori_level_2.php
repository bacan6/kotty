<div class="wraper container-fluid">
    <div class="portlet" col-md-6><!-- /primary heading -->
        <div class="portlet-heading">
            <h3 class="portlet-title text-dark text-uppercase">
                Kategori
            </h3>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12 table-responsive">
            			<form action="<?php echo base_url('parameter/kategori_level_2_submit'); ?>" method="post">
                            <div class="form-inline">
                                <div class="form-group">
                                    <select class="form-control" name="id_level_1">
                                        <option value="">--Pilih Departemen--</option>

                                        <?php
                                            foreach($kategori as $row){
                                        ?>
                                        <option value="<?php echo $row->id_kategori; ?>"><?php echo $row->kategori; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                					<input type="text" class="form-control" name="IDkategori" placeholder="ID Kategori" />
                				</div>
                                <div class="form-group">
                					<input type="text" class="form-control" name="kategori"/>
                				</div>

                				<div class="form-group">
                					<input type="submit" class="btn btn-primary" value="Submit"/>
                				</div>
                            </div>
            			</form>           
            		</div>
            	</div>               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

