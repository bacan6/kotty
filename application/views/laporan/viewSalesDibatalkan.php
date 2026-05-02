				<p style="text-align: center;">Laporan Transaksi yang Dibatalkan</p>			
				<p style="text-align: center;">Periode</p>
				<p style="text-align: center;"><?php echo $periode; ?></p>

	
				<table class="table table-bordered" style="font-size: 12px;">
        <thead>
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>No. Invoice</td>
                      <td>ID Produk</td>
                      <td>Produk</td>
                      <td>HPP</td>
                      <td>Harga</td>
                      <td>Tgl</td>
                      <td>User</td>
                      <td>Toko</td>
                    </tr>
                    </thead>
                    <tbody>
        <?php
        $no = 0; $hpp = 0; $harga=0;
        foreach($viewReport as $w){
          $no++;
          $hpp += $w->hpp;
          $harga += $w->harga_jual;
          ?>
          <tr>
            <td><?php echo $no?></td>
            <td><?php echo $w->no_invoice?></td>
            <td><?php echo $w->id_produk?></td>
            <td><?php echo $w->nama_produk?></td>
            <td><?php echo number_format($w->hpp,'0',',','.');?></td>
            <td><?php echo number_format($w->harga_jual,'0',',','.');?></td>
            <td><?php echo $w->tanggal?></td>
            <td><?php echo $w->username?></td>
            <td><?php echo $w->store?></td>

          </tr>
        <?php } ?>
        <tr>
        <td colspan="4" align="center">T O T A L</td>
        <td><?php echo number_format($hpp,'0',',','.');?></td>
        <td><?php echo number_format($harga,'0',',','.');?></td>
        <td colspan=3></td>
        </tr>
        </tbody>    
                  </table>