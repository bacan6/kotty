<tr id="form-input<?php echo $no; ?>">
	<?php
		foreach($data_produk->result() as $row){
	?>
	<td><?php echo $row->id_produk; ?></td>
	<td><?php echo $row->nama_produk; ?></td>
	<td align="right"><?php echo number_format($row->harga,'0',',','.'); ?></td>
	<td align="right">
		<?php
			$max = $this->model1->data_stok_toko($row->id_produk,$id_store);
		?>
		<input type="number" name="jumlah_beli[]" class="form-control qty_beli<?php echo $no; ?>" min="0" id="jumlah_beli"/>
		<input type="hidden" name="sku[]" value="<?php echo $row->id_produk; ?>"/> 
		<input type="hidden" name="harga[]" value="<?php echo $row->harga; ?>"/> 
		<input type="hidden" name="hpp[]" value="<?php echo $row->hpp; ?>"/>
		<input type="hidden" name="id_produk" class="sku<?php echo $no; ?>" value="<?php echo $row->id_produk; ?>"/>
		<input type="hidden" class="total_beli_hidden" id="total_beli_hidden<?php echo $no; ?>">
		<input type="hidden" class="harga_beli<?php echo $no; ?>" value="<?php echo $row->harga; ?>"/>
		<input type="hidden" id="total_beli_kategori<?php echo $no; ?>" class="id_kategori[]" data-id_kategori="<?php echo $row->id_kategori; ?>" data-value_kategori=""/>
	</td>
	<td align="right" id="total_harga<?php echo $no; ?>"></td>
	<td align="right">
		<input type="number" class="total_diskon_hidden form-control" id="total_diskon_hidden<?php echo $no; ?>" name="diskon_item[]">
	</td>
	<td align="right" id="grandTotal<?php echo $no; ?>"></td>
	<td><a class="hapus-form" id="<?php echo $no; ?>"><i class="fa fa-trash"></i></a></td>
	<?php } ?>
</tr>

<script type="text/javascript">
	$('.hapus-form').on("click",function(){
        id = this.id;
        $('#form-input'+id).remove();
    });  

	function formatAngka(angka) {
		 if (typeof(angka) != 'string') angka = angka.toString();
		 var reg = new RegExp('([0-9]+)([0-9]{3})');
		 while(reg.test(angka)) angka = angka.replace(reg, '$1.$2');
		 return angka;
	}

	$('.qty_beli<?php echo $no; ?>').on("change",function(){
		jumlah_beli = $('.qty_beli<?php echo $no; ?>').val();
		harga 		= $('.harga_beli<?php echo $no; ?>').val();

		total_harga = jumlah_beli*harga;

		$('#total_beli_hidden<?php echo $no; ?>').val(total_harga);
		$('#total_beli_kategori<?php echo $no; ?>').data('value_kategori',total_harga);

		$('#total_harga<?php echo $no; ?>').text(formatAngka(total_harga));


		var sum = 0;

       $("input[class *= 'total_beli_hidden']").each(function(){
            sum += +$(this).val();
       });
 
        $("#total_purchase").text(formatAngka(sum));

        //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
        $("#total_purchase_temp").val(sum);
		

		//cek discount
		sku = $('.sku<?php echo $no; ?>').val();

		$.ajax({
				type 	: "POST",
				url 	: "<?php echo base_url('penjualan/cek_diskon'); ?>",
				data 	: {sku : sku},
				success : function(result){
						  	if(result==1){
						  		$.ajax({
						  			type 	: "POST",
						  			url 	: "<?php echo base_url('penjualan/ambil_nilai_diskon'); ?>",
						  			data 	: {sku : sku, qty : jumlah_beli},
						  			success	: function(diskon){

						  				if(diskon > 0){
						  					nilai_diskon = diskon;
						  				} else {
						  					nilai_diskon = 0;
						  				}

						  				$('#discount<?php echo $no; ?>').text(formatAngka(nilai_diskon*jumlah_beli));
						  				$('#grandTotal<?php echo $no; ?>').text(formatAngka(total_harga-(nilai_diskon*jumlah_beli)));
						  				$('#total_diskon_hidden<?php echo $no; ?>').val(nilai_diskon*jumlah_beli);

						  				var diskon_otomatis = 0;

						                $("input[class *= 'total_diskon_hidden']").each(function(){
						                    diskon_otomatis += +$(this).val();
						                });
             
						                $("#diskon_otomatis").text(formatAngka(diskon_otomatis));

						                //SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
						                simpan_diskon(diskon_otomatis);

						                diskon          = $('#diskon_temp').val();
						                sum             = $('#total_purchase_temp').val();
						                diskon_promosi  = $('#diskon_promosi_temp').val();
						                poin            = $('#poin_temp').val();
						                ongkir          = $('#ongkir_temp').val();
						                diskon_otomatis = $('#diskon_otomatis_temp').val();

						                grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

						                $('#grand_total').text(formatAngka(grand_total));
                                        $('#total_belanja_notif').text(formatAngka(grand_total));

						  			}
						  		});
						  	} 
						  }
		});
	});

	$('#total_diskon_hidden<?php echo $no; ?>').on("change",function(){
		//ambil nilai quantity dan harga 
		jumlah_beli = $('.qty_beli<?php echo $no; ?>').val();
		harga 		= $('.harga_beli<?php echo $no; ?>').val();
		
		total_harga = jumlah_beli*harga;

		//ambil nilai diskon
		diskon 		= $('#total_diskon_hidden<?php echo $no; ?>').val();

		//tampilkan di harga total
		$('#grandTotal<?php echo $no; ?>').text(formatAngka(total_harga-diskon));

		var diskon_otomatis = 0;

		$("input[class *= 'total_diskon_hidden']").each(function(){
			diskon_otomatis += +$(this).val();
		});
             
		$("#diskon_otomatis").text(formatAngka(diskon_otomatis));

		//SIMPAN ANGKA TOTAL SEMENTARA PADA INPUT TYPE HIDDEN
		simpan_diskon(diskon_otomatis);

		diskon          = $('#diskon_temp').val();
		sum             = $('#total_purchase_temp').val();
		diskon_promosi  = $('#diskon_promosi_temp').val();
		poin            = $('#poin_temp').val();
		ongkir          = $('#ongkir_temp').val();
		diskon_otomatis = $('#diskon_otomatis_temp').val();

		grand_total = (parseInt(ongkir)+parseInt(sum))-(parseInt(total_diskon)+parseInt(diskon_promosi)+parseInt(poin)+parseInt(diskon_otomatis));

		$('#grand_total').text(formatAngka(grand_total));
        $('#total_belanja_notif').text(formatAngka(grand_total));
	});
</script>