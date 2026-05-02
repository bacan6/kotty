<table class="table table-striped" width="100%">
<tr>
    <th>No.</th>
    <th>No. Receive</th>
    <th>No. PO</th>
    <th>Tanggal Order</th>
    <th>Tanggal Terima</th>
    <th>PIC</th>
</tr>
<?php 
$no = 0;
foreach ($data as $row){
    $no++;
    ?>
<tr>
    <td><?php echo $no?></td>
    <td><a href="<?php echo base_url('bahan_masuk/invoice_receive?no_receive='.$row->no_receive);?>" target="_blank"><?php echo $row->no_receive?></a></td>
    <td><a href="<?php echo base_url('purchase_order/form_po?no_po='.$row->no_po);?>" target="_blank"><?php echo $row->no_po?></a></td>
    <td><?php echo $row->tanggal_po?></td>
    <td><?php echo $row->tanggal_terima?></td>
    <td><?php echo $row->first_name?></td>
</tr>
<?php }?>

</table>