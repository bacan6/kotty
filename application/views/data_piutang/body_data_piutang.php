<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title"><i class="fa fa-money"></i> Data Piutang</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">

                        <ul class="nav nav-tabs"> 
                            <li class="active"> 
                                <a class="belumJatuhTempo" data-toggle="tab" aria-expanded="false"> 
                                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                                    <span class="hidden-xs">Belum Jatuh Tempo</span>  <span class="badge bg-success"><?php echo number_format($belumJatuhTempo,'0',',','.'); ?></span>
                                </a> 
                            </li> 
                            <li> 
                                <a class="melebihiTempo" data-toggle="tab" aria-expanded="true"> 
                                    <span class="visible-xs"><i class="fa fa-home"></i></span> 
                                    <span class="hidden-xs">Melebihi Tempo</span>  <span class="badge bg-danger"><?php echo number_format($melebihiTempo,'0',',','.'); ?></span>
                                </a> 
                            </li> 
                            <li class=""> 
                                <a class="lunas" data-toggle="tab" aria-expanded="false"> 
                                    <span class="visible-xs"><i class="fa fa-home"></i></span>  
                                    <span class="hidden-xs">Lunas</span> <span class="badge bg-info"><?php echo number_format($piutangLunas,'0',',','.'); ?></span>
                                </a> 
                            </li> 
                        </ul> 
                        <div class="tab-content"> 
                            
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
