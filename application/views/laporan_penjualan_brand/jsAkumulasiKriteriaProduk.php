<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    }); 
    
    // Select2
    jQuery(".select2").select2({
        width: '100%'
    });

    $('#kategori').change(function(){
        kategori = $('#kategori').val();
        url = "<?php echo base_url('laporan/get_subkategori'); ?>";

        $('#subkategori').load(url,{id_kategori : kategori});
    });

    $('#viewReport').on("click",function(){
    	var dateStart 		= $('#dateStart').val();
    	var dateEnd 	 	= $('#dateEnd').val();
        var toko            = $('#toko').val();
        var tempat          = $('#tempat').val();
        var customer        = $('#customer-form').val();
        var kategori        = $('#kategori').val();
        var subkategori     = $('#subkategori2').val();
        var subkategori2    = $('#subkategori_3').val();
        var id_supplier     = $('#id_supplier').val();
        var id_brand        = $('#id_brand').val();

    	var url = "<?php echo base_url('laporan_brand/laporanPenjualanPerkriteriaProduk'); ?>";

        $.ajax({
                    method : "POST",
                    url : "<?php echo base_url('laporan_brand/cekUserAccess'); ?>",
                    data : {idToko : toko},
                    success : function(status){
                                if(status==0){
                                    $.Notification.notify('error','top right', 'Akses Ditolak', 'Anda Tidak Mempunyai Akses Ke Toko Ini, Silahkan Hubungi Admin');
                                } else {
                                    $.ajax({
                                                method      : "POST",
                                                data        : {dateStart : dateStart, dateEnd : dateEnd, toko : toko, tempat : tempat, customer : customer, kategori : kategori, subkategori : subkategori, subkategori2 : subkategori2, id_supplier:id_supplier,id_brand: id_brand},
                                                url         : url,
                                                beforeSend  : function(){
                                                                var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";
                                                                $('#dataReport').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
                                                              },
                                                success     : function(data){
                                                                $('#dataReport').html(data);
                                                              }
                                    });
                                }
                    }
                });


    	
    });
</script>