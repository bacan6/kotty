<div class="wraper container-fluid">
    <div class="page-title"> 
    	<h3 class="title">Invoice Retur</h3> 
	</div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="clearfix">
                            <?php foreach($receipt->result() as $rc){ ?>
                            <div class="pull-left">
                                <h4><img src="<?php echo base_url('assets/'.$rc->image); ?>" alt="velonic" height="30px"></h4>
                                <h5><?php echo $rc->alamat; ?></h5>
                                <h5><?php echo $rc->kontak; ?></h5>
                            </div>
                            <?php } ?>
                            <!-- BEGIN INVOICE DETAIL-->
                            <div class="pull-right">
                                <h4>Retur No# <br>
                                    <strong><?php echo $_GET['no_retur']; ?></strong>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
