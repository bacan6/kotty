<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title"><i class="fa fa-book"></i> Stock Opname Toko</h3>
    </div>

    <div class="portlet">
        <!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <legend>Download Format SO</legend>

                        <form action="<?php echo base_url('stock_opname_toko/download_format_so'); ?>" method="post">
                            <div class="form-group">
                                <label>Store</label>
                                <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" name="id_store" required>
                                    <option value="">--Pilih Store--</option>

                                    <?php
                                        foreach($toko as $tk){
                                        if ($idStore>0){
                                            if ($idStore==$tk->id_store){
                                                ?>
                                        <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                    <?php }
                                        }else{
                                    ?>
                                    <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                    <?php } // end if
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tempat</label>
                                <select style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" name="tempat">
                                    <option value="">--Pilih Tempat--</option>

                                    <?php
                                        foreach($stand as $st){
                                    ?>
                                    <option value="<?php echo $st->id_stand; ?>"><?php echo $st->stand; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Kategori</label> <label id="kategoriAlert" style="color:red;"></label>
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
                                <input type="submit" class="btn btn-primary" value="Download" />
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <legend>Upload Hasil SO</legend>
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
