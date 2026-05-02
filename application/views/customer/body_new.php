<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-cube"></i> Customer</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body" style="padding:30px;">
                <div class="row">
                
                    <div class="col-md-12" style="text-align: right;">
                        <div class="col-md-12">
                            <form action="<?php echo base_url('customer'); ?>" method="get" class="form-inline">
                                <div class="form-group">
                                    <select name="kategori" class="select2" style="width:20%!important">
                                        <option value=''> - Pilih Group - </option>
                                    <?php foreach($group as $row){ ?>
                                        <option value="<?php echo $row->id_group?>"><?php echo $row->group_customer?></option>
                                   <?php } ?>
                                    
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                                <div class="form-group">
                                    <?php
                                    if($idUser==15 || $idUser==34){
                                    $export_params = array();
                                    if (!empty($kategori)) $export_params['kategori'] = $kategori;
                                    $export_base = base_url('customer/export_csv') . (empty($export_params) ? '?' : '?' . http_build_query($export_params) . '&');
                                    ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-file-text-o"></i> Export CSV <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a href="<?php echo $export_base; ?>delimiter=comma">Comma (,)</a></li>
                                            <li><a href="<?php echo $export_base; ?>delimiter=semicolon">Semicolon (;)</a></li>
                                        </ul>
                                    </div>
                                    <?php } ?>
                                    <a href="#approvalCustomer" id="minta-setuju" class="btn btn-danger" data-toggle="modal" onclick="$('#userApprover').focus();"><i class="fa fa-check"></i> Minta Persetujuan Edit</a>
                                    <a href="<?php echo base_url('customer/add_customer'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> Add Customer</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="table table-bordered" id="customerDatatables">
                            <thead>
                              <tr style="font-weight: bold;">
                                <td width="5%" align="center">No</td>
                                <td width="20%">Nama</td>
                                <td>Kontak</td>
                                <td width="10%">Tgl Lahir</td>
                                <td width="10%">Tanggal Input</td>
                                <td width="25%">Alamat</td>
                                <td>Kategori</td>
                                <td width="5%">Diskon (%)</td>
                                <td width="5%">Point</td>
                                <td width="5%">Aktif?</td>
                                <td width="10%"></td>
                              </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>

<div id="approvalCustomer" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check"></i> Approval Retur</h4>
            </div>                             
            <div class="modal-body">

                <div class="row" id="approvalContent" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                            <input type="hidden" class="form-control" id="userApprover">
                            <input type="hidden" class="form-control" id="passApprover">
                            <input type="hidden" id="chartID">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <select id="select_scan" onchange="login_selectuser()" class='select2'>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $s = 0;
                                            foreach($approver->result() as $app){
                                                //$s = ($app->username=='robert')?base64_encode(base_url('kepala_kasir/verification?user_id='.$app->username)):$s;
                                                $value = base64_encode(base_url('kepala_kasir/verification?user_id='.$app->username));
                                        ?>
                                        <option value="<?php echo $value; ?>" username="<?php echo $app->username; ?>"><?php echo $app->Nama; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </form>                            
                    </div>
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" onsubmit="$('#verifyApproval').click();return false;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Verifikasi</label>
                                <div class="col-md-10">
                                    <a href="" id="button_login" class="btn btn-success">Login</a>
                                    <label id="labelvrf" style="color:green;"></label>
                                </div>
                            </div>
                        </form>                           
                    </div>

                </div>     
            </div>        
            <div class="modal-footer">
                
            </div>                            
        </div>

    </div>
</div>