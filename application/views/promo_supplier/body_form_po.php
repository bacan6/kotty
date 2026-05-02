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
                                    Promosi Produk
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="margin-top: 5px;font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td style="width: 15%;">Tanggal</td>
                                            <td style="width: 1%;">:</td>
                                            <td>
                                                <?php
                                                    $date_po = date_create($tanggal_buat);

                                                    echo date_format($date_po,'d M Y');
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width: 15%;">No Promo</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo $_GET['no_promo']; ?></td>
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
                                            <td style="width: 15%;">Tipe</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo ($tipe==0?"All Customer":($tipe==1?'K-Member':'Twenties')); ?></td>
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

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="4%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="12%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="18%">Nama Produk</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="14%">Cabang</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td style="text-align: center;border-right: solid 1px black;">Diskon<br>Toko</td>
                                <td style="text-align: center;border-right: solid 1px black;">Diskon<br>Supplier</td>
                                <td style="text-align: center;border-right: solid 1px black;">Quota<br>(Pcs)</td>
                                <td style="text-align: center;border-right: solid 1px black;">Quota<br>(Rp)</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;
                                foreach($purchase_item->result() as $row){
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo isset($row->nama_cabang) ? htmlspecialchars($row->nama_cabang) : ''; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo number_format($row->discount,'0',',','.'); ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo number_format($row->disc_supplier,'0',',','.'); ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo number_format($row->quota,'0',',','.'); ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo number_format($row->quotarp,'0',',','.'); ?></td>
                            </tr>
                            <?php  $i++; } ?>

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
