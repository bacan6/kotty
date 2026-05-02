<div class="wraper container-fluid">
    <div class="page-title"> 
      <h3 class="title">Konversi Produk ke Bahan Baku</h3> 
    </div>

    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" id="produk-ajax" style="width: 100%;"/>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-12" style="text-align: right;">
                        <button class="btn btn-primary" id="prosesConvert"><i class="fa fa-save"></i> Submit</button>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="font-weight: bold;">
                                    <td width="15%">SKU</td>
                                    <td>Nama Produk</td>
                                    <td width="20%">Harga Beli</td>
                                    <td width="10%">Qty</td>
                                    <td width="10%">Satuan</td>
                                    <td width="20%">Total</td>
                                    <td width="5%"></td>
                                </tr>
                            </thead>

                            <tbody id="dataCart">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>

