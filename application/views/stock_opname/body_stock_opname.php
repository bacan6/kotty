<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-book"></i> Stock Opname Gudang</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <div class="row">
                <div class="col-md-6">
                    <b><u>Download Format SO</u></b>
                    <br>
                    <br>
                    <form action="<?php echo base_url('stock_opname/exportExcelFG'); ?>" method="post">
                        <div class="form-group">
                            <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori" name="kategori">
                                <option value="">--Pilih Kategori--</option>
                                    <?php 
                                        foreach($show_kategori as $kt){
                                    ?>
                                <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                                    <?php } ?>
                            </select>
                        </div>

                        <div class="form-group" id="sub_kategori">
                        </div>

                        <div class="form-group" id="sub_kategori_2">
                        </div>

                        <div class="form-group" style="text-align: right;">
                            <input type="submit" class="btn btn-primary" value="Download"/>
                        </div>
                    </form>
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

