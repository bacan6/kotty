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
                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php echo $hd->nama_perusahaan; ?><br>
                                    Cabang <?php echo $store; ?><br>
                                    Promosi Khusus Brand
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="margin-top: 5px;font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td style="width: 25%;">Tanggal</td>
                                            <td style="width: 1%;">:</td>
                                            <td>
                                                <?php
                                                    $date_po = date_create($tanggal_buat);

                                                    echo date_format($date_po,'d M Y');
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 25%;">No Promo</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo $_GET['no_promo']; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="width: 25%;">Minimal Belanja</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo number_format($minBelanja,0); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">Diskon</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo number_format($discount,0); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">Tipe Promo</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php 
                                            if($rules_type=='Count2Price') echo "Minimal ".number_format($minBelanja,0)." (item) - diskon Rp".number_format($discount,0);
                                            if($rules_type=='Count2Percent') echo "Minimal ".number_format($minBelanja,0)." (item) - diskon ".number_format($discount,0)."%";
                                            if($rules_type=='Sum2Price') echo "Minimal Rp".number_format($minBelanja,0)." - diskon Rp".number_format($discount,0);
                                            if($rules_type=='Sum2Percent') echo "Minimal Rp".number_format($minBelanja,0)." - diskon ".number_format($discount,0)."%";?></td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;">Keterangan</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo $keterangan; ?></td>
                                        </tr>
                                       
                                                                            
                                    </table>
                                </td>

                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="width: 25%;">Brand</td>
                                            <td style="width: 1%;">:</td>
                                            <td>
                                                <?php echo $brand; ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 25%;">Tanggal Mulai</td>
                                            <td style="width: 1%;">:</td>
                                            <td>
                                                <?php
                                                    $date_send = date_create($tanggalMulai);

                                                    echo date_format($date_send,'d M Y');
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 25%;">Tanggal Selesai</td>
                                            <td style="width: 1%;">:</td>
                                            <td>
                                                <?php
                                                    $date_send = date_create($tanggalSelesai);

                                                    echo date_format($date_send,'d M Y');
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">Pada Jam</td>
                                            <td style="width: 1%;">:</td>
                                            <td>
                                                <?php
                                                    echo "$setJam";
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <center>
                        <table style="margin-top: 10px;font-size: 12px;" width="70%">
                            <tr>
                                <td width="33.3%">
                                    <table style="width: 90%">
                                        <tr style="font-weight: bold;">
                                            <td style="text-align: center;">Dibuat oleh</td>
                                        </tr>
                                        <tr height="10px">
                                            <td>
                                                
                                            </td>
                                        </tr>  
                                        <tr height="10px">
                                            <td style="border-bottom: solid 1px black;">
                                               
                                            </td>
                                        </tr> 
                                    </table>
                                </td>

                                <td width="33.3%">
                                    <table style="width: 90%;">
                                        <tr style="font-weight: bold;">
                                            <td style="text-align: center;">Diverifikasi oleh</td>
                                        </tr>
                                        <tr height="10px">
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        <tr height="10px" style="border-bottom: solid 1px black;">
                                            <td></td>
                                        </tr>
                                    </table>
                                </td>

                                <td width="33.3%">
                                    <table style=";width: 90%;">
                                        <tr style="font-weight: bold;">
                                            <td style="text-align: center;">Disetujui oleh</td>
                                        </tr>
                                        <tr height="10px">
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        <tr height="10px">
                                            <td style="border-bottom: solid 1px black;"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        </center>
                    </div>
                </div>    
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
