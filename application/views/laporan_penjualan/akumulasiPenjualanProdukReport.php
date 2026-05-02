<h3 style="text-align: center;">Laporan Akumulasi Penjualan Produk</h3>
<h4 style="text-align: center;">Periode</h4>
<h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($start),'d F Y'); ?></h5>

<button type="button" class="btn btn-success" onclick="window.location='<?php echo base_url('laporan/exportExcelakumulasiPenjualanProduk?dateStart='.$start.'&dateEnd='.$end.'&id_toko='.$id_toko);?>'" style="margin:20px"><i class="fa fa-print"></i> Print</i></button>
                    <table class="table" style="font-size:11px;">
                      <tr style="font-weight: bold;">
                        <td width="4%">No</td>
                        <td width="13%">SKU</td>
                        <td>Nama Produk</td>
                        <td>Satuan</td>
                        <td align="right">Harga Beli</td>
                        <td align="right">Harga Jual</td>
                        <td align="right">Qty Terjual</td>
                        <td align="right">Stok</td>
                        <td align="right">Total HPP</td>
                        <td align="right">Total Terjual</td>
                        <td align="right">Profit</td>
                      </tr>

                      <?php
                        $i = 1;
                        $qty_terjual = 0;
                        $total_hpp = 0;
                        $total_terjual = 0;
                        $profit = 0;

                        $count = $laporanAkumulasiProduk->num_rows();

                        if($count > 0){

                        foreach($laporanAkumulasiProduk->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->id_produk; ?></td>
                        <td><?php echo $row->nama_produk; ?></td> 
                        <td><?php echo $row->satuan; ?></td>
                        <td align="right"><?php echo number_format($row->hpp,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->stok,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->hpp*$row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga_jual*$row->qty_terjual,'0',',','.'); ?></td>
                        <td align="right">
                          <?php
                            echo number_format(($row->harga_jual*$row->qty_terjual)-($row->hpp*$row->qty_terjual),'0',',','.');
                          ?>
                        </td>
                      </tr>
                      <?php 
                        $qty_terjual    = $qty_terjual + $row->qty_terjual; 
                        $total_hpp      = $total_hpp+($row->hpp*$row->qty_terjual);
                        $total_terjual  = $total_terjual+($row->harga_jual*$row->qty_terjual);
                        $profit         = $profit+(($row->harga_jual*$row->qty_terjual)-($row->hpp*$row->qty_terjual));
                        $i++; 
                        } 
                      ?>
                      

                      <tr style="font-weight: bold;">
                        <td colspan="6" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($qty_terjual,'0',',','.'); ?></td>
                        <td>&nbsp;</td>
                        <td align="right"><?php echo number_format($total_hpp,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($total_terjual,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($profit,'0',',','.'); ?></td>
                      </tr>

                    <?php } else { ?>
                        <tr>
                          <td colspan="11" align="center">--BELUM ADA DATA UNTUK DITAMPILKAN--</td>
                        </tr>
                    <?php } ?>
                    </table>