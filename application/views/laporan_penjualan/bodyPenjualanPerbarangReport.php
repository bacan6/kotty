<table width="50%">
              				<?php
                        foreach($info_produk as $pr){
                      ?>
                      <tr>
              					<td width="25%">SKU</td>
              					<td>:</td>
              					<td><?php echo $pr->id_produk; ?></td>
              				</tr>

              				<tr>
              					<td>Nama Produk</td>
              					<td>:</td>
              					<td><?php echo $pr->nama_produk; ?></td>
              				</tr>

              				<tr>
              					<td>Kategori</td>
              					<td>:</td>
              					<td><?php echo $pr->kategori; ?></td>
              				</tr>

              				<tr>
              					<td>Periode</td>
              					<td>:</td>
              					<td>
                            <?php
                              echo date_format(date_create($dateStart),'d F Y')." - ".date_format(date_create($dateEnd),'d F Y');
                            ?>     
                        </td>
              				</tr>
                      <?php } ?>
              			</table>

              			<table class="table" style="font-size: 11px;">
              				<tr style="font-weight: bold;">
              					<td width="5%">No</td>
              					<td>No Invoice</td>
              					<td>Tanggal</td>
              					<td align="right">Harga</td>
                        <td align="right">Diskon</td>
                        <td align="right">Qty</td>
              					<td align="right">Total</td>
              				</tr>

              				<?php
              					$rows = $penjualan_perbarang->num_rows();

              					if($rows < 1){
              				?>

              				<tr>
              					<td colspan="6" align="center">--Belum Ada Data Untuk Ditampilkan--</td>
              				</tr>

              				<?php } else { ?>

              				<?php
              					$i = 1;
              					$total = 0;
                        $qty = 0;
                        $diskon = 0;
              					foreach($penjualan_perbarang->result() as $row){
              				?>
	              				<tr>
	              					<td><?php echo $i; ?></td>
	              					<td width="15%"><?php echo $row->no_invoice; ?></td>
	              					<td><?php echo date_format(date_create($row->tanggal),'d M Y'); ?></td>
	              					<td align="right"><?php echo number_format($row->harga_jual,'0',',','.'); ?></td>
                          <td align="right"><?php echo number_format($row->diskon,'0',',','.'); ?></td>
                          <td align="right"><?php echo $row->qty; ?></td>
	              					<td align="right"><?php echo number_format($row->total-$row->diskon,'0',',','.'); ?></td>
	              				</tr>
	              			<?php
	              			 $total = $total+($row->total-$row->diskon); $qty = $qty+$row->qty; $i++; $diskon=$diskon+$row->diskon;} 
	              			?>
	              				<tr style="background: #fff;font-weight: bold;">
	              					<td colspan="4" align="center">TOTAL</td>
                          <td align="right"><?php echo number_format($diskon,'0',',','.'); ?></td>
                          <td align="right"><?php echo number_format($qty,'0',',','.'); ?></td>
	              					<td align="right"><?php echo number_format($total,'0',',','.'); ?></td>
	              				</tr>
	              			<?php
	              				} 
	              			?>

              			</table>