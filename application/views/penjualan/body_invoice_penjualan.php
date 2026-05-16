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
                            <td style="vertical-align:top;" colspan="2"><?php echo number_format($dt->harga_jual,'0',',','.'); ?></td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->qty; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->harga_jual*$dt->qty),'0',',','.')?></td>    
                        </tr>

                        <?php
                            $diskon_persen = '';
                            if($dt->diskon > 0){
                                $diskon_persen = 100 - (((($dt->harga_jual*$dt->qty) - $dt->diskon) / ($dt->harga_jual *$dt->qty)) * 100);
                        ?>
                        <tr>
                            <td colspan="3" align="right">Diskon <?php echo number_format($diskon_persen,2)?>%</td>
                            <td align="right">(<?php echo number_format(($dt->harga_jual*$dt->qty) - $dt->diskon,'0',',','.'); ?>)</td>
                        </tr>
                        <?php 
                            $jumlah_item = $jumlah_item + $dt->qty;
                            
                        } ?>

                        <!-- END DISKON-->

                        <?php $item = $item + $dt->qty; $diskon_peritem = $diskon_peritem + $dt->diskon; } ?>

                        <?php } ?>

                        <?php
                            foreach($no_invoice->result() as $st){
                        ?>

                        <!-- <tr style="border-top:dashed 1px #000;">
                            <td colspan="3" align="RIGHT">Total Item</td>
                            <td align="right"><?php echo $item_barang;  ?></td>
                        </tr> -->

                        <tr style="border-top:dashed 1px #000;">
                            <td colspan="3" align="RIGHT">Qty</td>
                            <td align="right"><?php echo $qty_barang; ?></td>
                        </tr>

                        <!-- <tr>
                            <td colspan="3" align="RIGHT">Subtotal</td>
                            <td align="right"><?php echo number_format($st->total-$diskon_peritem,'0',',','.'); ?></td>
                        </tr> -->
                        
                        <?php
                            if(!empty($st->ongkir)){
                        ?>    
                        <tr>
                            <td colspan="3" align="RIGHT">Ongkir</td>
                            <td align="right"><?php echo number_format($st->ongkir,'0',',','.'); ?></td>
                        </tr>          
                        <?php } ?>

                        <?php
                            if(!empty($st->diskon)){
                        ?>    
                        <tr>
                            <td colspan="3" align="RIGHT">Diskon Member</td>
                            <td align="right"><?php echo number_format($st->diskon,'0',',','.'); ?></td>
                        </tr>          
                        <?php } ?>

                        <?php
                            if(!empty($st->diskon_free)){
                        ?>    
                        <tr>
                            <td colspan="3" align="RIGHT">Diskon</td>
                            <td align="right"><?php echo number_format($st->diskon_free,'0',',','.'); ?></td>
                        </tr>          
                        <?php } ?>

                        <?php
                            if(!empty($st->poin_value)){
                        ?>    
                        <tr>
                            <td colspan="3" align="RIGHT">Poin Reimburs</td>
                            <td align="right"><?php echo number_format($st->poin_value,'0',',','.'); ?></td>
                        </tr>          
                        <?php } ?>

                        <?php
                            if($st->surcharge>0){
                        ?>    
                        <tr>
                            <td colspan="3" align="RIGHT">Surcharge</td>
                            <td align="right"><?php echo number_format($st->surcharge,'0',',','.'); ?></td>
                        </tr>          
                        <?php } ?>
                        <?php
                            if($st->voucher>0){
                        ?>    
                        <tr>
                            <td colspan="3" align="RIGHT">Voucher</td>
                            <td align="right"><?php echo number_format($st->voucher,'0',',','.'); ?></td>
                        </tr>          
                        <?php } ?>

                        <tr>
                            <td colspan="3" align="RIGHT">Grand Total</td>
                            <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format(($st->ongkir+$st->total+$st->surcharge)-($st->diskon+$st->diskon_free+$st->poin_value+$diskon_peritem+$st->voucher),'0',',','.'); ?></td>
                        </tr> 

                        <tr>
                            <td colspan="3" align="RIGHT"><?php echo $tipe_bayar; ?></td>
                            <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($st->jumlah_bayar,'0',',','.'); ?></td>
                        </tr>

                        <tr>
                            <td colspan="3" align="RIGHT">Kembali</td>
                            <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($st->jumlah_bayar-(($st->ongkir+$st->total+$st->surcharge)-($st->diskon+$st->diskon_free+$st->poin_value+$diskon_peritem+$st->voucher)),'0',',','.'); ?></td>
                        </tr>
                        
                        <?php
                            
                            $kustomer='';
                            if(!empty($st->nama) && !empty($st->no_kartu)){
                                $kustomer = '<br>Kustomer: '.$st->nama.'<br>No. Kartu: '.$st->no_kartu.'<br>Poin Sebelumnya: '.$st->poin_before.'<br>Total Poin: '.$st->point;
                                ?>
                                <tr>
                                    <td colspan="3" align="RIGHT">Poin</td>
                                    <td align="right"><?php echo number_format($st->poin,'0',',','.'); ?></td>
                                </tr>
                            <?php } 
                    $tanggal = $st->tanggal;
                    } ?>

                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="4" align="center">
                                <?php echo $_GET['no_invoice']." | ".date_format(date_create($tanggal),'d-m-y')." | ".date_format(date_create($tanggal),'H:i:s'); ?>
                                <br>
                                <?php echo $nama_kasir.$kustomer;?>  
                            </td>
                        </tr>
                        
                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="4" align="center"><?php echo $cf->footer; ?></td>
                        </tr>
                        <tr style="">
                            <td colspan="4" align="center"><img src="<?php echo base_url('barcode.php?h=50&f=png&s=code-128&d='.$_GET['no_invoice']); ?>"></center></td>
                        </tr>
                        <?php if (!empty($voucher_struk_receipt)) { foreach ($voucher_struk_receipt as $vs) { ?>
                        <tr>
                            <td colspan="4" align="center" style="border-top:dashed 1px #000;padding-top:8px;">
                                Selamat! Anda mendapatkan voucher berikut:<br/>
                                <small><strong><?php echo htmlspecialchars((string) $vs['nm_voucher'], ENT_QUOTES, 'UTF-8'); ?></strong></small><br/>
                                <?php if (!empty($vs['minimal_belanja']) && (float) $vs['minimal_belanja'] > 0) { ?>
                                <small>Minimum belanja untuk pakai voucher: Rp <?php echo number_format((float) $vs['minimal_belanja'], 0, ',', '.'); ?></small><br/>
                                <?php } ?>
                                <small style="white-space:pre-wrap;"><?php echo nl2br(htmlspecialchars((string) $vs['desc'], ENT_QUOTES, 'UTF-8')); ?></small><br/>
                                <span style="font-family:monospace;font-size:11px;"><?php echo htmlspecialchars((string) $vs['id_voucher'], ENT_QUOTES, 'UTF-8'); ?></span><br/>
                                <img src="<?php echo base_url('barcode.php?h=48&f=png&s=code-128&d=' . rawurlencode((string) $vs['id_voucher'])); ?>" alt=""/>
                            </td>
                        </tr>
                        <?php } } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
