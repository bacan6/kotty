<h3 style="text-align: center;">Laporan Akumulasi Pembelian per Brand</h3>
<h4 style="text-align: center;">Periode</h4>
<h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($end),'d F Y'); ?></h5>
<p align=right style='margin-bottom:30px'><a href="<?php echo base_url('laporan/exportExcelakumulasiPembelianBrand?dateStart='.$start.'&dateEnd='.$end.'&toko='.$toko); ?>" class="btn btn-success"><i class="fa fa-file-excel-o"></i> Export to Excel</a></p>


                    <table class="table" style="font-size:11px;">
                      <tr style="font-weight: bold;">
                        <td width="4%">No</td>
                        <td>Nama Brand</td>
                        <td align="right">Qty Received</td>
                        <td align="right">Harga Beli</td>
                        <td align="right">Harga Jual</td>
                      </tr>

                      <?php
                        $i = 1;
                        $rpRec = 0;

                        $count = $laporanAkumulasiBrand->num_rows();

                        if($count > 0){

                        foreach($laporanAkumulasiBrand->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->brand; ?></td> 
                        <td align="right"><?php echo number_format($row->qty,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->hargajual,'0',',','.'); ?></td>
                        
                      </tr>
                      <?php 
                        $rpRec    += $row->harga;
                        $i++; 
                        } 
                      ?>
                      

                      <tr style="font-weight: bold;">
                        <td colspan="3" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($rpRec,'0',',','.'); ?></td>
                      </tr>

                    <?php } else { ?>
                        <tr>
                          <td colspan="10" align="center">--BELUM ADA DATA UNTUK DITAMPILKAN--</td>
                        </tr>
                    <?php } ?>
                    </table>