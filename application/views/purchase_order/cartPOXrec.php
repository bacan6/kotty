<?php
	$total = 0;

	$num = $viewCartPO->num_rows();

	if($num > 0 ){
		$tab = 0;
	foreach($viewCartPO->result() as $row){
		//if($row->hargajual>0 && $row->harga>0){
			$tab++;
		//$lastPurchased = $this->modelPurchaseOrder->lastPurchased($row->id_produk,$idStore);
		//$lastSales = $this->modelPurchaseOrder->lastSales($row->id_produk,$idStore,$lastPurchased->tanggal);
		$margin = ($row->hargajual-$row->harga)/$row->hargajual*100;
?>
<tr id="row<?php echo $row->id; ?>">
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td><input type="number" value="<?php echo $row->qty; ?>" tabindex="<?php echo $tab?>" name="qty" class="form-control qty" id="qty<?php echo $row->id; ?>" size=12 data-urut="<?php echo $row->id ?>" data-id="<?php echo $row->id; ?>" data-produk="<?php echo $row->id_produk; ?>" style="width:65px"/></td>
	<td><input type="text" class="harga" id="<?php echo $row->id; ?>" data-produk="<?php echo $row->id_produk; ?>" data-id="<?php echo $row->id; ?>" value="<?php echo $row->harga; ?>" size=15 style="margin:0;width:60px"/></td>
	<td><input type="text" class="hargajual" id="<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>" data-produk="<?php echo $row->id_produk; ?>" value="<?php echo $row->hargajual; ?>" size=15 style="width:65px"/></td>
	<td><input type="text" value="<?php echo number_format($margin,'2',',','.');?>" class="margin" id="<?php echo $row->id; ?>" size=3 data-id="<?php echo $row->id; ?>" style="width:67px"/></td>
	
	<td>
	    <input type="text" name='bonus' id="bonus<?php echo $row->id; ?>" size=3 data-urut="<?php echo $row->id; ?>" data-id="<?php echo $row->id_produk; ?>" data-price="<?php echo $row->harga; ?>" data-max="<?php echo $row->qty; ?>" min="0" class="bonus" value="<?php echo $row->bonus; ?>" />
	</td>
    <td><input class="diskon1" id="diskon1<?php echo $row->id; ?>" type="text" size=5 value="<?php echo $row->diskon1; ?>" data-urut="<?php echo $row->id ?>" onChange="javascript:editHarga3(<?php echo $row->id_produk; ?>);"></td>
    <td><input class="diskon2" id="diskon2<?php echo $row->id; ?>" type="text" size=5 value="<?php echo $row->diskon2; ?>" data-urut="<?php echo $row->id; ?>" onChange="javascript:editHarga3(<?php echo $row->id_produk; ?>);"></td>
    <td><input class="diskon3" id="diskon3<?php echo $row->id; ?>" type="text" size=5 value="0" data-urut="<?php echo $row->id; ?>" onChange="javascript:editHarga3(<?php echo $row->id_produk; ?>);"></td>
	<td align="right" id="totalItem<?php echo $row->id; ?>"><?php echo number_format($row->qty*$row->harga,'0',',','.'); ?></td>
	<td align="center"><a class="hapusCart" id="<?php echo $row->id; ?>"><i class="fa fa-trash"></i></a></td>
</tr>
<?php $total = $total+($row->qty*$row->harga);
		//}
		} ?>

<tr>
	<td colspan="10" align="center"><b>TOTAL</b></td>
	<td align="right" id="totalCart" style="font-weight: bold;"></td>
	<td></td>
</tr>

<?php } else {
?>
	<tr>
		<td colspan="12" align="center">--Belum Ada Data Terinput--</td>
	</tr>
<?php	
} ?>

<script type="text/javascript">
	var urlTotalCart = "<?php echo base_url('purchase_order_xrec/totalCart'); ?>";
	var TotalDiscount = 0;
	var GrandTotal = 0;
	var DiscountGlobal = 0;
	
	

	$('.qty').on("keyup change",function(){
		var idProduk = this.id;
		var qty = $(this).val();
		var id = $(this).data('id');
		var produk = $(this).data('produk');

		var urlUpdateQty = "<?php echo base_url('purchase_order_xrec/updateQtyCart'); ?>";

		$.post(urlUpdateQty,{id : id, qty : qty},function(response){
			$('#totalItem'+id).text(response);
			//totalCart();
			editHarga3();
		});
	});
	$('.bonus').on("keyup change",function(){
		var idProduk = this.id;
		var bonus = $(this).val();
		var id = $(this).data('urut');
		var produk = $(this).data('produk');

		var urlUpdateQty = "<?php echo base_url('purchase_order_xrec/updateBonusCart'); ?>";

		$.post(urlUpdateQty,{id : id, bonus : bonus},function(response){
			$('#totalItem'+id).text(response);
		});
	});

	$('.harga').on("change",function(){
		var idProduk = this.id;
		var harga = $(this).val();
		var id = $(this).data('id');
		var produk = $(this).data('produk');
		var hargajual = $("#"+id+".hargajual").val();
		

		var margin = (hargajual-harga)/hargajual*100;

        $("#"+id+".margin").val((margin).toFixed(2));

		var urlUpdateHarga = "<?php echo base_url('purchase_order_xrec/updateHargaCart'); ?>";

		$.post(urlUpdateHarga,{id : id, harga : harga},function(response){
			$('#totalItem'+id).text(response);
			//totalCart();
			editHarga3();
		});
	});

	$('.hargajual').on("change",function(){
		var idProduk = this.id;
		var id = $(this).data('id');
		var hargajual = $(this).val();
		var hargabeli = $("#"+id+".harga").val();
		var produk = $(this).data('produk');
		

		var margin = (hargajual-hargabeli)/hargajual*100;

        $("#"+idProduk+".margin").val((margin).toFixed(2));
		var urlUpdateHarga = "<?php echo base_url('purchase_order_xrec/updateHargaJualCart'); ?>";

		$.post(urlUpdateHarga,{id : id, harga : hargajual},function(response){
			// none
		});
	});

	$('.diskon1').on("change",function(){
		var id = $(this).data('urut');
		var diskon1 = $(this).val();

		var urlUpdateHarga = "<?php echo base_url('purchase_order_xrec/updateDiskon1'); ?>";

		$.post(urlUpdateHarga,{id : id, diskon : diskon1},function(response){
			// none
		});
	});
	$('.diskon2').on("change",function(){
		var id = $(this).data('urut');
		var diskon2 = $(this).val();

		var urlUpdateHarga = "<?php echo base_url('purchase_order_xrec/updateDiskon2'); ?>";

		$.post(urlUpdateHarga,{id : id, diskon : diskon2},function(response){
			// none
		});
	});

	$('.margin').on("change",function(){
		var idProduk = this.id;
		var id = $(this).data('id');
		var margin = $(this).val();
		var hargabeli = $("#"+id+".harga").val();
		

		hargajual = hargabeli/((100-margin)/100);

        $("#"+id+".hargajual").val((hargajual).toFixed(2));
	});

	function totalCart(){
		$('#totalCart').load(urlTotalCart);
		$("#stTotal").load(urlTotalCart);
		setTimeout(function(){GrandTotal=$('stTotal').html()},2000);
	}

	$('.hapusCart').on("click",function(){
		var id = this.id;

		var urlHapusCart = "<?php echo base_url('purchase_order_xrec/hapusCart'); ?>";

		$.post(urlHapusCart,{id : id}, function(){
			$('#data-input').load(urlCartPO);
		});
	});

	function editHarga2(diskon){
                var tot2 = 0;var jml2=0;var urut2=0;
                var totalHarga2 = 0; totalDiskon = 0;
				var idbaris2 = 0;
                  $( ".harga" ).each(function() {
                    urut2 = $( this ).data('id');
					idbaris2 = $( this ).data('produk');
					
					//alert(idbaris2);
                    jml2 = $("#qty"+urut2).val();
					
                    hargaBeli = Number(jml2)*Number($( this ).val());
					
                    diskon1 = Number($('#diskon1'+urut2).val())/100*(hargaBeli);
                    diskon2 = Number($('#diskon2'+urut2).val())/100*(hargaBeli-diskon1);
                    
                    diskon3 = Number($('#diskon3'+urut2).val())*Number(jml2);
                    
                    totalDiskon = Number(totalDiskon) + diskon1 + diskon2 + diskon3;

					subtotal = hargaBeli-(diskon1 + diskon2 + diskon3);
                    
                    totalHarga2 = Number(totalHarga2)+(subtotal);
                    //totalHarga3 = Number(totalHarga3)+(hargaBeli-(diskon1 + diskon2 + diskon3));
                    $("#totalItem"+urut2).html(subtotal.toFixed(2));
                    
                  });
				ppn = $('#PPN').val();
				
                totalHarga2 = Number(totalHarga2) - Number(diskon);

				if(ppn=='1'){
                        totalHarga2 = totalHarga2+(0.11*totalHarga2);
                    }
                $("#stTotal").html(totalHarga2.toFixed(2));
                $("#stDiskon").html(totalDiskon.toFixed(2));

            }

	
	function editHarga3(){
                var tot3 = 0;var jml3=0;var urut3=0;
                var totalHarga3 = 0; totalDiskon = 0;
				var idbaris3 = 0; var ppn=0; 
                  $( ".harga" ).each(function() {
                    urut3 = $( this ).data('id');
					idbaris3 = $( this ).data('produk');
					//alert(idbaris2);
                    jml3 = $("#qty"+urut3).val();
					
                    hargaBeli = Number(jml3)*Number($( this ).val());
					
                    diskon1 = Number($('#diskon1'+urut3).val())/100*(hargaBeli);
                    diskon2 = Number($('#diskon2'+urut3).val())/100*(hargaBeli-diskon1);
                    
                    diskon3 = Number($('#diskon3'+urut3).val())*Number(jml3);
                    
                    totalDiskon = Number(totalDiskon) + diskon1 + diskon2 + diskon3;

					subtotal = hargaBeli-(diskon1 + diskon2 + diskon3);
                    totalHarga3 = Number(totalHarga3)+(subtotal);
                    //totalHarga3 = Number(totalHarga3)+(hargaBeli-(diskon1 + diskon2 + diskon3));
                    $("#totalItem"+urut3).html(subtotal.toFixed(2));
                    
                  });
				
				$('#totalCart').html(totalHarga3.toFixed(0));

				var diskon = Number($("#diskon").val());
				
				ppn = $('#PPN').val();
				
                totalHarga3 = Number(totalHarga3) - Number(diskon);

				if(ppn=='1'){
                        totalHarga3 = totalHarga3+(0.11*totalHarga3);
                    }
                $("#stTotal").html(totalHarga3.toFixed(2));
                $("#stDiskon").html(totalDiskon.toFixed(2));
				
				//$("#stDiskon").html(0);

            }
	editHarga3();

	$("#btn-diskon1").on("click",function(){
		var set = $('#setDiskon1').val();
		$(".diskon1").each(function( index, element ) {
			$(element).val(set);
		});
	});
	$("#btn-diskon2").on("click",function(){
		var set = $('#setDiskon2').val();
		$(".diskon2").each(function( index, element ) {
			$(element).val(set);
		});
	});
	$("#btn-diskon3").on("click",function(){
		var set = $('#setDiskon3').val();
		$(".diskon3").each(function( index, element ) {
			$(element).val(set);
		});
	});
</script>