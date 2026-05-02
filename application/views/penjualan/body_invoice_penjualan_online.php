<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" style="text-align: right;">
            <a href="<?php echo base_url('penjualan'); ?>" class="btn btn-success btn-rounded m-b-5"> &laquo; Kembali</a> 
            <a href="<?php echo base_url($this->uri->segment(1).'/invoice_penjualan?no_invoice='.$_GET['no_invoice']); ?>" class="btn btn-default btn-rounded m-b-5"><i class="fa fa-forumbee"></i> Invoice 1</a> 
            <a href="<?php echo base_url($this->uri->segment(1).'/invoiceA4?no_invoice='.$_GET['no_invoice']); ?>" target="_blank" class="btn btn-warning btn-rounded m-b-5"><i class="fa fa-forumbee"></i> Invoice 2</a> 
            <a href="<?php echo base_url($this->uri->segment(1).'/suratJalan?no_invoice='.$_GET['no_invoice']); ?>" class="btn btn-success btn-rounded m-b-5" target="_blank"><i class="fa fa-copy"></i> Surat Jalan</a> 
            <a href="<?php echo base_url($this->uri->segment(1).'/shippingLabel?no_invoice='.$_GET['no_invoice']); ?>" target="_blank" class="btn btn-inverse btn-rounded m-b-5" id="shippingLabel"><i class="fa fa-barcode"></i> Shipping Label</a>
        </div>
    </div>
<style>
h5,table,td,th{font-family:'Courier New';}
</style>
    <div class="panel panel-default" >
        <div class="panel-body" id="print-area">
            <div class="hidden-print">
                <div class="pull-right">
                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
                </div>
            </div>

            <div class="row">
                <div class= "col-md-12" id="dataContent">
                    <img src="<?php echo base_url('logo.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:40px;"/>
                    <?php
                        foreach($receipt->result() as $cf){
                    ?>
                    <!--<h5 align="center"><?php echo $cf->store; ?></h5>-->
                    <h5 align="center"><small style="color:#000!important"><?php echo $cf->alamat; ?></small></h5>
                    <h5 align="center"><small style="color:#000!important"><?php echo $cf->kontak; ?></small></h5>
                     
                    <center>
                    <table>
                        <tr style="border-top:dashed 1px #000;">
                            <td width="160px"></td>
                            <td width="20px"></td>
                            <td width="80px" align="right"></td>
                            <td align="right" width="80px"></td>
                        </tr>

                        <?php
                            foreach($no_invoice->result() as $ket){
                                $pay_type = $ket->tipe_bayar;
                                $account_bank = $ket->account;
                            }

                            $jumlah_item = 0;
                            $diskon_peritem = 0;
                            $item = 0;
                            foreach($invoice_item->result() as $dt){
                        ?>
                        <tr>
                            <td colspan="4"><?php echo substr($dt->nama_produk,0,35); ?></td> 
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2"></td>
                            <td style="vertical-align:top;" align="right"></td>
                            <td align="right" style="vertical-align:top;">x <?php echo $dt->qty; ?></td>    
                        </tr>

                        <?php
                            $diskon_persen = '';
                            if($dt->diskon > 0){
                                
                        
                            $jumlah_item = $jumlah_item + $dt->qty;
                            
                        } ?>

                        <!-- END DISKON-->

                        <?php $item = $item + $dt->qty; } ?>

                        <?php } ?>

                        <?php
                            foreach($no_invoice->result() as $st){
                        ?>

                        <tr style="border-top:dashed 1px #000;">
                            <td colspan="3" align="RIGHT">Qty</td>
                            <td align="right"><?php echo $qty_barang; ?></td>
                        </tr>

                        
                        
                        <?php
                            
                           
                    $tanggal = $st->tanggal;
                    } ?>

                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="4" align="center">
                                <?php echo $_GET['no_invoice']." | ".date_format(date_create($tanggal),'d-m-y')." | ".date_format(date_create($tanggal),'H:i:s'); ?>
                                <br>
                                <?php echo $nama_kasir;?>  
                            </td>
                        </tr>
                        
                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="4" align="center"><?php echo $cf->footer; ?></td>
                        </tr>
                        <tr style="">
                            <td colspan="4" align="center"><img src="<?php echo base_url('barcode.php?h=50&f=png&s=code-128&d='.$_GET['no_invoice']); ?>"></center></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
