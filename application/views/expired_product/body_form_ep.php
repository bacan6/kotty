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
                                    Produk Kadaluarsa
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="margin-top: 5px;font-size: 12px;">
                            <tr>
                                <td width="50%">
                                    <table width="100%">

                                        <tr>
                                            <td style="width: 15%;">Nomor</td>
                                            <td style="width: 1%;">:</td>
                                            <td><?php echo $_GET['no_po']; ?></td>
                                        </tr>
                                        

                                                                            
                                    </table>
                                </td>

                            </tr>
                        </table>

                        
                        <table width="100%" class="table table-bordered" border="1" cellspacing="0" cellpadding="4" style="margin-top: 5px;font-size: 12px;">
                        <tr>
                            <th>No.</th>
                            <th>ID Produk</th>
                            <th>Nama</th>
                            <th>Qty</th>
                            <th>HPP</th>
                            <th>Subtotal 1</th>
                            <th>Harga</th>
                            <th>Subtotal 2</th>
                        </tr>
                            <?php
                                $i=1;
                                $value = 0;$total=0;$totalHPP=0;
                                foreach($expired_product_item->result() as $row){
                                    $total += $row->qty*$row->harga;
                                    $totalHPP += $row->qty*$row->hpp;
                            ?>
                            
                            <tr>
                                <td><?php echo $i?></td>
                                <td><?php echo $row->id_produk; ?></td>
                                <td><?php echo strtoupper($row->nama_produk); ?></td>
                                <td><?php echo $row->qty; ?></td>
                                <td><?php echo number_format($row->hpp,0); ?></td>
                                <td><?php echo number_format($row->qty*$row->hpp,0); ?></td>
                                <td><?php echo number_format($row->harga,0); ?></td>
                                <td><?php echo number_format($row->qty*$row->harga,0); ?></td>
                                </tr>
                            
                                
                            
                            <?php $value = $value+$row->total; $i++; } ?>
                            <tr><td colspan='5' align='center'><b>TOTAL</b></td>
                            <td><?php echo number_format($totalHPP,0)?></td>
                            <td>&nbsp;</td>
                            <td><?php echo number_format($total,0)?></td></tr>
                            </table>
                            <?php
                                    $uri = $this->uri->segment(1);

                                    ?>
                        
                    </div>
                </div>    
            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>
