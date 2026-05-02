<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-glass"></i> Ekspedisi</h3> 
    </div>


    <div class="portlet"><!-- /primary heading -->        
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
             	<div class="row">
             		<div class="col-md-6">

                        <?php
                            if($this->session->userdata("message") != ''){
                        ?>
                            <div class="alert alert-success alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                   <?php echo $this->session->userdata("message"); ?>
                            </div>    
                        <?php     
                            }
                        ?>

                        <table class="table table-bordered" style="font-size: 11px;">
                            <tr style="font-weight: bold;"> 
                                <td width="3%">No</td>
                                <td>Ekspedisi</td>
                                <td width="10%"></td>
                            </tr>

                            <tr>
                                <form action="<?php echo base_url('ekspedisi/ekspedisi_sql'); ?>" method="post">
                                    <td></td>
                                    <td><input name="ekspedisi" type="text" class="form-control" required/></td>
                                    <td><input type="submit" class="btn btn-primary" value="Submit"/></td>
                                </form>
                            </tr>

                            <?php
                                $i = 1;
                                foreach($ekspedisi->result() as $row){
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row->ekspedisi; ?></td>
                                <td><a href="<?php echo base_url('ekspedisi/hapus_ekspedisi?id='.$row->id_ekspedisi); ?>" onclick="return confirm('Apakah anda yakin ?')" class="btn btn-danger">Hapus</a></td>
                            </tr>
                            <?php $i++; } ?>
                        </table>
             		</div>
             	</div>
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>


