<?php //ini_set('display_errors',1);error_reporting(E_ALL); var_dump($cash);?>
<div class="wraper container-fluid">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12" style="text-align: right;">
            <a href="<?php echo base_url('penjualan'); ?>" class="btn btn-success btn-rounded m-b-5"> &laquo; Kembali</a> 
        </div>
    </div>
    <div class="panel panel-default" >
        <div class="panel-body" id="print-area">
            <style>
h5,table,td,th{font-family:'Courier'}
</style>
            <div class="hidden-print">
                <div class="pull-right">
                    <a href="#" onclick="printContent('print-area')" class="btn btn-inverse"><i class="fa fa-print"></i></a>
                </div>
            </div>

            <div class="row">
                <div class= "col-md-12" id="dataContent">
                    <img src="<?php echo base_url('logo.png'); ?>" style="margin-left:auto;margin-right:auto;display:block;height:20px;"/>
                    <?php
                        foreach($receipt->result() as $cf){
                    ?>
                    <!--<h5 align="center"><?php echo $cf->store; ?></h5>-->
                    <h5 align="center"><?php echo $cf->store; ?><br><small style="color:#000!important"><?php echo $cf->alamat; ?></small></h5>
                    <h5 align="center">SLIP PENJUALAN<br>TUTUP SHIFT</h5>
                     
                    <center>
                    <table>
                        <?php
                            foreach($setoran_item->result() as $dt){
                                $tanggal = $dt->tanggal;
                                $jam_setor = $dt->jam_setor;
                        ?>
                        <tr>
                            <td colspan="4">
                            <?php echo $_GET['no_setor']." | ".date_format(date_create($tanggal),'d-m-y')." | ".date_format(date_create($jam_setor),'H:i:s'); ?>    
                            <br>Jumlah Struk: <?php echo $struk?></td> 
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td> 
                        </tr>
                        <tr>
                            <td colspan="4">DATA PENJUALAN</td> 
                        </tr>
                        
                        <tr style="border-top:dashed 1px #000;">
                            <td style="vertical-align:top;" colspan="2">Penjualan</td>
                            <td style="vertical-align:top;" align="right">:</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format($cash[0]->total,'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Diskon</td>
                            <td style="vertical-align:top;" align="right">:</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format($cash[0]->diskon,'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Retur (<?php echo number_format($retur_count,'0',',','.')?>x)</td>
                            <td style="vertical-align:top;" align="right">:</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format($retur,'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Poin Reimbursment</td>
                            <td style="vertical-align:top;" align="right">:</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format($cash[0]->diskon_poin,'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Voucher</td>
                            <td style="vertical-align:top;" align="right">:</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format($voucher,'0',',','.')?></td>    
                        </tr>
                        <tr style="border-top:dashed 1px #000;">
                            <td style="vertical-align:top;" colspan="2">Penjualan Bersih</td>
                            <td style="vertical-align:top;" align="right">:</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format($cash[0]->total - $cash[0]->diskon - $cash[0]->diskon_poin - $voucher,'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td> 
                        </tr>
                        <tr>
                            <td width="160px"></td>
                            <td width="20px"></td>
                            <td width="80px" align="right"></td>
                            <td align="right" width="80px"></td>
                        </tr>
                        <tr>
                            <td colspan="4">A C T U A L</td> 
                        </tr>
                        
                        <tr style="border-top:dashed 1px #000;">
                            <td colspan="4">Kas</td> 
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 100.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n100k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n100k*100000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 75.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n75k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n75k*75000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 50.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n50k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n50k*50000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 20.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n20k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n20k*20000),'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 10.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n10k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n10k*10000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 5.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n5k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n5k*5000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 2.000</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n2k; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n2k*2000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 1.000 ker.</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n1kp; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n1kp*1000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 1.000 koin</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n1kc; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n1kc*1000),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 500</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n500; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n500*500),'0',',','.')?></td>    
                        </tr>

                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 200</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n200; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n200*200),'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Nom 100</td>
                            <td style="vertical-align:top;" align="right">x <?php echo $dt->n100; ?></td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->n100*100),'0',',','.')?></td>    
                        </tr>
                        <tr>
                            <td style="vertical-align:top;" colspan="2">Voucher</td>
                            <td style="vertical-align:top;" align="right">x</td>
                            <td align="right" style="vertical-align:top;"><?php echo number_format(($dt->voucher),'0',',','.')?></td>    
                        </tr>


                        <?php $total = ($dt->n100k*100000)+($dt->n75k*75000)+($dt->n50k*50000)+($dt->n20k*20000)+($dt->n10k*10000)+($dt->n5k*5000)+($dt->n2k*2000)+($dt->n1kp*1000)+($dt->n1kc*1000)+($dt->n500*500)+($dt->n200*200)+($dt->n100*100)+($dt->voucher); 
                                $catatan = $dt->catatan;
                                $penggantian = $dt->penggantian;
                        }  
                    } ?>

                        <tr style="border-top: dashed 1px #000!important;">
                            <td colspan="3">Total Kas</td>
                            <td align="right"><?php echo number_format($total,'0',',','.'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Kas Sebelumnya</td>
                            <td align="right"><?php echo number_format($setorBefore,'0',',','.'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Grand Total Kas</td>
                            <td align="right"><?php echo number_format($total+$setorBefore,'0',',','.'); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">Penggantian</td>
                            <td align="right"><?php echo number_format($penggantian,'0',',','.')?></td>
                        </tr>
                        <tr>
                            <td colspan="4">&nbsp;</td> 
                        </tr>
                        <tr style="border-bottom: dashed 1px #000;">
                            <td colspan="4" >C H A N N E L</td> 
                        </tr>

                        <?php 
                        $ptype = '';$pchannel = 0;$totchannels = 0;
                        foreach ($channel as $c){ 
                        if ($ptype!=$c->payment_type){ 

                            if($ptype!='' && $ptype!=$c->payment_type){ ?>
                            <tr style="border-top: dashed 1px #000;">
                                <td colspan="3"></td>
                                <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($pchannel,'0',',','.'); ?></td>
                            </tr>
                            <?php 
                                $pchannel=0;
                            }
                            $ptype=$c->payment_type;
                            ?>
                        <tr>
                            <td colspan="4"><?php echo $c->payment_type?></td> 
                        </tr>
                        <?php }
                            $pchannel+=$c->penjualan;
                            $totchannels+=$c->penjualan;
                        ?>
                        
                        <tr>
                            <td colspan="3">&nbsp;- <?php echo $c->account; ?></td>
                            <td align="right"><?php echo number_format($c->penjualan,'0',',','.'); ?></td>
                        </tr>
                                <?php } ?>
                        <tr style="border-top: dashed 1px #000;">
                                <td colspan="3"></td>
                                <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($pchannel,'0',',','.'); ?></td>
                        </tr>
                        <?php $grandtotal = $cash[0]->total - $cash[0]->diskon - $cash[0]->diskon_poin - $voucher + $totchannels;?>
                        <tr style="border-top: dashed 1px #000;">
                                <td colspan="3">Total Channel</td>
                                <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($totchannels,'0',',','.'); ?></td>
                        </tr>
                        <tr style="border-top: dashed 1px #000;">
                                <td colspan="3">Cash + Channel</td>
                                <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($grandtotal,'0',',','.'); ?></td>
                        </tr>
                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="3">Selisih</td>
                            <td align="right" style="border-top:dashed 1px #000;"><?php echo number_format($total+$setorBefore + $penggantian - ($cash[0]->total - $cash[0]->diskon - $voucher),'0',',','.'); ?></td>
                        </tr>
  
                        <tr style="border-top: dashed 1px #000;">
                            <td colspan="4" align="center">
                                <?php if(!empty($catatan)){?>
                                    <br>
                                Catatan: <?php echo $catatan;?> 
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" align=center style="text-align:center">
                            <br><br><br>    
                            (<u><?php echo $nama_kasir;?></u>)<br>Kasir
                            <br><br><br>
                            (<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>)<br>
                            KEPALA TOKO</td> 
                        </tr>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
