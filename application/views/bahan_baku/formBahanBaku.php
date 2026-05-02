<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Tambah Bahan Baku</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Bahan Baku / SKU</label> <label id="skuAlert" style="color:red;"></label> 
                            <input type="text" id="sku" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"/>
                        </div>
                    </div>

            		<div class="col-md-6">
            			<div class="form-group">
            				<label>Nama Bahan Baku</label> <label id="skuAlert" style="color:red;"></label> 
						    <input type="text" id="namaBahan" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"/>
						</div>
            		</div>

            		<div class="col-md-6">
            			<div class="form-group">
            				<label>Harga Satuan</label> <label id="skuAlert" style="color:red;"></label>
						    <input type="text" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="harga"/>
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
                                <option value="<?php echo $st->satuan; ?>"><?php echo $st->satuan; ?></option>
                                <?php } ?>
                            </select>
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
						        <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
						        <?php } ?>
						    </select>
						</div>
            		</div>
            	</div> 

            	<div class="row">
            		<div class="col-md-12">
            			<button class="btn btn-primary" id="simpanBahanBaku"><i class="fa fa-save"></i> Simpan</button>
            		</div>
            	</div>              
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
