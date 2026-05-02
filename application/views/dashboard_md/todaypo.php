<table class="table table-striped" width="100%">
<tr>
    <th>No.</th>
    <th>No. PO</th>
    <th>Brand</th>
    <th>Tanggal</th>
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
    <td><a href="<?php echo base_url('purchase_order/form_po?no_po='.$row->no_po);?>" target="_blank"><?php echo $row->no_po?></a></td>
    <td><?php echo $row->brand?></td>
    <td><?php echo $row->tanggal_po?></td>
    <td><?php echo $row->supplier?></td>
    <td><?php echo $row->first_name?></td>
</tr>
<?php }?>

</table>