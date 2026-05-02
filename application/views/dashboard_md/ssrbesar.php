<table class="table table-striped" width="100%">
<tr>
    <th>No.</th>
    <th>Brand</th>
    <th>SSR</th>
</tr>
<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$no = 0;
//var_dump($_SESSION['ssrbesar']);
asort($_SESSION['ssrbesar']);
foreach ($_SESSION['ssrbesar'] as $row){
    $no++;
    ?>
<tr>
    <td><?php echo $no?></td>
    <td><?php echo $row['brand']?></td>
    <td><?php echo number_format($row['ssr'],2)?></td>
</tr>
<?php }?>

</table>