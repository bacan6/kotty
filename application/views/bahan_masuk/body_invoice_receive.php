<?php error_reporting(0);ini_set('display_errors',0);?>
<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->    
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
	                <div class="col-md-12" style="text-align: right;">
	                    <a onclick="printContent('area-print')" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
	                </div>
                </div>

                <div class="row" id="area-print">
                	<div class="col-md-12">
                		<table width="100%" style="font-size: 13px;">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php echo $hd->nama_perusahaan; ?> <br>
                                    <u>Goods Received / Nota Penerimaan</u>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <?php
                            foreach($dataReceive as $row){
                                $diskon = $row->diskon;
                        ?>
                        <table width="100%" style="margin-top: 5px;font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">No Receive</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->no_receive; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">No PO</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->no_po; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Received date</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo date_format(date_create($row->tanggal_terima),'d F Y'); ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Received by</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->received_by; ?></td>
                                        </tr>
                                    </table>
                                </td>

                                <td style="vertical-align: top;">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">Checked by</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->checked_by; ?></td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Received at </td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                                <?php
                                                    if($row->diterimaDi==0){
                                                        echo "Gudang";
                                                    } else {
                                                        $namaStore = $this->model1->namaStore($row->diterimaDi);                                               
                                                        echo $namaStore;
                                                    }
                                                ?>  
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Supplier</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->supplier;?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:  bold;">No. Faktur</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->nofaktur;?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:  bold;">Term of Payment</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $row->termOfPay;?></td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                        <?php } ?>

                        <table style="margin-top: 5px;width: 100%;border:solid 1px black;font-size: 12px;">
                            <tr style="font-weight: bold;border-bottom: solid 1px black;">
                                <td width="5%" align="center" style="border-right: solid 1px black;">No</td>
                                <td width="12%" style="border-right: solid 1px black;padding-left: 1px;">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 1px;">Nama Produk</td>
                                <td width="8%" style="text-align: center;border-right: solid 1px black;">Qty Bonus</td>
                                <td width="8%" style="text-align: center;border-right: solid 1px black;">Quantity</td>
                                <td width="10%" style="text-align: center;border-right: solid 1px black;">Harga Beli</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Disc1</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Disc2</td>
                                <td width="5%" style="text-align: center;border-right: solid 1px black;">Disc3</td>
                                <td width="10%" style="text-align: center;">Jumlah</td>
                           
                            </tr>

                            <?php
                                $i=1;
                                $total = 0;$subtotal=0;$diskon1 = 0;$diskon2 = 0; $diskon3 = 0; $totDiskon=0; $setPPN=0;
                                foreach($receive_item->result() as $row){
                                    
                                    $diskon1 = ($row->diskon1/100)*(($row->price*$row->qty));
                                    $diskon2 = ($row->diskon2/100)*(($row->price*$row->qty)-$diskon1);

                                    $diskon3 = $row->diskon3*$row->qty;

                                    $totDiskon = $diskon1 + $diskon2 + $diskon3;
                                    $subtotal = ($row->price*$row->qty)-$totDiskon;
                                    $setPPN = $row->ppn;
                                    $total+=$subtotal;
                            ?>
                            <tr>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->id_produk; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 1px;"><?php echo $row->nama_produk; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->bonus; ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                                <td style="text-align: right;border-right: solid 1px black;padding-right: 5px"><?php echo number_format($row->price,'2',',','.'); ?></td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->diskon1; ?>%</td>
                                <td style="text-align: center;border-right: solid 1px black;"><?php echo $row->diskon2; ?>%</td>
                                <td style="text-align: right;border-right: solid 1px black;padding-right: 5px">Rp<?php echo $row->diskon3; ?></td>
                                <td style="text-align: right;padding-right: 5px"><?php echo number_format($subtotal,'2',',','.'); ?></td>
                            </tr>
                            <?php $i++; } ?>
                            <tr>
                                <td colspan="9" align="right" style="border-right: solid 1px black;border-top: solid 1px black;padding-right: 5px">
                                    <b>S U B T O T A L</b>
                                </td>
                                <td align="right" style="border-top: solid 1px black;padding-right: 5px">
                                    <b><?php echo number_format($total,'2',',','.'); ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9" align="right" style="border-right: solid 1px black;border-top: solid 1px black;padding-right: 5px">
                                    <b>DISKON GLOBAL</b>
                                </td>
                                <td align="right" style="border-top: solid 1px black;padding-right: 5px">
                                    <b><?php echo number_format($diskon,'2',',','.'); ?></b>
                                </td>
                            </tr>
                            <?php 
                            if ($setPPN==1){
                                $ppn = (($total-$diskon)*0.11);
                            }else $ppn = 0;
                            ?>
                            
                            <tr>
                                <td colspan="9" align="right" style="border-right: solid 1px black;border-top: solid 1px black;padding-right: 5px">
                                    <b>PPN (11%)</b>
                                </td>
                                <td align="right" style="border-top: solid 1px black;padding-right: 5px">
                                    <b><?php echo number_format($ppn,'2',',','.'); ?></b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9" align="right" style="border-right: solid 1px black;border-top: solid 1px black;padding-right: 5px">
                                    <b>T O T A L</b>
                                </td>
                                <td align="right" style="border-top: solid 1px black;padding-right: 5px">
                                    <b><?php echo number_format($total-$diskon+$ppn,'2',',','.'); ?></b>
                                </td>
                            </tr>
                        </table>       

                        <table width="100%">
                            <tr>
                                <td width="50%" style="text-align: center;">
                                    <center>
                                    <table width="50%">
                                        <tr>
                                            <td style="height: 50px;border-bottom: solid 1px black;text-align: center;font-size: 12px;">
                                                Penerima
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </td>

                                <td width="50%" style="text-align: center;">
                                    <center>
                                    <table width="50%">
                                        <tr>
                                            <td style="height: 50px;border-bottom: solid 1px black;text-align: center;font-size: 12px;">
                                                Pemeriksa
                                            </td>
                                        </tr>
                                    </table>
                                    </center>
                                </td>
                            </tr>
                        </table> 
                	</div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
