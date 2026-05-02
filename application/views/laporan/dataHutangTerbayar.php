<?php
    $i=1;
    $total = 0;
    foreach($hutang_terbayar->result() as $row){
?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $row->no_payment; ?></td>
        <td><?php echo $row->no_po; ?></td>
        <td><?php echo $row->supplier; ?></td>
        <td><?php echo $row->first_name; ?></td>
        <td><?php echo date_format(date_create($row->tanggal_pembayaran),'d/m/y H:i'); ?></td>
        <td><?php echo $row->paymentType; ?></td>
        <td><?php echo $row->keterangan; ?></td>
        <td align="right"><?php echo number_format($row->pembayaran,'0',',','.'); ?></td>
    </tr>
<?php 
    $total = $total+$row->pembayaran;
    $i++; 
    } 
?>

<tr>
    <td colspan="8" align="center" style="font-weight: bold;">TOTAL</td>
    <td align="right" style="font-weight: bold;"><?php echo number_format($total,'0',',','.'); ?></td>
</tr>