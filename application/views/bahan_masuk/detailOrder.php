<?php
    $i=1;
    $value = 0;
    $qtyOrder = 0;
    $qtyReceived = 0;
    foreach($purchase_item->result() as $row){
?>

<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $row->id_produk; ?></td>
    <td><?php echo $row->nama_produk; ?></td>
    <td style="text-align: center;"><?php echo $row->qty_req; ?></td>
    <td style="text-align: center;"><?php echo $row->qty_approved; ?></td>
    <td style="text-align: center;"><?php echo $row->qty; ?></td>
    <td style="text-align: center;"><?php echo $row->bonus; ?></td>
    <td style="text-align: center;">
        <?php
            $sku            = $row->id_produk;
            $delivered_qty  = $this->model1->delivered_qty($no_po,$sku);

            echo $delivered_qty;
        ?>
    </td>
    <td style="text-align: center;">
        <?php 
            echo $delivered_qty-$row->qty;  
        ?>
    </td>
    <td style="text-align: center;"><?php echo $row->satuan; ?></td>
</tr>
<?php 
$qtyOrder += $row->qty_approved;
$qtyReceived += $delivered_qty;
$qtyBonus += $row->bonus;
$value = $value+$row->total; $i++; } ?>
<tr>
    <td colspan=9 align="right" valign="top"><b>Total Qty Ordered</b></td>
    <td><?php echo number_format($qtyOrder,0)?></td>
</tr>
<tr>
    <td colspan=9 align="right" valign="top"><b>Total Qty Bonus</b></td>
    <td><?php echo number_format($qtyBonus,0)?></td>
</tr>
<tr>
    <td colspan=9 align="right" valign="top"><b>Total Qty Received</b></td>
    <td><?php echo number_format($qtyReceived,0)?></td>
</tr>
<tr>
    <td colspan=9 align="right" valign="top"><b>Service Level</b></td>
    <td><b><?php echo number_format(($qtyReceived/$qtyOrder)*100,2)?></b></td>
</tr>