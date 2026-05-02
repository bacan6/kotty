
<h3 style="text-align: center;">Laporan Penjualan Pertoko</h3>
<h3 style="text-align: center;"><?php echo $storeName; ?></h3>
<h4 style="text-align: center;">Periode</h4>
<h5 style="text-align: center;"><?php echo date_format(date_create($start),'d F Y')." - ".date_format(date_create($end),'d F Y'); ?></h5>

                    <table class="table" style="font-size:11px;">
                      <tr style="font-weight: bold;">
                        <td width="4%">No</td>
                        <td width="13%">No Invoice</td>
                        <td>Tanggal</td>
                        <td align="right">Subtotal</td>
                        <td align="center">Diskon<br>Channel</td>
                        <td align="right">Diskon</td>
                        <td align="center">Poin<br>Reimburs</td>
                        <td align="center">Diskon<br>Peritem</td>
                        <td align="right">Total</td>
                        <td align="right">Margin</td>
                        <td align="center">%</td>
                      </tr>

                      <?php
                        $rows = $laporanPertoko->num_rows();

                        if($rows < 1){
                      ?>

                      <tr>
                        <td colspan="10" align="center">--BELUM ADA DATA UNTUK DI TAMPILKAN--</td>
                      </tr>
                      <?php } else { ?>

                      <?php
                        $i=1;
                        $subtotal        = 0;
                        $ongkir          = 0;
                        $diskon_ch       = 0;
                        $diskon          = 0;
                        $poin_reimburs   = 0;
                        $grand_total     = 0;
                        $diskon_otomatis = 0;
                        $laba            = 0;
                        $margin          = 0;
                        $persen          = 0;
                        foreach($laporanPertoko->result() as $row){
                          $persen = $row->total>0?((($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis)-$row->hpp)/(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis))*100):0;
                      ?>

                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><a href="#modalDetail" class="detailPenjualan" id="<?php echo $row->no_invoice; ?>" data-toggle="modal"><?php echo $row->no_invoice; ?></a></td>
                        <td><?php echo date_format(date_create($row->tanggal),'d/m/y H:i'); ?></td>
                        <td align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->diskon_free,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->poin_value,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->total>0?$row->diskon_otomatis:0,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->total>0?($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis):0,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($row->total>0?($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis)-$row->hpp:0,'0',',','.'); ?></td>
                        <td align="center"><?php echo number_format($persen,'2',',','.'); ?>%</td>
                      </tr>
                      <?php 
                          $i++; 
                          $subtotal         = $subtotal+$row->total;
                          $ongkir           = $ongkir+$row->ongkir;
                          $diskon_ch        = $diskon_ch+$row->diskon;
                          $diskon           = $diskon+($row->total>0? $row->diskon_free:0);
                          $poin_reimburs    = $poin_reimburs+$row->poin_value;
                          $diskon_otomatis  = $diskon_otomatis+($row->total>0?$row->diskon_otomatis:0);
                          $grand_total      = $grand_total+($row->total>0?($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis):0);
                          $margin = $margin + ($row->total>0?($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis)-$row->hpp:0);
                        } //end foreach
                      ?>

                      <!-- DECLARE TOTAL-->
                      <tr style="background: white;font-weight: bold;">
                        <td colspan="3" align="center">TOTAL</td>
                        <td align="right"><?php echo number_format($subtotal,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon_ch,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($poin_reimburs,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($diskon_otomatis,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($grand_total,'0',',','.'); ?></td>
                        <td align="right"><?php echo number_format($margin,'0',',','.'); ?></td>
                        <td align="center"><?php echo number_format(($margin/$grand_total)*100,'2',',','.'); ?>%</td>
                      </tr>

                      <?php } ?>
                    </table>
<p align=right>
    <strong>
        Basket Size: Rp.<?php echo number_format(($grand_total/$i),'0',',','.');?><br>
        Sisa Inventori: Rp.<?php echo number_format($totalInv[0]->nilai,'0',',','.');?>
    </strong>
</p>
<script type="text/javascript">
  $('.detailPenjualan').on("click",function(){
    var noInvoice = this.id;

    var url = "<?php echo base_url('laporan/detailPenjualan'); ?>";

    $.ajax({
              method      : "POST",
              data        : {noInvoice : noInvoice},
              url         : url,
              beforeSend  : function(){
                              var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";
                              $('#dataPenjualan').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
                            },
              success     : function(data){
                              $('#dataPenjualan').html(data);
                            }
    });
  });
</script>

