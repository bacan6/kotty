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
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                          <div class="col-sm-10">
                            <a class="btn btn-primary editUser"><i class="fa fa-save"></i> Submit</a>
                          </div>
                      </div>
                  </div>

                </div>  
               </form>          		               
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>