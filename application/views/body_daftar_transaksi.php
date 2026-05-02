<div class="wraper container-fluid">
    <div class="page-title"> 
        <h3 class="title">Data Transaksi</h3> 
    </div>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs"> 
                <?php
                    $tab = $this->uri->segment(3);
                ?>

                <li <?php if($tab==0 or $tab=='') {echo "class='active'";}?>> 
                    <a href="<?php echo base_url('data_transaksi/index/0'); ?>">               
                        <span>Pending</span> <span class="badge"><?php echo $count_pending; ?></span>
                    </a> 
                </li> 

                <li <?php if($tab==1) {echo "class='active'";}?>> 
                    <a href="<?php echo base_url('data_transaksi/index/1'); ?>"> 
                        <span>On Process</span> <span class="badge"><?php echo $count_on_process; ?> 
                    </a> 
                </li> 

                <li <?php if($tab==2) {echo "class='active'";}?>> 
                    <a href="<?php echo base_url('data_transaksi/index/2'); ?>"> 
                        <span>Terkirim</span> <span class="badge"><?php echo $terkirim; ?>
                    </a> 
                </li> 

                <li <?php if($tab==3) {echo "class='active'";}?>> 
                    <a href="<?php echo base_url('data_transaksi/index/3'); ?>"> 
                        <span>Dibatalkan</span> <span class="badge"><?php echo $transaksi_batal; ?>
                    </a> 
                </li> 
            </ul> 
            
            <div class="tab-content"> 
                <div class="page-title"> 
                    <h3 class="title">
                        <?php
                            switch ($tab) {
                                case '0' or '':
                                    echo "Pending";
                                    break;
                                case '1':
                                    echo "On Process";
                                    break;

                                case '2':
                                    echo "Terkirim";
                                    break;

                                case '3':
                                    echo "Dibatalkan";
                                    break;

                                case '4':
                                    echo "Retur";
                                    break;
                                default:
                                    echo "Not Found";
                                    break;
                            }
                        ?>
                    </h3> 
                </div>

                <?php
                    if($this->session->userdata("message")!=NULL){
                        echo $this->session->userdata("message");
                    }

                    if($tab==2){
                        echo "<a href='".base_url('data_transaksi/index/4')."'><p style='text-align:right;'><i class='fa fa-book'></i> Daftar Retur <span class='badge'>".$count_retur."</span></p></a>";
                    }
                ?>

                <table class="table table-bordered table-striped" style="font-size: 11px;">
                    <tr style="background: #2A303A;color:white;font-weight: bold;">
                        <td width="4%" align="center">No</td>
                        <td width="10%">No Invoice</td>
                        <td width="10%">Customer</td>
                        <td width="10%">Tanggal</td>
                        <td width="9%" align="right">Nilai Penjualan</td>
                        <td width="9%" align="right">Ongkir</td>
                        <td width="9%" align="right">Diskon Channel</td>
                        <td width="9%" align="right">Diskon</td>
                        <td width="10%" align="right">Poin Reimburs</td>
                        <td width="10%" align="right">Total</td>

                        <?php
                            if($tab==0 or $tab==1){
                        ?>
                        <td></td>
                        <?php } elseif($tab==2){ ?>
                        <td align="center" width="10%">Tanggal Kirim</td>
                        <?php } elseif($tab==3){ ?>
                        <td>Alasan Pembatalan</td>
                        <?php } ?>

                        <!-- tab jika terkirim-->
                        <?php
                            if($tab==2){
                        ?>
                        <td>Ekspedisi</td>
                        <td></td>
                        <?php
                            }
                        ?>
                        <!--tutup tab terkirim-->
                    </tr>

                    <?php
                        if(empty($this->uri->segment(4))){
                            $i=0+1;
                        } else {
                            $i=$this->uri->segment(4)+1;
                        
}
                        foreach($daftar_penjualan->result() as $row){
                    ?>
                    
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td>
                            <?php 
                                if($tab!=4){
                            ?>
                                <a href="<?php echo base_url('data_transaksi/invoice_penjualan?no_invoice='.$row->no_invoice); ?>"><?php echo $row->no_invoice; ?></a>
                            <?php } else { ?>
                                <a href="<?php echo base_url('data_transaksi/invoice_penjualan?no_invoice='.$row->no_invoice.'&tab=4'); ?>"><?php echo $row->no_invoice; ?></a>
                            <?php
                                }
                            ?>
                        </td>
                        <td><?php echo $row->nama; ?></td>
                        <td>
                            <?php 
                                echo date_format(date_create($row->tanggal),'d/m/y H:i'); 
                            ?>
                        </td>
                        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->ongkir,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value),'0',',','.'); ?></td>
                        
                        <!--SET AKSI UNTUK MASING-MASING STATUS-->
                        <?php
                            if($tab==0 or $tab==''){
                        ?>    
                        <td align="center" width="8%">
                            <a href="<?php echo base_url('data_transaksi/set_on_process?id='.$row->no_invoice); ?>" onclick="return confirm('Apakah Anda Yakin ?')"><span class="badge bg-success"><i class="fa fa-check"></i></span></a> <a href="#modal-cancel" class="cancel-trx" id="<?php echo $row->no_invoice; ?>" data-toggle="modal"><span class="badge bg-danger"><i class="fa fa-ban"></i></span></a> 
                        </td>
                        <?php } ?>

                        <?php
                            if($tab==1){
                        ?>    
                            <td align="center" width="8%">
                                <a href="#modal-resi" data-toggle="modal" class="siap_dikirim_button" id="<?php echo $row->no_invoice; ?>"><span class="badge bg-primary"><i class="fa fa-car"></i></span></a>
                            </td>
                        <?php } ?>

                        <?php
                            if($tab==2){
                        ?>
                            <td align="center">
                                 <?php echo date_format(date_create($row->tanggal_kirim),'d/m/y'); ?>
                            </td>
                        <?php } ?>

                        <?php
                            if($tab==3){
                        ?>
                            <td>
                                 <?php echo $row->alasan_cancel; ?>
                            </td>
                        <?php } ?>

                         <!-- tab jika terkirim-->
                        <?php
                            if($tab==2){
                        ?>
                        <td><?php echo $row->ekspedisi." - ".$row->no_resi; ?></td>
                        <td><a href="<?php echo base_url('data_transaksi/retur_penjualan?no_invoice='.$row->no_invoice); ?>"><span class="badge bg-warning"><i class='fa fa-mail-reply'></i></span></a></td>
                        <?php
                            }
                        ?>
                        <!--tutup tab terkirim-->
                    </tr>
                    <?php $i++; } ?>
                </table> 

                <div class="row" align="center">
                    <?php echo $paging; ?>
                </div>
            </div> 
        </div> 
    </div>
                        
</div>

<div id="modal-cancel" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Alasan Dibatalkan</h4>
            </div>

            <div class="modal-body" id="modal-cancel-form">
                                                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-resi" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Pengiriman</h4>
            </div>

            <div class="modal-body" id="modal-resi-form">
                                                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->