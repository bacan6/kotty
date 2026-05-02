<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Edit Bahan Baku</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama Bahan Baku</label> <label id="skuAlert" style="color:red;"></label> 
                            <input type="text" value="<?php echo $bahanBaku->nama_bahan; ?>" id="namaBahan" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Harga Satuan</label> <label id="skuAlert" style="color:red;"></label>
                            <input type="text" value="<?php echo $bahanBaku->harga; ?>" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="harga"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori</label> <label id="skuAlert" style="color:red;"></label>
                            <select id="kategori" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                <option value="">--Pilih Kategori--</option>
                                <?php
                                    foreach($get_kategori->result() as $kt){
                                ?>
                                <option value="<?php echo $kt->id_kategori; ?>" <?php if($kt->id_kategori==$bahanBaku->id_kategori){echo "selected";} ?>><?php echo $kt->kategori; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Satuan</label> <label id="skuAlert" style="color:red;"></label>
                            <select id="satuan" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                                <option value="">--Pilih Satuan--</option>
                                <?php
                                foreach($get_satuan->result() as $st){
                                ?>
                                <option value="<?php echo $st->satuan; ?>" <?php if($st->satuan==$bahanBaku->satuan){echo "selected";} ?>><?php echo $st->satuan; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Status</label> <label id="skuAlert" style="color:red;"></label>
                        <select id="status" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                            <option value="1" <?php if($bahanBaku->status==1){echo "selected";} ?>>Aktif</option>
                            <option value="0" <?php if($bahanBaku->status==0){echo "selected";} ?>>Non-Aktif</option>
                        </select>
                        <input type="hidden" id="sku" value="<?php echo $bahanBaku->sku; ?>"/>
                    </div>
                </div> 

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <button class="btn btn-primary" id="editBahanBaku"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>              
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
