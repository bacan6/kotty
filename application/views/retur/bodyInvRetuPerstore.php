<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">

                    <div class="col-md-12" style="text-align: right;">
                        <a class="btn btn-default" onclick="printContent('print-area')"><i class="fa fa-print"></i> Print</a>
                
                    </div>
                </div>
                <div class="row" id="print-area">
                    <div class="col-md-12" style="text-align: center;font-weight: bold;">
                        <?php
                            foreach($infoCompany as $in){
                        ?>
                        <?php echo $in->nama_perusahaan; ?> <br>
                        Retur
                        <?php } ?>
                    </div>

                    <div class="col-md-12" style="margin-top: 5px;">
                        <?php
                            foreach($infoRetur as $rt){
                        ?>
                        <table width="100%" style="font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td width="20%">No Retur</td>
                                            <td width="1%">:</td>
                                            <td><?php echo $rt->NoRetur; ?></td>
                                        </tr>

                                        <tr>
                                            <td>Tanggal</td>
                                            <td>:</td>
                                            <td><?php echo date_format(date_create($rt->tanggal),"d M Y H:i"); ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td width="20%">User</td>
                                            <td width="1%">:</td>
                                            <td><?php echo $rt->first_name; ?></td>
                                        </tr>  

                                        <tr>
                                            <td>Retur Dari</td>
                                            <td>:</td>
                                            <td><?php echo $rt->store; ?></td>
                                        </tr>  
                                    </table>  
                                </td>
                            </tr>
                        </table>
                        <?php } ?>
                    </div>

                    <div class="col-md-12" style="margin-top: 5px;">
                        <table style="font-size: 12px;border:solid 1px black;" width="100%">
                            <tr style="border-bottom: solid 1px black;">
                                <th width="5%" style="text-align: center;border-right:solid 1px black;">No</th>
                                <th width="13%" style="border-right: solid 1px black;padding-left: 1px;">SKU</th>
                                <th style="border-right: solid 1px black;padding-left: 1px;">Nama Produk</th>
                                <th width="10%" style="text-align: right;padding-right: 1px;">Jumlah Retur</th>
                            </tr>

                            <?php
                                $i = 1;
                                $total = 0;
                                foreach($returItem as $row){
                            ?>
                            <tr>
                                <td style="text-align: center;border-right:solid 1px black;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->sku; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td align="right" style="padding-right: 1px;"><?php echo number_format($row->qty,'0',',','.'); ?></td>
                            </tr>
                            <?php $i++; $total = $total+$row->qty; } ?>

                            <tr style="border-top:solid 1px black;">
                                <td style="text-align: center;font-weight: bold;border-right: solid 1px black;" colspan="3">TOTAL</td>
                                <td align="right" style="font-weight: bold;padding-right: 1px;"><?php echo number_format($total,'0',',','.'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
