<h3 align="center" style="color: black;"><?php echo $department->kategori; ?></h3>


<br>

<table class="table table-bordered" style="font-size: 12px;">
<thead >
<tr style="font-weight: bold;color:black">
<th><u>BULAN</u><br>GROWTH</th>
<?php 
//var_dump($title);
foreach ($title as $j){
echo "<td align=center>$j</td>";
}?>
</tr>
</thead>
<tbody>
<tr style="color:black">
<td><b>PENDAPATAN</b></td>
<?php 
//var_dump($title);
foreach ($value as $j){
	echo "<td align=right>Rp ".number_format($j,0,',','.')."</td>";
}?>
</tr>
<tr style="color:black">
<td><b>POTONGAN</b></td>
<?php 
//var_dump($title);
foreach ($potongan as $j){
	echo "<td align=right>Rp ".number_format($j,0,',','.')."</td>";
}?>
</tr>
<tr style="color:black">
<td><b>TOTAL</b></td>
<?php 
//var_dump($title);
$id = 0;
foreach ($potongan as $j){
	echo "<td align=right><strong>Rp ".number_format($value[$id]-$potongan[$id],0,',','.')."</strong></td>";
	$id++;
}?>
</tr>
</tbody>
</table>
<script type="text/javascript">
	getGrowthTable(<?php echo $department->id_kategori+1; ?>);
</script>