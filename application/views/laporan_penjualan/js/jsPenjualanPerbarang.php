<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    });	
	
    jQuery(".select2").select2({
        width: '100%'
    });
    
	$('#produk-ajax').select2({
         	 	placeholder: "Pilih Data Produk",
                ajax: {
                    url         : '<?php echo base_url('laporan/ajax_produk'); ?>',
                    dataType    : 'json',
                    quietMillis : 500,
                    method      : "GET",
                    data: function (params) {
                        return {
                            term : params
                        };
                    },
                    results: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({    
                                'id': item.id,
                                'text': item.text,
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                },
                minimumInputLength: 3,
    });

    $('#viewReport').on("click",function(){
        var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";
        var dateStart       = $('#dateStart').val();
        var dateEnd         = $('#dateEnd').val();
        var idProduk        = $('#produk-ajax').val();
        var idToko          = $('#toko').val();

        var url = "<?php echo base_url('laporan/penjualanPerbarangReport'); ?>";

        $.ajax({
                    method : "POST",
                    url : "<?php echo base_url('laporan/cekUserAccess'); ?>",
                    data : {idToko : idToko},
                    success : function(status){
                                if(status==0){
                                    $.Notification.notify('error','top right', 'Akses Ditolak', 'Anda Tidak Mempunyai Akses Ke Toko Ini, Silahkan Hubungi Admin');
                                } else {
                                    $.ajax({
                                                method          : "POST",
                                                data            : {idToko : idToko, dateStart : dateStart, dateEnd : dateEnd, idProduk : idProduk},
                                                url             : url,
                                                beforeSend      : function(){
                                                                    $('#dataReport').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
                                                                  },
                                                success         : function(data){
                                                                    $('#dataReport').html(data);
                                                                 } 
                                    }); 
                                }
                    }
                });
    });
</script>