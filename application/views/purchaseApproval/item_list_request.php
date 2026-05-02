<div class="row">
	<div class="col-md-12">
			<?php
				foreach($data_request->result() as $row){
			?>
			<table width="100%">
				<tr>
					<td width="30%">No Request</td>
					<td width="1%">:</td>
					<td><?php echo $row->purchase_no; ?></td>
				</tr>

				<tr>
					<td>User Request</td>
					<td width="1%">:</td>
					<td><?php echo $row->nama_user; ?></td>
				</tr>

				<tr>
					<td>Nama Item</td>
					<td width="1%">:</td>
					<td><?php echo $row->nama_bahan; ?></td>
				</tr>

				<tr>
					<td>Jumlah Request</td>
					<td width="1%">:</td>
					<td><?php echo $row->qty." ".$row->satuan; ?></td>
				</tr>

				<tr>
					<td colspan="3" align="right"><a href="#" class="btn btn-danger ditolak" id="<?php echo $row->purchase_no; ?>"> <i class="fa fa-remove"></i> Ditolak </a></td>
				</tr>
			</table>
			<?php } ?>
	</div>
</div>

<div class="row" style="margin-top: 20px;"> 
	<div class="col-md-12" id="tolak-form">

	</div>
</div>

<div class="row" style="margin-top: 10px;">
	<div class="col-md-12" id="table-list-item">
		<table class="table table-bordered tabke-striped" style="font-size: 10px;">
			<tr style="background: #2A303A;color:white;font-weight: bold;">
				<td width="3%">No</td>
				<td>Supplier</td>
				<td width="30%" align="right">Harga</td>
				<td width="20%">Remark</td>
				<td width="3%"></td>
			</tr>

			<?php
				$i = 1;
				foreach($item_request->result() as $dt){
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $dt->supplier; ?></td>
				<td align="right"><?php echo number_format($dt->harga,'0',',','.'); ?></td>
				<td><?php echo $dt->remark; ?></td>
				<td><a href="#" class='choose-item' id="<?php echo $dt->id; ?>" data-no_request="<?php echo $no_request; ?>" data-harga="<?php echo $dt->harga; ?>" data-sku="<?php echo $dt->sku; ?>"><span class="badge bg-success"><i class="fa fa-check"></i></span></a></td>
			</tr>
			<?php $i++; } ?>
		</table>
	</div>
</div>

<script type="text/javascript">
	$('.choose-item').on("click",function(){
		id 			= this.id;
		no_request 	= $(this).data('no_request');
		harga 		= $(this).data('harga');
		sku 		= $(this).data('sku');
		url 		= "<?php echo base_url('purchase_approval/choose_item'); ?>";
		daftar_request  = "<?php echo base_url('purchase_approval/wait_approve_list'); ?>";

		swal({   
            title: "Apa anda yakin ?",   
            text: "Ini akan menjadi acuan harga pembelian selanjutnya",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#1c9a08",   
            confirmButtonText: "Ya, Pilih Ini",   
            closeOnConfirm: false 
        }, function(){   
            $.post(url,{id : id, no_request : no_request, harga : harga, sku : sku }, function(){
            	$('#modal-approvel').modal('hide');
            	$('.modal-backdrop').remove();
            	$('#daftar-request-wait').load(daftar_request);
            });

            swal("Berhasil", "Harga Acuan Telah Dipilih", "success"); 
        });
	});

	$('.ditolak').on("click",function(){
		id = this.id;

		url = "<?php echo base_url('purchase_approval/form_ignore'); ?>";

		$('#table-list-item').empty();
		$('#tolak-form').load(url,{id : id});
	});
</script>