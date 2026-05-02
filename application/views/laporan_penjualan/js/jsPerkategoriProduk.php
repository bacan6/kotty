<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    });	


    $('#submitReport').on("click",function(){
        var dateStart       = $('#date_start').val();
        var dateEnd         = $('#date_end').val();
        var idKategori      = $('#kategori').val();

        var url = "<?php echo base_url('laporan/penjualanPerkategoriProdukReport'); ?>";

        $.ajax({
                    method      : "POST",
                    data        : {dateStart : dateStart, dateEnd : dateEnd, idKategori : idKategori},
                    url         : url,
                    beforeSend  : function(){
                                    var imageUrl = "<?php echo base_url('assets/loading.gif'); ?>";
                                    $('#dataReport').html("<table width='100%'><tr><td colspan='12' align='center'><img src='"+imageUrl+"'/></td></tr></table>");
                                  },
                    success     : function(data){
                                    $('#dataReport').html(data);
                                  }
        });
    });
</script>