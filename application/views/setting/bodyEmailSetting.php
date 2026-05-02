<div class="wraper container-fluid">
    <div class="page-title"> 
        <h3 class="title"><i class="fa fa-envelope"></i> Email Setting</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                            echo $this->session->userdata("message");
                        ?>
                        <form class="form-horizontal" action="<?php echo base_url(); ?>setting/updateEmailSetting" method="post" role="form">
                            <?php
                                foreach($viewEmailSetting as $row){
                            ?>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">SMTP Host</label>
                                <div class="col-sm-9">
                                    <input type="text" name="SMTPHost" value="<?php echo $row->SMTPHost; ?>" style="width: 100%;border:0;border-bottom: solid 1px #ccc;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">SMTP Port</label>
                                <div class="col-sm-9">
                                    <input type="text" name="SMTPPort" value="<?php echo $row->SMTPPort; ?>" style="width: 100%;border:0;border-bottom: solid 1px #ccc;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">SMTP User</label>
                                <div class="col-sm-9">
                                    <input type="text" name="SMTPUser" value="<?php echo $row->SMTPUser; ?>" style="width: 100%;border:0;border-bottom: solid 1px #ccc;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">SMTP Password</label>
                                <div class="col-sm-9">
                                    <input type="text" name="SMTPPassword" value="<?php echo $row->SMTPPas; ?>" style="width: 100%;border:0;border-bottom: solid 1px #ccc;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Sender Name</label>
                                <div class="col-sm-9">
                                    <input type="text" name="SenderName" value="<?php echo $row->UserName; ?>" style="width: 100%;border:0;border-bottom: solid 1px #ccc;">
                                </div>
                            </div>
                                   
                            <div class="form-group m-b-0">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>
                            </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>

