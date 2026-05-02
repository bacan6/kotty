<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-hand-o-down"></i> Bahan Masuk</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-9">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-filter"></i> Filter</button>
                        <a href="<?php echo base_url('bahan_masuk/importReceiveItem'); ?>" class="btn btn-success"><i class="fa fa-edit"></i> Import Receiving Item (PDT)</a>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="padding: 30px;" id="contentPO">
                        <table class="table table-bordered" style="font-size:12px;" id="datatable">  
                           <thead>
                               <tr style="font-weight: bold;">
                                    <td width="5%" style="text-align: center;vertical-align: middle;">No</td>
                                    <td style="text-align: center;vertical-align: middle;" width="18%">No PO</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal PO</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal Kirim</td>
                                    <td style="text-align: center;vertical-align: middle;">Supplier</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">PIC</td>
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

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Filter Bahan Masuk</h4>
            </div>
            <div class="form-group">
                    <label>Exclusive PO / PO</label>
                    <select class="select2" id="jenis">
                        <option value="">--Pilih PO/XPO--</option>
                        <option value="PO">PO</option>
                        <option value="XPO">Exclusive PO</option>
                    </select>
                </div>
            <div class="modal-body">                                   
                <div class="form-group">
                    <label>Tanggal PO</label>
                    <input type="text" class="datepicker" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tanggalPO"> 
                </div>

                <div class="form-group">
                    <label>Tanggal Kirim</label>
                    <input type="text" class="datepicker" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="tanggalKirim"> 
                </div>

                <div class="form-group">
                    <label>Diterima Di</label>
                    <select class="select2" id="store">
                        <option value="">--Pilih Toko--</option>
                        <?php 
                            foreach($store as $st){
                        ?>
                            <option value="<?php echo $st->id_store?>"><?php echo $st->store; ?></option>
                        <?php        
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Supplier</label>
                    <select class="select2" id="supplier">
                        <option value="">--Pilih Supplier--</option>
                        <?php 
                            foreach($supplier as $dt){
                        ?>
                            <option value="<?php echo $dt->id_supplier?>"><?php echo $dt->supplier; ?></option>
                        <?php        
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select class="select2" id="status">
                        <option value="">--Pilih Status--</option>
                        <option value="0">Menunggu Approve</option>
                        <option value="1">Diterima</option>
                        <option value="2">Ditolak</option>
                        <option value="3">Selesai</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Problem Faktur</label>
                    <select class="select2" id="status_receive">
                        <option value="">--Pilih Status--</option>
                        <option value="Done">Done</option>
                        <option value="Problem Faktur">Problem Faktur</option>
                    </select>
                </div>

                <div class="form-group">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="filterDatatables">Filter</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


