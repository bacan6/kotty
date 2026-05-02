<h3 style="text-align: center;">Laporan Penjualan Per Departemen Produk</h3>
<h4 style="text-align: center;">Periode</h4>
<h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($end),'d F Y'); ?></h5>


                          <table width="100%" class="table">
                            <tr style="font-weight: bold;">
                              <td>SKU</td>
                              <td width="30%">Nama Produk</td>
                              <td align="right" width="14%">Harga</td>
                              <td align="right" width="14%">Qty</td>
                              <td align="right" width="14%">Total</td>
                              <td align="right" width="14%">Diskon</td>
                              <td align="right" width="14%">Grand Total</td>
                              <td align="right" width="14%">HPP</td>
                              <td align="right" width="14%">Laba</td>
                              <td align="right" width="14%">Margin</td>
                            </tr>

                            <?php
                              
                              $count = $sales_perkategori->num_rows();

                              if($count > 0) {

                              $diskon = 0;
                              $total = 0;
                              $tlaba = 0;
                              $tmargin = 0;
                              $thpp = 0;
                              ini_set('display_errors', 0);
                              error_reporting(0);
                              foreach($sales_perkategori->result() as $dt){
                                $laba = ($dt->harga_jual*$dt->qty)-($dt->hpp*$dt->qty)-$dt->diskon + 0;
                                $hpp = intval($dt->hpp*$dt->qty);
                                $margin = intval($laba)/intval($dt->harga_jual*$dt->qty)*100;
                                $tlaba += $laba;
                                $thpp +=($dt->hpp*$dt->qty);
                                $thjual += $dt->harga_jual*$dt->qty;
                            ?>
                            <tr>
                              <td><?php echo $dt->id_produk; ?></td>
                              <td><?php echo $dt->nama_produk; ?></td>
                              <td align="right"><?php echo number_format($dt->harga_jual,'0',',','.') ?></td>
                              <td align="right"><?php echo number_format($dt->qty,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($dt->total,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($dt->diskon,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($dt->total-$dt->diskon,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format(($dt->hpp*$dt->qty),'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($laba,'0',',','.'); ?></td>
                              <td align="right"><?php echo number_format($margin,'2',',','.'); ?></td>
                            </tr>
                            <?php $total = $total+$dt->total; $diskon = $diskon+$dt->diskon; }
                            $tmargin = ($tlaba/$thjual)*100; ?>

                            <tr style="font-weight: bold;">
                              <td colspan="4" style="font-weight: bold;text-align: center;">TOTAL</td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($diskon,'0',',','.'); ?></td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($total,'0',',','.'); ?></td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($thpp,'0',',','.'); ?></td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($tlaba,'0',',','.'); ?></td>
                              <td align="right" style="font-weight: bold;"><?php echo number_format($tmargin,'2',',','.'); ?></td>
                            </tr>

                          <?php } else { ?>

                            <tr>
                              <td colspan="6" align="center">BELUM ADA DATA UNTUK DITAMPILKAN</td>
                            </tr>

                          <?php } ?>
                          </table>
