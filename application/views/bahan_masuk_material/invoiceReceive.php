<?php
      $x=1;
      $jumlah = 0;
      foreach($received_invoice->result() as $rcv){
?>
      
<tr>
      <td><?php echo $x; ?></td>
      <td><a href="<?php echo base_url('bahanMasukMaterial/invoiceReceive?noReceive='.$rcv->no_receive); ?>" target="blank"><?php echo $rcv->no_receive; ?></a></td>
      <td><?php echo $rcv->tanggal_terima; ?></td>
      <td><?php echo $rcv->received_by; ?></td>
      <td><?php echo $rcv->checked_by; ?></td>
</tr>
<?php $jumlah = $jumlah+$rcv->total; $x++; } ?>