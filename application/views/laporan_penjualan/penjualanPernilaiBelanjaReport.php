

                      <h3 style="text-align: center;">Laporan Penjualan Berdasarkan Nilai Belanja</h3>
                      <h3 style="text-align: center;">Between <?php echo number_format($_GET['first_value'],'0',',','.')."-".number_format($_GET['second_value'],'0',',','.'); ?></h3>
                      <h4 style="text-align: center;">Periode</h4>
                      <h5 style="text-align: center;"><?php echo date_format(date_create($_GET['date_start']),'d F Y')." - ".date_format(date_create($_GET['date_end']),'d F Y'); ?></h5>


                    <table class="table table-striped" style="font-size:11px;">
                      <tr style="background: #2A303A;color:white;font-weight: bold;">
                        <td width="4%">No</td>
                        <td width="13%">No Invoice</td>
                        <td>Tanggal</td>
                        <td>Customer</td>
                        <td>Tipe Bayar</td>
                        <td align="right">Subtotal</td>
                        <td align="right">Ongkir</td>
                        <td align="right">Diskon Channel</td>
                        <td align="right">Diskon</td>
                        <td align="right">Poin Reimburs</td>
                        <td align="right">Diskon Peritem</td>
                        <td align="right">Total</td>
                      </tr>

                      <?php
                        if(empty($_GET['date_start'])){
                      ?>
                      <tr>
                        <td colspan="11" align="center">--BELUM ADA DATA UNTUK DITAMPILKAN--</td>
                      </tr>
                      <?php } else { ?>

                      <?php
                        $i = 1;
                        $subtotal      = 0;
                        $ongkir        = 0;
                        $diskon_ch     = 0;
                        $diskon        = 0;
                        $poin_reimburs = 0;
                        $grand_total   = 0;
                        $diskon_peritem= 0;
                        foreach($penjualan_pernilai_belanja->result() as $row){
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->no_invoice; ?></td>
                        <td><?php echo date_format(date_create($row->tanggal),'d/m/y H:i'); ?></td>
                        <td><?php echo $row->nama; ?></td>
                        <td><?php echo $row->payment_type." ".$row->account; ?></td>
                        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->ongkir,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon_otomatis,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis),'0',',','.'); ?></td>
                      </tr>

                      <?php 
                        $i++;
                        $subtotal      = $subtotal+$row->total;
                          $ongkir        = $ongkir+$row->ongkir;
                          $diskon_ch     = $diskon_ch+$row->diskon;
                          $diskon        = $diskon+$row->diskon_free;
                          $poin_reimburs = $poin_reimburs+$row->poin_value;
                          $diskon_peritem = $diskon_peritem+$row->diskon_otomatis;
                          $grand_total   = $grand_total+(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value)+$row->diskon_otomatis);
                      } //end foreach ?>

                      <!-- DECLARE TOTAL-->
                      <tr style="background: white;font-weight: bold;">
                        <td colspan="5" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($subtotal,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($ongkir,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon_ch,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($poin_reimburs,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon_peritem,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($grand_total,'0',',','.'); ?></td>
                      </tr>

                      <?php } ?>
                    </table>