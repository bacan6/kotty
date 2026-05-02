
<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Laporan Penjualan Akumulasi Produk</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              	<div class="row">
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
                            <label>Time Start</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                <input type="text" class="form-control timepicker" id="timeStart" value="00:00" step="60">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Time End</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                <input type="text" class="form-control timepicker" id="timeEnd" value="23:59" step="60">
                            </div>
                        </div>

                        <div class="form-group">
                          <label>Toko</label>
                          <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                              <select class="select2" id="toko">
                                  
                                <?php
                                  foreach($toko as $tk){
                                    if($idUser==49){ ?>
                                    <option value="5">Shopee Medan</option>
                                    <?php }
                              ?>
                                <option value="<?php echo $tk->id_store; ?>"><?php echo $tk->store; ?></option>
                              <?php }
                                    ?>
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
                          <label>Nama Customer</label>
                           <div class="input-group">
                              <span class="input-group-addon"><i class="fa fa-users"></i></span>
                              <select class="select2" id="customer-form">
                               <option value="">Semua</option>
                               <?php
                                foreach($customer as $cs){
                               ?> 
                               <option value="<?php echo $cs->id_customer; ?>"><?php echo "(".$cs->id_customer.") ".$cs->nama; ?></option>
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
                              <select class="select2" id="id_brand" multiple="multiple">
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

				              	<div class="form-group">
				              		<button class="btn btn-info" id="viewReport">Submit</button>
				              	</div>
			              	</div>

                      <div class="col-md-9" id="dataReport">
                      </div>
              	</div>

              	<div class="row" style="margin-top: 30px;">
              		<div class="col-md-12" id="dataReport">
              		</div>
              	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

