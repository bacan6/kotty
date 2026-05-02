<?php
    $i=1;
    $value = 0;
    foreach($purchase_item->result() as $row){
?>

<tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $row->id_produk; ?></td>
    <td><?php echo $row->nama_produk; ?></td>
    <td style="text-align: center;"><?php echo $row->qty; ?></td>
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
<?php $value = $value+$row->total; $i++; } ?>