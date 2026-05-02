<table class="table table-striped" width="100%">
<tr>
    <th>No.</th>
    <th>Tanggal</th>
    <th>No. Retur</th>
    <th>No. PO</th>
    <th>Supplier</th>
    <th>PIC</th>
</tr>
<?php 
$no = 0;
foreach ($data as $row){
    $no++;
    ?>
<tr>
    <td><?php echo $no?></td>
    <td><?php echo $row->tanggal_retur?></td>
    <td><a href="<?php echo base_url('retur/nota_retur?no_retur='.$row->no_retur);?>" target="_blank"><?php echo $row->no_retur?></a></td>
    <td><a href="<?php echo base_url('purchase_order/form_po?no_po='.$row->no_po);?>" target="_blank"><?php echo $row->no_po?></a></td>
    <td><?php echo $row->supplier?></td>
    <td><?php echo $row->first_name?></td>
</tr>
<?php }?>

</table>