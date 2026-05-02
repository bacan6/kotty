<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Daftar Promo Supplier</h3> 
    </div>
    
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 20px;">
                <form method="get" action="<?php echo base_url('promo_supplier/daftar_po'); ?>">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-inline pull-right">
                        <div class="form-group">
                          <input type="text" placeholder="Date Start" name="dateStart" readonly="" value='<?php echo $date_start;?>' class="form-control datepicker" required>
                        </div>

                        <div class="form-group">
                          <input type="text" placeholder="Date End" name="dateEnd" readonly="" value='<?php echo $date_end;?>' class="form-control datepicker" required>
                        </div>

                        <div class="form-group" style="width:250px;">
                          <select class="select2" name="id_toko">
                            <option value="">- Semua -</option>
                          	<?php
                              foreach($store as $st){
                                $sel = $st->id_store==$id_toko?'selected':'';
                                ?>
                                <option value="<?php echo $st->id_store; ?>" <?php echo $sel?>><?php echo $st->store; ?></option>
                              <?php 
                              }?>
                          </select>
                        </div>
                        <div class="form-group">
                          <button class="btn btn-info" id="viewReport">Submit</button>
                        </div>
                      </div>

                  </div>
                </div>
                </form>
                    <div class="col-md-12" style="padding: 20px;">
                        <table class="table table-bordered" style="font-size:12px;" id="datatable">  
                           <thead>
                               <tr style="font-weight: bold;">
                                    <td width="5%" style="text-align: center;vertical-align: middle;">No</td>
                                    <td style="text-align: center;vertical-align: middle;" width="18%">No Promo</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal Buat</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal Mulai</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">Tanggal Selesai</td>
                                    <td style="text-align: center;vertical-align: middle;">Brand/Supplier</td>
                                    <td style="text-align: center;vertical-align: middle;" width="15%">PIC</td>
                                    <td style="text-align: center;vertical-align: middle;" >Act</td>
                               </tr>

                            </thead>
                        </table>
                   </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
