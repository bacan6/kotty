<h3 style="text-align: center;">Laporan Akumulasi Penjualan per Brand</h3>
<h4 style="text-align: center;">Periode</h4>
<h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($end),'d F Y'); ?></h5>
<p align=right style='margin-bottom:30px'><a href="<?php echo base_url('laporan/exportExcelakumulasiPenjualanSupplier?dateStart='.$start.'&dateEnd='.$end.'&id_toko='.$id_toko); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel</a></p>


                    <table class="table" style="font-size:11px;">
                      <tr style="font-weight: bold;">
                        <td width="4%">No</td>
                        <td>Nama Brand</td>
                        <td align="right">Qty Terjual</td>
                        <td align="right">Total HPP</td>
                        <td align="right">Total Terjual</td>
                        <td align="right">Disc. Supplier</td>
                        <td align="right">Disc. Toko</td>
                        <td align="right">Total Diskon</td>
                        <td align="right">Profit</td>
                        <td align="right">Grand Total</td>
                      </tr>

                      <?php
                        $i = 1;
                        $qty_terjual = 0;
                        $total_hpp = 0;
                        $total_terjual = 0;
                        $profit = 0;
                        $diskon = 0;
                        $disc_supplier = 0;
                        $disc_toko = 0;
                        $grand_total = 0;

                        $count = $laporanAkumulasiSupplier->num_rows();

                        if($count > 0){

                        foreach($laporanAkumulasiSupplier->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->brand; ?></td> 
                        <td align="right"><?php echo number_format($row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->hpp,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->disc_supplier,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->disc_toko,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                        <td align="right">
                          <?php
                            echo number_format(($row->harga_jual-$row->diskon)-($row->hpp),'0',',','.');
                          ?>
                        </td>
                        <td align="right">
                          <?php
                            echo number_format(($row->harga_jual-$row->diskon),'0',',','.');
                          ?>
                        </td>
                      </tr>
                      <?php 
                        $qty_terjual    = $qty_terjual + $row->qty_terjual; 
                        $total_hpp      = $total_hpp+($row->hpp);
                        $total_terjual  = $total_terjual+($row->harga_jual);
                        $diskon         = $diskon + $row->diskon;
                        $profit         = $profit+(($row->harga_jual)-$row->diskon-($row->hpp));
                        $disc_supplier  += $row->disc_supplier;
                        $disc_toko      += $row->disc_toko;
                        $grand_total    += ($row->harga_jual-$row->diskon);
                        $i++; 
                        } 
                      ?>
                      

                      <tr style="font-weight: bold;">
                        <td colspan="2" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($total_hpp,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($total_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($disc_supplier,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($disc_toko,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($profit,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($grand_total,'0',',','.'); ?></td>
                      </tr>

                    <?php } else { ?>
                        <tr>
                          <td colspan="10" align="center">--BELUM ADA DATA UNTUK DITAMPILKAN--</td>
                        </tr>
                    <?php } ?>
                    </table>