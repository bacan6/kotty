<div class="wraper container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="portlet"><!-- /primary heading -->
		        <div class="portlet-heading">
		            <h3 class="portlet-title text-dark text-uppercase">
		                Data Stok Toko
		            </h3>
		            
		            <div class="portlet-widgets">
		                <a data-toggle="collapse" data-parent="#accordion1" href="#portlet2"><i class="ion-minus-round"></i></a>
		                <span class="divider"></span>
		                <a href="#" data-toggle="remove"><i class="ion-close-round"></i></a>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		        
		        <div id="portlet2" class="panel-collapse collapse in">
		            <div class="portlet-body">
						<div class="row">
							<div class="col-md-12">
								<form action="<?php echo base_url('data_stok_toko/index'); ?>" method="get">
									<div class="form-inline pull-right">
										<div class="form-group" style="width: 300px;">
											<select class="select2" name="idToko" required>
												<option>--Pilih Toko--</option>
												<?php
													foreach($distributor->result() as $dt){
                                                        //if ($idStore>0){
                                                        //    if ($idStore==$dt->id_store){
                                                                ?>
                                                    <!--<option value="<?php echo $dt->id_store; ?>"><?php echo $dt->store; ?></option>-->
                                                <?php
                                                           // }
                                                        //}
                                                //else{
												?>
												<option value="<?php echo $dt->id_store; ?>"><?php echo $dt->store; ?></option>
												<?php   //} // end if 
                                                    } ?>
											</select>
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
	</div>
</div>