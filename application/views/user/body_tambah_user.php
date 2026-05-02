<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title">Tambah User</h3> 
    </div>

	<div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
              <form class="form-horizontal" action="#">
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Depan</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="namaDepan">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Belakang</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="namaBelakang">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="noHP">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-10">
                            <input type="Email" class="form-control" id="email">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Toko</label>
                          <div class="col-sm-10">
                            <select class="select2" id="toko">
                              <option value="">--Pilih Toko--</option>
                              <?php
                                foreach($store as $st){
                              ?>
                              <option value="<?php echo $st->id_store; ?>"><?php echo $st->store; ?></option>
                              <?php } ?>
                            </select>
                            <p>Digunakan untuk kasir sesuai dengan toko yang ditugaskan</p>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kewenangan Edit Produk?</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="is_admin">
                            <br> 1 = ya, 0 = tidak
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="username">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="password">
                          </div>
                      </div>

                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                          <div class="col-sm-10">
                            <a class="btn btn-primary simpanUser"><i class="fa fa-save"></i> Submit</a>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6">
                    <?php
                      $permitAccess = json_decode($permitAccess);

                      foreach($navigation as $row){

                        echo "<input type='checkbox' data-id='".$row->id."' id='menu'/>".$row->menu."<br>";

         
                        $submenu = $this->model1->submenu($row->id);
                        foreach($submenu as $dt){
                          echo "&nbsp &nbsp &nbsp <input type='checkbox' data-id='".$dt->idSub."' id='submenu'/>".$dt->menu."<br>";
                        }
                      }
                    ?>
                  </div>

                </div>  
               </form>          		               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>