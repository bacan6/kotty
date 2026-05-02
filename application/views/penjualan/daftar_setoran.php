            <div class="wraper container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- TODO -->
                        <div class="portlet" id="todo-container"><!-- /primary heading -->
                            <div class="portlet-heading">
                                <h3 class="portlet-title text-dark text-uppercase">
                                    Daftar Setoran Kasir
                                </h3>
                            </div>
                            <div id="portlet-5" class="panel-collapse collapse in">
                                <div class="portlet-body">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <table class="table">
                                          <tr style="font-weight: bold;">
                                            <td width="5%">No</td>
                                            <td width="30%">No Setoran</td>
                                            <td width="47%">Tanggal</td>
                                            <td width="47%">Kasir</td>
                                          </tr>  

                                          <?php
                                            $i=1;
                                            foreach($pendingList as $row){
                                          ?>
                                          <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><a href="<?php echo base_url('penjualan/invoice_setorankasir?no_setor='.$row->no_setor); ?>" target="_blank"><?php echo $row->no_setor; ?></a></td>
                                            <td><?php echo date_format(date_create($row->tanggal),"d M Y ").$row->jam_setor; ?></td>
                                            <td><?php echo $row->first_name; ?></td>
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

