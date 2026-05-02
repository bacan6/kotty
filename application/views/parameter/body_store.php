            <div class="wraper container-fluid">
              <div class="page-title"> 
              <h3 class="title"><i class="fa fa-building"></i> Store</h3> 
            </div>

                <div class="row">
                    <div class="col-lg-12">
                        <!-- TODO -->
                        <div class="portlet" id="todo-container"><!-- /primary heading -->
                            <div id="portlet-5" class="panel-collapse collapse in">
                                <div class="portlet-body">
   									<div class="row">

                      <!-- <div class="col-md-12" style="text-align: right;">
                        <a href="<?php echo base_url('store/tambah_toko'); ?>" class="btn btn-primary">Tambah Toko</a>
                      </div> -->

   										<div class="col-md-12" style="margin-top: 20px;">
   											<?php 				
					               echo $this->session->userdata("message");
					              ?>


   											<table class="table" style="font-size:11px;">
   												<tr style="font-weight: bold;">
   													<td width="5%">No</td>
   													<td width="30%">Nama Toko</td>
   													<td>Alamat</td>
                            						<td>Footer</td>
													<td>Gambar</td>
   													<td width="10%"></td>
   												</tr>

   												<?php
   													$i = 1;
   													foreach($toko->result() as $row){
   												?>
   												<tr>
   													<td><?php echo $i; ?></td>
   													<td><?php echo $row->store; ?></td>
   													<td><?php echo $row->alamat; ?></td>
                            						<td><?php echo $row->footer; ?></td>
													<td><img src='<?php echo base_url('uploads/files/store/'.$row->gambar); ?>' width=120px /></td>
   													<td align="center"><a href="<?php echo base_url('store/hapus_toko?id='.$row->id_store); ?>" onclick="return confirm('Apakah anda yakin menghapus data ini ?')" class="btn btn-icon btn-danger m-b-5"><i class="fa fa-trash"></i></a> <a href="<?php echo base_url('store/edit_toko?id='.$row->id_store); ?>" class="btn btn-icon btn-success m-b-5"><i class="fa fa-pencil"></i></a></td>
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

