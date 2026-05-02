<?php 
  $i=1;
  $subtotal = 0;
  $terbayar = 0;
  $saldo = 0;
  
  foreach($tagihan_hutang->result() as $row){
?>
  <tr>
    <td align="center"><?php echo $i; ?></td>
    <td><?php echo $row->no_tagihan; ?></td>
    <td><?php echo $row->supplier; ?></td>
    <td><?php echo date_format(date_create($row->tanggal_po),"d M Y"); ?></td>
    <td><?php echo date_format(date_create($row->jatuh_tempo),"d M Y"); ?></td>
    <td><?php echo $row->first_name; ?></td>
    <td align="right">
      <?php echo number_format($row->total_hutang-$row->diskon-$row->total_retur,'0',',','.'); ?>
    </td>
    <td align="right"><?php echo number_format($row->total_terbayar,'0',',','.'); ?></td>
    <td align="right"><?php echo number_format($row->total_hutang-$row->diskon-$row->total_retur-$row->total_terbayar,'0',',','.'); ?></td>
    <td><?php echo $row->keterangan; ?></td>
  </tr>

  <?php 
      $subtotal = $subtotal+($row->total_hutang-$row->diskon-$row->total_retur);
      $terbayar = $terbayar+($row->total_terbayar);
      $saldo = $saldo+($row->total_hutang-$row->diskon-$row->total_retur-$row->total_terbayar);
      $i++; 
    } 
  ?>

  <tr style="font-weight: bold;">
    <td colspan="6" align="center"><b>TOTAL</b></td>
    <td align="right"><?php echo number_format($subtotal,'0',',','.'); ?></td>
    <td align="right"><?php echo number_format($terbayar,'0',',','.'); ?></td>
    <td align="right"><?php echo number_format($saldo,'0',',','.'); ?></td>
  </tr>