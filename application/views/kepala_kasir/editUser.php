<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Edit User</h3> 
    </div>
    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nama</label> <label id="skuAlert" style="color:red;"></label> 
                            <input type="text" value="<?php echo $user_approver->Nama; ?>" id="Nama" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"/>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Username</label> <label id="skuAlert" style="color:red;"></label>
                            <input type="text" value="<?php echo $user_approver->username; ?>" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;" id="username"/>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Password</label> <label id="skuAlert" style="color:red;"></label> 
                            <input type="text" value="" id="pass" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Status</label> <label id="skuAlert" style="color:red;"></label>
                        <select class="form-group select2" id="NA" style="border:0;border-bottom: solid 0.5px #ccc;width: 100%;">
                            <option value="N" <?php if($user_approver->NA=='N'){echo "selected";} ?>>Aktif</option>
                            <option value="Y" <?php if($user_approver->NA=='Y'){echo "selected";} ?>>Non-Aktif</option>
                        </select>
                        <input type="hidden" id="username" value="<?php echo $user_approver->username; ?>"/>
                    </div>
                </div>


                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <button class="btn btn-primary" id="editUser"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>              
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
