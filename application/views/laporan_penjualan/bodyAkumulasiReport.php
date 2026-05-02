<?php
   $i=1;
   $subtotal        = 0;
   $ongkir          = 0;
   $diskon_ch       = 0;
   $diskon          = 0;
   $poin_reimburs   = 0;
   $grand_total     = 0;
   $diskon_otomatis = 0;
   foreach($akumulasi_penjualan->result() as $row){
   ?>
<tr>
   <td><?php echo $i; ?></td>
   <td><a href="#modalDetail" class="detailPenjualan" id="<?php echo $row->no_invoice; ?>" data-toggle="modal"><?php echo $row->no_invoice; ?></a></td>
   <td><?php echo $row->store; ?></td>
   <td><?php echo date_format(date_create($row->tanggal),'d/m/y H:i'); ?></td>
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
   $subtotal         = $subtotal+$row->total;
   $ongkir           = $ongkir+$row->ongkir;
   $diskon_ch        = $diskon_ch+$row->diskon;
   $diskon           = $diskon+$row->diskon_free;
   $poin_reimburs    = $poin_reimburs+$row->poin_value;
   $diskon_otomatis  = $diskon_otomatis+$row->diskon_otomatis;
   $grand_total      = $grand_total+(($row->total+$row->ongkir)-($row->diskon+$row->diskon_free+$row->poin_value+$row->diskon_otomatis));
   } //end foreach
   ?>
<!-- DECLARE TOTAL-->
<tr style="background: white;font-weight: bold;">
   <td colspan="5" align="center">TOTAL</td>
   <td align="right"><?php echo number_format($subtotal,'0',',','.'); ?></td>
   <td align="right"><?php echo number_format($ongkir,'0',',','.'); ?></td>
   <td align="right"><?php echo number_format($diskon_ch,'0',',','.'); ?></td>
   <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
   <td align="right"><?php echo number_format($poin_reimburs,'0',',','.'); ?></td>
   <td align="right"><?php echo number_format($diskon_otomatis,'0',',','.'); ?></td>
   <td align="right"><?php echo number_format($grand_total,'0',',','.'); ?></td>
</tr>

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


