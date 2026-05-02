<table class="table table-striped" width="100%">
<tr>
    <th>No.</th>
    <th>No. PO</th>
    <th>Tanggal</th>
    <th>Supplier</th>
    <th>PIC</th>
    <th>Nominal</th>
</tr>
<?php 
$no = 0;$total = 0;
foreach ($data as $row){
    $no++;$total+=$row->trx;
    ?>
<tr>
    <td><?php echo $no?></td>
    <td><a href="<?php echo base_url('purchase_order/form_po?no_po='.$row->no_po);?>" target="_blank"><?php echo $row->no_po?></a></td>
    <td><?php echo $row->tanggal_po?></td>
    <td><?php echo $row->supplier?></td>
    <td><?php echo $row->first_name?></td>
    <td align=right><?php echo number_format($row->trx,0)?></td>
</tr>
<?php }?>
<tr>
    <th colspan=5>TOTAL</th>
    <td align=right><strong><?php echo number_format($total,0)?></strong></td>
</tr>
</table>