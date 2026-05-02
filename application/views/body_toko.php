            <div class="wraper container-fluid">
              <div class="page-title"> 
              <h3 class="title">Store</h3> 
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
                          echo $this->session->userdata("message");
                        ?>
   											<table class="table" style="font-size:11px;">
   												<tr style="font-weight: bold;">
   													<td width="5%">No</td>
   													<td width="30%">Nama Toko</td>
   													<td>Alamat</td>
   													<td width="8%"></td>
   												</tr>

   												<tr>
   													<form action="<?php echo base_url('store/add_toko_sql'); ?>" method="post">
	   													<td></td>
	   													<td><input type="text" class="form-control" name="nama_toko" required/></td>
	   													<td><input type="text" class="form-control" name="alamat" required/></td>
	   													<td><input type="submit" value="Submit" class="btn btn-primary"/></td>
   													</form>
   												</tr>

   												<?php
   													$i = 1;
   													foreach($toko->result() as $row){
   												?>
   												<tr>
   													<td><?php echo $i; ?></td>
   													<td><?php echo $row->store; ?></td>
   													<td><?php echo $row->alamat; ?></td>
   													<td align="center"><a href="<?php echo base_url('store/hapus_toko?id='.$row->id_store); ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-icon btn-danger m-b-5"><i class="fa fa-trash"></i></a></td>
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

