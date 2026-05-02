<?php 
$y = date("His");
$margin = (($hargaJual-$harga)/$hargaJual)*100;
?>
<tr>
    <td style="vertical-align: middle;"><?php echo $idProduk; ?></td>
    <td style="vertical-align: middle;"><?php echo $nama_produk; ?></td>
    <td style="vertical-align: middle;" align='center'>0</td>
    <td>
        <input type="number" name='qty' id="qtyProduk<?php echo $y; ?>" size=5 data-urut="<?php echo $y; ?>" data-id="<?php echo $idProduk; ?>" data-price="<?php echo $harga; ?>" data-max="<?php //echo $dt->qty; ?>" min="0" class="qtyAjax" value="0" onChange="javascript:editHarga(<?php echo $y; ?>);" style="width:60px"/>
    </td>
    <td><input class="harga" id="hrgProduk<?php echo $y; ?>" type="text" size=7 value="<?php echo $harga; ?>" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
    <td><input id="hrgProdukJual<?php echo $y; ?>" type="text" size=7 value="<?php echo $hargaJual; ?>" data-urut="<?php echo $y; ?>" onChange="javascript:hitungMargin(<?php echo $y; ?>);"></td>
    <td>
        <input id="margin<?php echo $y; ?>" type="text" size=3 value="<?php echo number_format($margin,2) ?>" data-urut="<?php echo $y; ?>" onChange="javascript:hargaJual(<?php echo $y; ?>);">
    </td>
    <td>
        <input type="text" name='bonus' id="bonus<?php echo $y; ?>" size=3 data-urut="<?php echo $y; ?>" data-id="<?php echo $idProduk; ?>" data-price="<?php echo $harga; ?>" data-max="1000" min="0" class="bonus" value="0" />
    </td>
    <td><input class="diskon1" id="diskon1<?php echo $y; ?>" type="text" size=5 value="0" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
    <td><input class="diskon2" id="diskon2<?php echo $y; ?>" type="text" size=5 value="0" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
    <td><input class="diskon3" id="diskon3<?php echo $y; ?>" type="text" size=5 value="0" data-urut="<?php echo $y; ?>" onChange="javascript:editHarga(<?php echo $y; ?>);"></td>
    <td align=right><span id="subTotal<?php echo $y?>">0</span></td>
</tr>