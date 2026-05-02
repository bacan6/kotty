<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12" style="text-align: right;">
                        <a onclick="printContent('area-print')" class="btn btn-default"> <i class="fa fa-print"></i> Print </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="area-print" style="padding:5px;font-size: 13px;">
                        

                            <?php
                                $i=1;
                                $value = 0;
                                foreach($label_item->result() as $row){
                                    //var_dump($row);
                                    $diskon = $this->modelProduk->hargaPromo($row->id_produk,$idStore)+0;
                                    $tgldiskon = $this->modelProduk->tanggalPromo($row->id_produk,$idStore);
                                    $strPersen = '';
                                    if($diskon==0){
                                        $data = $this->modelProduk->hargaPromoBrand($row->id_produk,$idStore);
                                        $discount = 0;
                                        foreach($data as $dp){
                                            $rule = $dp->rules_type;
                                            $minBelanja = $dp->minBelanja;
                                            $discount = $dp->discount;
                                            $tgldiskon = $dp->tgl;
                                        }
                                        if ($discount>0){
                                            //'Count2Percent', 'Count2Price', 'Sum2Percent'
                                            if ($rule=='Count2Percent' || $rule=='Sum2Percent'){
                                                $diskon = $row->harga*($discount/100);
                                                
                                            }else $diskon = $discount;
                                            
                                        }
                                    }
                                    $hargaPromo = $row->harga-$diskon;
                                    $countPersen = ($diskon/$row->harga)*100;
                                    $strPersen = "";
                                    $float = $diskon>0? "float:left;":'';
                                    $str = $diskon>0? "<table style='float:right;width:100%;margin-bottom:8px;'>
                                                            <tr>
                                                            <td style='background-color:#000;color:#fff;padding:1px' align=center bgcolor=black>
                                                            Harga Normal
                                                            </td>
                                                            </tr>
                                                            <tr>
                                                            <td align=center><strike>Rp".number_format($row->harga,0)."</strike></td>
                                                            </tr>
                                                        </table>":'';
                                    $strHarga = $hargaPromo<$row->harga? $hargaPromo:$row->harga;
                                    $tgl = $diskon>0? '<br><span style="font-weight:bolder">Promo s/d:</span> '.$tgldiskon:'';
                            for ($x = 1; $x <= $row->qty; $x++) {
                            ?>
                            <table width="230px" style="font-size: 9.5px;margin:7px;float:left;max-width:230px!important;min-height:115px!important;">
                            <tr>
                                <td style="border: solid 1px black;font-weight:bold;max-width:230px!important;overflow-wrap: break-word;text-align:center">
                                    <?php echo strtoupper($row->nama_produk); ?><br>
                                    <span style="font-size:15px;vertical-align:top;<?php echo $float?>">Rp </span>
                                    <span style="font-size:31px;<?php echo $float?>margin-bottom:1px"><?php echo number_format($strHarga,'0',',','.'); ?></span>&nbsp;
                                    
                                    
                                    <div style="width:220px!important;" align="center"><?php echo $row->id_produk; ?> 
                                <span style="font-size:12px;float:right"><?php echo $str?></span></div>
                                    <?php echo $tgl?>
                                    
                                    <div style="border-top: 1px solid black; margin-top: 5px;  display: flex; justify-content: space-between; font-size: 10px; font-family: sans-serif; width:100%; background-color: #e91a62 !important; color: #fff !important; padding: 2px;
                                        -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;">
                                        <span style="text-transform: uppercase; width:50%;"><img src="<?php echo base_url('assets/Logo_white.png'); ?>" alt="logo" style=" height: 13px; vertical-align: middle; margin-right: 5px;"></span>
                                        <span style="width:50%;"><?php echo date('d-m-Y'); ?></span>
                                    </div>
                                    
                                </td>
                            </tr>
                            </table>
                                
                            
                            <?php $i++;} 
                        } ?>

                            <?php
                                    $uri = $this->uri->segment(1);

                                    ?>
                        
                    </div>
                </div>    
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
