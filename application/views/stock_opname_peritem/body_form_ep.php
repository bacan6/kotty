<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: right;">
                        <a onclick="printContent('area-print')" class="btn btn-default"> <i class="fa fa-print"></i> Print </a>
                        <a href="#approvalSO" data-toggle="modal" class="btn btn-info" onclick="$('#userApprover').focus();"><i class="fa fa-cog"></i> Revisi</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="area-print" style="padding:5px;font-size: 13px;">
                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php echo $hd->nama_perusahaan; ?><br>
                                    Stok Opname Per Item<br>
                                    <?php echo $infoSO->store?>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="margin-top: 5px;font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">

                                        <tr>
                                            <td style="width: 15%;">Nomor</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo $_GET['no_so']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;">Tanggal</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo $tanggal_so; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 15%;">Kategori</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo !empty($infoSO->nama_kategori) ? htmlspecialchars($infoSO->nama_kategori) : '—'; ?></td>
                                        </tr>

                                                                            
                                    </table>
                                </td>

                            </tr>
                        </table>

                        
                        <table width="100%" class="table table-bordered" border="1" cellspacing="0" cellpadding="4" style="margin-top: 5px;font-size: 12px;">
                        <tr>
                            <th>No.</th>
                            <th>ID Produk</th>
                            <th>Nama</th>
                            <th>HPP</th>
                            <th>Harga</th>
                            <th>Stok Sistem Update</th>
                            <th>Stok Sistem</th>
                            <th>Stok Toko</th>
                            <th>Selisih</th>
                            <th>HPP Selisih</th>
                            <th>Harga Selisih</th>
                            <th>Nilai Akhir</th>
                            <th>Revisi</th>
                        </tr>
                            <?php
                                $i=1;
                                $value = 0;$total=0;
                                foreach($SO_item->result() as $row){
                                    $total += $row->stok_after*$row->harga;
                                    $totalhj += ($row->stok_before-$row->stok_after)*$row->harga_jual;
                                    if($row->revisi=='' && $status_selisih){
                                        $revisi = "<input type=text value='' size=4 maxlength=4 class='revisi' data-id='$row->id_produk' data-no='$row->no_so'>";
                                    }else{
                                        $revisi = $row->revisi;
                                    }
                                    $stok_update = $this->model1->stokKartu($row->id_produk,$infoSO->id_toko);
                            ?>
                            
                            <tr>
                                <td><?php echo $i?></td>
                                <td><?php echo $row->id_produk; ?></td>
                                <td><?php echo strtoupper($row->nama_produk); ?></td>
                                <td><?php echo number_format($row->harga,0); ?></td>
                                <td><?php echo number_format($row->harga_jual,0); ?></td>
                                <td align=center><?php echo $stok_update; ?></td>
                                <td align=center><?php echo $row->stok_before; ?></td>
                                <td align=center><?php echo $row->stok_after; ?></td>
                                <td align=center><?php echo $row->stok_before-$row->stok_after; ?></td>
                                <td align=right><?php echo number_format(($row->stok_before-$row->stok_after)*$row->harga,0); ?></td>
                                <td align=right><?php echo number_format(($row->stok_before-$row->stok_after)*$row->harga_jual,0); ?></td>
                                <td align=right><?php echo number_format($row->stok_after*$row->harga,0); ?></td>
                                <td><?php echo $revisi?></td>
                                </tr>
                            
                                
                            
                            <?php 
                            $value = $value+$row->total;
                            $hargaselisih += ($row->stok_before-$row->stok_after)*$row->harga;
                            $selisih += $row->stok_before-$row->stok_after;
                            $i++; } ?>
                            <tr><td colspan='8' align='center'><b>TOTAL</b></td>
                            <td align=center><?php echo number_format($selisih,0)?></td>
                            <td align=right><?php echo number_format($hargaselisih,0)?></td>
                            <td align=right><?php echo number_format($totalhj,0)?></td>
                            <td align=right><?php echo number_format($total,0)?></td>
                            </tr>
                            </table>
                            <?php
                                    $uri = $this->uri->segment(1);

                                    ?>
                        
                    </div>
                </div>    
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
<div id="approvalSO" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-check"></i> Approval Revisi SO</h4>
            </div>                             
            <div class="modal-body">

                <div class="row" id="approvalContent" style="margin-top: 10px;">
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form">
                            <input type="hidden" id="chartID">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Username</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="userApprover" required>
                                    <label id="labelpwd" style="color:red;"></label>
                                </div>
                            </div>

                        </form>                            
                    </div>
                    <div class="col-md-6">
                        <form class="form-horizontal" role="form" onsubmit="$('#verifyApproval').click();return false;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Password</label>
                                <div class="col-md-10">
                                    <input type="password" class="form-control" id="passApprover" required>
                                </div>
                            </div>

                        </form>                            
                    </div>

                </div>     
            </div>        
            <div class="modal-footer">
                <button class="btn btn-success" id="verifyApproval"><i class="fa fa-check"></i> Setujui</button>
            </div>                            
        </div>

    </div>
</div>