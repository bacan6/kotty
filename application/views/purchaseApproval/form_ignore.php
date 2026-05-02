<div class="form-group">
	<textarea class="form-control" placeholder="Alasan Ditolak" id="reason"></textarea>
</div>

<div class="form-group">
	<a class="btn btn-warning" id="submit-ignore"> Submit </a>
</div>

<script type="text/javascript">
	$('#submit-ignore').on("click", function(){
		reason = $('#reason').val();
		no_request = "<?php echo $id; ?>";
		daftar_request  = "<?php echo base_url('purchase_approval/wait_approve_list'); ?>";

		url = "<?php echo base_url('purchase_approval/submit_ignore'); ?>";

		swal({   
            title: "Apa anda yakin ?",   
            text: "Purchase Request Akan Ditolak dan Diteruskan Kembali Ke Purchasing",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#1c9a08",   
            confirmButtonText: "Ya, Pilih Ini",   
            closeOnConfirm: false 
        }, function(){   
            $.post(url,{ reason : reason, no_request : no_request}, function(){
				$('#modal-approval').modal('hide');
				$('.modal-backdrop').remove();
	            $('#daftar-request-wait').load(daftar_request);
			});

            swal("Berhasil", "Purchase Request Telah Ditolak", "success"); 
        });
	});
</script>