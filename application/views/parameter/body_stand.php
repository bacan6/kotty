            <div class="wraper container-fluid">
              <div class="page-title"> 
                <h3 class="title">Tempat</h3> 
              </div>

                <div class="row">
                    <div class="col-lg-12">
                        <!-- TODO -->
                        <div class="portlet" id="todo-container"><!-- /primary heading -->

                            <div id="portlet-5" class="panel-collapse collapse in">
                                <div class="portlet-body">
   									<div class="row">
   										<div class="col-md-12">
   											<?php 
					                            if($this->session->userdata("message")!=NULL){
					                        ?>
					                            <div class="alert alert-success alert-dismissable">
					                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					                                <?php
					                                    echo $this->session->userdata("message");
					                                ?>      
					                            </div>

					                        <?php
					                            }
					                        ?>
   											<table class="table table-bordered" style="font-size:11px;">
   												<tr style="font-weight: bold;">
   													<td width="4%">No</td>
   													<td>Nama Stand</td>
   													<td width="1%"></td>
   												</tr>

   												<tr>
   													<form action="<?php echo base_url('stand/add_stand_sql'); ?>" method="post">
	   													<td></td>
	   													<td><input type="text" class="form-control" name="nama_stand" required/></td>
	   													<td><input type="submit" value="Submit" class="btn btn-primary"/></td>
   													</form>
   												</tr>

   												<?php
   													$i = 1;
   													foreach($stand->result() as $row){
   												?>
   												<tr>
   													<td><?php echo $i; ?></td>
   													<td><?php echo $row->stand; ?></td>
   													<td align="center"><a href="<?php echo base_url('stand/hapus_stand?id='.$row->id_stand); ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-icon btn-danger m-b-5"><i class="fa fa-trash"></i></a></td>
   												</tr>
   												<?php $i++; } ?>
   											</table>
   										</div>
   									</div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>

            </div>

