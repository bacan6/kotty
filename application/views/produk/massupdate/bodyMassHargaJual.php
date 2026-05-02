<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Mass Update Harga Jual</h3> 
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
                                    <a style="font-weight: bold;font-size: 12px;color: orange;" data-toggle="modal" href='#myModal'>Download Template</a> <br>
                                </div>
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

<!-- sample modal content -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo base_url('produk/templateUpdateHargaJual'); ?>" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Download Template Update Harga Jual</h4>
            </div>

            <div class="modal-body">                                   
                <div class="form-group">
                    <label>Toko</label>
                    <select class="select2" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" name="toko" required>
                        <option value="">--Pilih Toko--</option>
                        <?php 
                            foreach($toko as $tk){
                        ?>
                        <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select class="select2" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="kategori" name="kategori">
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

                <div class="form-group">
                    <label>Tempat</label>
                    <select class="select2" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" name="stand">
                        <option value="">--Pilih Tempat--</option>
                        <?php 
                            foreach($stand as $st){
                        ?>
                        <option value="<?php echo $st->id_stand; ?>"><?php echo $st->stand; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Brand</label>
                    <select class="select2" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" name="brand">
                        <option value="">--Pilih Brand--</option>
                        <?php 
                            foreach($brand as $br){
                        ?>
                        <option value="<?php echo $br->id_brand; ?>"><?php echo $br->brand; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit"class="btn btn-primary">Download</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


