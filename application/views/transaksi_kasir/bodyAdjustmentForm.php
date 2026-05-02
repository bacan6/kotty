<p>Current Payment Type : <?php echo $paymentType; ?></p>

<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
        <select class="form-control" name="type_bayar" id="type_bayar">
            <option val="">--Pilih Tipe Bayar</option>
            <?php
                foreach($getPaymentType->result() as $pt){
            ?>
                <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>
            <?php } ?>
        </select>
	</div>
</div>

<div class="form-group" id="tempo-place">
</div>

<div class="form-group" style="text-align: right;">
	<a class="btn btn-success updatePaymentType" id="<?php echo $noInvoice; ?>"><i class="fa fa-save"></i> Save</a>
</div>


<script type="text/javascript">
	$('#type_bayar').change(function(){
        type = $('#type_bayar').val();

        sub_account = "<?php echo base_url('kasir/sub_account'); ?>";            
        $('#tempo-place').load(sub_account,{id : type});            
    });

    $('.updatePaymentType').on("click",function(){
    	var noInvoice = this.id;
    	var urlUpdate = "<?php echo base_url('kasir/updatePaymentTypeSQL'); ?>";
    	var paymentType = $('#type_bayar').val();
    	var account 	= $('#subAccount').val();
    	var idUser = "<?php echo $idUser; ?>";
    	var tanggal = "<?php echo $tanggal; ?>";

    	$.ajax({
    				type 		: "POST",
    				data 		: {noInvoice : noInvoice, paymentType : paymentType, account : account},
    				url 		: urlUpdate,
    				beforeSend 	: function(){
    									$('.bodyAdjustPaymentType').text('Harap Tunggu...');
    							  },
    				success 	: function(data){
    								if(data == 1){
    									//success
    									$.Notification.notify('success','top right', 'Adjusment', 'Data Telah Diubah');
    									$('#modalAdjusment').modal('hide');

    									//reload
    									var url = "<?php echo base_url('kasir/dataAdjusmentKasir'); ?>";
                						$('#viewTrxKasir').load(url,{idUser : idUser, tanggal : tanggal});
    								} else {
    									//gagal
    									$.Notification.notify('error','top right', 'Adjusment', 'Data Gagal Diubah');
    									$('#modalAdjusment').modal('hide');

    									//reload
    									var url = "<?php echo base_url('kasir/dataAdjusmentKasir'); ?>";
                						$('#viewTrxKasir').load(url,{idUser : idUser, tanggal : tanggal});
    								}
    							  }
    	});
    });
</script>