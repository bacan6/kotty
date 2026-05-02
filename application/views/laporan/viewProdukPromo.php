				<p style="text-align: center;">Laporan Produk Promo</p>			
				<p style="text-align: center;">Periode</p>
				<p style="text-align: center;"><?php echo $periode; ?></p>

	
				<table class="table table-bordered" style="font-size: 12px;">
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>Nama Produk</td>
                      <td>Qty</td>
                      <td>Disc. Toko</td>
                      <td>Disc. Supplier</td>
                      <td>Harga Promo</td>
                      <td>Tanggal</td>
                      <td>Atur Jam</td>
                      <td>Atur Hari</td>
                      <td>No Promo</td>
                      <td>Act</td>
                    </tr>
 
                    <?php
                      $i = 1;
                      foreach($list as $row){
                    ?>
                    <tr id="list<?php echo $row->id; ?>">
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->id_produk; ?><br><b><?php echo $row->nama_produk; ?></b><br><small><?php echo $row->brand; ?></small></td>
                      <td><?php echo $row->qty; ?></td>
                      <td><?php echo number_format($row->discount,0); ?></td>
                      <td><?php echo number_format($row->disc_supplier,0); ?></td>
                      <td><?php echo number_format($row->hargax,0); ?></td>
                      <td align=center><?php echo $row->date_start; ?><br>s/d<br><?php echo $row->date_end; ?></td>
                      <td><?php echo $row->Jam; ?></td>
                      
                      <td><?php 
                      $arrHari = explode(".",$row->Hari);
                      foreach ($arrHari as $h) {
                        $this->ModelPromoSupplier->showHari($h);
                      } ?></td>
                      <td><?php echo $row->no_promo; ?></td>
                      <td>
                      <a onclick="if (confirm('Yakin Hapus Data?')){hapusPromo(<?php echo $row->id; ?>);}" ><i class="fa fa-trash"></i></a></td>
                    </tr>
<?php 
$i++;
} ?>
                      
                  </table>