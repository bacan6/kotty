<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-user"></i> Transaksi Kasir</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
            		<div class="col-md-12" style="text-align: right;">
	            		<div class="form-inline">
							<div class="form-group" style="width:250px;">
							<select class="select2" id="id_toko">
								<?php
								foreach($store as $st){
									if ($isSuperadmin!=1){
										if ($idStore==$st->id_store){ ?>
								?>
								<option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>
								<?php       }
									}else{
								?>
									<option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>
								<?php }
								}?>
							</select>
							</div>
	                        <div class="form-group">
	                            <input type="text" class="form-control tanggal-filter" placeholder="Date" id="datepicker" name="tanggal" readonly>
	                       	</div>

	                       	<div class="form-group">
	                            <a id="submit-filter-kasir" class="btn btn-primary"> Submit </a>
	                       	</div>
	                    </div>
                	</div>
            	</div>

            	<div class="row" style="margin-top: 40px;" id="list-kasir-trx">

            	</div>      
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

