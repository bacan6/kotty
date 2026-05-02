<center>
<table width="1000px">
   <tr>
      <td>
         <?php
            foreach($header->result() as $hd){
            ?> 
         <center>
            <h4><?php echo $hd->nama_perusahaan; ?></h4>
            <h5>PURCHASE ORDER (PO)</h5>
         </center>
         <?php } ?>
         <table width="100%">
            <tr>
               <td style="font-weight:  bold;width: 15%;">Tanggal</td>
               <td style="width: 3%;">:</td>
               <td>
                  <?php
                     $date_po = date_create($tanggal_po);
                     
                     echo date_format($date_po,'d M Y');
                     ?>
               </td>
            </tr>
            <tr>
               <td style="font-weight:  bold;width: 15%;">No PO</td>
               <td style="width: 3%;">:</td>
               <td><?php echo $noPO; ?></td>
            </tr>
            <tr>
               <td style="font-weight:  bold;width: 15%;">Dikirim ke</td>
               <td style="width: 3%;">:</td>
               <td><?php echo '<b>'.$supplier.'</b> '.$alamat_pengiriman; ?></td>
            </tr>
            <tr>
               <td style="font-weight:  bold;width: 15%;">Tanggal Kirim</td>
               <td style="width: 3%;">:</td>
               <td>
                  <?php
                     $date_send = date_create($tanggal_kirim);
                     
                     echo date_format($date_send,'d M Y');
                     ?>
               </td>
            </tr>
            <tr>
               <td style="font-weight:  bold;width: 15%;">Keterangan</td>
               <td style="width: 3%;">:</td>
               <td><?php echo $keterangan; ?></td>
            </tr>
         </table>
         <table style="margin-top: 20px;font-size:12px;" border="1" cellspacing="0" width="100%">
            <tr style="font-weight: bold;">
               <td width="5%" align="center">No</td>
               <td>Nama Produk</td>
               <td width="8%" style="text-align: center;">Qty</td>
               <td width="8%" style="text-align: center;">Unit</td>
               <td width="20%" style="text-align: right;">Harga Satuan</td>
               <td width="20%" style="text-align: right;">Total</td>
            </tr>
            <?php
               $i=1;
               $value = 0;
               foreach($purchase_item->result() as $row){
               ?>
            <tr>
               <td align="center"><?php echo $i; ?></td>
               <td><?php echo $row->nama_produk; ?></td>
               <td style="text-align: center;"><?php echo $row->qty; ?></td>
               <td style="text-align: center;"><?php echo $row->satuan; ?></td>
               <td style="text-align: right;"><?php echo number_format($row->harga,'0',',','.'); ?></td>
               <td style="text-align: right;"><?php echo number_format($row->total,'0',',','.'); ?></td>
            </tr>
            <?php $value = $value+$row->total; $i++; } ?>
            <tr>
               <td colspan="5" style="text-align: center;font-weight: bold;">TOTAL</td>
               <td style="text-align: right;"><?php echo number_format($value,'0',',','.'); ?></td>
            </tr>
            <?php
               $status_ppn = $ppn;
               if($status_ppn==1){
               ?>  
            <tr>
               <td colspan="5" style="text-align: center;font-weight: bold;">PPN <?php echo $nilai_ppn; ?> %</td>
               <td style="text-align: right;"><?php echo number_format(($nilai_ppn/100)*$value,'0',',','.'); ?></td>
            </tr>
            <tr>
               <td colspan="5" style="text-align: center;font-weight: bold;">GRAND TOTAL</td>
               <td style="text-align: right;"><?php echo number_format((($nilai_ppn/100)*$value)+$value,'0',',','.'); ?></td>
            </tr>
            <?php }  ?>
         </table>
      </td>
   </tr>
</table>
</center>