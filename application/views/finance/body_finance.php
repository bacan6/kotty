<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-money"></i> Hutang PO</h3> 
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="col-lg-3" style="margin-bottom:10px">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                        <select class="select2" id="supplier">
                            <option value="">--Semua Supplier--</option>
                            <?php
                            foreach($supplier as $sp){
                            ?>
                            <option value="<?php echo $sp->id_supplier; ?>"><?php echo $sp->supplier; ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3" style="margin-bottom:10px">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-truck"></i></span>
                        <select class="select2" id="store">
                            <option value="">--Semua Cabang--</option>
                            <?php
                            foreach($store as $sp){
                            ?>
                            <option value="<?php echo $sp->id_store; ?>"><?php echo $sp->store; ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-3" style="margin-bottom:10px">
                <div class="form-group">
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-plug"></i></span>
                    <select class="select2" id="status">
                        <option value="">--Semua Status--</option>
                        <option value="NO">Belum Terbayar</option>
                        <option value="1">Terbayar</option>
                        <option value="2">Selesai</option>
                    </select>
                </div>
            
            </div>
            </div>
            <div class="col-lg-3" >
                    <div class="form-group">
                        <button class="btn btn-info" id="viewReport" style="margin-top:2px">Submit</button>
                    </div>
            </div>
        </div>
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12" style="padding: 20px;">
                        <!-- <a href="<?php echo base_url('finance/analisa_umur_hutang'); ?>">Analisa Umur Hutang</a> -->
                        <br>
                        <table class="table table-bordered" id="datatable" style="font-size: 12px;">
                            <thead>
                                <tr style="font-weight: bold;">  
                                   <td width="5%">No</td> 
                                   <td width="10%">No PO</td>
                                   <td>Supplier</td>
                                   <td>Tanggal PO</td>
                                   <td>Jatuh Tempo</td>
                                   <td>PIC</td>
                                   <td>Keterangan</td>
                                   <td>Receive</td>
                                   <td width="8%">Status</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
