<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Mass Update Supplier</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row" style="padding: 10px;">
                            <div class="col-md-6">
                                <div style="border:solid 1px #ddd;min-height: 100px;border-radius: 5px;vertical-align: middle;padding: 5px;">
                                    <img src="<?php echo base_url('assets/iconexcel.ico'); ?>" height="90px"/>
                                    <a style="font-weight: bold;font-size: 12px;color: orange;" href="<?php echo base_url('produk/templateUpdateSupplier'); ?>">Download Template</a> <br>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div style="border:solid 1px #ddd;min-height: 100px;border-radius: 5px;vertical-align: middle;padding: 5px;">
                                    <img src="<?php echo base_url('assets/pdficon.png'); ?>" height="90px"/>
                                    <a style="font-weight: bold;font-size: 12px;color: orange;" href="<?php echo base_url('produk/supplierProduk'); ?>">Supplier List</a>
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

