<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->    
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
	                <div class="col-md-12" style="text-align: right;">
	                    <a onclick="printContent('area-print')" class="btn btn-default"><i class="fa fa-print"></i> Print </a>
	                </div>
                </div>

                <div class="row" id="area-print" style="font-family:arial;font-size: 12px;">
                	<div class="col-md-12">
                		<table width="100%">
                            <?php
                                foreach($header->result() as $hd){
                            ?>
                            <tr>
                                <td style="text-align: center;">
                                    <?php echo $hd->nama_perusahaan; ?><br>
                                    Form Pengeluaran Barang
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                        <table width="100%" style="font-size: 12px;">
                            <?php
                                foreach($info->result() as $dt){
                            ?>
                            <tr>
                                <td width="50%">
                                    
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;width: 30%;">No Pengeluaran</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $dt->no_bahan_keluar; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:  bold;">Tanggal</td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                            	<?php
                                            		$tanggal_keluar = date_create($dt->tanggal_keluar);

                                            		echo date_format($tanggal_keluar,'d M Y H:i');
                                            	?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table width="100%">
                                        <tr>
                                            <td style="font-weight:  bold;">Store Tujuan</td>
                                            <td style="width: 3%;">:</td>
                                            <td>
                                                <?php 
                                                    echo $dt->store;
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:  bold;">Keterangan</td>
                                            <td style="width: 3%;">:</td>
                                            <td><?php echo $dt->keterangan; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

         
                        <table width="100%" style="font-size: 12px;border-bottom: solid 1px black;border-left:solid 1px black;border-right:solid 1px black;">
                            <tr style="font-weight: bold;border: solid 1px black;">
                                <td width="4%" style="text-align: center;border-right: solid 1px black;">No</td>
                                <td width="15%" style="border-right: solid 1px black;padding-left: 2px;">SKU</td>
                                <td style="border-right: solid 1px black;padding-left: 2px;">Produk</td>
                                <td width="10%" style="text-align: center;border-right: solid 1px black;">Qty</td>
                                <td width="12%" style="text-align: right;padding-right: 1px;border-right: solid 1px black;">Harga Beli</td>
                                <td width="12%" style="text-align: right;padding-right: 1px;">Jumlah</td>
                            </tr>	
                            <?php
                            	$i=1;$total=0;
                            	foreach($spending_item->result() as $row){
                            ?>	
                            <tr>
                            	<td style="text-align: center;border-right: solid 1px black;"><?php echo $i; ?></td>
                                <td style="border-right: solid 1px black;padding-left: 2px;"><?php echo $row->id_produk; ?></td>
                            	<td style="border-right: solid 1px black;padding-left: 2px;"><?php echo $row->nama_produk; ?></td>
                            	<td align="center" style="border-right: solid 1px black;"><?php echo $row->qty; ?></td>
                            	<td align="right" style="border-right: solid 1px black;padding-right: 1px;">
                                    <?php 
                                        $harga = $row->hpp;


                                        if($harga){
                                            echo number_format($harga,'2',',','.');
                                            $jumlah = $row->qty*$harga;
                                            $total += $jumlah;
                                        } else {
                                            echo "Harga Belum Diinput";
                                        }
                                    ?>
                                </td>
                                <td align="right" style="padding-right: 1px;">
                                    <?php echo number_format($jumlah,'2',',','.');?>
                                </td>
                            </tr>
                            <?php $i++; } ?>
                            <tr>
                                <td colspan="5" align="right" style="border-right: solid 1px black;border-top: solid 1px black;padding-right: 10px">
                                    <b>T O T A L</b>
                                </td>
                                <td align="right" style="border-right: solid 1px black;border-top: solid 1px black;padding-right: 10px">
                                    <b><?php echo number_format($total,'2',',','.');
                                    ?></b>
                                </td>
                            </tr>
                        </table> 

                        <center>
                        <table style="margin-top: 5px;" width="100%">
                            <tr>
                                <td width="50%" align="center">
                                    <table style="font-size: 12px;">
                                        <tr>
                                            <td style="text-align: center;">Created By</td>
                                        </tr>
                                        <tr height="10px">
                                            <td>
                                                
                                            </td>
                                        </tr>  
                                        <tr height="10px">
                                            <td style="text-align: center;font-weight: bold;">
                                                <?php echo $dt->first_name; ?>
                                            </td>
                                        </tr> 
                                    </table>
                                </td>

                                <td width="50%" align="center">
                                    <table style="font-size: 12px;">
                                        <tr>
                                            <td style="text-align: center;">Receive By</td>
                                        </tr>
                                        <tr height="10px">
                                            <td>
                                               
                                            </td>
                                        </tr>
                                        <tr height="10px">
                                            <td style="text-align: center;font-weight: bold;">
                                                <?php echo $dt->nama_penerima; ?>
                                            </td>
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
