				<p style="text-align: center;">Laporan Penjualan Per Departemen</p>			
				<p style="text-align: center;">Periode</p>
				<p style="text-align: center;"><?php echo $periode; ?></p>

	
				<table class="table table-bordered" style="font-size: 12px;">
                    <tr style="font-weight: bold;">
                      <td width="5%">No</td>
                      <td>Kategori</td>
                      <td style="text-align: right;">Sales-Diskon</td>
                      <td style="text-align: right;">HPP</td>
                      <td style="text-align: right;">Margin</td>
                      <td style="text-align: right;">%</td>
                    </tr>
 
                    <?php
                      $i = 1;$persen2=0;$persen3=0;$persen=0;
                      $totSales=0;$totHPP=0;
                      $kategori = $this->db->get("ap_kategori")->result();
                      foreach($kategori as $row){
                        $sales = $this->ModelSalesByKategori->salesPerkategori($start,$end,$row->id_kategori,$idStore)+0;
                        $hpp = $this->ModelSalesByKategori->salesPerkategoriHPP($start,$end,$row->id_kategori,$idStore)+0;
                        $margin = $sales - $hpp;
                        $persen = $margin<>0? ($margin/$sales)*100:0;
                        $totSales+=$sales+0;
                        $totHPP+=$hpp;
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $row->kategori; ?></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($sales,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($hpp,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($margin,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($persen,'2',',','.'); ?></b></u></td>
                    </tr>

                      <?php
                        $id_kategori = $row->id_kategori;

                        $level_2_kategori = $this->db->get_where("ap_kategori_1",array("id_kategori" => $id_kategori))->result();

                        foreach($level_2_kategori as $dt){
                        $sales2 = $this->ModelSalesByKategori->salesPerkategori2($start,$end,$row->id_kategori,$dt->id,$idStore);
                        $hpp2 = $this->ModelSalesByKategori->salesPerkategori2HPP($start,$end,$row->id_kategori,$dt->id,$idStore);
                        $margin2 = $sales2 - $hpp2;
                        $persen2 = $margin2<>0? ($margin2/$sales2)*100:0;
                      ?>

                        <tr>
                          <td></td>
                          <td style="padding-left: 30px;"><li><?php echo $dt->kategori_level_1; ?></li></td>
                          <td style="text-align: right;"><i><?php echo number_format($sales2,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($hpp2,'0',',','.'); ?></i></td>
                      <td style="text-align: right;"><i><?php echo number_format($margin2,'0',',','.'); ?></i></td>
                      <td style="text-align: right;"><i><?php echo number_format($persen2,'2',',','.'); ?></i></td>
                        </tr>

                        <?php
                          $id_kategori_2 = $dt->id;

                          $level_3_kategori = $this->db->get_where("ap_kategori_2",array("id_kategori_1" => $id_kategori_2));

                          foreach($level_3_kategori->result() as $tg){
                            $sales3 = $this->ModelSalesByKategori->salesPerkategori3($start,$end,$row->id_kategori,$dt->id,$tg->id,$idStore);
                        $hpp3 = $this->ModelSalesByKategori->salesPerkategori3HPP($start,$end,$row->id_kategori,$dt->id,$tg->id,$idStore);
                        $margin3 = $sales3 - $hpp3;
                        $persen3 = $margin3<>0? ($margin3/$sales3)*100:0;
                        ?>
                        <tr>
                          <td></td>
                          <td style="padding-left: 50px;"><li><?php echo $tg->kategori_3; ?></li></td>
                          <td style="text-align: right;"><i><?php echo number_format($sales3,'0',',','.'); ?></i></td>
                          <td style="text-align: right;"><i><?php echo number_format($hpp3,'0',',','.'); ?></i></td>
                      <td style="text-align: right;"><i><?php echo number_format($margin3,'0',',','.'); ?></i></td>
                      <td style="text-align: right;"><i><?php echo number_format($persen3,'2',',','.'); ?></i></td>
                        </tr>
                        <?php } ?>
                      <?php } ?>

                    <?php $i++; }
                    $totMargin=($totSales-$totHPP)+0;
                    $totMargin = $totMargin<=0?1:$totMargin;
                    $totSales = $totSales <=0?1:$totSales;
                    $totPersen=($totMargin/$totSales)*100;
                     ?>
                    <tr bgcolor="#ddd">
                      <td colspan="2" align="center">T O T A L</td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totSales,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totHPP,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totMargin,'0',',','.'); ?></b></u></td>
                      <td style="text-align: right;"><u><b><?php echo number_format($totPersen,'2',',','.'); ?></b></u></td>
                    </tr>
                  </table>