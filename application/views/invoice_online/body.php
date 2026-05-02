<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>

<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title"><i class="fa fa-file"></i> Tambah Resi</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <!-- <div class="row">
            		<div class="col-md-12" style="text-align: right;font-size:15px;"> 
            			<a href="<?php echo base_url('invoice_online/daftar'); ?>"><i class="fa fa-book"></i> Daftar Resi</a>
            		</div>
            	</div> -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <form action="<?php echo base_url('invoice_online/addInvoice');?>" method="post">
                            <input type="text" name="no_invoice_online" id="no_invoice_online" style="width: 100%;height:40px;font-size:34px" placeholder="Scan disini" />
                        </form>
                    </div>
                </div> 

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                            <table class="table table-bordered" style="font-size:12px;">
                                <thead>
                                    <tr style="font-weight: bold;">
                                        <td>No. Invoice</td>
                                        <td>Tanggal Input</td>
                                        <td width="15%">Tanggal Proses</td>
                                        <td width="15%">Status</td>
                                        <td width="5%"></td>
                                    </tr>
                                </thead>

                                <tbody id="data-input">
                                </tbody>
                            </table>
                    </div>
                </div>        
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>
