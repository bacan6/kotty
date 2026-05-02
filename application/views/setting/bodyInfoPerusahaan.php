<div class="wraper container-fluid">
    <div class="row">
        <div class="col-lg-6">
            <div class="page-title"> 
              <h3 class="title">Company Information</h3> 
            </div>

            <div class="portlet"><!-- /primary heading -->
                  <div id="portlet2" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <form action="<?php echo base_url('setting/updateInfoPerusahaanSQL'); ?>" method="post">
                        <?php
                            echo $this->session->userdata("message");
                        ?>
                        <table width="100%">
                            <?php
                                foreach($apReceipt as $row){
                            ?>
                            <tr>
                                <td width="30%">Nama Perusahaan</td>
                                <td><input type="text" value="<?php echo $row->nama_perusahaan; ?>" name="companyName" style="border:0;border-bottom:solid 1px #ccc;width: 100%" /></td>
                            </tr>

                            <tr>
                                <td width="30%">Kontak</td>
                                <td><input type="text" value="<?php echo $row->kontak; ?>" name="kontak" style="border:0;border-bottom:solid 1px #ccc;width: 100%" /></td>
                            </tr>

                            <tr>
                                <td width="30%">Alamat</td>
                                <td><input type="text" value="<?php echo $row->alamat; ?>" name="address" style="border:0;border-bottom:solid 1px #ccc;width: 100%" /></td>
                            </tr>

                            <tr style="height: 50px;">
                                <td colspan="2" align="right"><input type="submit" class="btn btn-primary" value="Submit"/></td>
                            </tr>
                            <?php } ?>
                        </table>
                        </form>
                    </div>
                </div>
            </div> <!-- /Portlet -->    
        </div>
    </div>  
</div>

