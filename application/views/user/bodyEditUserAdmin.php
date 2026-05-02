<div class="wraper container-fluid">
  <div class="page-title"> 
      <h3 class="title">Edit User</h3> 
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
                            <input type="text" class="form-control" id="namaDepan" value="<?php echo $user->first_name; ?>">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nama Belakang</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $user->last_name; ?>" id="namaBelakang">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">No HP</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $user->phone; ?>" id="noHP">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-10">
                            <input type="Email" class="form-control" value="<?php echo $user->email; ?>" id="email">
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
                              <option value="<?php echo $st->id_store; ?>" <?php if($st->id_store==$user->toko){echo "selected";} ?>><?php echo $st->store; ?></option>
                              <?php } ?>
                            </select>
                            <p>Digunakan sesuai dengan toko yang ditugaskan</p>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Kewenangan Edit Produk?</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="is_admin" value="<?php echo $user->is_admin; ?>">
                            <br> 1 = ya, 0 = tidak
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?php echo $user->username; ?>" id="username">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="password" placeholder="Kosongkan Jika Tidak Ingin Mengganti Password">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Password PDA</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="password_pda" placeholder="Kosongkan Jika Tidak Ingin Mengganti Password">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                          <div class="col-sm-10">
                            <select class="form-control" id="status">
                              <option value="1" <?php if($user->active=='1') {echo "selected"; } ?>>Aktif</option>
                              <option value="0" <?php if($user->active=='0') {echo "selected"; } ?>>Non Aktif</option>
                            </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="inputbrand" class="col-sm-2 control-label">Set Brand</label>
                          <div class="col-sm-10">
                            <select class="form-control select2" id="brand" multiple="multiple">
                              <?php 
                      $tanggungjawab = json_decode($user->brand);
                      foreach($brand as $br){
                          $setbrand = in_array($br->id_brand,$tanggungjawab);

                          $selected = $setbrand > 0 ? "selected" : "";
                          echo "<option data-id='".$br->id_brand."' value='".$br->id_brand."' $selected />".$br->brand."</option>";
                        }
                        ?>
                            </select>
                          </div>
                      </div>
                      

                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                          <div class="col-sm-10">
                            <a class="btn btn-primary editUser"><i class="fa fa-save"></i> Submit</a>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-6">
                    <?php
                      $permitAccess = json_decode($user->menu);
                      $permitAccessSub = json_decode($user->sub_menu);

                      foreach($navigation as $row){

                        $accessMenu = in_array($row->id,$permitAccess);

                        $checked = $accessMenu > 0 ? "checked" : "";

                        echo "<input type='checkbox' data-id='".$row->id."' id='menu' $checked/>".$row->menu."<br>";

         
                        $submenu = $this->model1->submenu($row->id);
                        foreach($submenu as $dt){
                          $accessSubMenu = in_array($dt->idSub,$permitAccessSub);

                          $checkedSub = $accessSubMenu > 0 ? "checked" : "";
                          echo "&nbsp &nbsp &nbsp <input type='checkbox' data-id='".$dt->idSub."' id='submenu' $checkedSub/>".$dt->menu."<br>";
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