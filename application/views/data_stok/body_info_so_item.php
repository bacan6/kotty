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
                                    <b style="color:red">Kartu Stok Produk</b><br>
                                    <i>Kartu stok mulai dioperasikan per tanggal 8 Oktober 2023.</i>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;margin-bottom:30px">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="5%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" >Nama Produk</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">Tanggal</td>
                                <td style="text-align: center;border-right: solid 1px black;">No. Transaksi</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Saldo</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Username</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Nama</td>
                                <td width="10%" style="text-align: center;border-right: solid 1px black;">Keterangan</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;$row=''; $saldo = 0;
                                foreach($info_KartuStok->result() as $row){
                                    $saldo += $row->qty;
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->no_transaksi; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $saldo; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->username; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->first_name; ?></td>
                                <td style="border-right: solid 1px black;padding-left:10px"><?php echo "<b>".$row->tipe."</b><br>".$row->keterangan; ?></td>
                            </tr>
                            <?php $i++; $value+=$row->qty;
                        } ?>
                        <tr>
                            <td colspan=5 align=center style="border-top:solid 1px black"><b>TOTAL</b></td>
                            <td align=center style="border-top:solid 1px black"><?php echo $value;?></td>
                            <td colspan=4 style="border-top:solid 1px black"></td>
                        </tr>

                        </table>

                        <!-- end kartu stok -->
                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    INFO STOCK OPNAME ITEM
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="15%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" >Nama Produk</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">tanggal SO</td>
                                <td style="text-align: center;border-right: solid 1px black;">nomor SO</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Stok Awal</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Stok Akhir</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Keterangan</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Username</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Nama</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;
                                foreach($info_SO->result() as $row){
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->sku; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->no_so; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->stok_before; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->stok_after; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->keterangan; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->username; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->first_name; ?></td>
                            </tr>
                            <?php $i++; } ?>

                        </table>

                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    INFO RECEIVING ITEM
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="15%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" >Nama Produk</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">tanggal</td>
                                <td style="text-align: center;border-right: solid 1px black;">No Receive</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Bonus</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Username</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Nama</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;$row='';
                                foreach($info_Receive->result() as $row){
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->sku; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->no_receive; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->bonus; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->username; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->first_name; ?></td>
                            </tr>
                            <?php $i++; $value+=$row->qty+$row->bonus;
                        } ?>
                        <tr>
                            <td colspan=5 align=center style="border-top:solid 1px black">TOTAL</td>
                            <td align=center style="border-top:solid 1px black"><?php echo $value;?></td>
                            <td colspan=2 style="border-top:solid 1px black"></td>
                        </tr>

                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    INFO SALES ITEM
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="15%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" >Nama Produk</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">tanggal</td>
                                <td style="text-align: center;border-right: solid 1px black;">nomor INV</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Keterangan</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Username</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Nama</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;$row='';
                                foreach($info_Sales->result() as $row){
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->no_invoice; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->keterangan; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->username; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->first_name; ?></td>
                            </tr>
                            <?php $i++; $value+=$row->qty;
                        } ?>
                        <tr>
                            <td colspan=5 align=center style="border-top:solid 1px black">TOTAL</td>
                            <td align=center style="border-top:solid 1px black"><?php echo $value;?></td>
                            <td colspan=3 style="border-top:solid 1px black"></td>
                        </tr>

                        </table>

                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    INFO TRANSFER STOK (DIKIRIM)
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="15%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" >Nama Produk</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">Tanggal Kirim</td>
                                <td style="text-align: center;border-right: solid 1px black;">No. Transfer</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Keterangan</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Pengirim</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">Diterima Tujuan</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty Diterima</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;$row='';
                                foreach($info_Transfer->result() as $row){
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->idProduk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->noTransfer; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->keterangan; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->first_name; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal_terima; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty_rec; ?></td>
                            </tr>
                            <?php $i++; $value+=$row->qty;
                        } ?>
                        <tr>
                            <td colspan=5 align=center style="border-top:solid 1px black">TOTAL</td>
                            <td align=center style="border-top:solid 1px black"><?php echo $value;?></td>
                            <td colspan=5 style="border-top:solid 1px black"></td>
                        </tr>

                        </table>

                        <table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    INFO TRANSFER STOK (DITERIMA)
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" style="border-right: solid 1px black;text-align: center;">No</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" width="15%">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;" >Nama Produk</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">Tanggal Terima</td>
                                <td style="text-align: center;border-right: solid 1px black;">No. Transfer</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Keterangan</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Penerima</td>
                                <td width="15%" style="text-align: center;border-right: solid 1px black;">Tanggal Kirim</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Qty Dikirim</td>
                            </tr>

                            <?php
                                $i=1;
                                $value = 0;$row='';
                                foreach($info_TransferDiterima->result() as $row){
                            ?>
                            <tr>
                                <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->idProduk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal_terima; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->noTransfer; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty_rec; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->keterangan; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->first_name; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->tanggal; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                            </tr>
                            <?php $i++; $value+=$row->qty;
                        } ?>
                        <tr>
                            <td colspan=5 align=center style="border-top:solid 1px black">TOTAL</td>
                            <td align=center style="border-top:solid 1px black"><?php echo $value;?></td>
                            <td colspan=5 style="border-top:solid 1px black"></td>
                        </tr>

                        </table>
                    </div>
                </div>    
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
