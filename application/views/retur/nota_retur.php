<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading"> 
            <div class="portlet-widgets">
               <a onclick="printContent('area-print')" class="btn btn-primary"><i class="fa fa-print"></i> Print </a>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="portlet2" class="panel-collapse collapse in">
            <div id="area-print" class="portlet-body">
               <table width="100%">
                    <?php
                        foreach($header->result() as $hd){
                    ?>
                        <tr>
                            <td style="text-align: center;">
                                <h4><?php echo $hd->nama_perusahaan; ?></h4> 
                                <h5>RETUR</h5>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                
                <table width="100%">
                    <tr>
                        <td width="50%">
                            <table width="100%">
                                <tr>
                                    <td width="25%">No Retur</td>
                                    <td width="1%">:</td>
                                    <td><?php echo $returInfo->no_retur; ?></td>
                                </tr>

                                <tr>
                                    <td>No PO</td>
                                    <td>:</td>
                                    <td><?php echo $returInfo->no_po; ?></td>
                                </tr>

                                <tr>
                                    <td>Tanggal Retur</td>
                                    <td>:</td>
                                    <td>
                                        <?php echo date_format(date_create($returInfo->tanggal_retur),'d M Y H:i'); ?>
                                    </td>
                                </tr>

                                
                            </table>
                        </td>

                        <td width="50%" style="vertical-align: top;">
                            <table width="100%">
                                <tr>
                                    <td width="25%" style="vertical-align: top;">Supplier</td>
                                    <td width="1%" style="vertical-align: top;">:</td>
                                    <td>
                                        <?php echo $returInfo->supplier; ?> <br>
                                        <?php echo $returInfo->alamat; ?> <br>
                                        <?php echo $returInfo->kontak; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>PIC</td>
                                    <td>:</td>
                                    <td><?php echo $returInfo->nama_user; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="margin-top: 5px;border:solid 1px black;" width="100%">
                    <tr style="font-weight: bold;border-bottom: solid 1px black;">
                        <td style="border-right: solid 1px black;text-align: center;">No</td>
                        <td style="border-right: solid 1px black;padding-left: 1px;">SKU</td>
                        <td style="border-right: solid 1px black;padding-left: 1px;">Nama Produk</td>
                        <td style="border-right: solid 1px black;" align="center">Jumlah Retur</td>
                        <td style="border-right: solid 1px black;padding-left: 1px;">Satuan</td>
                        <td align="right" style="border-right: solid 1px black;padding-right: 1px;">Harga Satuan</td>
                        <td align="right" style="padding-right: 1px;">Subtotal</td>
                    </tr>

                    <?php
                        $i = 1;
                        $total = 0;
                        foreach($returItem as $row){
                    ?>
                    <tr>    
                        <td style="border-right: solid 1px black;text-align: center;"><?php echo $i; ?></td>
                        <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                        <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                        <td style="border-right: solid 1px black;" align="center"><?php echo $row->qty; ?></td>
                        <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->satuan; ?></td>
                        <td style="border-right: solid 1px black;padding-right: 1px;" align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
                        <td align="right" style="padding-right: 1px;"><?php echo number_format($row->subtotal,'0',',','.'); ?></td>
                    </tr>
                    <?php $i++; $total = $total+($row->subtotal); } ?>

                    <tr style="border-top:solid 1px black;">
                        <td colspan="6" style="text-align: center;font-weight: bold;border-right: solid 1px black;">TOTAL</td>
                        <td align="right" style="font-weight: bold;"><?php echo number_format($total,'0',',','.'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
