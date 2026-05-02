<table class="table table-striped" width="100%">
<tr>
    <th>No.</th>
    <th>No. Transfer</th>
    <th>Tanggal</th>
    <th>Pengirim</th>
    <th>Penerima</th>
    <th>Keterangan</th>
</tr>
<?php 
$no = 0;
foreach ($data as $row){
    $no++;
    ?>
<tr>
    <td><?php echo $no?></td>
    <td><a href="<?php echo base_url('laporan/invoiceTransfer?noTransfer='.$row->noTransfer);?>" target="_blank"><?php echo $row->noTransfer?></a></td>
    <td><?php echo $row->tanggal?></td>
    <td><?php echo $row->store?></td>
    <td><?php echo $row->first_name?></td>
    <td><?php echo $row->keterangan?></td>
</tr>
<?php }?>

</table>