            <div class="wraper container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- TODO -->
                        <div class="portlet" id="todo-container"><!-- /primary heading -->
                            <div class="portlet-heading">
                                <h3 class="portlet-title text-dark text-uppercase">
                                    Edit Toko
                                </h3>
                            </div>
                            <div id="portlet-5" class="panel-collapse collapse in">
                                <div class="portlet-body">
                 									<div class="row">
                 										<div class="col-md-6">

                                      <form action="<?php echo base_url('store/edit_toko_sql'); ?>" method="post" class="form-horizontal" role="form" enctype="multipart/form-data">
                                        <?php
                                          foreach($toko->result() as $row){
                                        ?>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Nama Toko</label>
                                            <div class="col-sm-9">
                                              <input type="text" class="form-control" name="nama_toko" value="<?php echo $row->store; ?>">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Kontak</label>
                                            <div class="col-sm-9">
                                              <input type="text" class="form-control" name="kontak" value="<?php echo $row->kontak; ?>">
                                            </div>
                                        </div>

                                         <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Alamat</label>
                                            <div class="col-sm-9">
                                              <textarea class="form-control" name="alamat"><?php echo $row->alamat; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Footer</label>
                                            <div class="col-sm-9">
                                              <textarea class="form-control" name="footer"><?php echo $row->footer; ?></textarea>
                                              <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                          <input type="file" class="form-control" name="gambar" placeholder="Gambar" id="gambar"/>
                                          <input type="hidden" name="file_lama" class="form-control" value="<?php echo $row->gambar; ?>"/>
                                        </div>

                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-3 control-label">  </label>
                                            <div class="col-sm-9">
                                              <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <?php } ?>
                                      </form>
                 										</div>

                                    
                 									</div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>

            </div>

