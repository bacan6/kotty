<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Daftar Waste</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <form method="get" action="<?php echo base_url('waste/daftar_waste')?>">
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                <select class="select2" name="id_brand" id="id_brand">
                                    <option value="">--Brand--</option>
                                    <?php
                                        foreach($brand->result() as $ws){
                                            $sel = $_GET['id_brand']==$ws->id_brand?'selected':'';
                                    ?>
                                    <option value="<?php echo $ws->id_brand; ?>" <?php echo $sel?>><?php echo $ws->brand; ?></option>
                                    <?php } ?>
                            </select>
                            </div>      
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-car"></i></span>
                                <select class="select2" name="id_supplier" id="id_supplier">
                                    <option value="">--Supplier--</option>
                                    <?php
                                        foreach($supplier->result() as $ws){
                                            $sel = $_GET['id_supplier']==$ws->id_supplier?'selected':'';
                                    ?>
                                    <option value="<?php echo $ws->id_supplier; ?>" <?php echo $sel?>><?php echo $ws->supplier; ?></option>
                                    <?php } ?>
                            </select>
                            </div>      
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                <select class="select2" name="status" id="status">
                                    <option value="">--Pembayaran--</option>
                                    <option value="Lunas" <?php echo ($_GET['status']=='Lunas' ? 'selected':'');?>>Lunas</option>
                                    <option value="Belum Lunas" <?php echo ($_GET['status']=='Belum Lunas' ? 'selected':'');?>>Belum Lunas</option>
                            </select>
                            </div>      
                        </div>
                    </div>
                    <div class="col-md-1">
                        <input type="submit" class="btn btn-success" value="Submit" style="margin-top:3px">
                    </div>
                </div>
                </form>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="padding: 20px;">
                        <table class="table table-bordered" style="font-size:12px;" id="datatable">  
                           <thead>
                               <tr style="font-weight: bold;">
                                    <td width="5%" style="text-align: center;vertical-align: middle;">No</td>
                                    <td style="text-align: center;vertical-align: middle;" width="18%">No Waste</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal</td>
                                    <td style="text-align: center;vertical-align: middle;">Keterangan</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">PIC</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Store</td>
                                    <td style="text-align: center;vertical-align: middle;" width="8%">Jenis</td>
                                    <td style="text-align: center;vertical-align: middle;" width="8%">Brand</td>
                                    <td style="text-align: center;vertical-align: middle;" width="8%">Supplier</td>
                                    <td style="text-align: center;vertical-align: middle;" width="8%">Status</td>
                               </tr>

                            </thead>
                        </table>
                   </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
