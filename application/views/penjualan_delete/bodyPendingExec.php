<div id="CssLoader" style="display: none;">
    <div class='spinftw'></div>
</div>


<div id="payment_total_notif" style="width: 350px;position: fixed;z-index: 1;left: 8px;top:8px;display: none;" >
    <div class="alert alert-danger" style="opacity: 0.9;">
        <table width="100%" style="font-size: 18px;">
            <tr>
                <td width="50%">Total Belanja</td>
                <td align="right" id="total_belanja_notif"></td>
            </tr>

            <tr>
                <td width="50%">Jumlah Bayar</td>
                <td align="right" id="jumlah_bayar_notif"></td>
            </tr>

            <tr>
                <td width="50%">Kembali</td>
                <td align="right" id="kembali_notif"></td>
            </tr>
        </table>
    </div>
</div>


<div class="wraper container-fluid">
    <div class="portlet"><!-- /primary heading -->
        <div id="portlet2" class="panel-collapse collapse in">
            <div class="portlet-body">
                
                    <div class="row">
                        <div class="col-md-12" style="text-align: right;">
                            <button id="button-submit" class="btn btn-icon btn-primary btn-rounded m-b-5"> <i class=" fa fa-check-square-o"></i> Submit</button>
                            <a class="btn btn-con btn-danger btn-rounded m-b-5" href="<?php echo base_url('penjualan/cancelTrx'); ?>"><i class="fa fa-times"></i> Batal</a>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-9">                          
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="hidden" id="produk-ajax" name="customer" style="width: 100%;">
                                    </div>

                                    <div class="form-group" style="margin-top: 20px;" id="tableNiceScroll">
                                        <table class="table table-striped" style="font-size: 12px;">
                                            <thead>
                                                <tr style="background: #2A303A;color:white;font-weight: bold;">
                                                    <td width="14%">SKU</td>
                                                    <td>Nama Produk</td>
                                                    <td width="13%" align="right">Harga Jual</td>
                                                    <td width="13%" align="center">Qty</td>
                                                    <td width="13%" align="right">Total Harga</td>
                                                    <td width="13%" align="right">Discount</td>
                                                    <td width="13%" align="right">Grand Total</td>
                                                    <td width="3%"></td>
                                                </tr>
                                            </thead>

                                            <tbody id="data-input">

                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" id="totalQty" align="right" style="font-weight: bold;"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3" style="border-left:solid 0.1px #ccc;padding-left: 15px;">
                            <div class="row">
                                <div class="col-md-12" style="border-bottom: solid 0.1px #ccc;">
                                    <table class="table" style="font-size: 12px;">
                                        <tr>
                                            <td width="50%" style="font-weight: bold;color:#25aff4;"><i class="fa fa-crosshairs"></i> Subtotal</td>
                                            <td id="total_purchase" align="right" style="font-weight: bold;color:#25aff4;"></td>
                                        </tr>

                                        <tr id="diskonPeritem">
                                            <!--<td><i class="fa fa-bullhorn"></i> Diskon Peritem</td>
                                            <td id="diskon_otomatis" align="right"></td>-->
                                        </tr>

                                        <tr id="ongkirText">
                                            
                                            
                                        </tr>
                                        <tr id="diskonMember">
  
                                        </tr>
                                        <tr id="diskon_promosi">
                                            
                                        </tr>
                                        <tr id="poin-value-reimburs">
    
                                        </tr>
                                        <tr>
                                            <td style="color:#25aff4;"><i class='fa fa-bank'></i> <b>TOTAL</b></td>
                                            <td id="grand_total" align="right" style="font-weight: bold;"></td>
                                        </tr>
                                    </table> 
                                </div>
                            </div>

                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">

                                    

                                    <div class="form-group" id="data-customer">

                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-minus"></i></span>
                                            <input type="text" class="form-control" placeholder="Diskon" id="diskon" value="<?php if($diskonPromosi > 0){echo $diskonPromosi;}?>">
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                            <select class="form-control" name="type_bayar" id="type_bayar">
                                                <?php
                                                    foreach($payment_type->result() as $pt){
                                                ?>
                                                    <option value="<?php echo $pt->id; ?>"><?php echo $pt->payment_type; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="tempo-place">
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            <input type="text" class="form-control" placeholder="Jumlah Bayar" id="jumlah_bayar" name="jumlah_bayar" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                            <textarea class="form-control" placeholder="Keterangan" id="keterangan" name="keterangan"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group" align="right">
                                        <input type="hidden" id="total_purchase_temp" name="total_purchase" value="0"/>
                                        <input type="hidden" id="diskon_temp" name="diskon" value="0"/>
                                        <input type="hidden" id="ongkir_temp" value="0"/>
                                        <input type="hidden" id="diskon_promosi_temp" name="diskon_promosi_temp" value="0"/>
                                        <input type="hidden" id="diskon_otomatis_temp" name="diskon_otomatis_temp" value="0"/>
                                        <input type="hidden" id="poin_temp" name="poin_temp" value="0"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div> <!-- /Portlet -->    
</div>


