<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Adjusment Closing Kasir</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
            	<div class="row">
                    <div class="col-md-12">
                        <table width="100%">
                            <tr>
                                <td width="15%">Nama Kasir</td>
                                <td width="1%">:</td>
                                <td><?php echo $nama_kasir; ?></td>
                            </tr>
                            <tr>
                                <td width="15%">Tanggal</td>
                                <td width="1%">:</td>
                                <td><?php echo $tanggal; ?></td>
                            </tr>
                        </table>
                    </div>
            	</div>      
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group pull-right">
                            <input type="text" class="form-control" id="search" style="width: 200px;" placeholder="Search"/>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-md-12" id="viewTrxKasir">
                    </div>
                </div>  

            </div>
        </div>
    </div> <!-- /Portlet -->	
</div>

<div id="modalAdjusment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Adjusment</h4>
            </div>

            <div class="bodyAdjustPaymentType">
                                               
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->