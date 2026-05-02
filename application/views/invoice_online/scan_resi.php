<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-file"></i> Scan Resi</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <form action="<?php echo base_url('penjualan/cekResi');?>" method="post">
                            <input type="text" name="no_invoice_online" id="no_invoice_online" style="width: 100%;height:40px;font-size:34px" placeholder="Scan disini" />
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
<script type="text/javascript">
    $( "#no_invoice_online" ).focus();
</script>
