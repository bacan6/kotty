<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 10px;">
      <div class="col-md-6">
        <div class="page-title"> 
          <h3 class="title">Laporan Produk Promo</h3> 
        </div>
      </div>

      <div class="col-md-6" style="text-align: right;">
          <a class="btn btn-default" onclick="printContent('dataReport')"><i class="fa fa-print"></i> Print</a>
      </div>
    </div>

    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                
              	<div class="row" style="margin-top: 10px;">
              		<div class="col-md-3" style="border-right: solid 1px #ddd;">
			            <div class="form-group">
                          <label>Date Start</label>
                          <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control datepicker" placeholder="Date Start" id="dateStart" readonly>
                          </div>
                        </div>

  			           <div class="form-group">
                            <label>Date End</label>
                            <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                              <input type="text" class="form-control datepicker" placeholder="Date End" id="dateEnd" readonly>
                            </div>
                        </div>

                  <div class="form-group">
                    <label>Store</label>
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-building"></i></span>
                      <select class="select2" id="store">
                      <?php if ($idUser==1 || $idUser==2){?>
                                <option value="">--Semua--</option>
                                  <?php } 
                                  foreach($store as $tk){
                                      if ($idUser!=1 && $idUser!=2){
                                      if ($idStore==$tk->id_store){ ?>
                                ?><option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                                <?php       }
                                  }else{
                              ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                              <?php }
                                  }  ?>
                    </select>
                    </div>
                  </div>
                  <div class="form-group">
                          <label>Tempat</label>

                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-crosshairs"></i></span>
                              <select class="select2" id="tempat">
                                <option value="">--Semua--</option>
                                <?php
                                  foreach($tempat as $tm){
                                ?>
                                <option value="<?php echo $tm->id_stand; ?>"><?php echo $tm->stand; ?></option>
                                <?php } ?>
                              </select>
                            </div>
                        </div>

                  <div class="form-group">
                          <label>Supplier</label>
                          
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              <select class="form-control" id="id_supplier">
                            <option value="">--Semua--</option>
                            <?php
                              foreach($show_supplier->result() as $supp){ ?>
								<option value="<?php echo $supp->id_supplier; ?>">
				                <?php echo $supp->supplier; ?>
                                  </option>
				            <?php } ?>
                          </select>
                            </div>
                        </div>
                        <div class="form-group">
                          <label>Brand</label>
                          
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              <select class="form-control" id="id_brand">
                            <option value="">--Semua--</option>
                            <?php
                              foreach($show_brand->result() as $brand){ ?>
								<option value="<?php echo $brand->id_brand; ?>">
				                <?php echo $brand->brand; ?>
                                  </option>
				            <?php } ?>
                          </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                          <label>Kategori</label>
                          
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                              <select class="form-control" id="kategori">
                            <option value="">--Semua--</option>
                            <?php
                              foreach($kategori as $kt){
                            ?>
                            <option value="<?php echo $kt->id_kategori; ?>"><?php echo $kt->kategori; ?></option>
                            <?php } ?>
                          </select>
                            </div>
                        </div>
                        <!--id=subkategori2-->
                        <div class="form-group" id="subkategori">
                          <input type="hidden" id="subkategori2" value=""/>
                        </div>

                        <!--id=subkategori_3-->
                        <div class="form-group" id="sub_kategori_2">
                          <input type="hidden" id="subkategori_3" value=""/>
                        </div>

                        <div class="form-group" style="">
                        <label>SKU atau Nama Produk</label>
				              		<input type="hidden" id="produk-ajax" style="width: 100%;">
				              	</div>

  				        <div class="form-group">
  				            <button class="btn btn-info" id="viewReport">Submit</button>
  				        </div>
  			    	</div>

              <div class="col-md-12" id="dataReport">
              </div>
            </div>

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

