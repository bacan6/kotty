<?php
  $i = 1;
  $total = 0;$totDiskon = 0;$subtotal = 0;$totPPN=0;$PPN=0;
  foreach($purchaseItem as $row){
    $diskon = $row->diskon;
?>
<tr>
  <td align="center"><?php echo $i; ?></td>
  <td><?php echo $row->id_produk; ?></td>
  <td><?php echo $row->nama_produk; ?></td>
  <td align="center"><?php echo $row->qty; ?></td>
  <td align="center">
    <?php 
      $sku            = $row->id_produk;
      $delivered_qty  = $this->modelFinance->delivered_qty($noTagihan,$sku);

      echo $delivered_qty;
    ?>
  </td>
  <td align="center"><?php echo $row->qty-$delivered_qty; ?></td>
  <td align="center">
    <?php
      $returItem = $this->modelFinance->returItem($noTagihan,$sku);

      echo $returItem;

      $dis  = $this->modelFinance->diskonProduk($noTagihan,$sku);
      $subtotal1 = $row->harga*($delivered_qty-$returItem)-$dis->diskon1-$dis->diskon2-$dis->diskon3;
      if($row->ppn==1 && $delivered_qty>0) {
        $PPN = (0.11*$subtotal1);
        $subtotal1 = $subtotal1+$PPN;
      }else $PPN=0;
    ?>
  </td>
  <td align="right"><?php echo number_format($row->harga,'2',',','.'); ?></td>
  <td align="right"><?php echo number_format($dis->diskon1,'2',',','.'); ?></td>
  <td align="right"><?php echo number_format($dis->diskon2,'2',',','.'); ?></td>
  <td align="right"><?php echo number_format($dis->diskon3,'2',',','.'); ?></td>
  <td align="right"><?php echo ($row->ppn==1?'Ya':'Tidak'); ?></td>
  <td align="right"><?php echo number_format($subtotal1,'0',',','.'); ?></td>
</tr>
<?php $i++; $total = $total+$subtotal1;
$subtotal += $row->harga*($delivered_qty-$returItem);
$totDiskon += $dis->diskon1+$dis->diskon2+$dis->diskon3;
$totPPN += $PPN;


} ?>  

<tr>
  <td align="center" colspan="12" style="font-weight: bold;">JUMLAH</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($subtotal,'2',',','.'); ?></td>
</tr>
<tr>
  <td align="center" colspan="12" style="font-weight: bold;">DISKON PRODUK</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($totDiskon,'2',',','.'); ?></td>
</tr>
<tr>
  <td align="center" colspan="12" style="font-weight: bold;">PPN</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($totPPN,'2',',','.'); ?></td>
</tr>
<tr>
  <td align="center" colspan="12" style="font-weight: bold;">DISKON GLOBAL</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($diskon,'2',',','.'); ?></td>
</tr>
<tr>
  <td align="center" colspan="12" style="font-weight: bold;">
  <form method=post action="<?php echo base_url('finance/invoice_penagihan?no_tagihan='.$noTagihan);?>">POTONGAN RETUR
    <input type=text name="potongan_retur" value="<?php echo $potongan_retur; ?>">
    <input type=submit value="Simpan">
    </form>
</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($potongan_retur,'2',',','.'); ?></td>
</tr>
<tr>
  <td align="center" colspan="12" style="font-weight: bold;">GRAND TOTAL</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($total-$potongan_retur,'2',',','.'); ?></td>
</tr>

<tr>
  <td align="center" colspan="12" style="font-weight: bold;">TERBAYAR</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($hutangTerbayar,'2',',','.'); ?></td>
</tr>

<tr>
  <td align="center" colspan="12" style="font-weight: bold;">SISA PEMBAYARAN</td>
  <td align="right" style="font-weight: bold;"><?php echo number_format($total-$potongan_retur-$diskon-$hutangTerbayar,'2',',','.'); ?></td>
</tr>