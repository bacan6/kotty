<?php
    $i=1;
    $value = 0;$total = 0;$_SESSION['id_brand']=array();
    foreach($purchase_item->result() as $row){
        $lastPurchased = $this->modelPurchaseOrder->lastPurchased($row->id_produk,$noteInfo->id_toko);
		$lastSales = $this->modelPurchaseOrder->lastSales($row->id_produk,$noteInfo->id_toko,$lastPurchased->tanggal);
        $cekStokProduk = $this->modelBahanMasukMaterial->cekStokProduk($row->id_produk,$noteInfo->id_toko);
        if (!in_array($row->id_brand,$_SESSION['id_brand'])){
            $_SESSION['id_brand'][]=$row->id_brand;
        }
        
        $bg = ($row->qty_req==0)? "bgcolor=pink":'';
?>

<tr id="row<?php echo $row->id_produk;?>" <?php echo $bg;?>>
    <td><?php echo $i; ?></td>
    <td><?php echo $row->id_produk; ?></td>
    <td <?php echo $bg;?>><?php echo $row->nama_produk; ?></td>
    <td><?php echo $lastPurchased->tanggal; ?></td>
	<td><?php echo $lastPurchased->qty; ?></td>
	<td><?php echo $lastSales+0; ?></td>
    <td><?php echo $cekStokProduk;?></td>
    <td style="text-align: center;"><?php echo $row->qty_req; ?></td>
    <td style="text-align: center;"><?php echo $row->bonus; ?></td>
    <td style="text-align: center;"><?php echo $row->qty; ?></td>
    <td style="text-align: center;"><?php echo number_format($row->harga,'0',',','.'); ?></td>
    <td id="totalItem<?php echo $row->id_produk; ?>" align="right"><?php echo number_format($row->total,'0',',','.'); ?></td>
    <td style="text-align: center;">
        <input type=text size=4 id="prd_<?php echo $row->id_produk; ?>" class="qty" 
                    data-id="<?php echo $row->id_produk;?>" data-harga="<?php echo $row->harga?>" value='<?php
            echo $row->qty_req;
        ?>'  >
        <input type="button" value="Edit" class="btn btn-success btn-xs" onclick="javascript:saveApproval('<?php echo $row->id_produk; ?>');">
        <br><span id="<?php echo $row->id_produk; ?>"></span>
    </td>
</tr>
<?php
$total += $row->total;
$i++;
} ?>
<tr>
    <td colspan="11" align=right>T O T A L</td>
    <td align="right" id="total_request" style="font-weight:bold"><?php echo number_format($total,'0',',','.'); ?></td>
    <td></td>
</tr>
<script type="text/javascript">
$('.qty').on("keyup",function(){
		var harga = $(this).data('harga');
		var qty = $(this).val();
		var id = $(this).data('id');

        var total = Number(qty)*Number(harga);

		$('#totalItem'+id).html(Intl.NumberFormat().format(total));
        cekTotal();
        

});
function cekTotal(){
    var sum = 0;
        $("input[class *= 'qty']").each(function(){
                    sum += +$(this).data('harga')*$(this).val();
                });
        $("#total_request").html(Intl.NumberFormat().format(sum));
}

</script>