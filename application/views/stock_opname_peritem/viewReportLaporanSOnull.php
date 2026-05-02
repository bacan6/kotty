<p style="text-align: center;font-size: 18px;">Laporan Hasil Stock Opname <br> <?php echo $title; ?><br> Periode</p>
<p style="text-align: center;">
	<?php
        echo date_format(date_create($start),"d M Y")." - ".date_format(date_create($end),"d M Y");  
	?>
</p>
<p><a class="btn btn-success" href="<?php echo base_url('so_peritem/exportExcelHasilSO?start='.$start.'&end='.$end.'&toko='.$toko);?>"><i class="fa fa-download"></i> Export Sudah di SO</a>
<a class="btn btn-warning" href="<?php echo base_url('so_peritem/exportExcelHasilSOnull?start='.$start.'&end='.$end.'&toko='.$toko);?>"><i class="fa fa-download"></i> Export Belum di SO</a>
</p>

<table width="100%">

    <tr>
		<th>No.</th>
		<th>Brand</th>
		<th>Nama</th>
		<th>SKU</th>
		<th>Stok</th>
	</tr>
		<?php
			$i=1;
			$value = 0;$total=0;
			foreach($SO_item->result() as $row){
				$total += $row->stok_after*$row->harga;
				$stok = $this->modelSOPeritem->stokItem($idStore,$row->id_produk);
		?>
		<tr>
			<td><?php echo $i?></td>
			<td><?php echo strtoupper($row->brand); ?></td>
			<td><?php echo strtoupper($row->nama_produk); ?></td>
			<td><?php echo $row->id_produk; ?></td>
			<td><?php echo strtoupper($stok); ?></td>
			</tr>
		<?php 
		$i++; } ?>
</table>