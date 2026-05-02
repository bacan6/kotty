<script type="text/javascript">
    jQuery('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        autoclose : true
    }); 
    
    // Select2
    jQuery(".select2").select2({
        width: '100%'
    });

    $('#viewReport').on("click",function(){
        var dateStart   = $('#dateStart').val();
        var dateEnd     = $('#dateEnd').val();
        var idKasir     = $('#idKasir').val();
        var idPayment     = $('#idPayment').val();

        var url = "<?php echo base_url('laporan/penjualanPerkasirReport'); ?>";

        $.ajax({
                    method      : "POST",
                    data        : {dateStart : dateStart, dateEnd : dateEnd, idKasir : idKasir,idPayment:idPayment},
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