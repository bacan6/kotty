<div class="wraper container-fluid">
        <div class="page-title"> 
        <h3 class="title"><i class="fa fa-reply"></i> Retur To Warehouse</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <li>Pilih Store</li>
                        <br>
                        <ul class="nav nav-tabs"> 
                            <?php
                                foreach($getStore as $row){
                            ?>
                            <li class=""> 
                                <a class="selectStore" id="<?php echo $row->id_store; ?>" data-toggle="tab" aria-expanded="false"> 
                                    <span><?php echo $row->store; ?></span> 
                                </a> 
                            </li>  
                            <?php } ?>
                        </ul> 

                        <div class="tab-content"> 
                            <div class="tab-pane active" id="v-home"> 
                                
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
