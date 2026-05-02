<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-cube"></i> Cek Harga</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" style="padding:30px;">
                <div class="row">
                
                    <div class="col-md-12">
                        <form action="<?php echo base_url('cek_harga');?>" method="post">
                        <div class="col-md-5">
                            <div class="input-group">
                                <input type="text" id="input1-cek-harga" name="query" class="form-control" placeholder="Arahkan Barcode ke Scanner">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-effect-ripple btn-primary"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                            
                        </div>
                        <div class="col-md-7" align=right>
                            <img src="<?php echo base_url('assets/kotty_02.png'); ?>" height="40px"/>
                        </div>

                        
                    </form>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="table" id="produkDatatables">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td>Harga</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>

