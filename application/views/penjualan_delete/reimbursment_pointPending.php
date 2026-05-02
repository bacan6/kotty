<table style='font-size:12px;' width="100%">
	<?php
		foreach($customer_poin->result() as $row){
	?>
		<tr>
			<td colspan="2" align="right"><a class="deleteMemberSelected" id="<?php echo $idUser; ?>"><i class="fa fa-trash"></i></a></td>
		</tr>
		<tr>
			<td width='30%'><b>Nama</b></td>
			<td align='right'><?php echo $row->nama; ?></td>
		</tr>
		<tr>
			<td width='30%'><b>Kategori</b></td>
			<td align='right'><?php echo $row->group_customer; ?></td>
		</tr>
		<tr>
			<td width='30%'><b>Poin</b></td>
			<td align='right'><?php echo $row->point; ?></td>
		</tr>
		<tr>
			<td width='30%'><b>Reimbursment</b></td>
			<td align='right'> <input type='number' name="poin_reimburs" style="border:0;border-bottom:solid 1px #ccc;" id='poin-reimbursment' style='text-align:right;' value="<?php echo $poinValue; ?>" min="0"/> </td>
		</tr>
	<?php } ?>
</table>

<script type="text/javascript">
	$('#poin-reimbursment').on("keyup",function(){
		//dapatkan id customer dan ambil poin maksimal
		id_customer = $('#customer-form').val();

		poinVal = $('#poin-reimbursment').val();
		max 	 = <?php echo $row->point; ?>;

		totalPurchaseValue(poinVal,max);					
	});

	function totalPurchaseValue(poinVal,max){
		$.ajax({
					type 	: "POST",
					url  	: totalPurchaseUrl,
					data 	: {noCart : noCart},
					success : function(totalPurchase){
							  var totalPurchaseConvert = totalPurchase.split(".").join("");

							  if(totalPurchaseConvert > 0){
									//CEK INPUTAN POIN TIDAK BOLEH MELEBIHI NILAI POIN YANG DIMILIKI CUSTOMER
									if(poinVal > max){
										$.Notification.notify('error', 'top right', 'Reimburs poin gagal', 'Inputan poin melebihi jumlah poin yang dimilki customer');
							            $('#poin-reimbursment').val('');
							            $('#poin-value-reimburs').text('');	
									} else {
										//dapatkan nilai reimburs poin
										getNilaiReimburs(poinVal);
									}	
								} else {
									$.Notification.notify('error', 'top right', 'Reimburs poin gagal', 'Belum ada item terinput');
							            $('#poin-reimbursment').val('');
							            $('#poin-value-reimburs').text('');
							            getNilaiReimburs(0);
								}
					}
		});
	}

	function getNilaiReimburs(poinVal){
		//dapatkan nilai reimbursment pada database dengan ajax
		$.ajax({
					type 	: 'POST',
					data 	: {poin : poinVal, noCart : noCart},
					url 	: "<?php echo base_url('penjualan/get_nilai_reimburs'); ?>",
					success : function(nilaiReimburs){	
								//insert data poin to db
								var urlInsertPoin = "<?php echo base_url('penjualan/insertPoinPending'); ?>"; 

								$.post(urlInsertPoin,{poinVal : poinVal,nilaiReimburs : nilaiReimburs,noCart:noCart},function(){
									var urlNilaiReimburs = "<?php echo base_url('penjualan/viewNilaiReimbursPending'); ?>";

									$('#poin-value-reimburs').load(urlNilaiReimburs,{noCart : noCart});
									viewPricePanel();
								});
					}
		})
	}

	$('.deleteMemberSelected').on("click",function(){
		var idUser = this.id;
		var urlDeleteDiscMember = "<?php echo base_url('penjualan/deleteDiscMemberPending'); ?>";

		$.post(urlDeleteDiscMember,{noCart : noCart},function(){
			$('#data-customer').empty();
			$('#poin-value-reimburs').empty();
			viewPricePanel();
			$('#customer-form').select2("val","");
		});
	});



</script>