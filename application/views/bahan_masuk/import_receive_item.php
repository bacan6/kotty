<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Import Receiving Item (PDT)</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row" style="padding: 10px;">
                            <div class="col-md-12" align='right' style="margin-bottom:20px;">
                                <a href="<?php echo base_url('bahan_masuk'); ?>" class="btn btn-info"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Penerimaan Barang</a>
                                <a href="#" style="display:none" id="linkTutup" onclick="receiveMe();" class="btn btn-info"> Lanjutkan Penerimaan Barang <i class="fa fa-arrow-right"></i></a>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nomor Purchase Order</label>
                                    <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" name="no_po" id="no_po" class="form-control" onchange="javascript:bukaLink();" required>
                                        <option value="">--Pilih Nomor PO--</option>
                                        <?php 
                                            foreach($po->result_array() as $p){
                                        ?>
                                        <option value="<?php echo $p['no_po']; ?>"><?php echo $p['no_po'].' - '.$p['supplier']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div style="border:solid 1px #ddd;min-height: 100px;border-radius: 5px;vertical-align: middle;padding: 5px;">
                                    <img src="<?php echo base_url('assets/iconexcel.ico'); ?>" height="90px"/>
                                    <a style="font-weight: bold;font-size: 12px;color: orange;" href="<?php echo base_url('bahan_masuk/templateReceiveItem'); ?>">Download Template</a> <br>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <form action="/file-upload" class="dropzone" id="dropzone">
                              <div class="fallback">
                                <input name="file" type="file" multiple />
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>


