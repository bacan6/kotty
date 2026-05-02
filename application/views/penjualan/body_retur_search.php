<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Retur</h3>
    </div>
    <div class="portlet" id="todo-container"><!-- /primary heading -->
        <div id="portlet-5" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-6">
                        <!--<form action="<?php echo base_url('penjualan/retur_penjualan_sql'); ?>" method="post">-->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td width="5%" style="font-weight: bold;">No</td>
                                    <td style="font-weight: bold;">Nama Item</td>
                                    <td width="15%" style="font-weight: bold;">Qty</td>
                                    <td width="15%" align="right" style="font-weight: bold;">Harga</td>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                $i = 1;
                                foreach ($data_invoice->result() as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row->nama_produk; ?></td>
                                        <td align=right>
                                            <input type="text" id="produk" placeholder=""
                                                data-hpp="<?php echo $row->hpp; ?>"
                                                onchange="if(this.value > <?php echo $row->qty; ?>) { this.value='';return false;}"
                                                data-sku="<?php echo $row->id_produk; ?>"
                                                data-harga="<?php echo $row->harga_jual; ?>"
                                                data-diskon="<?php echo ($row->diskon / $row->qty); ?>"
                                                class="form-control" /> dari <?php echo $row->qty; ?>
                                        </td>
                                        <td align="right"><?php echo number_format($row->harga_jual, '0', ',', '.'); ?></td>
                                    </tr>
                                    <?php $i++;
                                } ?>

                                <tr>
                                    <td colspan="4" style="text-align: right;">
                                        <a href="#approvalKasir" id="minta-setuju" class="btn btn-danger"
                                            data-toggle="modal" onclick="$('#userApprover').focus();"><i
                                                class="fa fa-check"></i> Minta Persetujuan</a>
                                        <button type="button" id='submit-retur' value="Submit" class="btn btn-primary"
                                            style="display: none"><i class="fa fa-save"></i> Simpan</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!--</form>-->
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td width="1%">No</td>
                                    <td>No Retur</td>
                                    <td>Tanggal Retur</td>
                                    <td>Keterangan</td>
                                    <td width="5%"></td>
                                </tr>
                            </thead>

                            <tbody id="invoiceRetur">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end col -->
<div id="approvalKasir" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
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
                            <input type="hidden" id="chartID">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <select id="select_scan" onchange="login_selectuser()" class='select2'>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $s = 0;
                                        foreach ($approver->result() as $app) {
                                            //$s = ($app->username=='robert')?base64_encode(base_url('kepala_kasir/verification?user_id='.$app->username)):$s;
                                            $value = base64_encode(base_url('kepala_kasir/verification?user_id=' . $app->username));

                                            // new fingerprint
                                            $webhook = $this->model_crypto->encrypt(base_url('kepala_kasir/verification_new'));
                                            $user = $this->model_crypto->encrypt($app->username);
                                            ?>
                                            <option value="<?php echo $value; ?>" webhook_new="<?php echo $webhook ?>"
                                                fingerprint="<?php echo $app->fingerprint1 ?>" user_new="<?php echo $user ?>"
                                                username="<?php echo $app->username; ?>"><?php echo $app->Nama; ?></option>
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
                                    <a href="#!" id="button_login" class="btn btn-success btn-xs">Login</a>
                                    <a href="#!" id="button_login_new" class="btn btn-warning btn-xs">Login New</a>
                                    <label id="labelvrf" style="color:green;"></label>
                                    <?php
                                    $arUsr = array(45, 44, 43, 40, 47, 46, 34);
                                    if (in_array($idUser, $arUsr)) { ?>
                                        <input type="password" class="form-control" id="passApprover"
                                            onchange="javascript:verifyMe();">
                                    <?php } else { ?>
                                        <input type="hidden" class="form-control" id="passApprover">
                                    <?php } ?>

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