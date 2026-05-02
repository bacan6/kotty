<p style="text-align: center;font-size: 18px;">Laporan Hasil Stock Opname <br> <?php echo $title; ?><br> Periode</p>
<p style="text-align: center;">
	<?php
        echo date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");  
	?>
</p>
<p><a class="btn btn-success" href="<?php echo base_url('so_peritem/exportExcelHasilSO?start='.$start.'&end='.$end.'&toko='.$toko);?>"><i class="fa fa-download"></i> Export XLS</a></p>

<table width="100%">
    <tr>
		<th>No.</th>
		<th>Brand</th>
		<th>Kategori (SO)</th>
		<th>Nama</th>
		<th>SKU</th>
		<th>No. SO</th>
		<th>Harga</th>
		<th>Stok Sistem</th>
		<th>Stok Toko</th>
		<th>Selisih</th>
		<th>Harga Selisih</th>
		<th>Nilai Akhir</th>
	</tr>
		<?php
			$i=1;
			$value = 0;$total=0;
			foreach($SO_item->result() as $row){
				$total += $row->stok_after*$row->harga;
		?>
		
		<tr>
			<td><?php echo $i?></td>
			<td><?php echo strtoupper($row->brand); ?></td>
			<td><?php echo isset($row->kategori_so) ? htmlspecialchars($row->kategori_so) : '—'; ?></td>
			<td><?php echo strtoupper($row->nama_produk); ?></td>
			<td><?php echo $row->id_produk; ?></td>
			<td><?php echo strtoupper($row->no_so); ?></td>
			<td><?php echo number_format($row->harga,0); ?></td>
			<td align=center><?php echo $row->stok_before; ?></td>
			<td align=center><?php echo $row->stok_after; ?></td>
			<td align=center><?php echo $row->stok_after-$row->stok_before; ?></td>
			<td align=right><?php echo number_format(($row->stok_after-$row->stok_before)*$row->harga,0); ?></td>
			<td align=right><?php echo number_format($row->stok_after*$row->harga,0); ?></td>
			</tr>
		
			
		
		<?php 
		$value = $value+$row->total;
		$hargaselisih += ($row->stok_after-$row->stok_before)*$row->harga;
		$selisih += $row->stok_after-$row->stok_before;
		$i++; } ?>
		<tr><td colspan='9' align='center'><b>TOTAL</b></td>
		<td align=center><?php echo number_format($selisih,0)?></td>
		<td align=right><?php echo number_format($hargaselisih,0)?></td>
		<td align=right><?php echo number_format($total,0)?></td>
		</tr>
</table>