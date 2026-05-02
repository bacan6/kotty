<p style="text-align: center;font-size: 18px;">Laporan Laba Rugi Akumulasi <br> <?php echo $title; ?><br> Periode</p>
<p style="text-align: center;">
	<?php
        echo date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");  
	?>
</p>

<table width="100%">
    <tr>
       <td style="font-weight: bold;" colspan="3">PENDAPATAN</td>
    </tr>

    <?php
    	foreach($salesPerkategori as $row){
    ?>
    <tr>
        <td style="padding-left: 20px;"><?php echo $row->kategori; ?></td>
        <td style="text-align: right;"><?php echo number_format($row->totalPenjualan,'0',',','.'); ?></td>
    </tr>
	<?php } ?>

	<tr style="font-weight: bold;border-top: solid 1px #ccc;border-bottom: solid 1px #ccc;">
		<td>TOTAL PENDAPATAN</td>
		<td style="text-align: right;"><?php echo number_format($totalSales,'0',',','.'); ?></td>
	</tr>

	<tr>
       <td style="font-weight: bold;" colspan="3">HPP</td>
    </tr>

    <?php
    	$hpp = 0;
    	foreach($cogs as $dt){
    ?>
    <tr>
        <td style="padding-left: 20px;"><?php echo $dt->kategori; ?></td>
        <td style="text-align: right;"><?php echo number_format($dt->totalPenjualan,'0',',','.'); ?></td>
    </tr>
	<?php $hpp = $hpp+$dt->totalPenjualan; } ?>

	<tr style="font-weight: bold;border-top: solid 1px #ccc;border-bottom: solid 1px #ccc;">
		<td>TOTAL HPP</td>
		<td style="text-align: right;"><?php echo number_format($hpp,'0',',','.'); ?></td>
	</tr>

	<tr>
       <td style="font-weight: bold;" colspan="3">BEBAN PROMOSI</td>
    </tr>

    <tr>
    	<td style="padding-left: 20px;">Diskon Member</td>
    	<td style="text-align: right;"><?php echo number_format($diskonMember,'0',',','.'); ?></td>
    </tr>

    <tr>
    	<td style="padding-left: 20px;">Diskon Global</td>
    	<td style="text-align: right;"><?php echo number_format($diskonGlobal,'0',',','.'); ?></td>
    </tr>

    <tr>
    	<td style="padding-left: 20px;">Diskon Peritem</td>
    	<td style="text-align: right;"><?php echo number_format($diskonPeritem,'0',',','.'); ?></td>
    </tr>

    <tr>
    	<td style="padding-left: 20px;">Poin Reimbursment</td>
    	<td style="text-align: right;"><?php echo number_format($poinReimburs,'0',',','.'); ?></td>
    </tr>

    <tr style="font-weight: bold;border-top: solid 1px #ccc;border-bottom: solid 1px #ccc;">
		<td>TOTAL BEBAN PROMOSI</td>
		<td style="text-align: right;"><?php echo number_format($diskonMember+$diskonGlobal+$diskonPeritem+$poinReimburs,'0',',','.'); ?></td>
	</tr>

	<tr style="font-weight: bold;border-top: solid 1px #ccc;border-bottom: solid 1px #ccc;">
		<td>TOTAL RETUR PENJUALAN</td>
		<td style="text-align: right;"><?php echo number_format($retur,'0',',','.'); ?></td>
	</tr>


	<tr style="font-weight: bold;border-top: solid 1px #ccc;border-bottom: solid 1px #ccc;">
		<td>PENDAPATAN</td>
		<td style="text-align: right;">
			<?php 
				$bebanPromosi = $diskonMember+$diskonGlobal+$diskonPeritem+$poinReimburs;

				echo number_format($totalSales-($bebanPromosi+$hpp+$retur),'0',',','.'); 
			?>
		</td>
	</tr>
</table>