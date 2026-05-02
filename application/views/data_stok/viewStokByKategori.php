				<p style="text-align: center;">Laporan Penjualan dan Stok Per Departemen</p>			
				<p style="text-align: center;">Periode</p>
				<p style="text-align: center;"><?php echo $periode; ?></p>

	
				<table class="table table-bordered" style="font-size: 12px;">
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>Kategori</td>
                      <td style="text-align: right;">Sales</td>
                      <td style="text-align: right;">Stok</td>
                      <td style="text-align: right;">HPP x stok</td>
                      <td style="text-align: right;">Harga x stok</td>
                      
                    </tr>
 
                    <?php
                      $i = 1;
                      $totSales=0;$totHPP=0;$totstock=0;$totHarga=0;
                      $kategori = $this->db->get("ap_kategori")->result();
                      foreach($kategori as $row){
                        $sales = $this->ModelStokByKategori->salesPerkategori($start,$end,$row->id_kategori,$idStore,$id_brand)+0;
                        $stock = $this->ModelStokByKategori->stokPerkategori($start,$end,$row->id_kategori,$idStore,$id_brand);

                        $stok1 = 0; $modal1=0; $harga1=0;
                            foreach ($stock as $w) {
                              $stok1 = $w->totalPenjualan;
                              $modal1 = $w->modal;
                              $harga1= $w->harga;
                            }
                        //var_dump($stock);
                        $totSales+=$sales+0;
                        $totstock+=$stok1+0;
                        $totHPP+=$modal1+0;
                        $totHarga+=$harga1+0;

                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->kategori; ?></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($sales,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($stok1,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($modal1,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($harga1,'0',',','.'); ?></b></u></td>
                    </tr>

                      <?php
                        $id_kategori = $row->id_kategori;

                        $level_2_kategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori))->result();

                        foreach($level_2_kategori as $dt){
                        $sales2 = $this->ModelStokByKategori->salesPerkategori2($start,$end,$row->id_kategori,$dt->id,$idStore,$id_brand);
                        $stock2 = $this->ModelStokByKategori->stokPerkategori2($start,$end,$row->id_kategori,$dt->id,$idStore,$id_brand);

                        $stok2 = 0; $modal2=0; $harga2=0;
                            foreach ($stock2 as $w) {
                              $stok2 = $w->totalPenjualan;
                              $modal2 = $w->modal;
                              $harga2= $w->harga;
                            }
                      ?>

                        <tr>
                          <td></td>
                          <td style="padding-left: 30px;"><li><?php echo $dt->kategori_level_1; ?></li></td>
                          <td style="text-align: right;"><i><?php echo number_format($sales2,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($stok2,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($modal2,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($harga2,'0',',','.'); ?></i></td>
                        </tr>

                        <?php
                          $id_kategori_2 = $dt->id;

                          $level_3_kategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));

                          foreach($level_3_kategori->result() as $tg){
                            $sales3 = $this->ModelStokByKategori->salesPerkategori3($start,$end,$row->id_kategori,$dt->id,$tg->id,$idStore,$id_brand);
                            $stock3 = $this->ModelStokByKategori->stokPerkategori3($start,$end,$row->id_kategori,$dt->id,$tg->id,$idStore,$id_brand);
                            $stok = 0; $modal=0; $harga=0;
                            foreach ($stock3 as $w) {
                              $stok = $w->totalPenjualan;
                              $modal = $w->modal;
                              $harga = $w->harga;
                            }
                        ?>
                        <tr>
                          <td></td>
                          <td style="padding-left: 50px;"><li><?php echo $tg->kategori_3; ?></li></td>
                          <td style="text-align: right;"><i><?php echo number_format($sales3,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($stok,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($modal,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($harga,'0',',','.'); ?></i></td>
                        </tr>
                        <?php } ?>
                      <?php } ?>

                    <?php $i++; }
                    $totSales = $totSales <=0?1:$totSales;
                    $totstock = $totstock <=0?1:$totstock;
                     ?>
                    <tr bgcolor="#ddd">
                      <td colspan="2" align="center">T O T A L</td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totSales,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totstock,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totHPP,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totHarga,'0',',','.'); ?></b></u></td>
                    </tr>
                  </table>